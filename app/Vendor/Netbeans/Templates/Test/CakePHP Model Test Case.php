<#include "../freemarker_functions.ftl">
<?php
	/**
	 * Code source de la classe ${name}.
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
	 * La classe ${name} ...
	 *
	 * @package app.Test.Case.Model
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
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			parent::tearDown();
		}

		/**
		 * Test de la méthode ${class_name(name)?replace("Test", "", "r")}::method()
		 */
		public function testMethod() {
			$this->markTestIncomplete( 'Ce test n\'a pas encoré été implémenté.' );
		}
	}
?>
