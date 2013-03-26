<?php	
	/**
	 * Code source de la classe Canton.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Canton ...
	 *
	 * @package app.Model
	 */
	class Canton extends AppModel
	{
		public $name = 'Canton';

		public $displayField = 'canton';

		public $belongsTo = array(
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'foreignKey' => 'zonegeographique_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $validate = array(
			'canton' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'zonegeographique_id' => array(
				array(
					'rule' => 'integer',
					'message' => 'Veuillez entrer un nombre entier'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
			),
			'locaadr' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'codepos' => array(
				array(
					'rule' => array( 'between', 5, 5 ),
					'message' => 'Le code postal se compose de 5 caractères',
					'allowEmpty' => true
				)
			),
			'numcomptt' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => array( 'between', 5, 5 ),
					'message' => 'Le code INSEE se compose de 5 caractères'
				)
			),
		);

		/**
		*	FIXME: docs
		*/

		public function selectList( $filtre_zone_geo = false, $zonesgeographiques = array() ) {
			$conditions = array( 'Canton.canton IS NOT NULL', 'Canton.canton <> \'\'' );

			if( $filtre_zone_geo ) {
				$conditions['Canton.zonegeographique_id'] = $zonesgeographiques;
			}

			$queryData = array(
				'fields' => array( 'DISTINCT Canton.canton' ),
				'conditions' => $conditions,
				'recursive' => -1,
				'order' => array( 'Canton.canton ASC' )
			);

			$results = parent::find( 'all', $queryData );

			if( !empty( $results ) ) {
				$cantons = Set::extract( $results, '/Canton/canton' );
				return array_combine( $cantons, $cantons );
			}
			else {
				return $results;
			}
		}

		/**
		*	FIXME: docs
		*/

		public function queryConditions( $canton ) {
			$cantons = $this->find(
				'all',
				array(
					'conditions' => array(
					'Canton.canton' => $canton
					)
				)
			);
			$_conditions = array();
			foreach( $cantons as $canton ) {
				$_condition = array();
				// INFO: les couples numcomptt / codepos de la table adresses ne correspondent
				// pas toujours aux couples de la table cantons.
				if( !empty( $canton['Canton']['numcomptt'] ) && !empty( $canton['Canton']['codepos'] ) ) {
					$_condition['OR'] = array(
						 'Adresse.numcomptt' => $canton['Canton']['numcomptt'],
						 'Adresse.codepos' => $canton['Canton']['codepos']
					);
				}
				else {
					if( !empty( $canton['Canton']['numcomptt'] ) ) {
						$_condition['Adresse.numcomptt'] = $canton['Canton']['numcomptt'];
					}
					if( !empty( $canton['Canton']['codepos'] ) ) {
						$_condition['Adresse.codepos'] = $canton['Canton']['codepos'];
					}
				}
				if( !empty( $canton['Canton']['locaadr'] ) ) {
					$_condition['Adresse.locaadr ILIKE'] = $canton['Canton']['locaadr'];
				}
				if( !empty( $canton['Canton']['typevoie'] ) ) {
					$_condition['Adresse.typevoie ILIKE'] = $canton['Canton']['typevoie'];
				}
				if( !empty( $canton['Canton']['nomvoie'] ) ) {
					$_condition['Adresse.nomvoie ILIKE'] = $canton['Canton']['nomvoie'];
				}
				$_conditions[] = $_condition;
			}
			return array( 'or' => $_conditions );
		}

		/**
		*	FIXME: docs
		*/

		public function queryConditionsByZonesgeographiques( $zonesgeographiques ) {
			$cantons = array();
			if( !empty( $zonesgeographiques ) ) {
				$cantons = $this->find(
					'all',
					array(
						'conditions' => array(
							'Canton.zonegeographique_id' => $zonesgeographiques
						)
					)
				);
			}
			$_conditions = array();
			foreach( $cantons as $canton ) {
				$_condition = array();
				// INFO: les couples numcomptt / codepos de la table adresses ne correspondent
				// pas toujours aux couples de la table cantons.
				if( !empty( $canton['Canton']['numcomptt'] ) && !empty( $canton['Canton']['codepos'] ) ) {
					$_condition['OR'] = array(
						 'Adresse.numcomptt' => $canton['Canton']['numcomptt'],
						 'Adresse.codepos' => $canton['Canton']['codepos']
					);
				}
				else {
					if( !empty( $canton['Canton']['numcomptt'] ) ) {
						$_condition['Adresse.numcomptt'] = $canton['Canton']['numcomptt'];
					}
					if( !empty( $canton['Canton']['codepos'] ) ) {
						$_condition['Adresse.codepos'] = $canton['Canton']['codepos'];
					}
				}
				if( !empty( $canton['Canton']['locaadr'] ) ) {
					$_condition['Adresse.locaadr ILIKE'] = $canton['Canton']['locaadr'];
				}
				if( !empty( $canton['Canton']['typevoie'] ) ) {
					$_condition['Adresse.typevoie ILIKE'] = $canton['Canton']['typevoie'];
				}
				if( !empty( $canton['Canton']['nomvoie'] ) ) {
					$_condition['Adresse.nomvoie ILIKE'] = $canton['Canton']['nomvoie'];
				}
				$_conditions[] = $_condition;
			}
			return array( 'OR' => $_conditions );
		}

		/**
		 *
		 */
		public function joinAdresse( $adresseAlias = 'Adresse', $type = 'LEFT OUTER' ) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$fullTableName = $dbo->fullTableName( $this, true );

			$conditions = array();

			$conditions = array(
				'OR' => array(
					// 156-161
					array(
						'OR' => array(
							'OR' => array(
								"Canton.numcomptt IS NULL",
								"TRIM( BOTH ' ' FROM Canton.numcomptt ) = ''",
								"Canton.codepos IS NULL",
								"TRIM( BOTH ' ' FROM Canton.codepos ) = ''",
							),
							array(
								"Canton.numcomptt = Adresse.numcomptt",
								"Canton.codepos = Adresse.codepos",
							)
						),
					),
					array(
						array(
							'OR' => array(
								array(
									"Canton.numcomptt IS NULL",
									"TRIM( BOTH ' ' FROM Canton.numcomptt ) = ''",
								),
								"Canton.numcomptt = Adresse.numcomptt",
							)
						),
						array(
							'OR' => array(
								array(
									"Canton.codepos IS NULL",
									"TRIM( BOTH ' ' FROM Canton.codepos ) = ''",
								),
								"Canton.codepos = Adresse.codepos",
							)
						),
					)
				),
				// 170/178
				array(
					'OR' => array(
						'OR' => array(
							'Canton.locaadr IS NULL',
							"TRIM( BOTH ' ' FROM Canton.locaadr ) = ''",
						),
						'Adresse.locaadr ILIKE Canton.locaadr'
					)
				),
				array(
					'OR' => array(
						'OR' => array(
							'Canton.typevoie IS NULL',
							"TRIM( BOTH ' ' FROM Canton.typevoie ) = ''",
						),
						'Adresse.typevoie ILIKE Canton.typevoie'
					)
				),
				array(
					'OR' => array(
						'OR' => array(
							'Canton.nomvoie IS NULL',
							"TRIM( BOTH ' ' FROM Canton.nomvoie ) = ''",
						),
						'Adresse.nomvoie ILIKE Canton.nomvoie'
					)
				),
			);

			$sq = $this->sq(
				array(
					'alias' => 'cantons',
					'fields' => array( 'cantons.id' ),
					'conditions' => array_words_replace( $conditions, array( 'Canton' => 'cantons' ) ),
					'contain' => false,
					'recursive' => -1,
					'order' => array(
						'cantons.nomvoie DESC',
						'cantons.typevoie DESC',
					),
					'limit' => 1
				)
			);
			
			$conditions[] = "Canton.id IN ( {$sq} )";

			return array(
				'table'      => $fullTableName,
				'alias'      => $this->alias,
				'type'       => $type,
				'foreignKey' => false,
				'conditions' => $conditions
			);
		}

		/**
		*	FIXME: docs
		*/

		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			foreach( array( 'nomvoie', 'locaadr', 'canton' ) as $field ) {
				if( !empty( $this->data[$this->name][$field] ) ) {
					$this->data[$this->name][$field] = strtoupper( replace_accents( $this->data[$this->name][$field] ) );
				}
			}

			return $return;
		}
		
		/**
		*	Recherche des partenaires dans le paramétrage de l'application
		*
		*/
		public function search( $criteres ) {
			/// Conditions de base
			$conditions = array();

			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			$filtersCantons = array();
			foreach( array( 'canton', 'locaadr', 'codepos', 'numcomptt' ) as $critereCanton ) {
				if( isset( $criteres['Canton'][$critereCanton] ) && !empty( $criteres['Canton'][$critereCanton] ) ) {
					$conditions[] = 'Canton.'.$critereCanton.' ILIKE \''.$this->wildcard( $criteres['Canton'][$critereCanton] ).'\'';
				}
			}

			// Critère sur la structure référente de l'utilisateur
			if( isset( $criteres['Canton']['zonegeographique_id'] ) && !empty( $criteres['Canton']['zonegeographique_id'] ) ) {
				$conditions[] = array( 'Canton.zonegeographique_id' => $criteres['Canton']['zonegeographique_id'] );
			}


			$query = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Zonegeographique->fields()
				),
				'order' => array( 'Canton.canton ASC' ),
				'joins' => array(
					$this->join( 'Zonegeographique', array( 'type' => 'LEFT OUTER' ) )
				),
				'recursive' => -1,
				'conditions' => $conditions
			);

			return $query;
		}
	}
?>