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
	App::uses( 'CakeRequest', 'Network' );
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

			$this->DefaultForm->request = new CakeRequest( 'contacts/add', false );
			$this->DefaultForm->request->here = '/contacts/add';
			$this->DefaultForm->request['action'] = 'add';
			$this->DefaultForm->request->webroot = '';
			$this->DefaultForm->request->base = '';

			$this->DefaultForm->request->data = array(
				'Apple' => array(
					'id' => 666,
					'name' => 'Étagère',
					'date' => '2014-03-17',
					'category' => 'red',
					'description' => "Line 1\nLine2",
				)
			);
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
			$result = $this->DefaultForm->buttons( array( 'Save', 'Cancel', 'Reset' => array( 'type' => 'reset' ) ) );
			$expected = '<div class="submit">'
							. '<input name="Save" type="submit" value="Enregistrer"/>'
							. '<input name="Cancel" type="submit" value="Annuler"/>'
							. '<input name="Reset" type="reset" value="Remise à zéro"/>'
					. '</div>';
			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultFormHelper::fieldValue()
		 *
		 * @return void
		 */
		public function testFieldValue() {
			$result = $this->DefaultForm->fieldValue( 'Apple.id', array( 'label' => 'Id', 'type' => 'integer' ) );
			$expected = '<div class="input value integer"><span class="label">Id</span><span class="input">666</span></div>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultForm->fieldValue( 'Apple.name', array( 'label' => 'Name' ) );
			$expected = '<div class="input value"><span class="label">Name</span><span class="input">Étagère</span></div>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultForm->fieldValue( 'Apple.foo', array( 'label' => 'Name' ) );
			$expected = '<div class="input value"><span class="label">Name</span><span class="input"> </span></div>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultForm->fieldValue( 'Apple.date', array( 'label' => 'Date', 'type' => 'date' ) );
			$expected = '<div class="input value date"><span class="label">Date</span><span class="input">17/03/2014</span></div>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultForm->fieldValue( 'Apple.category', array( 'label' => 'Category', 'options' => array( 'red' => 'Red apple' ) ) );
			$expected = '<div class="input value"><span class="label">Category</span><span class="input">Red apple</span></div>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultForm->fieldValue( 'Apple.description', array( 'label' => 'Description', 'nl2br' => true ) );
			$expected = '<div class="input value"><span class="label">Description</span><span class="input">Line 1<br /> Line2</span></div>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultForm->fieldValue( 'Apple.name', array( 'label' => 'Id', 'hidden' => true ) );
			$expected = '<div class="input value"><input type="hidden" name="data[Apple][name]" value="Étagère" id="AppleName"/><span class="label">Id</span><span class="input">Étagère</span></div>';
			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultFormHelper::input()
		 *
		 * @return void
		 */
		public function testInput() {
			$result = $this->DefaultForm->input( 'Apple.id' );
			$expected = '<div class="input text"><label for="AppleId">Id</label><input name="data[Apple][id]" type="text" value="666" id="AppleId"/></div>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultForm->input( 'Apple.id', array( 'required' => true ) );
			$expected = '<div class="input text"><label for="AppleId"><abbr class="required" title="'.__( 'Validate::notEmpty' ).'">*</abbr></label><input name="data[Apple][id]" required="1" type="text" value="666" id="AppleId"/></div>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultForm->input( 'Apple.id', array( 'label' => 'Foo <', 'escape' => false ) );
			$expected = '<div class="input text"><label for="AppleId">Foo <</label><input name="data[Apple][id]" type="text" value="666" id="AppleId"/></div>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultForm->input( 'Apple.id', array( 'required' => true, 'label' => 'Foo <' ) );
			$expected = '<div class="input text"><label for="AppleId">Foo &lt; <abbr class="required" title="'.__( 'Validate::notEmpty' ).'">*</abbr></label><input name="data[Apple][id]" required="1" type="text" value="666" id="AppleId"/></div>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultForm->input( 'Apple.id', array( 'view' => true ) );
			$expected = '<div class="input value"><span class="label">Id</span><span class="input">666</span></div>';
			$this->assertEqualsXhtml( $result, $expected );
		}


		/**
		 * Test de la méthode DefaultFormHelper::input() pour un champ caché.
		 *
		 * @return void
		 */
		public function testInputHidden() {
			$result = $this->DefaultForm->input( 'Apple.id', array( 'type' => 'hidden', 'options' => array( 1, 2 ) ) );
			$expected = '<input type="hidden" name="data[Apple][id]" value="666" id="AppleId"/>';
			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultFormHelper::label()
		 *
		 * @return void
		 */
		public function testLabel() {
			$result = $this->DefaultForm->label( 'Apple.id' );
			$expected = '<label for="AppleId">Id</label>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultForm->label( 'Apple.id', null, array( 'required' => true ) );
			$expected = '<label for="AppleId"><abbr class="required" title="'.__( 'Validate::notEmpty' ).'">*</abbr></label>';
			$this->assertEqualsXhtml( $result, $expected );
		}
	}
?>