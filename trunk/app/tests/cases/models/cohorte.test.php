<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Cohorte');

	class CohorteTestCase extends CakeAppModelTestCase
	{

		function testPreOrientation() {
			$element = array(
				'Personne' => array(
					'id' => '1',
					'foyer_id' => '1',
					'qual' => 'MR',
					'nom' => 'Dupond',
					'prenom' => 'Azerty',
					'nomnai' => null,
					'prenom2' => null,
					'prenom3' => null,
					'nomcomnai' => null,
					'dtnai' => '1979-01-24',
					'rgnai' => '1',
					'typedtnai' => null,
					'nir' => null,
					'topvalec' => null,
					'sexe' => 'M',
					'nati' => null,
					'dtnati' => null,
					'pieecpres' => null,
					'idassedic' => null,
					'numagenpoleemploi' => null,
					'dtinscpoleemploi' => null,
					'numfixe' => null,
					'numport' => null,
				),
			);
			$result = $this->Cohorte->preOrientation($element);
			$this->assertNull($result);
		}

		function testStructuresAutomatiques() {
			/**FIXME : problème d'importation de modèle
			* remplacer 
		* 		App::import( 'Model', 'Structurereferente' );
		* 		$this->Structurereferente = new Structurereferente();
		* 		App::import( 'Model', 'Typeorient' );
		* 		$this->Typeorient = new Typeorient();
			* par
		*		$this->Structurereferente = ClassRegistry::init( 'Structurereferente' );
		*		$this->Typeorient = ClassRegistry::init( 'Typeorient' );
		*/
			$expected=array(
				2 => array(
					34090 => '2_3'
				)
			);
			$result=$this->Cohorte->structuresAutomatiques();
			$this->assertEqual($result,$expected);
		}


		function testSearch() {
			$statuOrientation = 'Orienté';
			$mesCodesInsee = null;
			$filtre_zone_geo = null;
			$criteres = null;
			$lockedDossiers = null;
			$limit = 2147483647;
			$result = $this->Cohorte->search($statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers, $limit);
			$this->assertFalse($result);
		}

		function testSearch2() {
			$statuOrientation = 'Orienté';
			$mesCodesInsee = array('34090', '34080', '34070');
			$filtre_zone_geo = null;
			$criteres = null;
			$lockedSubquery = null;
			$limit = 2147483647;
			$result = $this->Cohorte->search2($statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedSubquery, $limit);
			$this->assertTrue($result);
		}
	}
?>
