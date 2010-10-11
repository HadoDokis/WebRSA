<?php
	class Pieceactprof extends AppModel
	{
		public $name = 'Pieceactprof';

		public $displayField = 'libelle';

		public $order = array( 'Pieceactprof.libelle ASC' );

		public $validate = array(
			'libelle' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $hasAndBelongsToMany = array(
			'Actprof' => array(
				'className' => 'Actprof',
				'joinTable' => 'actsprofs_piecesactsprofs',
				'foreignKey' => 'pieceactprof_id',
				'associationForeignKey' => 'actprof_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ActprofPieceactprof'
			)
		);

	}
?>
