<?php
	class FormpermfimoPieceformpermfimo extends AppModel
	{
		public $name = 'FormpermfimoPieceformpermfimo';

		public $validate = array(
			'formpermfimo_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'pieceformpermfimo_id' => array(
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
			'Formpermfimo' => array(
				'className' => 'Formpermfimo',
				'foreignKey' => 'formpermfimo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Pieceformpermfimo' => array(
				'className' => 'Pieceformpermfimo',
				'foreignKey' => 'pieceformpermfimo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>