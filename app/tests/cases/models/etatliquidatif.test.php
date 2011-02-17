<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Etatliquidatif');
	App::import('Core', 'Sanitize');

	class EtatliquidatifTestCase extends CakeAppModelTestCase
	{

		function testListeApres() {
			$expected=array(
				'fields' => array(
					0 => 'Apre.id',
					1 => 'Apre.personne_id',
					2 => 'Apre.numeroapre',
					3 => 'Apre.statutapre',
					4 => 'Apre.datedemandeapre',
					5 => 'Apre.mtforfait',
					6 => 'Apre.montantaverser',
					7 => 'Apre.nbenf12',
					8 => 'Apre.nbpaiementsouhait',
					9 => 'Apre.montantdejaverse',
					10 => 'Personne.nom',
					11 => 'Personne.prenom',
					12 => 'Personne.qual',
					13 => 'Dossier.numdemrsa',
					14 => 'Adresse.locaadr',
					15 => 'Adresse.numvoie',
					16 => 'Adresse.nomvoie',
					17 => 'Adresse.complideadr',
					18 => 'Adresse.compladr',
					19 => 'Adresse.typevoie',
					20 => 'Adresse.codepos'
				),
				'joins' => array(
					0 => array(
				        'table' => 'personnes',
				        'alias' => 'Personne',
				        'type' => 'INNER',
				        'foreignKey' => '',
				        'conditions' => array(
							0 => 'Apre.personne_id = Personne.id'
						)
				    ),
					1 => array(
				        'table' => 'foyers',
				        'alias' => 'Foyer',
				        'type' => 'INNER',
				        'foreignKey' => '',
				        'conditions' => array(
				                0 => 'Personne.foyer_id = Foyer.id'
						)
					),
					2 => array(
				        'table' => 'dossiers',
				        'alias' => 'Dossier',
				        'type' => 'INNER',
				        'foreignKey' => '',
				        'conditions' => array(
				                0 => 'Foyer.dossier_id = Dossier.id'
				            )
				    ),
					3 => array(
						'table' => 'adressesfoyers',
						'alias' => 'Adressefoyer',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Foyer.id = Adressefoyer.foyer_id',
							1 => 'Adressefoyer.rgadr = \'01\'',
							2 => 'Adressefoyer.id IN ( SELECT MAX(adressesfoyers.id)
								FROM adressesfoyers
								WHERE adressesfoyers.rgadr = \'01\'
								GROUP BY adressesfoyers.foyer_id
							)'
						)
					),
					4 => array(
						'table' => 'adresses',
						'alias' => 'Adresse',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Adresse.id = Adressefoyer.adresse_id'
						)
					),
					5 => array(
						'table' => 'formsqualifs',
						'alias' => 'Formqualif',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Formqualif.apre_id'
						)
					),
					6 => array(
						'table' => 'formspermsfimo',
						'alias' => 'Formpermfimo',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Formpermfimo.apre_id'
						)
					),
					7 => array(
						'table' => 'actsprofs',
						'alias' => 'Actprof',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Actprof.apre_id'
						)
					),
					8 => array(
						'table' => 'permisb',
						'alias' => 'Permisb',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Permisb.apre_id'
						)
					),
					9 => array(
						'table' => 'amenagslogts',
						'alias' => 'Amenaglogt',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Amenaglogt.apre_id'
						)
					),
					10 => array(
						'table' => 'accscreaentr',
						'alias' => 'Acccreaentr',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Acccreaentr.apre_id'
						)
					),
					11 => array(
						'table' => 'acqsmatsprofs',
						'alias' => 'Acqmatprof',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Acqmatprof.apre_id'
						)
					),
					12 => array(
						'table' => 'locsvehicinsert',
						'alias' => 'Locvehicinsert',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Locvehicinsert.apre_id'
						)
					),
				),
				'recursive' => 1,
				'conditions' => array(
					0 => 'ici ma condition',
					'Apre.eligibiliteapre' => 'O'
				)
			);
			$result=$this->Etatliquidatif->listeApres("ici ma condition");
			$this->assertEqual($result,$expected);
		}

		function testListeApresEtatLiquidatif() {
			$conditions = 'ici ma condition';
			$etatliquidatif_id = '1';
			$result = $this->Etatliquidatif->listeApresEtatLiquidatif($conditions, $etatliquidatif_id);
			$this->assertTrue($result);
		}
	
		function testListeApresEtatLiquidatifNonTermine() {
			$conditions = 'ici ma condition';
			$etatliquidatif_id = '1';
			$result = $this->Etatliquidatif->listeApresEtatLiquidatifNonTermine($conditions, $etatliquidatif_id);
			$this->assertTrue($result);
		}
	
		function testListeApresEtatLiquidatifNonTerminePourVersement() {
			$conditions = 'ici ma condition';
			$etatliquidatif_id = '1';
			$result = $this->Etatliquidatif->listeApresEtatLiquidatifNonTerminePourVersement($conditions, $etatliquidatif_id);
			$this->assertTrue($result);
		}

		function testListeApresSansEtatLiquidatif() {
			$conditions = 'ici ma condition';
			$result = $this->Etatliquidatif->listeApresSansEtatLiquidatif($conditions);
			$this->assertTrue($result);
		}

		function testListeApresPourEtatLiquidatif() {
			$conditions = 'ici ma condition';
			$etatliquidatif_id = '1';
			$result = $this->Etatliquidatif->listeApresPourEtatLiquidatif($etatliquidatif_id, $conditions);
			$this->assertTrue($result);
		}
/*
		function testHopeyra() {
			$id = '1';
			$typeapre = 'forfaitaire';
			$result = $this->Etatliquidatif->hopeyra($id, $typeapre);

			$id = '1';
			$typeapre = 'complementaire';
			$result = $this->Etatliquidatif->hopeyra($id, $typeapre);
		}
	*/
		function testPdf() {
			$id = '1';
			$typeapre = 'complementaire';
			$result = $this->Etatliquidatif->pdf($id, $typeapre);
			//var_dump($result);
			$id = '1';
			$typeapre = 'forfaitaire';
			$result = $this->Etatliquidatif->pdf($id, $typeapre);
			//var_dump($result);
		}

	}
?>
