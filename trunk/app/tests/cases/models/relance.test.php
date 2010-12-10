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
			$statutRelance = $p1 = null;
			$mesCodesInsee = $p2 = null;
			$filtre_zone_geo  = $p3 = null; 
			$criteresrelance = $p4 = null;
			$lockedDossiers = $p5 = null;
			$result = $this->Relance->search($p1, $p2, $p3, $p4, $p5);
			$this->assertTrue($result);
		}
	}

?>
