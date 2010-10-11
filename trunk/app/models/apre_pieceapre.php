<?php
	class AprePieceapre extends AppModel
	{
		public $name = 'AprePieceapre';

		public $validate = array(
			'apre_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'pieceapre_id' => array(
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
			'Apre' => array(
				'className' => 'Apre',
				'foreignKey' => 'apre_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Pieceapre' => array(
				'className' => 'Pieceapre',
				'foreignKey' => 'pieceapre_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>