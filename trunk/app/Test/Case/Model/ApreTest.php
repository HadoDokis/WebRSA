<?php
	/**
	 * Code source de la classe ApreTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Apre', 'Model' );

	/**
	 * La classe ApreTest réalise les tests unitaires de la classe Apre.
	 *
	 * @package app.Test.Case.Model
	 */
	class ApreTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Apre',
		);

		/**
		 * Le modèle à tester.
		 *
		 * @var Apre
		 */
		public $Apre = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$this->Apre = ClassRegistry::init( 'Apre' );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Apre );
			parent::tearDown();
		}

		/**
		 * Test de la méthode Apre::modeleOdt()
		 */
		public function testModeleOdt() {
			$result = $this->Apre->modeleOdt( array() );
			$expected = 'APRE/apre.odt';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>
