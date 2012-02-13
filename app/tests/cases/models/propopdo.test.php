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

			$type = 'invalidparameter';
			$result = $this->Propopdo->prepare($type, null);
			$this->assertFalse($result);
*/
		}


		function testEtatPdo() {
			$pdo_id = '1';
			$this->Propopdo = ClassRegistry::init('Propopdo');
			$pdo = $this->Propopdo->find('first', array(
					'conditions' => array(
						'Propopdo.id' => $pdo_id
					)
				)
			);
			$result = $this->Propopdo->etatPdo($pdo);
			$this->assertFalse($result);
		}

		function testBeforeSave() {

			$pdo_id = '1';
			$this->Propopdo = ClassRegistry::init('Propopdo');
			$pdo = $this->Propopdo->find('first', array(
					'conditions' => array(
						'Propopdo.id' => $pdo_id
					)
				)
			);
			$this->Propopdo->data = $pdo;
			$options = null;
			$this->assertEqual($this->Propopdo->data['Propopdo']['etatdossierpdo'], 'instrencours');
			$result = $this->Propopdo->beforeSave($options);
			$this->assertTrue($result);
			$this->assertEqual($this->Propopdo->data['Propopdo']['etatdossierpdo'], 'instrencours');
		}

		function testEtatDossierPdo() {
			$typepdo_id = '1';
			$user_id = '1';
			$decisionpdo_id = '1';
			$avistechnique = '1';
			$validationavis = '1';
			$iscomplet = '1';
			$propopdo_id = '1';
			$result = $this->Propopdo->etatDossierPdo($typepdo_id, $user_id, $decisionpdo_id, $avistechnique, $validationavis, $iscomplet, $propopdo_id);
			$this->assertEqual($result, 'instrencours');
		}

		function testUpdateEtat() {
			$decisionpropopdo_id = '1';
			$result = $this->Propopdo->updateEtat($decisionpropopdo_id);
			$expected = array(
				'Propopdo' => array(
					'id' => '1',
					'etatdossierpdo' => 'instrencours',
					'modified' => date('Y-m-d H:i:s'),
				),
			);
			$this->assertEqual($result, $expected);
		}
	}
?>
