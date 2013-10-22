<?php
	/**
	 * Code source de la classe AllocatairelieBehaviorTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AllocatairelieBehavior', 'Model/Behavior' );

	/**
	 * La classe AllocatairelieBehaviorTest réalise les tests unitaires de la
	 * classe AllocatairelieBehavior.
	 *
	 * @package app.Test.Case.Model.Behavior
	 */
	class AllocatairelieBehaviorTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Dossier',
			'app.Foyer',
			'app.Personne',
			'app.Questionnaired2pdv93',
		);

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$this->Questionnaired2pdv93 = ClassRegistry::init( 'Questionnaired2pdv93' );
			$this->Questionnaired2pdv93->Behaviors->attach( 'Allocatairelie' );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Questionnaired2pdv93 );
			parent::tearDown();
		}

		/**
		 * Test de la méthode AllocatairelieBehavior::personneId()
		 */
		public function testPersonneId() {
			$result = $this->Questionnaired2pdv93->personneId( 1 );
			$expected = 1;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = $this->Questionnaired2pdv93->personneId( 3 );
			$expected = 3;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = $this->Questionnaired2pdv93->personneId( 6661 );
			$expected = null;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode AllocatairelieBehavior::dossierId()
		 */
		public function testDossierId() {
			$result = $this->Questionnaired2pdv93->dossierId( 1 );
			$expected = 1;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = $this->Questionnaired2pdv93->dossierId( 3 );
			$expected = 2;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			$result = $this->Questionnaired2pdv93->dossierId( 6661 );
			$expected = null;
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>