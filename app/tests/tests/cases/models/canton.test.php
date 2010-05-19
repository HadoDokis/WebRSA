<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Canton');

	class CantonTestCase extends CakeAppModelTestCase
	{

		function testSelectList() {
			$result=$this->Canton->selectList();
			$expected=array(
				1 => 1,
				2 => 2
			);
			$this->assertEqual($result,$expected);
		}

		function testQueryConditions() {
			$result=$this->Canton->queryConditions(1);
			$expected=array(
				'or' => array(
					'0' => array(
						'Adresse.numcomptt' => 12345,
						'Adresse.codepos' => 34000,
						'Adresse.locaadr ILIKE' => 'Montpellier',
						'Adresse.typevoie ILIKE' => 'R',
						'Adresse.nomvoie ILIKE' => 'pignon sur'
					)
				)
			);
			$this->assertEqual($result,$expected);

			$result=$this->Canton->queryConditions(2);
			$expected=array(
				'or' => array(
					0 => array(
						'Adresse.numcomptt' => 98765,
						'Adresse.codepos' => 34000,
						'Adresse.locaadr ILIKE' => 'Alès',
						'Adresse.typevoie ILIKE' => 'A',
						'Adresse.nomvoie ILIKE' => 'de saint martin les mines'
					),
					1 => array(
						'Adresse.numcomptt' => 36385,
						'Adresse.codepos' => 75000,
						'Adresse.locaadr ILIKE' => 'Paris',
						'Adresse.typevoie ILIKE' => 'P',
						'Adresse.nomvoie ILIKE' => 'pigalle'
					)
				)
			);
			$this->assertEqual($result,$expected);

			$result=$this->Canton->queryConditions(666);
			$expected=array(
				'or' => array()
			);
			$this->assertEqual($result,$expected);
		}

	}
?>
