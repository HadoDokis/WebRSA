<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ContactpartenaireFixture extends CakeAppTestFixture {
		var $name = 'Contactpartenaire';
		var $table = 'contactspartenaires';
		var $import = array( 'table' => 'contactspartenaires', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'qual' => '??',
				'nom' => 'Dupond',
				'prenom' => 'Azerty',
				'numtel' => null,
				'email' => null,
				'partenaire_id' => '1'
			),
		);
	}

?>
