<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Decisionnonrespectsanctionep93 extends AppModel
	{
		public $name = 'Decisionnonrespectsanctionep93';

		public $recursive = -1;

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'etape',
					'decision'
				)
			),
			'Autovalidate',
			'ValidateTranslate'
		);
	}
?>
