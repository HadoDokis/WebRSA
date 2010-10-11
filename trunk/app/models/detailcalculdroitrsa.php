<?php
	class Detailcalculdroitrsa extends AppModel
	{
		public $name = 'Detailcalculdroitrsa';

		public $validate = array(
			'detaildroitrsa_id' => array(
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
			'Detaildroitrsa' => array(
				'className' => 'Detaildroitrsa',
				'foreignKey' => 'detaildroitrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>
