<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Cohorte');

	class CohorteTestCase extends CakeAppModelTestCase
	{

		function testPreOrientation() {
			$this->Personne = ClassRegistry::init( 'Personne' );
			$personne_id = '1';
			$element = $this->Personne->find(
							'first', array(
								'conditions' => array(
									'Personne.id' => $personne_id,
								)

						)
			);
			$result = $this->Cohorte->preOrientation($element);
			$this->assertEqual($result, 'Emploi');

			$personne_id = '2';
			$element = $this->Personne->find(
							'first', array(
								'conditions' => array(
									'Personne.id' => $personne_id,
								)

						)
			);
			$result = $this->Cohorte->preOrientation($element);
			$this->assertEqual($result, 'Social');

			$personne_id = '3';
			$element = $this->Personne->find(
							'first', array(
								'conditions' => array(
									'Personne.id' => $personne_id,
								)

						)
			);
			$result = $this->Cohorte->preOrientation($element);
			$this->assertEqual($result, 'Social');

			$personne_id = '4';
			$element = $this->Personne->find(
							'first', array(
								'conditions' => array(
									'Personne.id' => $personne_id,
								)

						)
			);
			$result = $this->Cohorte->preOrientation($element);
			$this->assertEqual($result, 'Emploi');

			$personne_id = '5';
			$element = $this->Personne->find(
							'first', array(
								'conditions' => array(
									'Personne.id' => $personne_id,
								)

						)
			);
			$result = $this->Cohorte->preOrientation($element);
			$this->assertEqual($result, 'Socioprofessionnelle');

			$personne_id = '6';
			$element = $this->Personne->find(
							'first', array(
								'conditions' => array(
									'Personne.id' => $personne_id,
								)

						)
			);
			$result = $this->Cohorte->preOrientation($element);
			$this->assertEqual($result, 'Social');

			$personne_id = '7';
			$element = $this->Personne->find(
							'first', array(
								'conditions' => array(
									'Personne.id' => $personne_id,
								)

						)
			);
			$result = $this->Cohorte->preOrientation($element);
			$this->assertEqual($result, 'Socioprofessionnelle');

		}

		function testRecherche() {
			$statutOrientation = 'Orienté';
			$mesCodesInsee = '93066';
			$filtre_zone_geo = null;
			$criteres = null;
			$lockedDossiers = null;
			$result = $this->Cohorte->recherche($statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers);
			$this->assertTrue($result);

			$statutOrientation = 'Non Orienté';
			$mesCodesInsee = '93066';
			$filtre_zone_geo = null;
			$criteres = null;
			$lockedDossiers = null;
			$result = $this->Cohorte->recherche($statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers);
			$this->assertTrue($result);
		}

		function testSearch() {
			$statutOrientation = 'Orienté';
			$mesCodesInsee = null;
			$filtre_zone_geo = null;
			$criteres = null;
			$lockedDossiers = null;
			$limit = 2147483647;
			$result = $this->Cohorte->search($statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers, $limit);
			$this->assertFalse($result);
		}

		function testSearch2() {
			$statutOrientation = 'Orienté';
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
