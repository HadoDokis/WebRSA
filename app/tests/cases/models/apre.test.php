<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Apre');

	class ApreTestCase extends CakeAppModelTestCase
	{

		function testSousRequeteMontanttotal() {
			$expected="( COALESCE( \"Formqualif\".\"montantaide\", 0 ) + COALESCE( \"Formpermfimo\".\"montantaide\", 0 ) + COALESCE( \"Actprof\".\"montantaide\", 0 ) + COALESCE( \"Permisb\".\"montantaide\", 0 ) + COALESCE( \"Amenaglogt\".\"montantaide\", 0 ) + COALESCE( \"Acccreaentr\".\"montantaide\", 0 ) + COALESCE( \"Acqmatprof\".\"montantaide\", 0 ) + COALESCE( \"Locvehicinsert\".\"montantaide\", 0 ) )";
			$result=$this->Apre->sousRequeteMontanttotal();
			$this->assertEqual($expected,$result);
		}

		function testJoinsAidesLiees() {
			$tiersprestataire = false;
			$result=$this->Apre->joinsAidesLiees($tiersprestataire);
			$expected=array(
				0 => array(
					'table' => 'formsqualifs',
					'alias' => 'Formqualif',
					'type' => 'LEFT OUTER',
					'foreignKey' => '',
					'conditions' => array(
						0 => 'Apre.id = Formqualif.apre_id'
					)
				),
				'1' => array(
					'table' => 'formspermsfimo',
					'alias' => 'Formpermfimo',
					'type' => 'LEFT OUTER',
					'foreignKey' => '',
					'conditions' => array(
						'0' => 'Apre.id = Formpermfimo.apre_id'
					)
				),
				'2' => array(
					'table' => 'actsprofs',
					'alias' => 'Actprof',
					'type' => 'LEFT OUTER',
					'foreignKey' => '',
					'conditions' => array(
					'0' => 'Apre.id = Actprof.apre_id'
					)
				),
				'3' => array(
					'table' => 'permisb',
					'alias' => 'Permisb',
					'type' => 'LEFT OUTER',
					'foreignKey' => '',
					'conditions' => array(
						'0' => 'Apre.id = Permisb.apre_id'
					)
				),
				'4' => array(
					'table' => 'amenagslogts',
					'alias' => 'Amenaglogt',
					'type' => 'LEFT OUTER',
					'foreignKey' => '',
					'conditions' => array(
						'0' => 'Apre.id = Amenaglogt.apre_id'
					)
				),
				'5' => array(
					'table' => 'accscreaentr',
					'alias' => 'Acccreaentr',
					'type' => 'LEFT OUTER',
					'foreignKey' => '',
					'conditions' => array(
						'0' => 'Apre.id = Acccreaentr.apre_id'
					)
				),
				'6' => array(
					'table' => 'acqsmatsprofs',
					'alias' => 'Acqmatprof',
					'type' => 'LEFT OUTER',
					'foreignKey' => '',
					'conditions' => array(
						'0' => 'Apre.id = Acqmatprof.apre_id'
					)
				),
				'7' => array(
					'table' => 'locsvehicinsert',
					'alias' => 'Locvehicinsert',
					'type' => 'LEFT OUTER',
					'foreignKey' => '',
					'conditions' => array(
						'0' => 'Apre.id = Locvehicinsert.apre_id'
					)
				)
			);
			$this->assertEqual($expected,$result);
		}

		function testDossierId() {
			$this->assertEqual(1,$this->Apre->dossierId(1));
			$this->assertEqual(3,$this->Apre->dossierId(2));
			$this->assertNull($this->Apre->dossierId(666));
		}

		function test_nbrNormalPieces() {
			$result = $this->Apre->_nbrNormalPieces();
			$expected = array(
				'Apre' => '1',
				'Formqualif' => '2',
				'Formpermfimo' => '2',
				'Actprof' => '2',
				'Permisb' => '2',
				'Amenaglogt' => '2',
				'Acccreaentr' => '2',
				'Acqmatprof' => '2',
				'Locvehicinsert' => '2'
			);
			$this->assertEqual($expected, $result);
		}

		function testDetails() {
			$expected=array(
				'Piecepresente' => array(
					'Apre' => 1,
					'Actprof' => 1,
					'Permisb' => 1,
					'Amenaglogt' => 1,
					'Acccreaentr' => 1,
					'Acqmatprof' => 1,
				),
				'Piecemanquante' => array(
					'Apre' => 0,
					'Actprof' => 1,
					'Permisb' => 1,
					'Amenaglogt' => 1,
					'Acccreaentr' => 1,
					'Acqmatprof' => 1,
				),
				'Piece' => array (
					'Manquante' => array(
						'Apre' => array(),
						'Actprof' => array('2' => 'pieceactproflibelle2'),
						'Permisb' => array('2' => 'piecepermisblibelle2'),
						'Amenaglogt' => array('2' => 'pieceamenaglogtlibelle2'),
						'Acccreaentr' => array('2' => 'pieceacccreaentrlibelle2'),
						'Acqmatprof' => array('2' => 'pieceacqmatproflibelle2'),
					)
				),
				'Natureaide' => array (
					'Formqualif' => 0,
					'Formpermfimo' => 0,
					'Actprof' => 1,
					'Permisb' => 1,
					'Amenaglogt' => 1,
					'Acccreaentr' => 1,
					'Acqmatprof' => 1,
					'Locvehicinsert' => 0
				)
			);
			$apre_id = '1';
			$result=$this->Apre->_details($apre_id);
			$this->assertEqual($expected,$result);

			$expected=array(
				'Piecepresente' => array(
					'Apre' => 0
				),
				'Piecemanquante' => array(
					'Apre' => 1
				),
				'Piece' => array (
					'Manquante' => array(
						'Apre' => array(
							1 => 'Attestation CAF datant du dernier mois de prestation versée',
						)
					)
				),
				'Natureaide' => array (
					'Formqualif' => 0,
					'Formpermfimo' => 0,
					'Actprof' => 0,
					'Permisb' => 0,
					'Amenaglogt' => 0,
					'Acccreaentr' => 0,
					'Acqmatprof' => 0,
					'Locvehicinsert' => 0
				)
			);
			$apre_id = '2';
			$result=$this->Apre->_details($apre_id);
			$this->assertEqual($expected,$result);

			$expected=array(
				'Piecepresente' => array(
					'Apre' => 0
				),
				'Piecemanquante' => array(
					'Apre' => 1
				),
				'Piece' => array (
					'Manquante' => array(
						'Apre' => array(
							1 => 'Attestation CAF datant du dernier mois de prestation versée',
						)
					)
				),
				'Natureaide' => array (
					'Formqualif' => 0,
					'Formpermfimo' => 0,
					'Actprof' => 0,
					'Permisb' => 0,
					'Amenaglogt' => 0,
					'Acccreaentr' => 0,
					'Acqmatprof' => 0,
					'Locvehicinsert' => 0
				)
			);
			$result=$this->Apre->_details(666);
			$this->assertEqual($expected,$result);
		}

		function testAfterFind() {
			$results = array(
				'id' => '1',
				'personne_id' => '1',
				'numeroapre' => '1',
				'typedemandeapre' => 'FO',
				'datedemandeapre' => '2009-05-12',
				'naturelogement' => null,
				'precisionsautrelogement' => null,
				'anciennetepoleemploi' => null,
				'projetprofessionnel' => null,
				'secteurprofessionnel' => null,
				'activitebeneficiaire' => null,
				'dateentreeemploi' => null,
				'typecontrat' => null,
				'precisionsautrecontrat' => null,
				'nbheurestravaillees' => null,
				'nomemployeur' => null,
				'adresseemployeur' => null,
				'quota' => null,
				'derogation' => null,
				'avistechreferent' => null,
				'etatdossierapre' => null,
				'eligibiliteapre' => null,
				'mtforfait' => null,
				'secteuractivite' => null,
				'nbenf12' => null,
				'statutapre' => 'C',
				'justificatif' => null,
				'structurereferente_id' => 1,
				'referent_id' => 1,
				'montantaverser' => '1000',
				'nbpaiementsouhait' => '2000',
				'montantdejaverse' => '300',
				// 'dureecontrat' => null,
				'isdecision' => 'N',
				'hasfrais' => null,
			);
			$primary = false;
			$result = $this->Apre->afterFind($results, $primary);
			$this->assertEqual($result, $results);
		}

		function testBeforeSave() {
			$options = array();
			$this->Apre->data = array(
				'Apre' => array(
					'id' => '1',
					'personne_id' => '1',
					'numeroapre' => '1',
					'typedemandeapre' => 'FO',
					'datedemandeapre' => '2009-05-12',
					'naturelogement' => null,
					'precisionsautrelogement' => null,
					'anciennetepoleemploi' => null,
					'projetprofessionnel' => null,
					'secteurprofessionnel' => null,
					'activitebeneficiaire' => null,
					'dateentreeemploi' => null,
					'typecontrat' => null,
					'precisionsautrecontrat' => null,
					'nbheurestravaillees' => null,
					'nomemployeur' => null,
					'adresseemployeur' => null,
					'quota' => null,
					'derogation' => null,
					'avistechreferent' => null,
					'etatdossierapre' => null,
					'eligibiliteapre' => null,
					'mtforfait' => null,
					'secteuractivite' => null,
					'nbenf12' => null,
					'statutapre' => 'C',
					'justificatif' => null,
					'structurereferente_id' => '1',
					'referent_id' => '1',
					'montantaverser' => '1000',
					'nbpaiementsouhait' => '2000',
					'montantdejaverse' => '300',
					// 'dureecontrat' => null,
					'isdecision' => 'N',
					'hasfrais' => null,
				),
			);
			$result = $this->Apre->beforeSave($options);
			$this->assertTrue($result);
			$this->assertEqual('COM', $this->Apre->data['Apre']['etatdossierapre']);

			$this->Apre->data = array(
				'Apre' => array(
					'id' => '2',
					'personne_id' => '5',
					'numeroapre' => null,
					'typedemandeapre' => null,
					'datedemandeapre' => '2009-05-12',
					'naturelogement' => null,
					'precisionsautrelogement' => null,
					'anciennetepoleemploi' => null,
					'projetprofessionnel' => null,
					'secteurprofessionnel' => null,
					'activitebeneficiaire' => null,
					'dateentreeemploi' => null,
					'typecontrat' => null,
					'precisionsautrecontrat' => null,
					'nbheurestravaillees' => null,
					'nomemployeur' => null,
					'adresseemployeur' => null,
					'quota' => null,
					'derogation' => null,
					'avistechreferent' => null,
					'etatdossierapre' => null,
					'eligibiliteapre' => null,
					'mtforfait' => null,
					'secteuractivite' => null,
					'nbenf12' => null,
					'statutapre' => 'F',
					'justificatif' => null,
					'structurereferente_id' => '1',
					'referent_id' => '1',
					'montantaverser' => '1000',
					'nbpaiementsouhait' => '2000',
					'montantdejaverse' => '300',
					// 'dureecontrat' => null,
					'isdecision' => 'N',
					'hasfrais' => null,
				),
			);
			$result = $this->Apre->beforeSave($options);
			$this->assertTrue($result);
			$this->assertEqual('COM', $this->Apre->data['Apre']['etatdossierapre']);
		}

		function testSupprimeFormationsObsoletes() {
			$apre = array(
				'id' => '1',
				'personne_id' => '1',
				'numeroapre' => '1',
				'typedemandeapre' => 'FO',
				'datedemandeapre' => '2009-05-12',
				'naturelogement' => null,
				'precisionsautrelogement' => null,
				'anciennetepoleemploi' => null,
				'projetprofessionnel' => null,
				'secteurprofessionnel' => null,
				'activitebeneficiaire' => null,
				'dateentreeemploi' => null,
				'typecontrat' => null,
				'precisionsautrecontrat' => null,
				'nbheurestravaillees' => null,
				'nomemployeur' => null,
				'adresseemployeur' => null,
				'quota' => null,
				'derogation' => null,
				'avistechreferent' => null,
				'etatdossierapre' => null,
				'eligibiliteapre' => null,
				'mtforfait' => null,
				'secteuractivite' => null,
				'nbenf12' => null,
				'statutapre' => 'C',
				'justificatif' => null,
				'structurereferente_id' => '1',
				'referent_id' => '1',
				'montantaverser' => '1000',
				'nbpaiementsouhait' => '2000',
				'montantdejaverse' => '300',
				// 'dureecontrat' => null,
				'isdecision' => 'N',
				'hasfrais' => null,
			);
			$result = $this->Apre->supprimeFormationsObsoletes($apre);
			$this->assertFalse($result);
		}
/*
		function testAfterSave() {
			$datas = array(
				'Apre' => array(
					'id' => '1',
					'personne_id' => '1',
					'numeroapre' => '1',
					'typedemandeapre' => 'FO',
					'datedemandeapre' => '2009-05-12',
					'naturelogement' => null,
					'precisionsautrelogement' => null,
					'anciennetepoleemploi' => null,
					'projetprofessionnel' => null,
					'secteurprofessionnel' => null,
					'activitebeneficiaire' => null,
					'dateentreeemploi' => null,
					'typecontrat' => null,
					'precisionsautrecontrat' => null,
					'nbheurestravaillees' => null,
					'nomemployeur' => null,
					'adresseemployeur' => null,
					'quota' => null,
					'derogation' => null,
					'avistechreferent' => null,
					'etatdossierapre' => null,
					'eligibiliteapre' => null,
					'mtforfait' => null,
					'secteuractivite' => null,
					'nbenf12' => null,
					'statutapre' => 'C',
					'justificatif' => null,
					'structurereferente_id' => 1,
					'referent_id' => 1,
					'montantaverser' => '1000',
					'nbpaiementsouhait' => '2000',
					'montantdejaverse' => '300',
					// 'dureecontrat' => null,
					'isdecision' => 'N',
					'hasfrais' => null,
				),
			);
			$created = null;
			$this->Apre->data = $datas;
			$result = $this->Apre->afterSave($created);
			debug($result);
		}
*/
		function testCalculMontantsDejaVerses() {
			$apre_ids = array('1', '2');
			$result = $this->Apre->calculMontantsDejaVerses($apre_ids);
			$this->assertTrue($result);
		}

		function testDonneesForfaitaireGedooo() {
			$apre_id = 1;
			$etatliquidatif_id = 1;
			$result = $this->Apre->donneesForfaitaireGedooo($apre_id, $etatliquidatif_id);
			$this->assertTrue($result);
			$this->assertNotNull($result['Apre']);
			$this->assertNotNull($result['Referent']);
			$this->assertNotNull($result['Personne']);

			$apre_id = 1337;
			$etatliquidatif_id = 1337;
			$result = $this->Apre->donneesForfaitaireGedooo($apre_id, $etatliquidatif_id);
			$this->assertFalse($result);
			
		}
		
	}
?>
