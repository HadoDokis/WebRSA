<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Propopdo');

	class PropopdoTestCase extends CakeAppModelTestCase {

		function testPrepare() {
			$type = 'propopdo';
			$result = $this->Propopdo->prepare($type, null);
			$this->assertTrue($result);

/*
			$type = 'etat';
			$result = $this->Propopdo->prepare($type, null);
			$this->assertTrue($result);
*/
			$type = 'other';
			$result = $this->Propopdo->prepare($type, null);
			$this->assertFalse($result);
		}


		function testEtatPdo() {
			$pdo = null;
			$result = $this->Propopdo->etatPdo($pdo);
			$this->assertFalse($result);
		}

		function testBeforeSave() {
			$options = null;
			$result = $this->Propopdo->beforeSave($options);
			$this->assertTrue($result);
		}
	}
?>
