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

			// test avec un adressefoyer_id incoherent
			$this->assertFalse($this->Adressefoyer->dossierId("toto"));
			///FIXME
			// Le fait de passer une string a la place d'un entier creer des exeptions
		}
/*
		function testSqDerniereRgadr01() {
			$field = "toto";
			$expected = " SELECT adressesfoyers.id FROM adressesfoyers WHERE adressesfoyers.foyer_id = " . $field . " AND 						adressesfoyers.rgadr = '01' ORDER BY adressesfoyers.dtemm DESC LIMIT 1 ";
			$this->assertEqual($expected, $this->Adressefoyer->SqDerniereRgadr01("toto"));
		}

		// test effectué sur cette fonction uniquement pour la couverture de code
		function testSqlFoyerActuelUnique() {
			$expected = '(
		                SELECT tmpadressesfoyers.id FROM (
                		    SELECT MAX(adressesfoyers.id) AS id, adressesfoyers.foyer_id
                		        FROM adressesfoyers
               			        WHERE adressesfoyers.rgadr = \'01\'
               			        GROUP BY adressesfoyers.foyer_id
					ORDER BY adressesfoyers.foyer_id
				) AS tmpadressesfoyers
			)';
			$result = $this->Adressefoyer->sqlFoyerActuelUnique("couverture_de_code");
			$this->assertEqual($expected, $result);			
		}
*/
	}
?>
