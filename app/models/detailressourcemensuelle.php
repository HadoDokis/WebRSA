<?php
	class Detailressourcemensuelle extends AppModel
	{
		public $name = 'Detailressourcemensuelle';

		public $validate = array(
			'ressourcemensuelle_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'dfpercress' => array (
				'rule' => 'date',
				'message' => 'Veuillez entrer une date valide'
			),
			// Montant de la ressource selon la nature
			'mtnatressmen' => array(
				array(
					'rule'          => array( 'comparison', '<=', 33333332 ),
					'message'       => 'Veuillez entrer un montant compris entre 0 et 33 333 332',
					'allowEmpty'    => true
				),
				array(
					'rule'          => array( 'comparison', '>=', 0 ),
					'message'       => 'Veuillez entrer un montant compris entre 0 et 33 333 332',
					'allowEmpty'    => true
				),
				array(
					'rule'      => array( 'between', 0, 11 ),
					'message'   => 'Veuillez entrer au maximum 11 caractères',
					'allowEmpty'    => true
				),
				array(
					'rule'      => 'numeric',
					'message'   => 'Veuillez entrer un nombre valide',
					'allowEmpty'    => true
				)
			),
		);

		public $belongsTo = array(
			'Ressourcemensuelle' => array(
				'className' => 'Ressourcemensuelle',
				'foreignKey' => 'ressourcemensuelle_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasAndBelongsToMany = array(
			'Ressourcemensuelle' => array(
				'className' => 'Ressourcemensuelle',
				'joinTable' => 'detailsressourcesmensuelles_ressourcesmensuelles',
				'foreignKey' => 'detailressourcemensuelle_id',
				'associationForeignKey' => 'ressourcemensuelle_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'DetailressourcemensuelleRessourcemensuelle'
			)
		);
	}
?>
