<?php
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

		public function selectList() {
			$queryData = array(
				'fields' => array( 'DISTINCT Canton.canton' ),
				'conditions' => array( 'Canton.canton IS NOT NULL', 'Canton.canton <> \'\'' ),
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
				/*if( !empty( $canton['Canton']['numcomptt'] ) ) {
					$_condition['Adresse.numcomptt'] = $canton['Canton']['numcomptt'];
				}
				if( !empty( $canton['Canton']['codepos'] ) ) {
					$_condition['Adresse.codepos'] = $canton['Canton']['codepos'];
				}*/
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
	}
?>
