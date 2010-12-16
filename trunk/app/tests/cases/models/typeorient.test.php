<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Typeorient');

	class TypeorientTestCase extends CakeAppModelTestCase {
		function testListOptions() {
			$result = $this->Typeorient->listOptions();
			$expected = array(
				'1' => 'Emploi',
				'3' => 'Social',
				'2' => 'Socioprofessionnelle',
			);
			$this->assertEqual($result, $expected);
		}

		function testOccurences() {
			$result = $this->Typeorient->occurences();
			$expected = array(
				'1' => '36',
				'3' => '2',
				'2' => '1',
			);
			$this->assertEqual($result, $expected);
		}

		function testGetIdLevel0() {
			$typeorient_id = '1';
			$result = $this->Typeorient->getIdLevel0($typeorient_id);
			$this->assertEqual('1', $result);

			$typeorient_id = '1337';
			$result = $this->Typeorient->getIdLevel0($typeorient_id);
			$this->assertFalse($result);
		}
	}

?>
