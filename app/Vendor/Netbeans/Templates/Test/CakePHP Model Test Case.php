<#include "../freemarker_functions.ftl">
<?php
	/**
	 * Code source de la classe ${class_name(name)}.
	 *
<#if php_version??>
	 * PHP ${php_version}
	 *
</#if>
	 * @package app.Test.Case.Model
	 * @license ${license}
	 */
	App::uses( '${class_name(name)?replace("Test", "", "r")}', 'Model' );

	/**
	 * Classe ${class_name(name)}.
	 *
	 * @package app.Test.Case.Model
	 */
<?php
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
		 * Set up the test
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
		}

		/**
		 * tearDown method
		 *
		 * @return void
		 */
		public function tearDown() {
			parent::tearDown();
		}

		/**
		 * Test de la méthode ${class_name(name)?replace("Test", "", "r")}::method()
		 *
		 * @return void
		 */
		public function testMethod() {
		}
	}
?>
