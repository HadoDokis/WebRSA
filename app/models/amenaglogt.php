<?php
	class Amenaglogt extends AppModel
	{
		public $name = 'Amenaglogt';

		public $actsAs = array(
			'Aideapre',
			'Enumerable' => array(
				'fields' => array(
					'typeaidelogement' => array( 'type' => 'typeaidelogement', 'domain' => 'apre' ),
				)
			),
			'Frenchfloat' => array( 'fields' => array( 'montantaide' ) )
		);

		public $validate = array(
			'apre_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'typeaidelogement' => array(
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
			'Pieceamenaglogt' => array(
				'className' => 'Pieceamenaglogt',
				'joinTable' => 'amenagslogts_piecesamenagslogts',
				'foreignKey' => 'amenaglogt_id',
				'associationForeignKey' => 'pieceamenaglogt_id',
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
