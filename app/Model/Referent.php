<?php
	/**
	 * Code source de la classe Referent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	define( 'CHAMP_FACULTATIF_REFERENT', Configure::read( 'Cg.departement' ) == 58 );

	/**
	 * La classe Referent s'occupe de la gestion des référents.
	 *
	 * @package app.Model
	 */
	class Referent extends AppModel
	{
		public $name = 'Referent';

		public $displayField = 'nom_complet';

		public $actsAs = array(
			'Autovalidate2',
			'Enumerable' => array(
				'fields' => array(
					'actif' => array( 'type' => 'no', 'domain' => 'default' )
				)
			),
			'Formattable' => array(
				'phone' => array( 'numero_poste' )
			),
			'ValidateTranslate',
			'Validation.ExtraValidationRules',
		);

		public $order = array( 'Referent.nom ASC', 'Referent.prenom ASC' );

		public $virtualFields = array(
			'nom_complet' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
			),
			'nom_complet_court' => array(
				'type'		=> 'string',
				'postgres'	=> '( "%s"."nom" || \' \' || "%s"."prenom" )'
			)
		);

		public $validate = array(
			'numero_poste' => array(
				'phoneFr' => array(
					'rule' => array( 'phoneFr' ),
					'allowEmpty' => true,
				)
			),
			'qual' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire',
					'allowEmpty' => CHAMP_FACULTATIF_REFERENT
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
			'fonction' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'email' => array(
				'email' => array(
					'rule' => 'email',
					'message' => 'Veuillez entrer une adresse email valide',
					'allowEmpty' => true
				)
			),
			'structurereferente_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
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
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
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
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
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
			),
			'PersonneReferent' => array(
				'className' => 'PersonneReferent',
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
			'Decisiondefautinsertionep66' => array(
				'className' => 'Decisiondefautinsertionep66',
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
			'Decisionsaisinebilanparcoursep66' => array(
				'className' => 'Decisionsaisinebilanparcoursep66',
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
			'Decisionpropoorientsocialecov58' => array(
				'className' => 'Decisionpropoorientsocialecov58',
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
			'Ficheprescription93' => array(
				'className' => 'Ficheprescription93',
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
			'Decisionnonorientationprocov58' => array(
				'className' => 'Decisionnonorientationprocov58',
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



		public function search( $criteres ) {
			/// Conditions de base
			$conditions = array();

			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			$filtersReferent = array();
			foreach( array( 'nom', 'prenom', 'fonction' ) as $critereReferent ) {
				if( isset( $criteres['Referent'][$critereReferent] ) && !empty( $criteres['Referent'][$critereReferent] ) ) {
					$conditions[] = 'Referent.'.$critereReferent.' ILIKE \''.$this->wildcard( $criteres['Referent'][$critereReferent] ).'\'';
				}
			}

			if( isset( $criteres['Referent']['id'] ) && !empty( $criteres['Referent']['id'] ) ) {
				$conditions[] = array( 'Referent.id' => $criteres['Referent']['id'] );
			}

			// Critère sur la structure référente de l'utilisateur
			if( isset( $criteres['Referent']['structurereferente_id'] ) && !empty( $criteres['Referent']['structurereferente_id'] ) ) {
				$conditions[] = array( 'Referent.structurereferente_id' => $criteres['Referent']['structurereferente_id'] );
			}


			$query = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Structurereferente->fields(),
					array(
						$this->PersonneReferent->sqNbLiesActifs( $this, 'Referent.id', 'nb_referents_lies' )
					)
				),
				'order' => array( 'Referent.nom ASC', 'Referent.prenom ASC' ),
				'joins' => array(
					$this->join( 'Structurereferente', array( 'type' => 'INNER' ) )
				),
				'recursive' => -1,
				'conditions' => $conditions
			);

			return $query;
		}


		/**
		 * Renvoit une liste clé / valeur avec clé qui est l'id de la structure référente underscore l'id du référent
		 * et la valeur qui est qual, nom, prénom du référent.
		 * Utilisé pour les valeurs des input select.
		 *
		 * @return array
		 */
		public function listOptions() {
			$cacheKey = 'referent_list_options';
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
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
						'conditions' => array(
							'Referent.actif' => 'O'
						)
					)
				);

				$results = array();
				foreach( $tmp as $key => $value ) {
					$results[$value['Referent']['structurereferente_id'].'_'.$value['Referent']['id']] = $value['Referent']['qual'].' '.$value['Referent']['nom'].' '.$value['Referent']['prenom'];
				}

				Cache::write( $cacheKey, $results );
				ModelCache::write( $cacheKey, array( 'Referent', 'Structurereferente', 'Typeorient' ) );
			}

			return $results;
		}

		/**
		*   Retourne la liste des Referents
		*/

		public function referentsListe( $structurereferente_id = null ) {
			// Population du select référents liés aux structures
			$conditions = array();
			$conditions = array( 'Referent.actif' => 'O' );
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

		/**
		 * Récupère la liste des référents groupés par structure référente
		 * Cette liste est mise en cache et on se sert de la classe ModelCache
		 * pour savoir quelles clés de cache supprimer lorsque les données de ce
		 * modèle changent.
		 *
		 * @return array
		 */
		public function listOptionsParStructure() {
			$cacheKey = 'referentparstructure_list_options';
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$results = $this->find(
					'list',
					array(
						'fields' => array(
							'Referent.id',
							'Referent.nom_complet',
							'Structurereferente.lib_struc',
						),
						'recursive' => -1,
						'joins' => array(
							$this->join( 'Structurereferente', array( 'type' => 'INNER' ) )
						),
						'order' => array(
							'Structurereferente.lib_struc ASC',
							'Referent.nom_complet_court ASC'
						),
						'conditions' => array(
							'Structurereferente.actif' => 'O'
						)
					)
				);
				Cache::write( $cacheKey, $results );
				ModelCache::write( $cacheKey, array( 'Referent', 'Structurereferente', 'Typeorient' ) );
			}
			return $results;
		}

		/**
		 * Suppression et regénération du cache.
		 *
		 * @return boolean
		 */
		protected function _regenerateCache() {
			$this->_clearModelCache();

			// Regénération des éléments du cache.
			$success = ( $this->listOptions() !== false )
				&& ( $this->listOptionsParStructure() !== false );

			return $success;
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les fonctions vides.
		 */
		public function prechargement() {
			$success = $this->_regenerateCache();
			return $success;
		}
	}
?>