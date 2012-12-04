<?php
	/**
	 * Fichier source de la classe User.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe User ...
	 *
	 * @package app.Model
	 */
	class User extends AppModel
	{
		public $name = 'User';

		public $displayField = 'username';

		public $actsAs = array(
			'Enumerable',/* => array(
				'fields' => array(
					'isgestionnaire',
					'sensibilite',
				)
			)*/
			'Pgsqlcake.PgsqlAutovalidate',
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
			'newpasswd' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'confnewpasswd' => array(
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
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
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
			),
			'Propoorientationcov58' => array(
				'className' => 'Propoorientationcov58',
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
			'Propoorientsocialecov58' => array(
				'className' => 'Propoorientsocialecov58',
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
			'Dossierpcg66' => array(
				'className' => 'Dossierpcg66',
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
			'Passagecommissionep' => array(
				'className' => 'Passagecommissionep',
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
			'Relancenonrespectsanctionep93' => array(
				'className' => 'Relancenonrespectsanctionep93',
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
			'Reorientationep93' => array(
				'className' => 'Reorientationep93',
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
			'Decisionnonrespectsanctionep93' => array(
				'className' => 'Decisionnonrespectsanctionep93',
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
			'Decisionreorientationep93' => array(
				'className' => 'Reorientationep93',
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
			'Cui' => array(
				'className' => 'Cui',
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
			'Propodecisioncui66' => array(
				'className' => 'Propodecisioncui66',
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
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
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
			'Histochoixcer93' => array(
				'className' => 'Histochoixcer93',
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
			'Cer93' => array(
				'className' => 'Cer93',
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

		public function search( $criteresusers ) {
			/// Conditions de base
			$conditions = array();

			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			$filtersPersonne = array();
			foreach( array( 'nom', 'prenom', 'username' ) as $criterePersonne ) {
				if( isset( $criteresusers['User'][$criterePersonne] ) && !empty( $criteresusers['User'][$criterePersonne] ) ) {
					$conditions[] = 'User.'.$criterePersonne.' ILIKE \''.$this->wildcard( $criteresusers['User'][$criterePersonne] ).'\'';
				}
			}

			// Critère sur le nom du groupe d'utilisateur
			if ( isset($criteresusers['Group']['name']) && !empty($criteresusers['Group']['name']) ) {
				$conditions[] = array('Group.id'=>$this->wildcard( $criteresusers['Group']['name'] ));
			}

			// Critère sur le nom du serviceinstructeur de l'utilisateur
			if ( isset($criteresusers['Serviceinstructeur']['lib_service']) && !empty($criteresusers['Serviceinstructeur']['lib_service']) ) {
				$conditions[] = array('Serviceinstructeur.id'=>$this->wildcard( $criteresusers['Serviceinstructeur']['lib_service'] ));
			}

			// Critère sur la structure référente de l'utilisateur
			if( isset( $criteresusers['User']['structurereferente_id'] ) && !empty( $criteresusers['User']['structurereferente_id'] ) ) {
				$conditions[] = array( 'User.structurereferente_id' => $criteresusers['User']['structurereferente_id'] );
			}

			// Critère sur le référent lié à l'utilisateur
			if( isset( $criteresusers['User']['referent_id'] ) && !empty( $criteresusers['User']['referent_id'] ) ) {
				$conditions[] = array( 'User.referent_id' => $criteresusers['User']['referent_id'] );
			}

			$query = array(
				'fields' => array(
					'User.nom',
					'User.prenom',
					'User.username',
					'User.date_deb_hab',
					'User.date_fin_hab',
					'User.date_naissance',
					'User.numtel',
					'Group.name',
					'Serviceinstructeur.lib_service'
				),
				'order' => array( '"User"."username" ASC' ),
				'conditions' => $conditions
			);

			return $query;
		}

		/**
		 * Hash du mot de passe.
		 *
		 * @param array $options
		 * @return boolean
		 */
		public function beforeSave( $options = array() ) {
			if( !empty( $this->data['User']['passwd'] ) ) {
				$this->data['User']['password'] = Security::hash( $this->data['User']['passwd'], null, true );
			}
			return parent::beforeSave( $options );
		}

		public function validatesPassword($data) {
			return ((!empty($data['User']['newpasswd'])) && (!empty($data['User']['confnewpasswd'])) && ($data['User']['newpasswd']==$data['User']['confnewpasswd']));
		}

		public function validOldPassword($data) {
			$oldPass = $this->find('first',array('conditions'=>array('id'=>$data['User']['id']),'fields'=>array('password'),'recursive'=>-1));
			return (Security::hash($data['User']['passwd'], null, true)==$oldPass['User']['password']);
		}

		/**
		 * Retourne les enregistrements pour lesquels une erreur de paramétrage
		 * a été détectée.
		 * Il s'agit des utilisateurs pour lesquels on ne connaît pas une des
		 * valeurs suivantes: nom, prenom, service instructeur, date de début
		 * d'habilitation, date de fin d'habilitation.
		 *
		 * @return array
		 */
		public function storedDataErrors() {
			return $this->find(
				'all',
				array(
					'fields' => array(
						'User.id',
						'User.username',
						'User.nom',
						'User.prenom',
						'User.serviceinstructeur_id',
						'User.date_deb_hab',
						'User.date_fin_hab',
					),
					'conditions' => array(
						'OR' => array(
							'User.nom IS NULL',
							'TRIM(User.nom)' => null,
							'User.prenom IS NULL',
							'TRIM(User.prenom)' => null,
							'User.serviceinstructeur_id IS NULL',
							'User.date_deb_hab IS NULL',
							'User.date_fin_hab IS NULL',
						)
					),
					'contain' => false,
				)
			);
		}
	}
?>