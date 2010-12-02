<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Cohorte');

	class CohorteTestCase extends CakeAppModelTestCase
	{

		function testStructuresAutomatiques() {
			/**FIXME : problème d'importation de modèle
			* remplacer 
            * 		App::import( 'Model', 'Structurereferente' );
            * 		$this->Structurereferente = new Structurereferente();
            * 		App::import( 'Model', 'Typeorient' );
            * 		$this->Typeorient = new Typeorient();
            * par
            *       $this->Structurereferente = ClassRegistry::init( 'Structurereferente' );
            * 		$this->Typeorient = ClassRegistry::init( 'Typeorient' );
			*/
			$expected=array(
				2 => array(
					34090 => '2_3'
				)
			);
			$result=$this->Cohorte->structuresAutomatiques();
			$this->assertEqual($result,$expected);
		}

		/*
		function testSearch() {
			$result = $this->Cohorte->search('Orienté', null, null, null, null, 2147483647);
			$this->assertFalse($result);
		}
		*/
		
		function testSearch2() {
			$result = $this->Cohorte->search2('Orienté', null, null, null, 2147483647);
			$this->assertTrue($result);
		}
	}
?>
