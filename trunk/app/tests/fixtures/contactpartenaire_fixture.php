<?php

	class ContactpartenaireFixture extends CakeTestFixture {
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
