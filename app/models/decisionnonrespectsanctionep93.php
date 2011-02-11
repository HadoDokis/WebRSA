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

		// TODO: lorsqu'on pourra reporter les dossiers,
		// il faudra soit faire soit un report, soit les validations ci-dessous
		// FIXME: dans ce cas, il faudra permettre au champ decision de prendre la valeur NULL
		public $validate = array(
			'decision' => array(
				array(
					'rule' => array( 'notEmpty' )
				)
			),
		);
	}
?>
