<?php

	class RendezvousFixture extends CakeTestFixture {
		var $name = 'Rendezvous';
		var $table = 'rendezvous';
		var $import = array( 'table' => 'rendezvous', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'structurereferente_id' => '1',
				'daterdv' => null,
				'objetrdv' => null,
				'commentairerdv' => null,
				'typerdv_id' => '1',
				'heurerdv' => null,
				'referent_id' => '1',
				'permanence_id' => '1',
				'statutrdv_id' => '1',
			),
		);
	}

?>
