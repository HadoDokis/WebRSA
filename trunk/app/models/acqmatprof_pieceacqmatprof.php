<?php
	class AcqmatprofPieceacqmatprof extends AppModel
	{
		public $name = 'AcqmatprofPieceacqmatprof';

		public $validate = array(
			'acqmatprof_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'pieceacqmatprof_id' => array(
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
			'Acqmatprof' => array(
				'className' => 'Acqmatprof',
				'foreignKey' => 'acqmatprof_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Pieceacqmatprof' => array(
				'className' => 'Pieceacqmatprof',
				'foreignKey' => 'pieceacqmatprof_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>