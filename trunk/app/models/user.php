<?php
	class User extends AppModel
	{
		public $name = 'User';

		public $displayField = 'username';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'isgestionnaire',
					'sensibilite',
				)
			)
		);

		public $virtualFields = array(
			'nom_complet' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."nom" || \' \' || "%s"."prenom" )'
			),
		);

		public $validate = array(
			'username' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Cet identifiant est déjà utilisé'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'passwd' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'group_id' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'serviceinstructeur_id' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'nom' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'prenom' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'numtel' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => array( 'between', 10, 14 ),
					'message' => 'Le numéro de téléphone est composé de 10 chiffres'
				)
			),
			'date_deb_hab' => array(
				'notEmpty' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			),
			'date_fin_hab' => array(
				'notEmpty' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			),
			'isgestionnaire' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'sensibilite' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $belongsTo = array(
			'Group' => array(
				'className' => 'Group',
				'foreignKey' => 'group_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Serviceinstructeur' => array(
				'className' => 'Serviceinstructeur',
				'foreignKey' => 'serviceinstructeur_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Connection' => array(
				'className' => 'Connection',
				'foreignKey' => 'user_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Jeton' => array(
				'className' => 'Jeton',
				'foreignKey' => 'user_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Jetonfonction' => array(
				'className' => 'Jetonfonction',
				'foreignKey' => 'user_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'user_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);


		public $hasAndBelongsToMany = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'joinTable' => 'contratsinsertion_users',
				'foreignKey' => 'user_id',
				'associationForeignKey' => 'contratinsertion_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ContratinsertionUser'
			),
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'joinTable' => 'users_zonesgeographiques',
				'foreignKey' => 'user_id',
				'associationForeignKey' => 'zonegeographique_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'UserZonegeographique'
			)
		);

		/**
		*
		*/

		public function beforeSave() {
			if( !empty( $this->data['User']['passwd'] ) ) {
				$this->data['User']['password'] = Security::hash( $this->data['User']['passwd'], null, true );
			}

			parent::beforeSave();
			return true;
		}

		/**
		*
		*/

		public function beforeDelete() {
			debug( $this->data );
			die();
		}
	}
?>
