<#include "../freemarker_functions.ftl">
<?php
	/**
	 * Code source de la classe ${class_name(name)}.
	 *
<#if php_version??>
	 * PHP ${php_version}
	 *
</#if>
	 * @package app.Test.Case.Model.Behavior
	 * @license ${license}
	 */
	App::uses( '${class_name(name)?replace("Test", "", "r")}', 'Model/Behavior' );

	/**
	 * Classe ${class_name(name)}.
	 *
	 * @package app.Test.Case.Model.Behavior
	 */
	class ${class_name(name)} extends CakeTestCase
	{
		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.Apple'
		);

		/**
		 * Préparation du test.
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			$this->Apple = ClassRegistry::init( 'Apple' );
			$this->Apple->Behaviors->attach( '${class_name(name)?replace("Test", "", "r")}' );
		}

		/**
		 * Nettoyage postérieur au test.
		 *
		 * @return void
		 */
		public function tearDown() {
			unset( $this->Apple );
			parent::tearDown();
		}

		/**
		 * Test de la méthode ${class_name(name)?replace("Test", "", "r")}::method()
		 *
		 * @return void
		 */
		public function testMethod() {
			$result = $this->Apple->method();
			$expected = null;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>