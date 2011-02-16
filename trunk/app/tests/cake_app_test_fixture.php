<?php

	class CakeAppTestFixture extends CakeTestFixture {
		
		public $masterDb = null;
		public $masterDbPrefix = null;
		public $testDb = null;
		public $testDbPrefix = null;
		public $_pgsqlEnumTypes = false;
		public $_masterTableTypes = array();

		/**
		* À partir du nom du type, on vérifie s'il existe déjà dans le base de test ou non.
		* S'il existe ou s'il n'a aucunes valeurs la fonction se termine.
		* S'il n'existe pas, on va rechercher ses valeurs et on le crée.
		*/

		protected function _createTypeIfNotExists( $typeName ) {
			$existsType = $this->testDb->query( "SELECT count (*) FROM pg_catalog.pg_type where typname= '{$this->testDbPrefix}{$typeName}';" );
			$values = $this->masterDb->query( "SELECT enum_range(null::{$this->masterDbPrefix}{$typeName});" );
			if( !empty( $values ) && $existsType[0][0]['count'] == 0 ) {
				$patterns = array( '{', '}' );
				$values = r( $patterns, '', Set::extract( $values, '0.0.enum_range' ) );
				$values = explode( ',', $values );
				
				$this->testDb->query( "CREATE TYPE {$this->testDbPrefix}{$typeName} AS ENUM ( '".implode( "', '", $values )."' );" );
			}
		}

		/**
		* Met à null la valeur par défaut du champ passé en paramètre et le cast avec ce type.
		*/

		protected function _alterColumns( $typeName, $columnName ) {
			$queries = array();
			
			if ( !$this->fields[$columnName]['null'] ) {
				$queries[] = "ALTER TABLE {$this->testDbPrefix}{$this->table} ALTER COLUMN {$columnName} DROP NOT NULL;";
			}
			
			$queries[] = "ALTER TABLE {$this->testDbPrefix}{$this->table} ALTER COLUMN {$columnName} SET DEFAULT NULL;";
			$queries[] = "ALTER TABLE {$this->testDbPrefix}{$this->table} ALTER COLUMN {$columnName} TYPE {$this->testDbPrefix}{$typeName} USING CAST({$columnName} AS {$this->testDbPrefix}{$typeName});";
			
			if ( !empty( $this->fields[$columnName]['default'] ) ) {
				$queries[] = "ALTER TABLE {$this->testDbPrefix}{$this->table} ALTER COLUMN {$columnName} SET DEFAULT '{$this->fields[$columnName]['default']}'::{$this->testDbPrefix}{$typeName};";
			}
			
			if ( !$this->fields[$columnName]['null'] ) {
				$queries[] = "ALTER TABLE {$this->testDbPrefix}{$this->table} ALTER COLUMN {$columnName} SET NOT NULL;";
			}

			foreach( $queries as $sql ) {
				$this->testDb->query( $sql );
			}
			
		}

		/**
		* Si la table est al dernière de la base à utiliser le type passé en paramètre, on supprime le type
		*/

		protected function _dropTypeIfLastTable( $typeName ) {
			$nbTableHaveType = $this->testDb->query( "SELECT COUNT( DISTINCT(table_name) ) FROM information_schema.columns WHERE data_type = 'USER-DEFINED' AND udt_name = '{$this->testDbPrefix}{$typeName}';" );
			if ( $nbTableHaveType <= 1 ) {
				$this->testDb->query( "DROP TYPE {$this->testDbPrefix}{$typeName} CASCADE" );
			}
		}

		/**
		* Retourne les types ainsi que les champs pour une table particulière. + valeurs par défaut
		* ex. pour la table users: array( 'type_no' => array( 'isgestionnaire', 'sensibilite' ) )
		*/

		protected function _masterTableTypes( $tableName ) {
			$results = $this->masterDb->query( "SELECT column_name, udt_name FROM information_schema.columns WHERE table_name = '{$this->masterDbPrefix}{$this->table}' AND data_type = 'USER-DEFINED';" );
			
			$return = array();
			foreach( $results as $key => $fields ) {
				$column_name = $fields[0]['column_name'];
				$udt_name = $fields[0]['udt_name'];
				$return[$udt_name][] = $column_name;
			}
			
			return $return;
		}


		/**
		* Création des champs "Enumerable" pour le modèle User
		*
		* @see http://www.tig12.net/downloads/apidocs/cakephp/cake/tests/lib/CakeTestFixture.class.html
		*/

		public function create( &$db ) {
			$return = parent::create( $db );
			$this->_initEnum( $db );
			return $return;
		}
		
		/**
		* Initialisation des enums
		*/
		protected function _initEnum( $db ) {
			if( $db->config['driver'] == 'postgres' ) {
				$this->testDb = $db;
				$this->testDbPrefix = $db->config['prefix'];
				
				$this->masterDb = ConnectionManager::getDataSource( 'default' );
				$this->masterDbPrefix = $this->masterDb->config['prefix'];
				
				$this->_masterTableTypes = $this->_masterTableTypes( $this->table );
				if ( !empty( $this->_masterTableTypes ) ) {
					$this->_pgsqlEnumTypes = true;
					
					foreach( $this->_masterTableTypes as $type => $fields) {
						$this->_createTypeIfNotExists( $type );
						foreach( $fields as $field ) {
							$this->_alterColumns( $type, $field );
						}
					}
				}
			}
		}
		
		/**
		*
		*/
		
		public function drop( &$db ) {
			$return = parent::drop( $db );
			$this->_dropEnum( $db );
			return $return;
		}
		
		/**
		* Suppression des enums
		*/
		
		protected function _dropEnum( $db ) {
			if( $this->_pgsqlEnumTypes && $db->config['driver'] == 'postgres' ) {
				/*$this->testDb = $db;
				$this->testDbPrefix = $db->config['prefix'];
				
				$this->masterDb = ConnectionManager::getDataSource( 'default' );
				$this->masterDbPrefix = $this->masterDb->config['prefix'];*/
				
				/// INFO: on estime qu'aucun enum ne sera modifié au cours des tests unitaires
				//$fieldsTypped = $this->_masterTableTypes( $this->table );
				
				foreach( $this->_masterTableTypes as $type => $fields) {
					$this->_dropTypeIfLastTable( $type );
				}
			}
		}
		
	}

?>
