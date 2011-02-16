<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class UserFixture extends CakeAppTestFixture {
		var $name = 'User';
		var $table = 'users';
		var $import = array( 'table' => 'users', 'connection' => 'default', 'records' => false);
		
		var $masterDb = null;
		var $masterDbPrefix = null;
		var $testDb = null;
		var $testDbPrefix = null;

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
			$queries = array(
				// FIXME: passage de la valeur par défaut à NULL temporairement
				"ALTER TABLE {$this->testDbPrefix}{$this->table} ALTER COLUMN {$columnName} SET DEFAULT NULL;",
				"ALTER TABLE {$this->testDbPrefix}{$this->table} ALTER COLUMN {$columnName} TYPE {$this->testDbPrefix}{$typeName} USING CAST(isgestionnaire AS {$this->testDbPrefix}{$typeName});"
			);

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
		* Retourne les types ainsi que les champs pour une table particulière.
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
		* @see http://www.tig12.net/downloads/apidocs/cakephp/cake/tests/lib/CakeAppTestFixture.class.html
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
				
				$fieldsTypped = $this->_masterTableTypes( $this->table );
				
				foreach( $fieldsTypped as $type => $fields) {
					$this->_createTypeIfNotExists( $type );
					foreach( $fields as $field ) {
						$this->_alterColumns( $type, $field );
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
			if( $db->config['driver'] == 'postgres' ) {
				$this->testDb = $db;
				$this->testDbPrefix = $db->config['prefix'];
				
				$this->masterDb = ConnectionManager::getDataSource( 'default' );
				$this->masterDbPrefix = $this->masterDb->config['prefix'];
				
				$fieldsTypped = $this->_masterTableTypes( $this->table );
				
				foreach( $fieldsTypped as $type => $fields) {
					$this->_dropTypeIfLastTable( $type );
				}
			}
		}

		/*
			SELECT DISTINCT(table_name) FROM information_schema.columns WHERE data_type = 'USER-DEFINED' AND udt_name = 'type_no';
			SELECT DISTINCT( udt_name ) FROM information_schema.columns WHERE table_name = 'users' AND data_type = 'USER-DEFINED';
		*/

		var $records = array(
			array(
				'id' => '4',
				'group_id' => '1',
				'serviceinstructeur_id' => '1',
				'username' => 'cg66',
				'password' => 'c41d80854d210d5f7512ab216b53b2f2b8e742dc',
				'nom' => null,
				'prenom' => null,
				'date_naissance' => null,
				'date_deb_hab' => null,
				'date_fin_hab' => null,
				'numtel' => null,
				'filtre_zone_geo' => '1',
				'numvoie' => null,
				'typevoie' => null,
				'nomvoie' => null,
				'compladr' => null,
				'codepos' => null,
				'ville' => null,
				'isgestionnaire' => null,
				'sensibilite' => null,
			),
			array(
				'id' => '3',
				'group_id' => '3',
				'serviceinstructeur_id' => '1',
				'username' => 'cg58',
				'password' => '5054b94efbf033a5fe624e0dfe14c8c0273fe320',
				'nom' => null,
				'prenom' => null,
				'date_naissance' => null,
				'date_deb_hab' => null,
				'date_fin_hab' => null,
				'numtel' => null,
				'filtre_zone_geo' => '1',
				'numvoie' => null,
				'typevoie' => null,
				'nomvoie' => null,
				'compladr' => null,
				'codepos' => null,
				'ville' => null,
				'isgestionnaire' => null,
				'sensibilite' => null,
			),
			array(
				'id' => '1',
				'group_id' => '2',
				'serviceinstructeur_id' => '1',
				'username' => 'cg23',
				'password' => 'e711d517faf274f83262f0cdd616651e7590927e',
				'nom' => null,
				'prenom' => null,
				'date_naissance' => null,
				'date_deb_hab' => null,
				'date_fin_hab' => null,
				'numtel' => null,
				'filtre_zone_geo' => '1',
				'numvoie' => null,
				'typevoie' => null,
				'nomvoie' => null,
				'compladr' => null,
				'codepos' => null,
				'ville' => null,
				'isgestionnaire' => null,
				'sensibilite' => null,
			),
			array(
				'id' => '2',
				'group_id' => '2',
				'serviceinstructeur_id' => '1',
				'username' => 'cg54',
				'password' => '13bdf5c43c14722e3e2d62bfc0ff0102c9955cda',
				'nom' => null,
				'prenom' => null,
				'date_naissance' => null,
				'date_deb_hab' => null,
				'date_fin_hab' => null,
				'numtel' => null,
				'filtre_zone_geo' => '1',
				'numvoie' => null,
				'typevoie' => null,
				'nomvoie' => null,
				'compladr' => null,
				'codepos' => null,
				'ville' => null,
				'isgestionnaire' => null,
				'sensibilite' => null,
			),
			array(
				'id' => '6',
				'group_id' => '1',
				'serviceinstructeur_id' => '1',
				'username' => 'webrsa',
				'password' => '83a98ed2a57ad9734eb0a1694293d03c74ae8a57',
				'nom' => 'auzolat',
				'prenom' => 'arnaud',
				'date_naissance' => '1981-09-11',
				'date_deb_hab' => '2000-01-01',
				'date_fin_hab' => '2020-12-31',
				'numtel' => '0466666666',
				'filtre_zone_geo' => null,
				'numvoie' => null,
				'typevoie' => null,
				'nomvoie' => null,
				'compladr' => null,
				'codepos' => null,
				'ville' => null,
				'isgestionnaire' => null,
				'sensibilite' => null,
			),
			array(
				'id' => '5',
				'group_id' => '1',
				'serviceinstructeur_id' => '1',
				'username' => 'cg93',
				'password' => 'ac860f0d3f51874b31260b406dc2dc549f4c6cde',
				'nom' => 'cg93',
				'prenom' => 'cg93',
				'date_naissance' => '1977-01-02',
				'date_deb_hab' => '2009-01-01',
				'date_fin_hab' => '2020-12-31',
				'numtel' => '0466666666',
				'filtre_zone_geo' => null,
				'numvoie' => null,
				'typevoie' => null,
				'nomvoie' => null,
				'compladr' => null,
				'codepos' => null,
				'ville' => null,
				'isgestionnaire' => null,
				'sensibilite' => null,
			),
		);
	}

?>
