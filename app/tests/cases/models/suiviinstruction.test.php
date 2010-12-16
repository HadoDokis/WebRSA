<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Suiviinstruction');

	class SuiviinstructionTestCase extends CakeAppModelTestCase {
		function testSqDerniere() {
			$field = '1';
			$table = 'suivisinstruction';
			$result = $this->Suiviinstruction->sqDerniere($field);
			$expected = "
				SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.dossier_id = ".$field."
					ORDER BY {$table}.dossier_id DESC
					LIMIT 1
			";
			$this->assertEqual($expected, $result);
		}
	}

?>
