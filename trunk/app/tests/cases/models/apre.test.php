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
			$result=$this->Apre->joinsAidesLiees();
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
			// créer des exceptions, insére "toto" à la place d'un integer (id)
			//$this->assertNull($this->Apre->dossierId("toto"));
			$this->assertNull($this->Apre->dossierId(-42));
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
						'Actprof' => array('2' => 'libellé2'),
						'Permisb' => array('2' => 'libellé2'),
						'Amenaglogt' => array('2' => 'libellé2'),
						'Acccreaentr' => array('2' => 'libellé2'),
						'Acqmatprof' => array('2' => 'libellé2'),
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
			$result=$this->Apre->_details(1);
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
			$result=$this->Apre->_details(2);
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

		function testBeforeSave() {
			$result = $this->Apre->beforeSave();
			$this->assertTrue($result);
		}		

	}
?>
