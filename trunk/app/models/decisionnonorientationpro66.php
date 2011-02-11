<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Decisionnonorientationpro66 extends AppModel
	{
		public $name = 'Decisionnonorientationpro66';

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
			'Nonorientationpro66' => array(
				'className' => 'Nonorientationpro66',
				'foreignKey' => 'nonorientationpro66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);
	}
?>
