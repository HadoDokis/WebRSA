<#include "freemarker_functions.ftl">
<?php
	/**
	 * Code source de la classe ${class_name(name)}.
	 *
<#if php_version??>
	 * PHP ${php_version}
	 *
</#if>
	 * @package app.Test.Fixture
	 * @license ${license}
	 */

	/**
	 * Classe ${class_name(name)}.
	 *
	 * @package app.Test.Fixture
	 */
	class ${class_name(name)} extends CakeTestFixture
	{
		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = array(
			'model' => '${class_name(name)?replace("Fixture$", "","r")}',
			'records' => false
		);

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = array(
		);

	}
?>