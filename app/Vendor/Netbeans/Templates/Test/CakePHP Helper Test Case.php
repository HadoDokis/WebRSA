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
			$this->${class_name(name)?replace("HelperTest", "", "r")} = new ${class_name(name)?replace("Test", "", "r")}( $this->View );
		}

		/**
		 * Nettoyage postérieur au test.
		 *
		 * @return void
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->View, $this->${class_name(name)?replace("HelperTest", "", "r")} );
		}

		/**
		 * Test de la méthode ${class_name(name)?replace("Test", "", "r")}::method()
		 *
		 * @return void
		 */
		public function testMethod() {
			$result = $this->${class_name(name)?replace("HelperTest", "", "r")}->method();
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>