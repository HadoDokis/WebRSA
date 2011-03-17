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
				)
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
	//                 array(
	//                     'rule' => 'isUnique',
	//                     'message' => 'Cette valeur est déjà utilisée'
	//                 ),
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
			)
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
		*/

		public function listOptions() {
			return  $this->find(
				'list',
				array (
					'fields' => array(
						'Serviceinstructeur.id',
						'Serviceinstructeur.lib_service'
					),
					'order'  => array( 'Serviceinstructeur.lib_service ASC' )
				)
			);
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
							'error' => ( ( $ds->config['driver'] == 'postgres' ) ? pg_last_error() : null )
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
	}
?>