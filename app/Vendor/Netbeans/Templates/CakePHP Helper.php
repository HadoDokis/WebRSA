<#include "freemarker_functions.ftl">
<?php
	/**
	 * Code source de la classe ${class_name(name)}.
	 *
<#if php_version??>
	 * PHP ${php_version}
	 *
</#if>
	 * @package app.View.Helper
	 * @license ${license}
	 */

	/**
	 * Classe ${class_name(name)}.
	 *
	 * @package       app.View.Helper
	 */
	class ${class_name(name)?replace("Helper$", "", "r")}Helper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array();

		/**
		 * Before render callback. beforeRender is called before the view file is rendered.
		 *
		 * Overridden in subclasses.
		 *
		 * @param string $viewFile The view file that is going to be rendered
		 * @return void
		 */
		public function beforeRender($viewFile) {
		}

		/**
		 * After render callback.  afterRender is called after the view file is rendered
		 * but before the layout has been rendered.
		 *
		 * Overridden in subclasses.
		 *
		 * @param string $viewFile The view file that was rendered.
		 * @return void
		 */
		public function afterRender($viewFile) {
		}

		/**
		 * Before layout callback.  beforeLayout is called before the layout is rendered.
		 *
		 * Overridden in subclasses.
		 *
		 * @param string $layoutFile The layout about to be rendered.
		 * @return void
		 */
		public function beforeLayout($layoutFile) {
		}

		/**
		 * After layout callback.  afterLayout is called after the layout has rendered.
		 *
		 * Overridden in subclasses.
		 *
		 * @param string $layoutFile The layout file that was rendered.
		 * @return void
		 */
		public function afterLayout($layoutFile) {
		}
	}
?>