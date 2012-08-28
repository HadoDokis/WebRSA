<?php
	class Structurereferente extends AppModel
	{
		public $name = 'Structurereferente';

		public $displayField = 'lib_struc';

		public $order = array( 'lib_struc ASC' );

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'contratengagement' => array( 'type' => 'no', 'domain' => 'default' ),
					'apre' => array( 'type' => 'no', 'domain' => 'default' ),
					'orientation' => array( 'type' => 'no', 'domain' => 'default' ),
					'pdo' => array( 'type' => 'no', 'domain' => 'default' ),
					'actif' => array( 'type' => 'no', 'domain' => 'default' ),
					'typestructure' => array( 'domain' => 'structurereferente' )
				)
			),
			'Formattable' => array(
				'phone' => array( 'numtel' )
			)
		);

		public $validate = array(
			'lib_struc' => array(
				array(
						'rule' => 'notEmpty',
						'message' => 'Champ obligatoire'
				),
				array(
						'rule' => 'isUnique',
						'message' => 'Valeur déjà utilisée'
				),
			),
			'num_voie' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'type_voie' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'nom_voie' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'code_postal' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'ville' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'code_insee' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'typeorient_id'=> array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'apre' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'contratengagement' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'numtel' => array(
				'rule' => 'phoneFr',
				'allowEmpty' => true,
				'message' => 'Ce numéro de téléphone n\'est pas valide'
			)
		);

		public $belongsTo = array(
			'Typeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'typeorient_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasOne = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'structurereferente_id',
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

		public $hasMany = array(
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'structurereferente_id',
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
				'foreignKey' => 'structurereferente_id',
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
				'foreignKey' => 'structurereferente_id',
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
				'foreignKey' => 'structurereferente_id',
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
			'Permanence' => array(
				'className' => 'Permanence',
				'foreignKey' => 'structurereferente_id',
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
				'foreignKey' => 'structurereferente_id',
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
				'foreignKey' => 'structurereferente_id',
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
				'foreignKey' => 'structurereferente_id',
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
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'structurereferente_id',
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
				'foreignKey' => 'structurereferente_id',
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
			'Regressionorientationep58' => array(
				'className' => 'Regressionorientationep58',
				'foreignKey' => 'structurereferente_id',
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
			'Decisionnonorientationproep58' => array(
				'className' => 'Decisionnonorientationproep58',
				'foreignKey' => 'structurereferente_id',
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
			'Decisionnonorientationproep93' => array(
				'className' => 'Decisionnonorientationproep93',
				'foreignKey' => 'structurereferente_id',
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
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'joinTable' => 'structuresreferentes_zonesgeographiques',
				'foreignKey' => 'structurereferente_id',
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
				'with' => 'StructurereferenteZonegeographique'
			)
		);

		/**
		 *
		 * @return array
		 */
		public function list1Options() {
			$cacheKey = 'structurereferente_list1_options';
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$tmp = $this->find(
					'all',
					array(
						'conditions' => array( 'Structurereferente.actif' => 'O' ),
						'fields' => array(
							'Structurereferente.id',
							'Structurereferente.typeorient_id',
							'Structurereferente.lib_struc'
						),
						'order'  => array( 'Structurereferente.lib_struc ASC' ),
						'recursive' => -1
					)
				);

				$results = array();
				foreach( $tmp as $key => $value ) {
					$results[$value['Structurereferente']['typeorient_id'].'_'.$value['Structurereferente']['id']] = $value['Structurereferente']['lib_struc'];
				}

				Cache::write( $cacheKey, $results );
			}

			return $results;
		}

		/**
		 * Récupère la liste des structures référentes groupées par type d'orientation.
		 * Cette liste est mise en cache, donc on se sert des fonctions de callback
		 * afterSave et afterDelete pour supprimer et regénérer le cache.
		 *
		 * @return array
		 */
		public function listOptions() {
			$cacheKey = 'structurereferente_list_options';
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$results = $this->find(
					'list',
					array(
						'fields' => array(
							'Structurereferente.id',
							'Structurereferente.lib_struc',
							'Typeorient.lib_type_orient'
						),
						'recursive' => -1,
						'joins' => array(
							$this->join( 'Typeorient', array( 'type' => 'INNER' ) )
						),
						'order' => array(
							'Typeorient.lib_type_orient ASC',
							'Structurereferente.lib_struc'
						),
						'conditions' => array(
							'Structurereferente.actif' => 'O'
						)
					)
				);
				Cache::write( $cacheKey, $results );
			}
			return $results;
		}

		/**
		*
		*/

		public function listePourApre() {
			///Récupération de la liste des référents liés à l'APRE
			$structsapre = $this->Structurereferente->find( 'list', array( 'conditions' => array( 'Structurereferente.apre' => 'O' ) ) );
			$this->set( 'structsapre', $structsapre );
		}

		/**
		*   Retourne la liste des structures référentes filtrée selon un type donné
		* @param array $types ( array( 'apre' => true, 'contratengagement' => true ) )
		* par défaut, toutes les clés sont considérées commen étant à false
		*/

		public function listeParType( $types ) {
			$conditions = array();

			foreach( array( 'apre', 'contratengagement', 'orientation', 'pdo' ) as $type ) {
				$bool = Set::classicExtract( $types, $type );
				if( !empty( $bool ) ) {
					$conditions[] = "Structurereferente.{$type} = 'O'";
				}
			}

			return $this->find( 'list', array( 'conditions' => $conditions, 'recursive' => -1 ) );
		}

		/**
		 * Retourne les enregistrements pour lesquels une erreur de paramétrage
		 * a été détectée.
		 * Il s'agit des structures pour lesquelles on ne sait pas si elles gèrent
		 * l'APRE ni le CER.
		 *
		 * @return array
		 */
		public function storedDataErrors() {
			return $this->find(
				'all',
				array(
					'fields' => array(
						'Structurereferente.id',
						'Structurereferente.lib_struc',
						'Structurereferente.apre',
						'Structurereferente.contratengagement'
					),
					'recursive' => -1,
					'conditions' => array(
						'OR' => array(
							'Structurereferente.apre' => NULL,
							'Structurereferente.contratengagement' => NULL
						)
					),
					'contain' => false
				)
			);
		}

		/**
		 * Suppression et regénération du cache.
		 *
		 * @return boolean
		 */
		protected function _regenerateCache() {
			// Suppression des éléments du cache.
			$keys = array(
				'structurereferente_list1_options',
				'structurereferente_list_options',
				'cohorte_structures_automatiques',
				'referent_list_options',
			);

			foreach( $keys as $key ) {
				Cache::delete( $key );
			}

			// Regénération des éléments du cache.
			$success = true;

			if( $this->alias == 'Structurereferente' ) {
				$tmp  = $this->listOptions();
				$success = !empty( $tmp ) && $success;

				$tmp  = $this->list1Options();
				$success = !empty( $tmp ) && $success;

				$tmp  = ClassRegistry::init( 'Cohorte' )->structuresAutomatiques();
				$success = !empty( $tmp ) && $success;
			}

			return $success;
		}

		/**
		 * Après une sauvegarde, on regénère les données en cache.
		 *
		 * @param boolean $created True if this save created a new record
		 * @return void
		 */
		public function afterSave( $created ) {
			parent::afterSave( $created );
			$this->_regenerateCache();
		}

		/**
		 * Après une suppression, on regénère les données en cache.
		 *
		 * @param boolean $created True if this save created a new record
		 * @return void
		 */
		public function afterDelete() {
			parent::afterDelete();
			$this->_regenerateCache();
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