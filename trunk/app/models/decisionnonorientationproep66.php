<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Decisionnonorientationproep66 extends AppModel
	{
		public $name = 'Decisionnonorientationproep66';

		public $recursive = -1;

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'etape',
					'decision'
				)
			),
			'Autovalidate',
			'ValidateTranslate',
			'Formattable'
		);

		public $belongsTo = array(
			'Nonorientationproep66' => array(
				'className' => 'Nonorientationproep66',
				'foreignKey' => 'nonorientationproep66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);
	}
?>
