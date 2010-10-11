<?php
	class Pieceacccreaentr extends AppModel
	{
		public $name = 'Pieceacccreaentr';

		public $displayField = 'libelle';

		public $order = array( 'Pieceacccreaentr.libelle ASC' );

		public $hasAndBelongsToMany = array(
			'Acccreaentr' => array(
				'className' => 'Acccreaentr',
				'joinTable' => 'accscreaentr_piecesaccscreaentr',
				'foreignKey' => 'pieceacccreaentr_id',
				'associationForeignKey' => 'acccreaentr_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'AcccreaentrPieceacccreaentr'
			)
		);
	}
?>
