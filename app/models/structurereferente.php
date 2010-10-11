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
					'pdo' => array( 'type' => 'no', 'domain' => 'default' )
				)
			),
			'Formattable'
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
			'Bilanparcours' => array(
				'className' => 'Bilanparcours',
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
			'Decisionparcours' => array(
				'className' => 'Decisionparcours',
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
			'Precosreorient' => array(
				'className' => 'Precosreorient',
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
		* Difficile à mettre en cache du fait des conditions
		*/

		public function list1Options( $conditions = array() ) {
			$tmp = $this->find(
				'all',
				array(
					'conditions' => $conditions,
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

			return $results;
		}


		/**
		* Récupère la liste des structures référentes groupées par type d'orientation
		* Cette liste est mise en cache, donc -> FIXME: supprimer le cache quand les
		* structuresreferentes sont modifiées.
		*/

		public function listOptions() {
			$cacheKey = Inflector::underscore( "{$this->alias}_".__FUNCTION__ );
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				//FIXME: à faire de façon plus propre
				$this->unbindModelAll();
				$this->bindModel( array( 'belongsTo' => array( 'Typeorient' ) ) );
				$results = $this->find(
					'list',
					array(
						'fields' => array(
							'Structurereferente.id',
							'Structurereferente.lib_struc',
							'Typeorient.lib_type_orient'
						),
						'recursive' => 0,
						'order' => array(
							'Typeorient.lib_type_orient ASC',
							'Structurereferente.lib_struc'
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
	//             $connection = ConnectionManager::getInstance();
	//             $dbo = $connection->getDataSource( $this->useDbConfig );
	//             $SQ = $dbo->startQuote;
	//             $EQ = $dbo->endQuote;

			$conditions = array();

			foreach( array( 'apre', 'contratengagement', 'orientation', 'pdo' ) as $type ) {
				$bool = Set::classicExtract( $types, $type );
				if( !empty( $bool ) ) {
					$conditions[] = "Structurereferente.{$type} = 'O'";
				}
			}

			return $this->find( 'list', array( 'conditions' => $conditions, 'recursive' => -1 ) );
		}
	}
?>