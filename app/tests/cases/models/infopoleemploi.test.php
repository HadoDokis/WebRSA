<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Infopoleemploi');

	class InfopoleemploiTestCase extends CakeAppModelTestCase {

		function testSqDerniere() {
			$field = "1";
			$result = $this->Infopoleemploi->sqDerniere($field);
			$expected = "
				SELECT infospoleemploi.id
					FROM infospoleemploi
					WHERE
						infospoleemploi.personne_id = ".$field."
					ORDER BY infospoleemploi.dateinscription DESC
					LIMIT 1
			";
			$this->assertEqual($result, $expected);
		}

	}

?>
