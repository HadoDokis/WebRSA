<?php
	class Locvehicinsert extends AppModel
	{
		public $name = 'Locvehicinsert';

		public $validate = array(
			'apre_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'societelocation' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'montantaide' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.',
					'allowEmpty' => true
				)/*,
				array(
					'rule' => array( 'inclusiveRange', 0, 700 ),
					'message' => 'Veuillez saisir un montant compris entre 0 et 700€ / 6 mois maximum.'
				)*/
			),
			'dureelocation' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.',
					'allowEmpty' => true
				),
				array(
					'rule' => array( 'comparison', '>=', 0 ),
					'message' => 'Veuillez saisir une valeur positive.'
				)
			)
		);

		public $belongsTo = array(
			'Apre' => array(
				'className' => 'Apre',
				'foreignKey' => 'apre_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasAndBelongsToMany = array(
			'Piecelocvehicinsert' => array(
				'className' => 'Piecelocvehicinsert',
				'joinTable' => 'locsvehicinsert_pieceslocsvehicinsert',
				'foreignKey' => 'locvehicinsert_id',
				'associationForeignKey' => 'piecelocvehicinsert_id',
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
