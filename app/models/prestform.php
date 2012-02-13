<?php
	class Prestform extends AppModel
	{
		public $name = 'Prestform';

		public $validate = array(
			'actioninsertion_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'refpresta_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Actioninsertion' => array(
				'className' => 'Actioninsertion',
				'foreignKey' => 'actioninsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Refpresta' => array(
				'className' => 'Refpresta',
				'foreignKey' => 'refpresta_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>