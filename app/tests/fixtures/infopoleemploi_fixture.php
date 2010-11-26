<?php

	class InfopoleemploiFixture extends CakeTestFixture {
		var $name = 'Infopoleemploi';
		var $table = 'infospoleemploi';
		var $import = array( 'table' => 'infospoleemploi', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'identifiantpe' => null,
				'dateinscription' => null,
				'categoriepe' => null,
				'datecessation' => null,
				'motifcessation' => null,
				'dateradiation' => null,
				'motifradiation' => null,
			)
		);
	}

?>
