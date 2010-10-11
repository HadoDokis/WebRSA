<?php
	class Permisb extends AppModel
	{
		public $name = 'Permisb';

		public $actsAs = array(
			'Aideapre',
			'Enumerable', // FIXME ?
			'Frenchfloat' => array( 'fields' => array( 'coutform', 'dureeform' ) )
		);

		public $validate = array(
			'tiersprestataireapre_id' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'adresseautoecole' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
//             'coutform' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 ),
//                 array(
//                     'rule' => 'numeric',
//                     'message' => 'Veuillez entrer une valeur numérique.',
// //                     'allowEmpty' => true
//                 ),
//                 array(
//                     'rule' => array( 'inclusiveRange', 0, 1000 ),
//                     'message' => 'Veuillez saisir un montant compris entre 0 et 1000€ maximum.'
//                 )
//             ),
			'dureeform' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.',
//                     'allowEmpty' => true
				),
				array(
					'rule' => array( 'comparison', '>=', 0 ),
					'message' => 'Veuillez saisir une valeur positive.'
				)
			),
			'montantaide' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.'
				)/*,
				array(
					'rule' => array( 'inclusiveRange', 0, 1000 ),
					'message' => 'Veuillez saisir un montant compris entre 0 et 1000€ maximum.'
				)*/
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
			'Piecepermisb' => array(
				'className' => 'Piecepermisb',
				'joinTable' => 'permisb_piecespermisb',
				'foreignKey' => 'permisb_id',
				'associationForeignKey' => 'piecepermisb_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'PermisbPiecepermisb'
			)
		);
	}
?>
