<?php
	class Aideapre66Piececomptable66 extends AppModel
	{
		public $name = 'Aideapre66Piececomptable66';

		public $validate = array(
			'aideapre66_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'piececomptable66_id' => array(
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
		//The Associations below have been created with all possible keys, those that are not needed can be removed

		public $belongsTo = array(
			'Aideapre66' => array(
				'className' => 'Aideapre66',
				'foreignKey' => 'aideapre66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Piececomptable66' => array(
				'className' => 'Piececomptable66',
				'foreignKey' => 'piececomptable66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>