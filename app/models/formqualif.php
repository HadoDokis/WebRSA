<?php
	class Formqualif extends AppModel
	{
		public $name = 'Formqualif';

		public $actsAs = array(
			'Aideapre',
			'Frenchfloat' => array(
				'fields' => array( 'coutform', 'montantaide', 'dureeform' )
			)
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
			'intituleform' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'tiersprestataireapre_id' => array(
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
			),
			'coutform' => array(
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
			),
			'dureeform' => array(
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
			),
			'ddform' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'dfform' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
		);

		public $belongsTo = array(
			'Apre' => array(
				'className' => 'Apre',
				'foreignKey' => 'apre_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Tiersprestataireapre' => array(
				'className' => 'Tiersprestataireapre',
				'foreignKey' => 'tiersprestataireapre_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasAndBelongsToMany = array(
			'Pieceformqualif' => array(
				'className' => 'Pieceformqualif',
				'joinTable' => 'formsqualifs_piecesformsqualifs',
				'foreignKey' => 'formqualif_id',
				'associationForeignKey' => 'pieceformqualif_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'FormqualifPieceformqualif'
			)
		);
	}
?>