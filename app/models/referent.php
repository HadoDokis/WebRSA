<?php
	class Referent extends AppModel
	{
		public $name = 'Referent';

		public $displayField = 'nom_complet';

		public $actsAs = array(
			'Autovalidate',
			'Formattable'
		);

		public $order = array( 'Referent.nom ASC', 'Referent.prenom ASC' );

		public $virtualFields = array(
			'nom_complet' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
			),
		);

		public $validate = array(
			'numero_poste' => array(
				array(
					'rule' => 'numeric',
					'message' => 'Le numéro de téléphone est composé de chiffres',
					'allowEmpty' => true
				),
				array(
					'rule' => array( 'between', 10, 14 ),
					'message' => 'Le N° de poste doit être composé de 10 chiffres'
				)/*,
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)*/
			),
			'qual' => array(
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
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'fonction' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'email' => array(
	//                 array(
	//                     'rule' => 'notEmpty',
	//                     'message' => 'Champ obligatoire'
	//                 ),
				array(
					'rule' => 'email',
					'message' => 'Veuillez entrer une adresse email valide',
					'allowEmpty' => true
				)
			),
			'structurereferente_id' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
		);

		public $belongsTo = array(
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'referent_id',
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
			'ActioncandidatPersonne' => array(
				'className' => 'ActioncandidatPersonne',
				'foreignKey' => 'referent_id',
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
			'Bilanparcours' => array(
				'className' => 'Bilanparcours',
				'foreignKey' => 'referent_id',
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
			'Entretien' => array(
				'className' => 'Entretien',
				'foreignKey' => 'referent_id',
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
			'Decisionparcours' => array(
				'className' => 'Decisionparcours',
				'foreignKey' => 'referent_id',
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
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'referent_id',
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
			'Apre' => array(
				'className' => 'Apre',
				'foreignKey' => 'referent_id',
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
			'Precosreorient' => array(
				'className' => 'Precosreorient',
				'foreignKey' => 'referent_id',
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
				'foreignKey' => 'referent_id',
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
			'Rendezvous' => array(
				'className' => 'Rendezvous',
				'foreignKey' => 'referent_id',
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
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'referent_id',
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
			'Personne' => array(
				'className' => 'Personne',
				'joinTable' => 'personnes_referents',
				'foreignKey' => 'referent_id',
				'associationForeignKey' => 'personne_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'PersonneReferent'
			)
		);

		/**
		*
		*/

		public function listOptions() {
			$this->unbindModelAll();
			$tmp = $this->find(
				'all',
				array (
					'fields' => array(
						'Referent.id',
						'Referent.structurereferente_id',
						'Referent.qual',
						'Referent.nom',
						'Referent.prenom'
					),
					'contain' => false,
					'order' => 'Referent.nom ASC',
				)
			);

			$return = array();
			foreach( $tmp as $key => $value ) {
				$return[$value['Referent']['structurereferente_id'].'_'.$value['Referent']['id']] = $value['Referent']['qual'].' '.$value['Referent']['nom'].' '.$value['Referent']['prenom'];
			}
			return $return;
		}

		/**
		*   Retourne la liste des Referents
		*/

		public function referentsListe( $structurereferente_id = null ) {
			// Population du select référents liés aux structures
			$conditions = array();
			if( !empty( $structurereferente_id ) ) {
				$conditions['Referent.structurereferente_id'] = $structurereferente_id;
			}

			$referents = $this->find(
				'all',
				array(
					'recursive' => -1,
					'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom' ),
					'conditions' => $conditions
				)
			);

			if( !empty( $referents ) ) {
				$ids = Set::extract( $referents, '/Referent/id' );
				$values = Set::format( $referents, '{0} {1} {2}', array( '{n}.Referent.qual', '{n}.Referent.nom', '{n}.Referent.prenom' ) );
				$referents = array_combine( $ids, $values );
			}
			return $referents;
		}

		/**
		* Retourne l'id du référent lié à une personne
		*/

		public function readByPersonneId( $personne_id ) {
			$referent_id = null;

			// Valeur par défaut préférée: à partir de personnes_referents
			$referent = $this->PersonneReferent->find(
				'first',
				array(
					'conditions' => array( 'personne_id' => $personne_id ), // FIXME ddesignation / dfdesignation
					'order' => array( 'dddesignation ASC' ),
					'recursive' => -1
				)
			);
			$referent_id = Set::classicExtract( $referent, 'PersonneReferent.referent_id' );

			// Valeur par défaut de substitution: à partir de orientsstructs
			if( empty( $referent_id ) ) {
				$orientstruct = $this->Personne->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'personne_id' => $personne_id,
							'statut_orient' => 'Orienté',
							'date_valid IS NOT NULL'
						),
						'order' => array( 'date_valid ASC' ),
						'recursive' => -1
					)
				);

				if( !empty( $orientstruct ) ) {
					$referent_id = Set::classicExtract( $orientstruct, 'Orientstruct.referent_id' );
					$structurereferente_id = Set::classicExtract( $orientstruct, 'Orientstruct.structurereferente_id' );
					$count = $this->Personne->Referent->find(
						'count',
						array(
							'conditions' => array( 'structurereferente_id' => $structurereferente_id ),
							'recursive' => -1
						)
					);

					if( empty( $referent_id ) && !empty( $structurereferente_id ) && ( $count == 1 ) ) {
						$referent = $this->Personne->Referent->find(
							'first',
							array(
								'conditions' => array( 'structurereferente_id' => $structurereferente_id ),
								'order' => array( 'id ASC' ),
								'recursive' => -1
							)
						);
						$referent_id = Set::classicExtract( $referent, 'Referent.id' );
					}
				}
			}

			if( !empty( $referent_id ) ) {
				return $this->Personne->Referent->find(
					'first',
					array(
						'conditions' => array( 'id' => $referent_id ),
						'recursive' => -1
					)
				);
			}

			return null;
		}
	}
?>
