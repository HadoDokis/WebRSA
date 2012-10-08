<?php
	class FormpermfimoPieceformpermfimo extends AppModel
	{
		public $name = 'FormpermfimoPieceformpermfimo';

		public $validate = array(
			'formpermfimo_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
			'pieceformpermfimo_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
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