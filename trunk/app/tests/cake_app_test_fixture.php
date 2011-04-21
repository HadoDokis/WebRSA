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
			//$this->testDb->query( 'SELECT 1;' );
			$sql = "SELECT count(*) FROM pg_catalog.pg_type where typname = '{$this->testDbPrefix}{$typeName}';";
			$existsType = $this->testDb->query( $sql );
			$existsType = $existsType[0][0]['count'];
			//$this->log( sprintf( '%s (%s, %s), %s', $sql, __LINE__, $this->testDb->config['database'], $existsType ), LOG_DEBUG);

			$values = $this->masterDb->query( "SELECT enum_range(null::{$this->masterDbPrefix}{$typeName});" );
			if( !empty( $values ) && $existsType == 0 ) {
				$patterns = array( '{', '}' );
				$values = r( $patterns, '', Set::extract( $values, '0.0.enum_range' ) );
				$values = explode( ',', $values );

				//$this->testDb->query( 'SELECT 2;' );
				$sql = "CREATE TYPE {$this->testDbPrefix}{$typeName} AS ENUM ( '".implode( "', '", $values )."' );";
				$this->testDb->query( $sql);
				//$this->log( sprintf( '%s (%s, %s)', $sql, __LINE__, $this->testDb->config['database']), LOG_DEBUG);

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
				//$this->log( sprintf( '%s (%s, %s)', $sql, __LINE__, $this->testDb->config['database']), LOG_DEBUG);
			}

		}

		/**
		* Si la table est al dernière de la base à utiliser le type passé en paramètre, on supprime le type
		*/

		protected function _dropTypeIfLastTable( $typeName ) {
			$sql = "SELECT COUNT( DISTINCT(table_name) ) FROM information_schema.columns WHERE data_type = 'USER-DEFINED' AND udt_name = '{$this->testDbPrefix}{$typeName}' AND table_name <> '{$this->testDbPrefix}{$this->table}';";
			$nbTableHaveType = $this->testDb->query( $sql );
			$nbTableHaveType = $nbTableHaveType[0][0]['count'];
			//$this->log( sprintf( '%s (%s, %s), %s', $sql, __LINE__, $this->testDb->config['database'], $nbTableHaveType), LOG_DEBUG);

			if ( $nbTableHaveType == 0 ) {
				$sql = "DROP TYPE {$this->testDbPrefix}{$typeName} CASCADE";
				$this->testDb->query( $sql );
				//$this->log( sprintf( '%s (%s, %s)', $sql, __LINE__, $this->testDb->config['database']), LOG_DEBUG);
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
			//$this->log( "CREATE TABLE {$this->table};", LOG_DEBUG);
			$return = parent::create( $db );
			$this->_initEnum( $db );
			return $return;
		}

		/**
		* Initialisation des enums
		*/
		protected function _initEnum( $db ) {
			if( $db->config['driver'] == 'postgres' ) {
				////$this->log( "----- _initEnum {$this->table}", LOG_DEBUG );
				$this->testDb = $db;
				$this->testDbPrefix = $db->config['prefix'];

				$this->masterDb = ConnectionManager::getDataSource( 'default' );
				$this->masterDbPrefix = $this->masterDb->config['prefix'];

				$masterTableTypes = $this->_masterTableTypes( $this->table );
				if ( !empty( $masterTableTypes ) ) {
					foreach( $masterTableTypes as $type => $fields) {
						$this->_createTypeIfNotExists( $type );
						foreach( $fields as $field ) {
							//$this->log("inittype=> '$type'", LOG_DEBUG);
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
			//$this->log( "DROP TABLE {$this->table};", LOG_DEBUG);
			$return = parent::drop( $db );
			$this->_dropEnum( $db );
			return $return;
		}

		/**
		* Suppression des enums
		*/

		protected function _dropEnum( $db ) {
			if( $db->config['driver'] == 'postgres' ) {
				////$this->log( "----- _dropEnum {$this->table}", LOG_DEBUG );

				$masterTableTypes = $this->_masterTableTypes( $this->table );
				if ( !empty( $masterTableTypes ) ) {
					foreach( $masterTableTypes as $type => $fields) {
						//$this->log("droptype=> '$type'", LOG_DEBUG);
						$this->_dropTypeIfLastTable( $type );
					}
				}
			}
		}

	}

?>
