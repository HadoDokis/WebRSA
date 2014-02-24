<?php
	/**
	 * Code source de la classe AllocataireTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Allocataire', 'Model' );

	/**
	 * La classe AllocataireTest ...
	 *
	 * @package app.Test.Case.Model
	 */
	class AllocataireTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Adresse',
			'app.Adressefoyer',
			'app.Calculdroitrsa',
			'app.Detaildroitrsa',
			'app.Dossier',
			'app.Foyer',
			'app.Personne',
			'app.PersonneReferent',
			'app.Prestation',
			'app.Referent',
			'app.Situationdossierrsa',
			'app.Structurereferente',
		);

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$this->Allocataire = ClassRegistry::init( 'Allocataire' );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Allocataire );
			parent::tearDown();
		}

		/**
		 * Test de la méthode Allocataire::options().
		 */
		public function testOptions() {
			$result = array_keys( $this->Allocataire->options() );
			$expected = array (
				'Adresse',
				'Adressefoyer',
				'Calculdroitrsa',
				'Detaildroitrsa',
				'Dossier',
				'Foyer',
				'Personne',
				'Prestation',
				'Referentparcours',
				'Structurereferenteparcours',
				'Situationdossierrsa',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Allocataire::search().
		 */
		public function testSearch() {
			$query = $this->Allocataire->search();
			$query['fields'] = array(
				'Dossier.id',
				'Personne.id',
				'Personne.nom_complet',
			);

			$result = ClassRegistry::init( 'Personne' )->find( 'first', $query );
			$expected = array(
				'Dossier' => array(
					'id' => 1,
				),
				'Personne' => array(
					'id' => 1,
					'nom_complet' => 'MR BUFFIN CHRISTIAN',
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Allocataire::testSearchConditions() avec une chaîne
		 * de caractères en paramètre.
		 *
		 * @medium
		 */
		public function testTestSearchConditionsString() {
			$result = $this->Allocataire->testSearchConditions( 'Foo' );
			unset( $result['sql'] );
			$expected = array (
				'success' => false,
				'message' => 'SQLSTATE[42703]: Undefined column: 7 ERREUR:  la colonne « foo » n\'existe pas
LIGNE 15 : ...e_id" = "Structurereferenteparcours"."id")  WHERE Foo   ORDE...
                                                                ^',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Allocataire->testSearchConditions( 'Dossier.id = 6' );
			unset( $result['sql'] );
			$expected = array (
				'success' => true,
				'message' => null,
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Allocataire::testSearchConditions() avec un array
		 * en paramètre.
		 *
		 * @medium
		 */
		public function testTestSearchConditionsArray() {
			$result = $this->Allocataire->testSearchConditions( array( 'Foo' => 6 ) );
			unset( $result['sql'] );
			$expected = array (
				'success' => false,
				'message' => 'SQLSTATE[42703]: Undefined column: 7 ERREUR:  la colonne « Foo » n\'existe pas
LIGNE 15 : ...e_id" = "Structurereferenteparcours"."id")  WHERE "Foo" = 6 ...
                                                                ^',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Allocataire->testSearchConditions( array( 'Dossier.id' => '6' ) );
			unset( $result['sql'] );
			$expected = array (
				'success' => true,
				'message' => null,
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Allocataire::prechargement().
		 *
		 * @medium
		 */
		public function testPrechargement() {
			$result = $this->Allocataire->prechargement();
			$this->assertTrue( $result );
		}
	}
?>
