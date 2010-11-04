<?php
	class Actprof extends AppModel
	{
		public $name = 'Actprof';

		public $actsAs = array(
			'Aideapre',
			'Enumerable' => array(
				'fields' => array(
					'typecontratact' => array( 'type' => 'typecontratact', 'domain' => 'apre' ),
				)
			),
			'Frenchfloat' => array( 'fields' => array( 'montantaide', 'coutform', 'dureeform' ) )
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
			'Pieceactprof' => array(
				'className' => 'Pieceactprof',
				'joinTable' => 'actsprofs_piecesactsprofs',
				'foreignKey' => 'actprof_id',
				'associationForeignKey' => 'pieceactprof_id',
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

		public $validate = array(
			'tiersprestataireapre_id' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'adresseemployeur' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'typecontratact' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'intituleformation' => array(
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
			'ddconvention' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'dfconvention' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);
	}
?>
