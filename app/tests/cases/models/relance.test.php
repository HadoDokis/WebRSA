<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Relance');

	class RelanceTestCase extends CakeAppModelTestCase {

		function testMountComparator() {
			$data = null;
			$result = $this->Relance->mountComparator($data);
			$this->assertTrue($result);
		}

		function testQuerydata() {
			$type = null;
			$params = null;
			$result = $this->Relance->querydata($type, $params);
			$this->assertFalse($result);
		}

		function testSearch() {
			$statutRelance = null;
			$mesCodesInsee = null;
			$filtre_zone_geo = null; 
			$criteresrelance = null;
			$lockedDossiers = null;
			$result = $this->Relance->search($statutRelance, $mesCodesInsee, $filtre_zone_geo, $criteresrelance, $lockedDossiers);
			$this->assertTrue($result);
		}
	}

?>
