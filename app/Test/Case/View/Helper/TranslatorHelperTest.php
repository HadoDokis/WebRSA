<?php
	/**
	 * Code source de la classe TranslatorHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	App::uses('WebrsaTranslator', 'Utility');
	App::uses('TranslatorHelper', 'View/Helper');
	App::uses('Controller', 'Controller');

	/**
	 * La classe TranslatorHelperTest ...
	 *
	 * @package app.Test.Case.View.Helper
	 */
	class TranslatorHelperTest extends CakeTestCase
	{
		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			App::build(array('locales' => APP.'Test'.DS.'Locale'.DS));
			WebrsaTranslator::domains(array('controller'));
			$Request = new CakeRequest();
			$this->Controller = new Controller($Request);
			$this->View = new View($this->Controller);
			$this->Translator = new TranslatorHelper($this->View);
		}
		
		public function testNormalize() {
			$fields = array(
				'Monmodel.field',
				'Model.test1',
				'Fake.field' => array('label' => 'déjà defini'),
				'data[Model][input]',
				'/controller/action/#Model.id#',
			);
			$results = $this->Translator->normalize($fields);
			$expected = array(
				'Monmodel.field' => array('label' => 'Monmodel.field dans controller.po'),
				'Model.test1' => array('label' => 'Model.test1 dans model.po'),
				'Fake.field' => array('label' => 'déjà defini'),
				'data[Model][input]' => array(),
				'/controller/action/#Model.id#' => array('msgid' => 'Traduction path dans controller.po'),
			);
			$this->assertEquals($results, $expected, "All in one");
		}
	}
?>