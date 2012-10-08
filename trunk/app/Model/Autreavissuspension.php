<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Autreavissuspension extends AppModel
	{
		public $name = 'Autreavissuspension';

		public $recursive = -1;

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'autreavissuspension'
				)
			),
			'Validation.Autovalidate',
			'ValidateTranslate'
		);

		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>