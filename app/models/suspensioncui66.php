<?php
	class Suspensioncui66 extends AppModel
	{
		public $name = 'Suspensioncui66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'Containable',
			'Enumerable' => array(
				'fields' => array(
					'decisioncui'
				)
			),
			'Formattable'
		);
		
		public $belongsTo = array(
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>