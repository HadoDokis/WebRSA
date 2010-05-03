<?php

	class UserFixture extends CakeTestFixture {
		var $name = 'User';
		var $table = 'users';
		var $import = array( 'table' => 'users', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'group_id' => '1',
				'serviceinstructeur_id' => '1',
				'username' => 'test1',
				'password' => 'motdepassesur40caracteresquineserapaslu.',
				'nom' => 'bono',
				'prenom' => 'jean',
				'date_naissance' => '1985-03-23',
				'date_deb_hab' => '2009-01-01',
				'date_fin_hab' => '2020-12-31',
				'numtel' => '0123456789',
				'filtre_zone_geo' => null
			),
			array(
				'id' => '2',
				'group_id' => '2',
				'serviceinstructeur_id' => '1',
				'username' => 'test2',
				'password' => 'motdepassesur40caracteresquineserapaslu.',
				'nom' => 'zétofraie',
				'prenom' => 'mélanie',
				'date_naissance' => '1983-12-25',
				'date_deb_hab' => '2009-01-01',
				'date_fin_hab' => '2020-12-31',
				'numtel' => '0213456789',
				'filtre_zone_geo' => null
			),
			array(
				'id' => '3',
				'group_id' => '1',
				'serviceinstructeur_id' => '1',
				'username' => 'test3',
				'password' => 'motdepassesur40caracteresquineserapaslu.',
				'nom' => 'deuf',
				'prenom' => 'john',
				'date_naissance' => '1980-01-01',
				'date_deb_hab' => '2009-01-01',
				'date_fin_hab' => '2020-12-31',
				'numtel' => '0312456789',
				'filtre_zone_geo' => true
			)
		);
	}

?>