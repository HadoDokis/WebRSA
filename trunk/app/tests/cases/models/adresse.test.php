<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Adresse');

	class AdresseTestCase extends CakeAppModelTestCase {

		function testListeCodesInsee() {
			$result=$this->Adresse->listeCodesInsee();
			$expected=array(
				'ighr8' => 'ighr8 Mont de Marsan',
				'pokf2' => 'pokf2 Montpellier'
			);
			$this->assertEqual($result,$expected);
		}
	}
?>
