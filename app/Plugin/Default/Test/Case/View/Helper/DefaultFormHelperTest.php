<?php
	/**
	 * Code source de la classe DefaultFormHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'DefaultFormHelper', 'Default.View/Helper' );
	App::uses( 'DefaultAbstractTestCase', 'Default.Test/Case' );

	/**
	 * La classe DefaultFormHelperTest ...
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 */
	class DefaultFormHelperTest extends DefaultAbstractTestCase
	{
		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
		);

		/**
		 * Préparation du test.
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			$controller = null;
			$this->View = new View( $controller );
			$this->DefaultForm = new DefaultFormHelper( $this->View );
		}

		/**
		 * Nettoyage postérieur au test.
		 *
		 * @return void
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->View, $this->DefaultForm );
		}

		/**
		 * Test de la méthode DefaultFormHelper::buttons()
		 *
		 * @return void
		 */
		public function testButtons() {
			$result = $this->DefaultForm->buttons( array( 'Save', 'Cancel', 'Reset' ) );
			$expected = '<div class="submit">
							<input  name="Save" type="submit" value="'.__( 'Save' ).'"/>
							<input  name="Cancel" type="submit" value="'.__( 'Cancel' ).'"/>
							<input  name="Reset" type="submit" value="'.__( 'Reset' ).'"/>
						</div>';
			$this->assertEqualsXhtml( $result, $expected );
		}
	}
?>