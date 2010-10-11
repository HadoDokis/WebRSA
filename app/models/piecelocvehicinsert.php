<?php
	class Piecelocvehicinsert extends AppModel
	{
		public $name = 'Piecelocvehicinsert';

		public $displayField = 'libelle';

		public $order = array( 'Piecelocvehicinsert.libelle ASC' );

		public $validate = array(
			'libelle' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $hasAndBelongsToMany = array(
			'Locvehicinsert' => array(
				'className' => 'Locvehicinsert',
				'joinTable' => 'locsvehicinsert_pieceslocsvehicinsert',
				'foreignKey' => 'piecelocvehicinsert_id',
				'associationForeignKey' => 'locvehicinsert_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'LocvehicinsertPiecelocvehicinsert'
			)
		);

	}
?>
