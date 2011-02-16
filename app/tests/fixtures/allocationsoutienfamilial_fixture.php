<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class AllocationsoutienfamilialFixture extends CakeAppTestFixture {
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
