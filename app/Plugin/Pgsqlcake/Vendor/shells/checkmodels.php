<?php
	App::import( 'Core', 'ConnectionManager' ); // CakePHP 1.2 fix

	class CheckmodelsShell extends Shell
	{
		/**
		*
		*/
		public $uses = array();

		/**
		*
		*/
		public $showSuccess = false;

		/**
		*
		*/
		protected $_connections = array();

		/**
		*
		*/
		protected $_dbos = array();

		/**
		*
		*/
		protected $_tables = array();

		/**
		* @see DboSource::fullTableName
		*/
		protected function _modelTable( $modelName ) {
			$file = MODELS.Inflector::underscore( $modelName ).'.php';
			if( !file_exists( $file ) ) {
				return false;
			}

			App::import( 'Model', $modelName );
			$reflection = new ReflectionClass( $modelName );
			$properties = $reflection->getDefaultProperties();

			if( $properties['useTable'] === false ) {
				return false;
			}
			else if( $properties['useTable'] === null ) {
				$properties['useTable'] = Inflector::tableize( $modelName );
			}

			if( !in_array( $properties['useDbConfig'], $this->_connections ) ) {
				$this->err( "La connection {$properties['useDbConfig']} n'est pas définie." );
				$this->hr();
				return false;
			}

			if( !isset( $this->_dbos[$properties['useDbConfig']] ) ) {
				$this->_dbos[$properties['useDbConfig']] = ConnectionManager::getDataSource( $properties['useDbConfig'] );
				$this->_tables[$properties['useDbConfig']] = $this->_dbos[$properties['useDbConfig']]->listSources();
			}

			$tableName = $this->_dbos[$properties['useDbConfig']]->config['prefix'].$properties['useTable'];

			if( !in_array( $tableName, $this->_tables[$properties['useDbConfig']] ) ) {
				$this->err( "La table {$tableName} n'est pas présente pour la connection {$properties['useDbConfig']}." );
				$this->hr();
				return false;
			}

			return $tableName;
		}

		/**
		*
		*/
		public function startup() {
			parent::startup();
			$this->_connections = array_keys( ConnectionManager::enumConnectionObjects() );
		}

		/**
		* FIXME:
		*	1°) faire des fonctions
		*	2°) quels sont les champs _id qui n'ont pas de fk ou pas de relation
		*	3°) quels sont les modèles qui devraient avoir une table et qui n'en ont pas
		*	4°) quelles sont les tables qui n'ont pas de modèles
		*	5°) intégrer les autres classes typiquement postgres dans le plugin (Pgsqlcake.Schema)
		*/
		protected function _check( $modelName ) {
			$buffer = array();
			$error = false;

			$buffer[] = "Analyse du modèle {$modelName}";

			$model = ClassRegistry::init( $modelName );
			$model->Behaviors->attach( 'Pgsqlcake.Schema' );
			$foreignKeysTo = $model->foreignKeysTo();
			$foreignKeysFrom = $model->foreignKeysFrom();

			// 1°) TODO: foreignKeysFrom
			$innerError = false;
			$buffer[] = "\tMatérialisation des clés étrangères";
			foreach( $foreignKeysTo as $fk ) {
				$fkModel = Inflector::classify( $fk['From']['table'] );
				if( !isset( $model->{$fkModel} ) ) {
					$buffer[] = "\t\tLa clé étrangère entre le modèle {$model->alias} et le modèle {$fkModel} ({$fk['From']['table']}.{$fk['From']['column']}) n'est pas matérialisée par une relation dans le modèle {$model->alias}.";
					$error = $innerError = true;
				}
			}
			if( !$innerError ) {
				$buffer[] = "\t\tOK";
			}

			$fkColumnsFrom = Set::extract( '/From/column', $foreignKeysFrom );
			$fkTablesTo = Set::extract( '/From/table', $foreignKeysTo );

			// 2°)
			$innerError = false;
			$buffer[] = "\tMatérialisation des relations du modèle";
			$associations = $model->getAssociated();
			foreach( $associations as $assocModel => $assocType ) {
				switch( $assocType ) {
					case 'belongsTo':
						$assoc = $model->belongsTo[$assocModel];
						if( !empty( $assoc['foreignKey'] ) && !in_array( $assoc['foreignKey'], $fkColumnsFrom ) ) {
							$buffer[] = "\t\tLa relation entre le modèle {$model->alias} et le modèle {$assocModel} ({$model->useTable}.{$assoc['foreignKey']}) n'est pas matérialisée par une clé étrangère au niveau de la base de données.";
							$error = $innerError = true;
						}
						break;
					case 'hasOne':
						$assoc = $model->hasOne[$assocModel];
						if( !empty( $assoc['foreignKey'] ) && !in_array( $model->{$assocModel}->useTable, $fkTablesTo ) ) {
							$buffer[] = "\t\tLa relation entre le modèle {$model->alias} et le modèle {$assocModel} ({$model->{$assocModel}->useTable}.{$assoc['foreignKey']}) n'est pas matérialisée par une clé étrangère au niveau de la base de données.";
							$error = $innerError = true;
						}
						break;
					case 'hasMany':
						$assoc = $model->hasMany[$assocModel];
						if( !empty( $assoc['foreignKey'] ) && !in_array( $model->{$assocModel}->useTable, $fkTablesTo ) ) {
							$buffer[] = "\t\tLa relation entre le modèle {$model->alias} et le modèle {$assocModel} ({$model->{$assocModel}->useTable}.{$assoc['foreignKey']}) n'est pas matérialisée par une clé étrangère au niveau de la base de données.";
							$error = $innerError = true;
						}
						break;
					case 'hasAndBelongsToMany':
						$assoc = $model->hasAndBelongsToMany[$assocModel];
						if( !empty( $assoc['associationForeignKey'] ) && !in_array( $model->{$assoc['with']}->useTable, $fkTablesTo ) ) {
							$buffer[] = "\t\tLa relation entre le modèle {$model->alias} et le modèle {$assoc['with']} ($model->{$assoc['with']}->useTable.{$assoc['associationForeignKey']}) n'est pas matérialisée par une clé étrangère au niveau de la base de données.";
							$error = $innerError = true;
						}
						break;
					default:
						die( $assocModel );
				}
			}
			if( !$innerError ) {
				$buffer[] = "\t\tOK";
			}

			if( $error ) {
				$this->err( $buffer );
			}
			else if( $this->showSuccess ) {
				$this->out( $buffer );
			}

			if( $error || $this->showSuccess ) {
				$this->hr();
			}
		}

		/**
		*
		*/
		protected function _modelsList( $accepted = array() ) {
			$models = array();
			$dirName = sprintf( '%smodels'.DS, APP );
			$dir = opendir( $dirName );
			while( ( $file = readdir( $dir ) ) !== false ) {
				$explose = explode( '~', $file );
				if( ( count( $explose ) == 1 ) && ( !is_dir( $dirName.$file ) ) && ( !in_array( $file, array( 'empty', '.svn' ) ) ) ) {
					$model = Inflector::classify( preg_replace( '/\.php$/', '', $file ) );
					if( empty( $accepted ) || in_array( $model, $accepted ) ) {
						$models[] = $model;
					}
				}
			}
			closedir( $dir );
			sort( $models );

			return $models;
		}

		/**
		*
		*/
		public function main() {
			$models = $this->_modelsList( $this->args );
			if( !empty( $models ) ) {
				foreach( $models as $modelName ) {
					$table = $this->_modelTable( $modelName );

					if( !empty( $table ) ) {
						$this->_check( $modelName );
					}
				}
			}
		}
	}
?>