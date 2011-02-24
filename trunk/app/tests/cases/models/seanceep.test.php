<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Seanceep');

	class SeanceepTestCase extends CakeAppModelTestCase {

		function testSearch() {
			$result = $this->Seanceep->search($criteresseanceep = null);
			$this->assertNull($result['conditions']['0']);
			$criteresseanceep = array(
				'Ep' => array(
					'id' => '1',
					'name' => 'CLI 1 Equipe 1.1',
					'identifiant' => 'EP1.1',
					'regroupementep_id' => '1',
					'defautinsertionep66' => 'nontraite',
					'saisineepbilanparcours66' => 'nontraite',
					'saisineepdpdo66' => 'nontraite',
					'nonrespectsanctionep93' => 'cg',
					'saisineepreorientsr93' => 'cg',
					'nonorientationpro58' => 'nontraite',
					'regressionorientationep58' => 'nontraite',
				),
				'Seanceep' => array(
					'id' => '3',
					'identifiant' => 'tretr',
					'name' => 'tert',
					'ep_id' => '1',
					'structurereferente_id' => '7',
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
			$result = $this->Seanceep->search($criteresseanceep);
			$expected = array(
		            '0' => Array
                		(
                		    'Ep.regroupementep_id' => '1'
                		),
		            '1' => Array
                		(
                		    'Seanceep.name' => 'tert'
                		),
		            '2' => Array
                		(
                		    'Seanceep.identifiant' => 'tretr'
                		),
		            '3' => Array
                		(
                		    'Seanceep.structurereferente_id' => '7'
                		),
		            '4' => Array
                		(
                		    'Structurereferente.ville' => 'SAINT DENIS'
                		),
			);
			$this->assertEqual($expected, $result['conditions']);
		}

		function testThemesTraites() {
			$id = null; // id de la seanceEp
			$result = $this->Seanceep->themesTraites($id);
			$this->assertNull($result['nonrespectsanctionep93']);
			$this->assertNull($result['saisineepreorientsr93']);

			$id = '3'; // id de la seanceEp
			$result = $this->Seanceep->themesTraites($id);
			$this->assertEqual($result['nonrespectsanctionep93'], 'cg');
			$this->assertEqual($result['saisineepreorientsr93'], 'cg');

			$id = '6'; // id de la seanceEp
			$result = $this->Seanceep->themesTraites($id);
			$this->assertEqual($result['nonrespectsanctionep93'], 'cg');
			$this->assertEqual($result['saisineepreorientsr93'], 'cg');
		}

		function testSaveDecisions() {

			$seanceep_id = '3';
			$data = array(
				'seanceep' => array(
					'id' => '3',
					'identifiant' => 'tretr',
					'name' => 'tert',
					'ep_id' => '1',
					'structurereferente_id' => '7',
					'dateseance' => '2031-01-01',
					'salle' => 'tert',
					'observations' => null,
					'finalisee' => null,
				),
				'dossierep' => array(
					'id' => '1',
					'personne_id' => '1001',
					'seanceep_id' => '3',
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
					'saisineepbilanparcours66' => 'nontraite',
					'saisineepdpdo66' => 'nontraite',
					'nonrespectsanctionep93' => 'cg',
					'saisineepreorientsr93' => 'cg',
					'nonorientationpro58' => 'nontraite',
					'regressionorientationep58' => 'nontraite',
				),
			);
			$niveauDecision = 'cg';
			$this->assertTrue($this->Seanceep->saveDecisions($seanceep_id, $data, $niveauDecision));

			$seanceep_id = '6';
			$data = array(
				'Seanceep' => array(
					'id' => '6',
					'identifiant' => 'EP1.2',
					'name' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
					'ep_id' => '2',
					'structurereferente_id' => '7',
					'dateseance' => '2017-01-01',
					'salle' => 'null',
					'observations' => null,
					'finalisee' => null,
				),
				'Dossierep' => array(
					'id' => '2',
					'personne_id' => '1002',
					'seanceep_id' => '6',
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
					'saisineepbilanparcours66' => 'nontraite',
					'saisineepdpdo66' => 'nontraite',
					'nonrespectsanctionep93' => 'cg',
					'saisineepreorientsr93' => 'cg',
					'nonorientationpro58' => 'nontraite',
					'regressionorientationep58' => 'nontraite',
				),
				'MembreepSeanceep' => array(
					'id' => '1',
					'seanceep_id' => '3',
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
			$this->assertTrue($this->Seanceep->saveDecisions($seanceep_id, $data, $niveauDecision));
		}

		function testDossiersParListe() {
			$seanceep_id = '3';
			$niveauDecision = 'cg';
			$expected = array(
				'id' => '1',
				'personne_id' => '1001',
				'seanceep_id' => '3',
				'etapedossierep' => 'cree',
				'themeep' => 'nonrespectssanctionseps93',
				'created' => null,
				'modified' => null,
			);
			$result = $this->Seanceep->dossiersParListe($seanceep_id, $niveauDecision);
			$this->assertEqual($result['Nonrespectsanctionep93']['liste']['0']['Dossierep'], $expected);

			$seanceep_id = '6';
			$niveauDecision = 'cg';
			$expected = array(
				'id' => '2',
				'personne_id' => '2002',
				'seanceep_id' => '6',
				'etapedossierep' => 'cree',
				'themeep' => 'nonrespectssanctionseps93',
				'created' => null,
				'modified' => null,
			);
			$result = $this->Seanceep->dossiersParListe($seanceep_id, $niveauDecision);
			$this->assertEqual($result['Nonrespectsanctionep93']['liste']['0']['Dossierep'], $expected);
		}

		function testPrepareFormData() {
			$seanceep_id = '3';
			$dossiers = array(
				array(
					'id' => '1',
					'personne_id' => '1001',
					'seanceep_id' => '3',
					'etapedossierep' => 'cree',
					'themeep' => 'nonrespectssanctionseps93',
					'created' => null,
					'modified' => null,
				),
				array(
					'id' => '2',
					'personne_id' => '2002',
					'seanceep_id' => '6',
					'etapedossierep' => 'cree',
					'themeep' => 'nonrespectssanctionseps93',
					'created' => null,
					'modified' => null,
				),
			);
			$niveauDecision = 'cg';
//			debug($this->Seanceep->prepareFormData($seanceep_id, $dossiers, $niveauDecision));

		}
/*
		function testFinaliser() {
			$seanceep_id;
			$niveauDecision;
		}

		function testClotureSeance() {
			$datas;
		}

		function testGetPdfPv() {
			$seanceep_id;
		}

		function testGetPdfOrdreDuJour() {
			$seanceep_id;
		}
*/
	}
?>
