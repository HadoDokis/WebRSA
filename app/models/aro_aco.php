<?php
	class AroAco extends AppModel
	{
		public $name = 'AroAco';

		public $validate = array(
			'aro_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'aco_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'_create' => array(
				'notempty' => array(
					'rule' => array('notempty'),
				),
			),
			'_read' => array(
				'notempty' => array(
					'rule' => array('notempty'),
				),
			),
			'_update' => array(
				'notempty' => array(
					'rule' => array('notempty'),
				),
			),
			'_delete' => array(
				'notempty' => array(
					'rule' => array('notempty'),
				),
			),
		);

		public $belongsTo = array(
			'Aro' => array(
				'className' => 'Aro',
				'foreignKey' => 'aro_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Aco' => array(
				'className' => 'Aco',
				'foreignKey' => 'aco_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>