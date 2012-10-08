<?php
	App::uses( 'XShell', 'Console/Command' );
	class DevShell extends XShell
	{

		/**
		 *
		 */
		public function main() {
			debug($this->params);
		}

	}
?>