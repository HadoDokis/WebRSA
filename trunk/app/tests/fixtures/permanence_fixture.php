<?php

	class PermanenceFixture extends CakeTestFixture {
		var $name = 'Permanence';
		var $table = 'permanences';
		var $import = array( 'table' => 'permanences', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'structurereferente_id' => '1',
				'libpermanence' => 'libpermanence?',
				'numvoie' => null,
				'typevoie' => null,
				'nomvoie' => null,
				'codepos' => null,
				'ville' => null,
				'canton' => null,
				'numtel' => null,
				'compladr' => null,
			)
		);
	}

?>
