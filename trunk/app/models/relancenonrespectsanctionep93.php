<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Relancenonrespectsanctionep93 extends AppModel
	{
		public $name = 'Relancenonrespectsanctionep93';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate'
		);

		public $belongsTo = array(
			'Nonrespectsanctionep93' => array(
				'className' => 'Nonrespectsanctionep93',
				'foreignKey' => 'nonrespectsanctionep93_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>
