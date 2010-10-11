<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Indicateurmensuel');

	class IndicateurmensuelTestCase extends CakeAppModelTestCase {

		function testNbrDossiersInstruits() {
			$result=$this->Indicateurmensuel->_nbrDossiersInstruits(2009);
			$expected=array(
				2 => 1,
				5 => 1
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrDossiersInstruits(2010);
			$expected=array(
				1 => 1,
				2 => 1,
				4 => 1
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrDossiersInstruits(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testNbrDossiersRejetesCaf() {
			$result=$this->Indicateurmensuel->_nbrDossiersRejetesCaf(2009);
			$expected=array(
				5 => 1
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrDossiersRejetesCaf(2010);
			$expected=array();
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrDossiersRejetesCaf(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testNbrOuverturesDroits() {
			$result=$this->Indicateurmensuel->_nbrOuverturesDroits(2009);
			$expected=array(
				2 => 1
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrOuverturesDroits(2010);
			$expected=array(
				1 => 1,
				2 => 1,
				4 => 1
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrOuverturesDroits(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testNbrAllocatairesDroitsEtDevoirs() {
			$result=$this->Indicateurmensuel->_nbrAllocatairesDroitsEtDevoirs(2009);
			$expected=array(
				2 => 2,
				5 => 1
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrAllocatairesDroitsEtDevoirs(2010);
			$expected=array(
				1 => 2,
				2 => 1,
				4 => 2
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrAllocatairesDroitsEtDevoirs(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testNbrPreorientations() {
			$result=$this->Indicateurmensuel->_nbrPreorientations(2009,'Socioprofessionnelle');
			$expected=array(
				1 => 1,
				3 => 1
			);
			$this->assertEqual($expected,$result);
			$result=$this->Indicateurmensuel->_nbrPreorientations(2009,'Emploi');
			$expected=array(
				1 => 1,
				3 => 1,
				7 => 1
			);
			$this->assertEqual($expected,$result);
			$result=$this->Indicateurmensuel->_nbrPreorientations(2009,'Social');
			$expected=array(
				1 => 1
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrPreorientations(2010,'Socioprofessionnelle');
			$expected=array();
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrPreorientations(2010,'Emploi');
			$expected=array(
				7 => 1,
				11 => 1
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrPreorientations(2010,'Social');
			$expected=array(
				8 => 1
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrPreorientations(2100,'Socioprofessionnelle');
			$expected=array();
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrPreorientations(2100,'Emploi');
			$expected=array();
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_nbrPreorientations(2100,'Social');
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testDelaiOuvertureNotification() {
			$result=$this->Indicateurmensuel->_delaiOuvertureNotification(2009);
			$expected=array(
				2 => 418.0000000000000000,
				5 => 249.0000000000000000
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_delaiOuvertureNotification(2010);
			$expected=array(
				1 => 158.0000000000000000,
				2 => 257.0000000000000000,
				4 => 292.0000000000000000
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_delaiOuvertureNotification(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testDelaiNotificationSignature() {
			$result=$this->Indicateurmensuel->_delaiNotificationSignature(2009);
			$expected=array(
				1 => 151.0000000000000000,
				2 => 50.0000000000000000,
				6 => 272.0000000000000000,
				11 => 176.0000000000000000
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_delaiNotificationSignature(2010);
			$expected=array(
				4 => 431.0000000000000000,
				6 => 112.5000000000000000
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_delaiNotificationSignature(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testMontantsIndusConstates() {
			$result=$this->Indicateurmensuel->_montantsIndusConstates(2009);
			$expected=array(
				4 => 14,
				6 => 561
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_montantsIndusConstates(2010);
			$expected=array(
				7 => 43
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_montantsIndusConstates(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testMontantsIndusTransferes() {
			$result=$this->Indicateurmensuel->_montantsIndusTransferes(2009);
			$expected=array(
				1 => 75,
				4 => 678
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_montantsIndusTransferes(2010);
			$expected=array(
				9 => 871
			);
			$this->assertEqual($expected,$result);

			$result=$this->Indicateurmensuel->_montantsIndusTransferes(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

	}
?>
