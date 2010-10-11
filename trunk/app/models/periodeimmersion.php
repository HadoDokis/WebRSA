<?php
	class Periodeimmersion extends AppModel
	{
		public $name = 'Periodeimmersion';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'objectifimmersion'
				)
			),
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