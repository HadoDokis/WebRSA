<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Signalementep93 extends AppModel
	{
		public $name = 'Signalementep93';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable'
		);

		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);
	}
?>
