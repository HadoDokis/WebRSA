<?php
	class ActprofPieceactprof extends AppModel
	{
		public $name = 'ActprofPieceactprof';

		public $validate = array(
			'actprof_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'pieceactprof_id' => array(
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
			'Actprof' => array(
				'className' => 'Actprof',
				'foreignKey' => 'actprof_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Pieceactprof' => array(
				'className' => 'Pieceactprof',
				'foreignKey' => 'pieceactprof_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>