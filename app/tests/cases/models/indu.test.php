<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Indu');

	class InduTestCase extends CakeAppModelTestCase {
		
		//test fonction search($mescodesinsee, $filtre_zone_geo, $criteres)
		function testSearch() {
			$result = $this->Indu->search(array(), array(), array('Dossier.id' => '1'));
			$this->assertTrue($result);
		}
	}

?>
