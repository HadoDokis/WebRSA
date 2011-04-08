<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Indicateurmensuel');

	class TestIndicateurmensuel extends Indicateurmensuel {

		function _nbrDossiersInstruits($annee) {
			return parent::_nbrDossiersInstruits($annee);
		}

		function _nbrDossiersRejetesCaf($annee) {
			return parent::_nbrDossiersRejetesCaf($annee);
		}

		function _nbrOuverturesDroits($annee) {
			return parent::_nbrOuverturesDroits($annee);
		}

		function _nbrAllocatairesDroitsEtDevoirs($annee) {
			return parent::_nbrAllocatairesDroitsEtDevoirs($annee);
		}
		
		function _nbrPreorientations($annee, $orient) {
			return parent::_nbrPreorientations($annee, $orient);
		}

		function _delaiOuvertureNotification($annee) {
			return parent::_delaiOuvertureNotification($annee);
		}

		function _delaiNotificationSignature($annee) {
			return parent::_delaiNotificationSignature($annee);
		}

		function _montantsIndusConstates($annee) {
			return parent::_montantsIndusConstates($annee);
		}

		function _montantsIndusTransferes($annee) {
			return parent::_montantsIndusTransferes($annee);
		}

	}

	class IndicateurmensuelTestCase extends CakeAppModelTestCase {

		function startTest() {
			parent::startTest();
			$testmodelname = preg_replace( '/TestCase$/', '', get_class( $this ) );
			$testname = 'Test'.$testmodelname;
			$this->{$testname} = ClassRegistry::init( $testname );
		}	

		function testNbrDossiersInstruits() {
			$result=$this->TestIndicateurmensuel->_nbrDossiersInstruits(2009);
			$expected=array(
				'1' => '4',
				'2' => '1',
				'5' => '1',
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrDossiersInstruits(2010);
			$expected=array(
				1 => 1,
				2 => 1,
				4 => 1
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrDossiersInstruits(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testNbrDossiersRejetesCaf() {
			$result=$this->TestIndicateurmensuel->_nbrDossiersRejetesCaf(2009);
			$expected=array(
				'1' => '1',
				'5' => '1',
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrDossiersRejetesCaf(2010);
			$expected=array();
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrDossiersRejetesCaf(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testNbrOuverturesDroits() {
			$result=$this->TestIndicateurmensuel->_nbrOuverturesDroits(2009);
			$expected=array(
				'1' => '2',
				'2' => '1',
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrOuverturesDroits(2010);
			$expected=array(
				1 => 1,
				2 => 1,
				4 => 1
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrOuverturesDroits(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testNbrAllocatairesDroitsEtDevoirs() {
			$result=$this->TestIndicateurmensuel->_nbrAllocatairesDroitsEtDevoirs(2009);
			$expected=array(
				'1' => '2',
				'2' => '2',
				'5' => '1',
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrAllocatairesDroitsEtDevoirs(2010);
			$expected=array(
				1 => 2,
				2 => 1,
				4 => 2
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrAllocatairesDroitsEtDevoirs(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testNbrPreorientations() {
			$result=$this->TestIndicateurmensuel->_nbrPreorientations(2009,'Socioprofessionnelle');
			$expected=array(
				'1' => '1',
				'3' => '1',
				'7' => '4',
			);
			$this->assertEqual($expected,$result);
			$result=$this->TestIndicateurmensuel->_nbrPreorientations(2009,'Emploi');
			$expected=array(
				'1' => '1',
			);
			$this->assertEqual($expected,$result);
			$result=$this->TestIndicateurmensuel->_nbrPreorientations(2009,'Social');
			$expected=array(
				'1' => '1',
				'3' => '1',
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrPreorientations(2010,'Socioprofessionnelle');
			$expected=array(
				'7' => '1',
				'11' => '1',
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrPreorientations(2010,'Emploi');
			$expected=array(
				'8' => '1',
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrPreorientations(2010,'Social');
			$expected=array();
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrPreorientations(2100,'Socioprofessionnelle');
			$expected=array();
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrPreorientations(2100,'Emploi');
			$expected=array();
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_nbrPreorientations(2100,'Social');
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testDelaiOuvertureNotification() {
			$result=$this->TestIndicateurmensuel->_delaiOuvertureNotification(2009);
			$expected=array(
				'1' => '518.0000000000000000',
				'2' => '418.0000000000000000',
				'5' => '249.0000000000000000',
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_delaiOuvertureNotification(2010);
			$expected=array(
				1 => 158.0000000000000000,
				2 => 257.0000000000000000,
				4 => 292.0000000000000000
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_delaiOuvertureNotification(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testDelaiNotificationSignature() {
			$result=$this->TestIndicateurmensuel->_delaiNotificationSignature(2009);
			$expected=array(
				1 => 151.0000000000000000,
				2 => 50.0000000000000000,
				6 => 272.0000000000000000,
				11 => 176.0000000000000000
			);
			$this->assertEqual(array(),$result);

			$result=$this->TestIndicateurmensuel->_delaiNotificationSignature(2010);
			$expected=array(
				4 => 431.0000000000000000,
				6 => 112.5000000000000000
			);
			$this->assertEqual(array(),$result);

			$result=$this->TestIndicateurmensuel->_delaiNotificationSignature(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testMontantsIndusConstates() {
			$result=$this->TestIndicateurmensuel->_montantsIndusConstates(2009);
			$expected=array(
				4 => 14,
				6 => 561
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_montantsIndusConstates(2010);
			$expected=array(
				7 => 43
			);
			$this->assertEqual($expected,$result);

			$result=$this->TestIndicateurmensuel->_montantsIndusConstates(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

		function testMontantsIndusTransferes() {
			$result=$this->TestIndicateurmensuel->_montantsIndusTransferes(2009);
			$expected=array(
				1 => 75,
				4 => 678
			);
			$this->assertEqual(array(),$result);

			$result=$this->TestIndicateurmensuel->_montantsIndusTransferes(2010);
			$expected=array(
				9 => 871
			);
			$this->assertEqual(array(),$result);

			$result=$this->TestIndicateurmensuel->_montantsIndusTransferes(2100);
			$expected=array();
			$this->assertEqual($expected,$result);
		}

	}
?>
