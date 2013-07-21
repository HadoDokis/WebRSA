<#include "freemarker_functions.ftl">
<?php
	/**
	 * Code source de la classe ${name}.
	 *
<#if php_version??>
	 * PHP ${php_version}
	 *
</#if>
	 * @package app.Console.Command
	 * @license ${license}
	 */

	/**
	 * La classe ${name} ...
	 *
	 * @package app.Console.Command
	 */
	class ${name} extends AppShell
	{

		/**
		 * startup
		 *
		 * @return void
		 */
		public function startup() {
		}

		/**
		 * Main function Prints out the list of shells.
		 *
		 * @return void
		 */
		public function main() {
		}

		/**
		 * get the option parser
		 *
		 * @return void
		 */
		public function getOptionParser() {
			$parser = parent::getOptionParser();
			return $parser;
		}
	}
?>