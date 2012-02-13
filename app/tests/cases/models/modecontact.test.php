<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Modecontact');

	class ModecontactTestCase extends CakeAppModelTestCase {
		//test function dossierId()
		function testDossierId() {
			$result = $this->Modecontact->dossierId(1);
			$this->assertEqual(1, $result);

			$result = $this->Modecontact->dossierId(2);
			$this->assertEqual(2, $result);

			$result = $this->Modecontact->dossierId(1337);
			$this->assertNull($result);

			$result = $this->Modecontact->dossierId(-42);
			$this->assertNull($result);
		}

		function testSqDerniere() {
			$field = '1';
			$table = 'modescontact';
			$expected = "
				SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.foyer_id = ".$field."
					ORDER BY {$table}.foyer_id DESC
					LIMIT 1
			";

			$result = $this->Modecontact->sqDerniere($field);
			$this->assertEqual($result, $expected);
		}
	}
?>
