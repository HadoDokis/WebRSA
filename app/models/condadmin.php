<?php
	class Condadmin extends AppModel
	{
		public $name = 'Condadmin';

		public $validate = array(
			'avispcgdroitrsa_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);

		public $belongsTo = array(
			'Avispcgdroitrsa' => array(
				'className' => 'Avispcgdroitrsa',
				'foreignKey' => 'avispcgdroitrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>