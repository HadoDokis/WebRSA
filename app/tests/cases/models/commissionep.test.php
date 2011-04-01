<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Commissionep');

	class CommissionepTestCase extends CakeAppModelTestCase {

		function testSearch() {
			$result = $this->Commissionep->search($criteresseanceep = null);
			$criteresseanceep = array(
				'Ep' => array(
					'id' => '1',
					'name' => 'CLI 1 Equipe 1.1',
					'identifiant' => 'EP1.1',
					'regroupementep_id' => '1',
					'defautinsertionep66' => 'nontraite',
					'saisinebilanparcoursep66' => 'nontraite',
					'saisinepdoep66' => 'nontraite',
					'nonrespectsanctionep93' => 'cg',
					'reorientationep93' => 'cg',
					'nonorientationproep58' => 'nontraite',
					'regressionorientationep58' => 'nontraite',
				),
				'Commissionep' => array(
					'id' => '3',
					'identifiant' => 'tretr',
					'name' => 'tert',
					'ep_id' => '1',
					//'structurereferente_id' => '7',
					'dateseance' => '2031-01-01',
					'salle' => 'tert',
					'observations' => null,
					'finalisee' => null,
				),
				'Structurereferente' => array(
					'id' => '7',
					'typeorient_id' => '5',
					'lib_struc' => 'CAF DE SAINT DENIS',
					'num_voie' => '1',
					'type_voie' => 'AV',
					'nom_voie' => 'De la République',
					'code_postal' => '93200',
					'ville' => 'SAINT DENIS',
					'code_insee' => '93066',
					'filtre_zone_geo' => null,
					'contratengagement' => 'O',
					'apre' => 'O',
					'orientation' => 'O',
					'pdo' => 'O',
				),
			);
			$result = $this->Commissionep->search($criteresseanceep);
			$expected = array(
		            '0' => Array
                		(
                		    'Ep.regroupementep_id' => '1'
                		),
		            '1' => Array
                		(
                		    'Commissionep.name' => 'tert'
                		),
		            '2' => Array
                		(
                		    'Commissionep.identifiant' => 'tretr'
                		),
		            '3' => Array
                		(
                		    'Commissionep.villeseance' => 'SAINT DENIS'
                		),
			    /*'4' => Array
                		(
                		    'Structurereferente.ville' => 'SAINT DENIS'
                		),*/
			);
			$this->assertEqual($expected, $result['conditions']);
		}

		function testThemesTraites() {
			$id = '3'; // id de la seanceEp
			$result = $this->Commissionep->themesTraites($id);
			$this->assertEqual($result['nonrespectsanctionep93'], 'cg');
			
			$id = '6'; // id de la seanceEp
			$result = $this->Commissionep->themesTraites($id);
			$this->assertEqual($result['nonrespectsanctionep93'], 'cg');
		}

		function testSaveDecisions() {

			$commissionep_id = '3';
			$data = array(
				'commissionep' => array(
					'id' => '3',
					'identifiant' => 'tretr',
					'name' => 'tert',
					'ep_id' => '1',
					//'structurereferente_id' => '7',
					'dateseance' => '2031-01-01',
					'salle' => 'tert',
					'observations' => null,
					'finalisee' => null,
				),
				'dossierep' => array(
					'id' => '1',
					'personne_id' => '1001',
					'commissionep_id' => '3',
					'etapedossierep' => 'cree',
					'themeep' => 'nonrespectssanctionseps93',
					'created' => null,
					'modified' => null,
				),
				'membreep' => array(
					'id' => '1',
					'fonctionmembreep_id' => '1',
					'qual' => 'Mlle.',
					'nom' => 'Dupont',
					'prenom' => 'Anne',
					'tel' => null,
					'mail' => null,
					'suppleant_id' => null,
				),
				'ep' => array(
					'id' => '1',
					'name' => 'CLI 1 Equipe 1.1',
					'identifiant' => 'EP1.1',
					'regroupementep_id' => '1',
					'defautinsertionep66' => 'nontraite',
					'saisinebilanparcoursep66' => 'nontraite',
					'saisinepdoep66' => 'nontraite',
					'nonrespectsanctionep93' => 'cg',
					'reorientationep93' => 'cg',
					'nonorientationproep58' => 'nontraite',
					'regressionorientationep58' => 'nontraite',
				),
			);
			$niveauDecision = 'cg';
			$this->assertTrue($this->Commissionep->saveDecisions($commissionep_id, $data, $niveauDecision));

			$commissionep_id = '6';
			$data = array(
				'Commissionep' => array(
					'id' => '6',
					'identifiant' => 'EP1.2',
					'name' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
					'ep_id' => '2',
					//'structurereferente_id' => '7',
					'dateseance' => '2017-01-01',
					'salle' => 'null',
					'observations' => null,
					'finalisee' => null,
				),
				'Dossierep' => array(
					'id' => '2',
					'personne_id' => '1002',
					'commissionep_id' => '6',
					'etapedossierep' => 'cree',
					'themeep' => 'nonrespectssanctionseps93',
					'created' => null,
					'modified' => null,
				),
				'Membreep' => array(
					'id' => '1',
					'fonctionmembreep_id' => '1',
					'qual' => 'Mlle.',
					'nom' => 'Dupont',
					'prenom' => 'Anne',
					'tel' => null,
					'mail' => null,
					'suppleant_id' => null,
				),
				'Ep' => array(
					'id' => '2',
					'name' => 'CLI 1 Equipe 1.2',
					'identifiant' => 'EP1.2',
					'regroupementep_id' => '1',
					'defautinsertionep66' => 'nontraite',
					'saisinebilanparcoursep66' => 'nontraite',
					'saisinepdoep66' => 'nontraite',
					'nonrespectsanctionep93' => 'cg',
					'reorientationep93' => 'cg',
					'nonorientationproep58' => 'nontraite',
					'regressionorientationep58' => 'nontraite',
				),
				'CommissionepMembreep' => array(
					'id' => '1',
					'commissionep_id' => '3',
					'membreep_id' => '1',
					'suppleant' => '0',
					'suppleant_id' => null,
					'reponse' => 'confirme',
					'presence' => null,
				),
				'EpZonegeographique' => array(
					'id' => '1',
					'ep_id' => '1',
					'zonegeographique_id' => '35',
				),
			);
			$niveauDecision = 'cg';
			$this->assertTrue($this->Commissionep->saveDecisions($commissionep_id, $data, $niveauDecision));
		}

		function testDossiersParListe() {
			$commissionep_id = '3';
			$niveauDecision = 'cg';
			$expected = array(
				'id' => '1',
				'personne_id' => '1001',
				'commissionep_id' => '3',
				'etapedossierep' => 'cree',
				'themeep' => 'nonrespectssanctionseps93',
				'created' => null,
				'modified' => null,
			);
			$result = $this->Commissionep->dossiersParListe($commissionep_id, $niveauDecision);
			$this->assertEqual($result['Nonrespectsanctionep93']['liste']['0']['Dossierep'], $expected);

			$commissionep_id = '6';
			$niveauDecision = 'cg';
			$expected = array(
				'id' => '2',
				'personne_id' => '2002',
				'commissionep_id' => '6',
				'etapedossierep' => 'cree',
				'themeep' => 'nonrespectssanctionseps93',
				'created' => null,
				'modified' => null,
			);
			$result = $this->Commissionep->dossiersParListe($commissionep_id, $niveauDecision);
			$this->assertEqual($result['Nonrespectsanctionep93']['liste']['0']['Dossierep'], $expected);
		}

		function testPrepareFormData() {
			$commissionep_id = '3';			
			$niveauDecision = 'cg';
			$dossiers = $this->Commissionep->dossiersParListe($commissionep_id, $niveauDecision);
			$expected = array(
				'Nonrespectsanctionep93' => array(
            				'0' => array(
                    				'id' => '1',
                    				'dossierep_id' => '1',
                			),

        			),
			    	'Decisionnonrespectsanctionep93' => array(
			            	'0' => array(
						'id' => '1',
            				        'nonrespectsanctionep93_id' => '1',
						'etape' => 'cg',
						'decision' => null,
						'montantreduction' => null,
						'dureesursis' => null,
						'commentaire' => null,
						'created' => null,
						'modified' => null,
                			),
       				 ),
			);
			$this->assertEqual($this->Commissionep->prepareFormData($commissionep_id, $dossiers, $niveauDecision), $expected);

			$commissionep_id = '6';			
			$niveauDecision = 'cg';
			$dossiers = $this->Commissionep->dossiersParListe($commissionep_id, $niveauDecision);
			$expected = array(
				'Nonrespectsanctionep93' => array(
            				'0' => array(
                    				'id' => '2',
                    				'dossierep_id' => '2',
                			),

        			),
			    	'Decisionnonrespectsanctionep93' => array(
			            	'0' => array(
						'id' => '2',
            				        'nonrespectsanctionep93_id' => '2',
						'etape' => 'cg',
						'decision' => null,
						'montantreduction' => null,
						'dureesursis' => null,
						'commentaire' => null,
						'created' => null,
						'modified' => null,
                			),
       				 ),
			);
			$this->assertEqual($this->Commissionep->prepareFormData($commissionep_id, $dossiers, $niveauDecision), $expected);
		}

		function testFinaliser() {
			$commissionep_id = '3';
			$niveauDecision = 'cg';
			$user_id = '6';
			$result = $this->Commissionep->finaliser($commissionep_id, $niveauDecision, $user_id);
			$this->assertFalse($result);

			$commissionep_id = '6';
			$niveauDecision = 'cg';
			$user_id = '6';
			$result = $this->Commissionep->finaliser($commissionep_id, $niveauDecision, $user_id);
			$this->assertFalse($result);

			$commissionep_id = '9';
			$niveauDecision = 'cg';
			$user_id = '6';
			$result = $this->Commissionep->finaliser($commissionep_id, $niveauDecision, $user_id);
			$this->assertTrue($result);

		}

		function testClotureSeance() {
			$datas = array(
				'Commissionep' => array(
					'id' => '6',
					'identifiant' => 'EP1.2',
					'name' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
					'ep_id' => '2',
					//'structurereferente_id' => '7',
					'dateseance' => '2017-01-01',
					'salle' => 'null',
					'observations' => null,
					'finalisee' => null,
				),
				'Ep' => array(
					'id' => '2',
					'name' => 'CLI 1 Equipe 1.2',
					'identifiant' => 'EP1.2',
					'regroupementep_id' => '1',
					'defautinsertionep66' => 'nontraite',
					'saisinebilanparcoursep66' => 'nontraite',
					'saisinepdoep66' => 'nontraite',
					'nonrespectsanctionep93' => 'cg',
					'reorientationep93' => 'cg',
					'nonorientationproep58' => 'nontraite',
					'regressionorientationep58' => 'nontraite',
					'radiepoleemploiep93' => 'nontraite',
				),
			);
			$this->assertFalse($this->Commissionep->clotureSeance($datas));

			$datas = array(
				'Commissionep' => array(
					'id' => '9',
					'identifiant' => 'EP2.1',
					'name' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
					'ep_id' => '3',
					//'structurereferente_id' => '7',
					'dateseance' => '2017-01-01',
					'salle' => 'null',
					'observations' => null,
					'finalisee' => 'cg',
				),
				'Ep' => array(
					'id' => '3',
					'name' => 'CLI 2 Equipe 2.1',
					'identifiant' => 'EP2.1',
					'regroupementep_id' => '2',
					'defautinsertionep66' => 'nontraite',
					'saisinebilanparcoursep66' => 'nontraite',
					'saisinepdoep66' => 'nontraite',
					'nonrespectsanctionep93' => 'cg',
					'reorientationep93' => 'cg',
					'nonorientationproep58' => 'nontraite',
					'regressionorientationep58' => 'nontraite',
					'radiepoleemploiep93' => 'nontraite',
				),
			);
			$this->assertTrue($this->Commissionep->clotureSeance($datas));
		}
/*
		function testGetPdfPv() {
			$commissionep_id = '3';
			$result = $this->Commissionep->getPdfPv($commissionep_id);
			$this->assertTrue($result);
		}

		function testGetPdfOrdreDuJour() {
			$commissionep_id = '3';
			$result = $this->Commissionep->getPdfOrdreDuJour($commissionep_id);
			$this->assertTrue($result);
		}
*/
		//FIXME: Renvoi une string trop longue
		function testIdentifiant() {
			$result = $this->Commissionep->identifiant();
			$this->assertEqual(strlen($result), 12);
		}

		function testBeforeValidate() {
			$options = array();
			$this->assertTrue($this->Commissionep->beforeValidate($options));
		}

	}
?>
