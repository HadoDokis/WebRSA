<?php
	class Objetcontratinsertion extends AppModel
	{
		public $name = 'Objetcontratinsertion';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'name'
				)
			),
			'Autovalidate',
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