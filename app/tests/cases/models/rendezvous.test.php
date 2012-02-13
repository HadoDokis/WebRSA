<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Rendezvous');

	class RendezvousTestCase extends CakeAppModelTestCase {
		function testDossierId() {
			// le parametre de dossierId() correspond au rdv_id
			$result = $this->Rendezvous->dossierId(1);
			$this->assertEqual('1', $result);

			$result = $this->Rendezvous->dossierId(2);
			$this->assertNull($result);

			$result = $this->Rendezvous->dossierId(42);
			$this->assertNull($result);
		}
	}

?>
