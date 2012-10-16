<?php
	class Questionpcg66 extends AppModel
	{
		public $name = 'Questionpcg66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'Enumerable' => array(
				'fields' => array(
					'defautinsertion',
					'recidive',
					'phase'
				)
			)
		);

		public $belongsTo = array(
			'Decisionpcg66' => array(
				'className' => 'Decisionpcg66',
				'foreignKey' => 'decisionpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Compofoyerpcg66' => array(
				'className' => 'Compofoyerpcg66',
				'foreignKey' => 'compofoyerpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>