<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Typeorient');

	class TypeorientTestCase extends CakeAppModelTestCase {
		function testListOptions() {
			$result = $this->Typeorient->listOptions();
			$expected = array(
				'4' => 'A sélectionner',
				'5' => 'CAF',
				'3' => 'Emploi',
				'2' => 'Social',
				'1' => 'Socioprofessionnelle',
			);
			$this->assertEqual($result, $expected);
		}

		function testOccurences() {
			$result = $this->Typeorient->occurences();
			$expected = array(
				'4' => '0',
				'1' => '36',
				'5' => '16',
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

		function testIsProOrientation() {
			$typeorient_id = null;
			$this->assertFalse($this->Typeorient->isProOrientation($typeorient_id));

			$typeorient_id = 1;
			$this->assertFalse($this->Typeorient->isProOrientation($typeorient_id));

			$typeorient_id = 2;
			$this->assertFalse($this->Typeorient->isProOrientation($typeorient_id));

			$typeorient_id = 3;
			$this->assertTrue($this->Typeorient->isProOrientation($typeorient_id));
		}

	}

?>
