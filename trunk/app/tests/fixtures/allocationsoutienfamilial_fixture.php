<?php

	class AllocationsoutienfamilialFixture extends CakeTestFixture {
		var $name = 'Allocationsoutienfamilial';
		var $table = 'allocationssoutienfamilial';
		var $import = array( 'table' => 'allocationssoutienfamilial', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'sitasf' => null,
				'parassoasf' => null,
				'ddasf' => null,
				'dfasf' => null,
				'topasf' => null,
				'topdemasf' => null,
				'topenfreconn' => null,
			),
		);
	}

?>		
