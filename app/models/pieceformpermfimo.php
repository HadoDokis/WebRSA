<?php
	class Pieceformpermfimo extends AppModel
	{
		public $name = 'Pieceformpermfimo';

		public $displayField = 'libelle';

		public $validate = array(
			'libelle' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $hasAndBelongsToMany = array(
			'Formpermfimo' => array(
				'className' => 'Formpermfimo',
				'joinTable' => 'formspermsfimo_piecesformspermsfimo',
				'foreignKey' => 'pieceformpermfimo_id',
				'associationForeignKey' => 'formpermfimo_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'FormpermfimoPieceformpermfimo'
			)
		);

	}
?>
