<?php
	class Aideapre66Pieceaide66 extends AppModel
	{
		public $name = 'Aideapre66Pieceaide66';

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
			'pieceaide66_id' => array(
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
			'Aideapre66' => array(
				'className' => 'Aideapre66',
				'foreignKey' => 'aideapre66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Pieceaide66' => array(
				'className' => 'Pieceaide66',
				'foreignKey' => 'pieceaide66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>