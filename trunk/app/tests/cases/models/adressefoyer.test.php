<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Adressefoyer');

	class AdressefoyerTestCase extends CakeAppModelTestCase {

		function testDossierId() {
			$this->assertEqual(1,$this->Adressefoyer->dossierId(1));
			$this->assertEqual(2,$this->Adressefoyer->dossierId(2));
			$this->assertFalse($this->Adressefoyer->dossierId(666));

			// test avec un adressefoyer_id incoherent
			$this->assertFalse($this->Adressefoyer->dossierId(-42));

		}

		function testSqDerniereRgadr01() {
			$field = "1";
        		$table = "adressesfoyers";
			$expected = "
		    	SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.foyer_id = ".$field."
                        AND {$table}.rgadr = '01'
					ORDER BY {$table}.dtemm DESC
					LIMIT 1
        	";
			$this->assertEqual($expected, $this->Adressefoyer->SqDerniereRgadr01($field));
		}
	}
?>
