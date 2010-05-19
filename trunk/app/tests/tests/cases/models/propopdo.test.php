<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Propopdo');

	class PropopdoTestCase extends CakeAppModelTestCase {

		function testDossierId() {
			///FIXME: enlever le debug dans le model à la fin de la fonction je pense pas que ce soit normal qu'il traine
			$result=$this->Propopdo->dossierId(1);
			$this->assertEqual(1,$result);

			//------------------------------------------------------------------

			$result=$this->Propopdo->dossierId(2);
			$this->assertEqual(2,$result);

			//------------------------------------------------------------------

			$result=$this->Propopdo->dossierId(666);
			$this->assertNull($result);
		}

	}
?>
