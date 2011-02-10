<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Decisionnonorientationpro58 extends AppModel
	{
		public $name = 'Decisionnonorientationpro58';

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
		
		public $belongsTo = array(
			'Nonorientationpro58' => array(
				'className' => 'Nonorientationpro58',
				'foreignKey' => 'nonorientationpro58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);
	}
?>
