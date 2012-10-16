<?php
	class Serviceinstructeur extends AppModel
	{
		public $name = 'Serviceinstructeur';

		public $displayField = 'lib_service';

		public $order = 'Serviceinstructeur.lib_service ASC';

		public $actsAs = array(
			'Autovalidate',
			'Formattable'
		);

		public $validate = array(
			'lib_service' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'isUnique',
					'message' => 'Valeur déjà utilisée'
				),
			),
			'type_voie' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'code_insee' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
					// FIXME: format
				)
			),
			'numdepins' => array(
				array(
					'rule' => 'alphaNumeric',
					'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
				),
				array(
					'rule' => array( 'between', 3, 3 ),
					'message' => 'Le n° de département est composé de 3 caractères'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'typeserins' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'numcomins' => array(
				array(
					'rule' => 'alphaNumeric',
					'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
				),
				array(
					'rule' => array( 'between', 3, 3 ),
					'message' => 'Le n° de commune est composé de 3 caractères'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'numagrins' => array(
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
				),
				array(
					'rule' => array( 'between', 1, 2 ),
					'message' => 'Le n° d\'agrément est composé de 2 caractères'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'sqrecherche' => array(
				array(
					'rule' => 'validateSqrecherche',
					'message' => 'Erreur SQL'
				)
			)
		);

		public $hasMany = array(
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'serviceinstructeur_id',
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
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
				'foreignKey' => 'serviceinstructeur_id',
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
				'foreignKey' => 'serviceinstructeur_id',
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
				'foreignKey' => 'serviceinstructeur_id',
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
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'joinTable' => 'orientsstructs_servicesinstructeurs',
				'foreignKey' => 'serviceinstructeur_id',
				'associationForeignKey' => 'orientstruct_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'OrientstructServiceinstructeur'
			)
		);

		/**
		*
		*/

		public $_types = array(
			'list' => array(
				'fields' => array(
					'"Serviceinstructeur"."id"',
					'"Serviceinstructeur"."lib_service"',
					'"Serviceinstructeur"."num_rue"',
					'"Serviceinstructeur"."nom_rue"',
					'"Serviceinstructeur"."complement_adr"',
					'"Serviceinstructeur"."code_insee"',
					'"Serviceinstructeur"."code_postal"',
					'"Serviceinstructeur"."ville"',
					'"Serviceinstructeur"."numdepins"',
					'"Serviceinstructeur"."typeserins"',
					'"Serviceinstructeur"."numcomins"',
					'"Serviceinstructeur"."numagrins"',
					'"Serviceinstructeur"."type_voie"',
					'COUNT("User"."id") AS "Serviceinstructeur__nbUsers"',
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'users',
						'alias'      => 'User',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Serviceinstructeur.id = User.serviceinstructeur_id' )
					),
				),
				'group' => array(
					'"Serviceinstructeur"."id"',
					'"Serviceinstructeur"."lib_service"',
					'"Serviceinstructeur"."num_rue"',
					'"Serviceinstructeur"."nom_rue"',
					'"Serviceinstructeur"."complement_adr"',
					'"Serviceinstructeur"."code_insee"',
					'"Serviceinstructeur"."code_postal"',
					'"Serviceinstructeur"."ville"',
					'"Serviceinstructeur"."numdepins"',
					'"Serviceinstructeur"."typeserins"',
					'"Serviceinstructeur"."numcomins"',
					'"Serviceinstructeur"."numagrins"',
					'"Serviceinstructeur"."type_voie"',
				),
				'order' => 'Serviceinstructeur.lib_service ASC',
			)
		);

		/**
		 *
		 * @return array
		 */
		public function listOptions( $typeserinsC = false ) {
			$cacheKey = 'serviceinstructeur_list_options_typec'.( $typeserinsC ? '1' : '0' );
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$conditions = ( $typeserinsC ? array( 'Serviceinstructeur.typeserins <>' => 'C' ) : array() );

				$results = $this->find(
					'list',
					array (
						'fields' => array(
							'Serviceinstructeur.id',
							'Serviceinstructeur.lib_service'
						),
						'order'  => array( 'Serviceinstructeur.lib_service ASC' ),
						'conditions' => $conditions
					)
				);

				Cache::write( $cacheKey, $results );
			}

			return $results;
		}

		/**
		*
		*/

		public function prepare( $type, $params = array() ) {
			$types = array_keys( $this->_types );
			if( !in_array( $type, $types ) ) {
				trigger_error( 'Invalid parameter "'.$type.'" for '.$this->name.'::prepare()', E_USER_WARNING );
			}
			else {
				$querydata = $this->_types[$type];
				$querydata = Set::merge( $querydata, $params );

				return $querydata;
			}
		}

		/**
		*
		*/

		protected function _queryDataError( &$model, $querydata ) {
			$querydata['limit'] = 1;
			$sql = $model->sq( $querydata );
			$ds = $model->getDataSource( $model->useDbConfig );

			$result = false;
			try {
				$result = @$model->query( "EXPLAIN $sql" );
			} catch( Exception $e ) {
			}

			if( $result === false ) {
				return $sql;
			}
			else {
				return false;
			}
		}

		/**
		*
		*/

		// 			$this->Serviceinstructeur->sqrechercheErrors( 'foo' );
		// FIXME: criterespdos/index, criterespdos/nouvelles, criterespdos/exportcsv ($this->Criterepdo->listeDossierPDO, Criterepdo->search)
		public function sqrechercheErrors( $condition ) {
			$errors = array();

			if( Configure::read( 'Recherche.qdFilters.Serviceinstructeur' ) ) {
				$models = array(
					'Dossier' => 'Dossier',
					'Critere' => 'Orientstruct',
					'Cohorteci' => 'Contratinsertion',
					'Criterecui' => 'Cui',
					'Cohorteindu' => 'Dossier',
					'Critererdv' => 'Rendezvous',
					'Criterepdo' => 'Propopdo',
				);

				foreach( $models as $modelSearch => $modelName ) {
					$search = ClassRegistry::init( $modelSearch );
					$model = ClassRegistry::init( $modelName );

					$querydata = @$search->search( array(), array(), array(), array(), array() );

					if( !empty( $condition ) ) {
						$querydata['conditions'][] = $condition;
					}

					$error = $this->_queryDataError( $model, $querydata );

					if( !empty( $error ) ) {
						$ds = $model->getDataSource( $model->useDbConfig );
						$errors[$model->alias] = array(
							'sql' => $error,
							'error' => $ds->lastError()
						);
					}
				}
			}
			return $errors;
		}

		/**
		*
		*/

		public function validateSqrecherche( $check ) {
			if( !is_array( $check ) ) {
				return false;
			}

			// TODO: meilleure validation ?
			$result = true;
			foreach( Set::normalize( $check ) as $key => $condition ) {
				$errors = $this->sqrechercheErrors( $condition );
				$result = empty( $errors ) && $result;
			}
			return $result;
		}

		/**
		 * Retourne les enregistrements pour lesquels une erreur de paramétrage
		 * a été détectée.
		 * Il s'agit des services instructeurs pour lesquels on ne connaît pas
		 * le nom du service, ou une des colonnes permettant de faire la jointure
		 * avec les dossiers.
		 *
		 * @return array
		 */
		public function storedDataErrors() {
			return $this->find(
				'all',
				array(
					'fields' => array(
						'Serviceinstructeur.id',
						'Serviceinstructeur.lib_service',
						'Serviceinstructeur.numdepins',
						'Serviceinstructeur.typeserins',
						'Serviceinstructeur.numcomins',
						'Serviceinstructeur.numagrins',
					),
					'conditions' => array(
						'OR' => array(
							'Serviceinstructeur.lib_service IS NULL',
							'TRIM(Serviceinstructeur.lib_service)' => null,
							'Serviceinstructeur.numdepins IS NULL',
							'TRIM(Serviceinstructeur.numdepins)' => null,
							'Serviceinstructeur.typeserins IS NULL',
							'TRIM(Serviceinstructeur.typeserins)' => null,
							'Serviceinstructeur.numcomins IS NULL',
							'TRIM(Serviceinstructeur.numcomins)' => null,
							'Serviceinstructeur.numagrins IS NULL'
						)
					),
					'contain' => false,
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
				'serviceinstructeur_list_options_typec1',
				'serviceinstructeur_list_options_typec0'
			);

			foreach( $keys as $key ) {
				Cache::delete( $key );
			}

			// Regénération des éléments du cache.
			$success = true;

			$tmp  = $this->listOptions();
			$success = !empty( $tmp ) && $success;

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