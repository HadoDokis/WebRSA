<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Ep');

	class EpTestCase extends CakeAppModelTestCase {

		function testListOptions() {
			$result = $this->Ep->listOptions();
			$expected = Array(
				'1' => 'CLI 1 Equipe 1.1',
				'2' => 'CLI 1 Equipe 1.2',
				'3' => 'CLI 2 Equipe 2.1',
			);
			$this->assertEqual($result, $expected);
		}

		function testThemes() {
			$result = $this->Ep->themes();
			$expected = array(
				'0' => 'saisineepbilanparcours66',
				'1' => 'saisineepdpdo66',
				'2' => 'defautinsertionep66',
				'3' => 'nonrespectsanctionep93',
				'4' => 'saisineepreorientsr93',
				'5' => 'nonorientationpro58',
				'6' => 'regressionorientationep58',
				'7' => 'sanctionep58',
			);
			$this->assertEqual($result, $expected);
		}

		function testIdentifiant() {
			$result = $this->Ep->identifiant();
			$this->assertNotNull($result);
		}

		function testBeforeValidate() {
			$options = null;
			
			$this->Ep->data = array(
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
			);

			$result = $this->Ep->beforeValidate($options);
			$this->assertEqual($result, true);
			$this->assertEqual($this->Ep->primaryKey, 'id');
			$this->assertEqual($this->Ep->data['Ep']['identifiant'], 'EP1.1');
		}
	}

?>
