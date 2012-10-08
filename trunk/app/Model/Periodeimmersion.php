<?php
	class Periodeimmersion extends AppModel
	{
		public $name = 'Periodeimmersion';

		public $actsAs = array(
			'Enumerable',
			'Formattable',
			'Validation.Autovalidate'
		);

		public $belongsTo = array(
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>