<?php

	class UserFixture extends CakeTestFixture {
		var $name = 'User';
		var $table = 'users';
		var $import = array( 'table' => 'users', 'connection' => 'default', 'records' => false);

		/**
		* Création des champs "Enumerable" pour le modèle User
		*
		* @see http://www.tig12.net/downloads/apidocs/cakephp/cake/tests/lib/CakeTestFixture.class.html
		*/

		public function create( &$db ) {
			$return = parent::create( $db );

			if( $db->config['driver'] == 'postgres' ) {
				$prefix = $db->config['prefix'];

				// SELECT enum_range(null::type_no);

				/*$masterDb = ConnectionManager::getDataSource( 'default' );
				debug( $masterDb );*/

				$queries = array(
// 					"CREATE TYPE type_no AS ENUM ( 'N', 'O' );",// FIXME
					// FIXME: passage de la valeur par défaut à NULL temporairement
					"ALTER TABLE {$prefix}users ALTER COLUMN isgestionnaire SET DEFAULT NULL;",
					"ALTER TABLE {$prefix}users ALTER COLUMN sensibilite SET DEFAULT NULL;",
					"ALTER TABLE {$prefix}users ALTER COLUMN isgestionnaire TYPE type_no USING CAST(isgestionnaire AS type_no);",
					"ALTER TABLE {$prefix}users ALTER COLUMN sensibilite TYPE type_no USING CAST(sensibilite AS type_no);"
				);

				foreach( $queries as $sql ) {
					$db->query( $sql );
				}
			}

			return $return;
		}

		/*
			SELECT DISTINCT(table_name) FROM information_schema.columns WHERE data_type = 'USER-DEFINED' AND udt_name = 'type_no';
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
