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
			'app.Detailcalculdroitrsa',
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
				'Detailcalculdroitrsa',
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
		 * Test des joins de la méthode Allocataire::searchQuery().
		 */
		public function testSearchQueryJoins() {
			// 1. Sans paramètre
			$result = $this->Allocataire->searchQuery();
			$result = Hash::combine( $result, 'joins.{n}.alias', 'joins.{n}.type' );
			$expected = array(
				'Calculdroitrsa' => 'LEFT OUTER',
				'Foyer' => 'INNER',
				'Prestation' => 'INNER',
				'Adressefoyer' => 'INNER',
				'Dossier' => 'INNER',
				'Adresse' => 'INNER',
				'Situationdossierrsa' => 'INNER',
				'Detaildroitrsa' => 'INNER',
				'PersonneReferent' => 'LEFT OUTER',
				'Referentparcours' => 'LEFT OUTER',
				'Structurereferenteparcours' => 'LEFT OUTER'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Avec paramètre
			$joins = array(
				'Adressefoyer' => 'LEFT OUTER',
				'Adresse' => 'LEFT OUTER',
				'Prestation' => 'LEFT OUTER',
			);
			$result = $this->Allocataire->searchQuery( $joins );
			$result = Hash::combine( $result, 'joins.{n}.alias', 'joins.{n}.type' );
			$expected = array(
				'Calculdroitrsa' => 'LEFT OUTER',
				'Foyer' => 'INNER',
				'Prestation' => 'LEFT OUTER',
				'Adressefoyer' => 'LEFT OUTER',
				'Dossier' => 'INNER',
				'Adresse' => 'LEFT OUTER',
				'Situationdossierrsa' => 'INNER',
				'Detaildroitrsa' => 'INNER',
				'PersonneReferent' => 'LEFT OUTER',
				'Referentparcours' => 'LEFT OUTER',
				'Structurereferenteparcours' => 'LEFT OUTER'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Allocataire::search().
		 *
		 * @medium
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
				'message' => preg_match( '/^SQLSTATE\[42703\]: Undefined column: 7.*foo/', $result['message'] ) ? $result['message'] : null,
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
				'message' => preg_match( '/^SQLSTATE\[42703\]: Undefined column: 7.*Foo/', $result['message'] ) ? $result['message'] : null,
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
