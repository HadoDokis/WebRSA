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
			'Enumerable',
			'Formattable' => array(
				'phone' => array( 'numtel' )
			),
			'Validation.ExtraValidationRules',
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
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => 'Cet identifiant est déjà utilisé'
				),
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'passwd' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				'passwordStrength' => array(
					'rule' => 'passwordStrength'
				),
			),
			'current_password' => array(
				array(
					'rule' => 'checkCurrentPassword'
				)
			),
			'new_password' => array(
				'passwordStrength' => array(
					'rule' => 'passwordStrength'
				),
				'checkIdenticalValues' => array(
					'rule' => array( 'checkIdenticalValues', 'new_password_confirmation' )
				)
			),
			'new_password_confirmation' => array(
				'passwordStrength' => array(
					'rule' => 'passwordStrength'
				),
				'checkIdenticalValues' => array(
					'rule' => array( 'checkIdenticalValues', 'new_password' )
				),
			),
			'group_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'serviceinstructeur_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'nom' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'prenom' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'numtel' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				'phoneFr' => array(
					'rule' => array( 'phoneFr' ),
					'allowEmpty' => true,
				)
			),
			'date_deb_hab' => array(
				'date' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			),
			'date_fin_hab' => array(
				'date' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			),
			'isgestionnaire' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'sensibilite' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'structurereferente_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'type', true, array( 'externe_cpdv', 'externe_secretaire' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'referent_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'type', true, array( 'externe_ci' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'email' => array(
				'email' => array(
					'rule' => array( 'email' ),
					'message' => 'Veuillez entrer une adresse mail valide'
				)
			),
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
            'Poledossierpcg66' => array(
				'className' => 'Poledossierpcg66',
				'foreignKey' => 'poledossierpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Annulateurcer93' => array(
				'className' => 'Cer93',
				'foreignKey' => 'annulateur_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
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
			),
			'Decisiondossierpcg66' => array(
				'className' => 'Decisiondossierpcg66',
				'foreignKey' => 'user_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Avistechniquedecisiondossierpcg66' => array(
				'className' => 'Decisiondossierpcg66',
				'foreignKey' => 'useravistechnique_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Propositiondecisiondossierpcg66' => array(
				'className' => 'Decisiondossierpcg66',
				'foreignKey' => 'userproposition_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
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
		 * Moteur de recherche des utilisateurs, retourne un querydata.
		 *
		 * @param array $criteresusers
		 * @return array
		 */
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

			$querydata = array(
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
				'order' => array( 'User.nom ASC' ),
				'joins' => array(
					$this->join( 'Group', array( 'type' => 'INNER' ) ),
					$this->join( 'Serviceinstructeur', array( 'type' => 'INNER' ) )
				),
				'contain' => false,
				'conditions' => $conditions
			);

			return $querydata;
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

		/**
		 * Vérification du mot de passe courant, avec la clé primaire se trouvant
		 * dans $this->data et le hash par défaut (salted).
		 *
		 * @param mixed $check Les valeurs à vérifier.
		 * @return boolean
		 */
		public function checkCurrentPassword( $check ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$result = true;
			foreach( Set::normalize( $check ) as $value ) {
				$count = $this->find(
					'count',
					array(
						'conditions' => array(
							"{$this->alias}.{$this->primaryKey}" => $this->data[$this->alias][$this->primaryKey],
							"{$this->alias}.password" => Security::hash( $value, null, true ),
						),
						'contain' => false
					)
				);
				$result = ( $count == 1 ) && $result;
			}

			return $result;
		}

		/**
		 * Vérification de la force du mot de passe. Il faut au moins 8
		 * caractères, un caractère spécial et un chiffre.
		 *
		 * @link http://edgeward.co.uk/blog/2010/08/cakephp-password-complexity-validation/ Inspiration regexp
		 *
		 * @param mixed $check Les valeurs à vérifier.
		 * @return boolean
		 */
		public function passwordStrength( $check ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$result = true;
			foreach( Set::normalize( $check ) as $value ) {
				$result = preg_match( '/(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*\W+).*$/', $value ) && $result;
			}

			return $result;
		}

		/**
		 * Vérification que la valeur du champ soit égale à la valeur de référence.
		 *
		 * @param mixed $check Les valeurs à vérifier.
		 * @param string $referenceField Le nom du champ de référence.
		 * @return boolean
		 */
		public function checkIdenticalValues( $check, $referenceField ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$result = true;
			foreach( Set::normalize( $check ) as $value ) {
				$result = ( $value == $this->data[$this->alias][$referenceField] );
			}

			return $result;
		}

		/**
		 * Vérification et mise à jour du mot de passe.
		 * Les champs attendus sont: id, current_password, new_password et
		 * new_password_confirmation, sous la clé User.
		 *
		 * @param array $data
		 * @return boolean
		 */
		public function changePassword( array $data ) {
			$this->create( $data );
			return $this->validates() && $this->updateAllUnBound(
				array( "{$this->alias}.password" => '\''.Security::hash( $data[$this->alias]['new_password'], null, true ).'\'' ),
				array( "{$this->alias}.{$this->primaryKey}" => $data[$this->alias][$this->primaryKey] )
			);
		}

		/**
		 * Retourne la liste des enums pour le modèle User.
		 *
		 * Lorsque l'on est CG 66, on filtre les valeurs possibles du champ type
		 * et on change une traduction pour les référents des OA.
		 *
		 * @return array
		 */
		public function enums() {
			$enums = parent::enums();

			if( isset( $enums[$this->alias]['type'] ) && Configure::read( 'Cg.departement' ) == 66 ) {
				unset( $enums[$this->alias]['type']['externe_cpdv'] );
				unset( $enums[$this->alias]['type']['externe_secretaire'] );

				$enums[$this->alias]['type']['externe_ci'] = 'Référent organisme agrée';
			}

			return $enums;
		}
	}
?>