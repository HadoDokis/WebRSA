<#include "../freemarker_functions.ftl">
<?php
	/**
	 * Code source de la classe ${name}.
	 *
<#if php_version??>
	 * PHP ${php_version}
	 *
</#if>
	 * @package app.Test.Case.View.Helper
	 * @license ${license}
	 */
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( '${class_name(name)?replace("Test", "", "r")}', 'View/Helper' );

	/**
	 * La classe ${name} ...
	 *
	 * @package app.Test.Case.Model.Behavior
	 */
	class ${name} extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
		);

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$controller = null;
			$this->View = new View( $controller );
			$this->${class_name(name)?replace("HelperTest", "", "r")} = new ${class_name(name)?replace("Test", "", "r")}( $this->View );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->View, $this->${class_name(name)?replace("HelperTest", "", "r")} );
		}

		/**
		 * Test de la méthode ${class_name(name)?replace("Test", "", "r")}::method()
		 */
		public function testMethod() {
			$this->markTestIncomplete( 'Ce test n\'a pas encoré été implémenté.' );
		}
	}
?>