<?php
	class Acqmatprof extends AppModel
	{
		public $name = 'Acqmatprof';

		public $actsAs = array(
			'Aideapre',
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
			'Pieceacqmatprof' => array(
				'className' => 'Pieceacqmatprof',
				'joinTable' => 'acqsmatsprofs_piecesacqsmatsprofs',
				'foreignKey' => 'acqmatprof_id',
				'associationForeignKey' => 'pieceacqmatprof_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'AcqmatprofPieceacqmatprof'
			)
		);
	}
?>
