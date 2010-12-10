<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Situationdossierrsa');

	class SituationdossierrsaTestCase extends CakeAppModelTestCase {

		function testEtatOuvert() {
			$result = $this->Situationdossierrsa->etatOuvert();
			$expected = array( 'Z', 2, 3, 4 ); // Z => dossier ajouté avec le formulaire "Préconisation ..."
			$this->assertEqual($expected, $result);
		}

		function testEtatAttente() {
			$result = $this->Situationdossierrsa->etatAttente();
			$expected =  array( 0, 'Z' );
			$this->assertEqual($result, $expected);
		}

		// droitsOuverts($dossier_id)
		function testDroitsOuverts() {
			$result = $this->Situationdossierrsa->droitsOuverts('1');
			$this->assertFalse($result);

			$result = $this->Situationdossierrsa->droitsOuverts(2);
			$this->assertTrue($result);

			$result = $this->Situationdossierrsa->droitsOuverts(42);
			$this->assertFalse($result);
		}

		// droitsEnAttente($dossier_id)
		function testDroitsEnAttente() {
			$result = $this->Situationdossierrsa->droitsEnAttente(1);
			$this->assertFalse($result);

			$result = $this->Situationdossierrsa->droitsEnAttente(2);
			$this->assertFalse($result);

			$result = $this->Situationdossierrsa->droitsEnAttente(42);
			$this->assertTrue($result);
		}
	}

?>
