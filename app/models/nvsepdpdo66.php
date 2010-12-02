<?php
	class Nvsepdpdo66 extends AppModel
	{
		public $name = 'Nvsepdpdo66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'etape'
				)
			)
		);

		public $belongsTo = array(
			'Decisionpdo' => array(
				'className' => 'Decisionpdo',
				'foreignKey' => 'decisionpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Saisineepdpdo66' => array(
				'className' => 'Saisineepdpdo66',
				'foreignKey' => 'saisineepdpdo66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		
	}
?>
