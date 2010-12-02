<?php

	class ContactpartenairePartenaireFixture extends CakeTestFixture {
		var $name = 'ContactpartenairePartenaire';
		var $table = 'contactspartenaires_partenaires';
		var $import = array( 'table' => 'contactspartenaires_partenaires', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'partenaire_id' => '1',
				'contactpartenaire_id' => '1',
			)
		);
	}

?>
