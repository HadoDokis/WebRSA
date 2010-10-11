<?php
	class Pieceamenaglogt extends AppModel
	{
		public $name = 'Pieceamenaglogt';

		public $displayField = 'libelle';

		public $order = array( 'Pieceamenaglogt.libelle ASC' );

		public $validate = array(
			'libelle' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $hasAndBelongsToMany = array(
			'Amenaglogt' => array(
				'className' => 'Amenaglogt',
				'joinTable' => 'amenagslogts_piecesamenagslogts',
				'foreignKey' => 'pieceamenaglogt_id',
				'associationForeignKey' => 'amenaglogt_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'AmenaglogtPieceamenaglogt'
			)
		);
	}
?>
