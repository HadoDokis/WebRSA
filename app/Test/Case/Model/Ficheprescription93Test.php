<?php
	/**
	 * Code source de la classe Ficheprescription93Test.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Ficheprescription93', 'Model' );

	/**
	 * La classe Ficheprescription93Test réalise les tests unitaires de la classe Ficheprescription93.
	 *
	 * @package app.Test.Case.Model
	 */
	class Ficheprescription93Test extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Actionfp93',
			'app.Adresse',
			'app.Adressefoyer',
			'app.Calculdroitrsa',
			'app.Categoriefp93',
			'app.Detaildroitrsa',
			'app.Dossier',
			'app.Ficheprescription93',
			'app.Filierefp93',
			'app.Foyer',
			'app.Personne',
			'app.PersonneReferent',
			'app.Prestatairefp93',
			'app.Prestation',
			'app.Referent',
			'app.Situationdossierrsa',
			'app.Structurereferente',
			'app.Thematiquefp93',
		);

		/**
		 * Le modèle à tester.
		 *
		 * @var Ficheprescription93
		 */
		public $Ficheprescription93 = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$this->Ficheprescription93 = ClassRegistry::init( 'Ficheprescription93' );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Ficheprescription93 );
			parent::tearDown();
		}

		/**
		 * Test de la méthode Ficheprescription93::search()
		 */
		public function testSearch() {
			$result = $this->Ficheprescription93->search();
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
				'Structurereferenteparcours' => 'LEFT OUTER',
				'Ficheprescription93' => 'LEFT OUTER',
				'Actionfp93' => 'LEFT OUTER',
				'Referent' => 'LEFT OUTER',
				'Filierefp93' => 'LEFT OUTER',
				'Prestatairefp93' => 'LEFT OUTER',
				'Categoriefp93' => 'LEFT OUTER',
				'Thematiquefp93' => 'LEFT OUTER',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>
