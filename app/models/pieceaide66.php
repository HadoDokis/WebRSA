<?php
	class Pieceaide66 extends AppModel
	{
		public $name = 'Pieceaide66';

		public $order = 'Pieceaide66.name ASC';

		public $validate = array(
			'name' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $hasAndBelongsToMany = array(
			'Aideapre66' => array(
				'className' => 'Aideapre66',
				'joinTable' => 'aidesapres66_piecesaides66',
				'foreignKey' => 'pieceaide66_id',
				'associationForeignKey' => 'aideapre66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Aideapre66Pieceaide66'
			),
			'Typeaideapre66' => array(
				'className' => 'Typeaideapre66',
				'joinTable' => 'piecesaides66_typesaidesapres66',
				'foreignKey' => 'pieceaide66_id',
				'associationForeignKey' => 'typeaideapre66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Pieceaide66Typeaideapre66'
			)
		);
	}
?>
