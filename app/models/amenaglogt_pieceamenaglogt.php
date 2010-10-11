<?php
	class AmenaglogtPieceamenaglogt extends AppModel
	{
		public $name = 'AmenaglogtPieceamenaglogt';

		public $validate = array(
			'amenaglogt_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'pieceamenaglogt_id' => array(
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
			'Amenaglogt' => array(
				'className' => 'Amenaglogt',
				'foreignKey' => 'amenaglogt_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Pieceamenaglogt' => array(
				'className' => 'Pieceamenaglogt',
				'foreignKey' => 'pieceamenaglogt_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>