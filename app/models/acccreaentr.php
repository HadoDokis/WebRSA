<?php
	class Acccreaentr extends AppModel
	{
		public $name = 'Acccreaentr';

		public $actsAs = array(
			'Aideapre',
			'Enumerable' => array(
				'fields' => array(
					'nacre' => array( 'type' => 'no', 'domain' => 'default' ),
					'microcredit' => array( 'type' => 'no', 'domain' => 'default' ),
				)
			),
			'Frenchfloat' => array( 'fields' => array( 'montantaide' ) )
		);

		public $validate = array(
			'nacre' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'microcredit' => array(
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
					'rule' => array( 'inclusiveRange', 0, 2000 ),
					'message' => 'Veuillez saisir un montant compris entre 0 et 2000€ maximum.'
				)*/
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
			'Pieceacccreaentr' => array(
				'className' => 'Pieceacccreaentr',
				'joinTable' => 'accscreaentr_piecesaccscreaentr',
				'foreignKey' => 'acccreaentr_id',
				'associationForeignKey' => 'pieceacccreaentr_id',
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
