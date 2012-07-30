<?php
	class Periodeimmersion extends AppModel
	{
		public $name = 'Periodeimmersion';

		public $actsAs = array(
			'Enumerable',
			'Formattable',
			'Autovalidate'
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