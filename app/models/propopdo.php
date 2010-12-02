<?php
	class Propopdo extends AppModel
	{
		public $name = 'Propopdo';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
	// 					'statutdecision' => array(  'domain' => 'propopdo' ),
					'choixpdo' => array( 'domain' => 'propopdo' ),
					'nonadmis' => array( 'domain' => 'propopdo' ),
					'iscomplet' => array( 'domain' => 'propopdo' ),
					'validationdecision' => array( 'domain' => 'propopdo' ),
					'decisionop' => array( 'domain' => 'propopdo' ),
					'etatdossierpdo'
				)
			),
			'Formattable',
			'Autovalidate',
		);

		public $validate = array(
			'orgpayeur' => array(
				'rule' => array( 'notEmpty' )
			),
			'typepdo_id' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'choixpdo' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire',
				'allowEmpty' => true
			),
			'originepdo_id' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'decisionpdo_id' => array(
				'rule' => array( 'allEmpty', 'decision' ),
				'message' => 'Si prise de décision, choisir un type de décision'
			),
			'iscomplet' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'datedecisionpdo' => array(
				'rule' => 'date',
				'message' => 'Veuillez entrer une date valide.',
				'allowEmpty' => true
			),
			'datereceptionpdo' => array(
				'rule' => 'date',
				'message' => 'Veuillez entrer une date valide.',
				'allowEmpty' => true
			),
	//             'validationdecision' => array(
	//                 'rule' => array( 'allEmpty', 'isvalidation' ),
	//                 'message' => 'Si validation, choisir une valeur'
	//             ),
		);

		public $belongsTo = array(
			'Typepdo' => array(
				'className' => 'Typepdo',
				'foreignKey' => 'typepdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Decisionpdo' => array(
				'className' => 'Decisionpdo',
				'foreignKey' => 'decisionpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typenotifpdo' => array(
				'className' => 'Typenotifpdo',
				'foreignKey' => 'typenotifpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Originepdo' => array(
				'className' => 'Originepdo',
				'foreignKey' => 'originepdo_id',
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
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
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
			'Serviceinstructeur' => array(
				'className' => 'Serviceinstructeur',
				'foreignKey' => 'serviceinstructeur_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Piecepdo' => array(
				'className' => 'Piecepdo',
				'foreignKey' => 'propopdo_id',
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
			'Traitementpdo' => array(
				'className' => 'Traitementpdo',
				'foreignKey' => 'propopdo_id',
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
			'Situationpdo' => array(
				'className' => 'Situationpdo',
				'joinTable' => 'propospdos_situationspdos',
				'foreignKey' => 'propopdo_id',
				'associationForeignKey' => 'situationpdo_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'PropopdoSituationpdo'
			),
			'Statutdecisionpdo' => array(
				'className' => 'Statutdecisionpdo',
				'joinTable' => 'propospdos_statutsdecisionspdos',
				'foreignKey' => 'propopdo_id',
				'associationForeignKey' => 'statutdecisionpdo_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'PropopdoStatutdecisionpdo'
			),
			'Statutpdo' => array(
				'className' => 'Statutpdo',
				'joinTable' => 'propospdos_statutspdos',
				'foreignKey' => 'propopdo_id',
				'associationForeignKey' => 'statutpdo_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'PropopdoStatutpdo'
			)
		);

		public $_types = array(
			'propopdo' => array(
				'fields' => array(
					'"Propopdo"."id"',
					'"Propopdo"."personne_id"',
					'"Propopdo"."typepdo_id"',
					'"Propopdo"."decisionpdo_id"',
					'"Propopdo"."typenotifpdo_id"',
					'"Propopdo"."datedecisionpdo"',
					'"Propopdo"."motifpdo"',
					'"Propopdo"."commentairepdo"',
					'"Propopdo"."etatdossierpdo"',
					'"Decisionpdo"."libelle"',
					'"Typenotifpdo"."id"',
					'"Typenotifpdo"."libelle"',
					'"Typepdo"."libelle"',

					'"Personne"."id"',
					'"Personne"."pieecpres"',
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Propopdo.personne_id = Personne.id' )
					),
					array(
						'table'      => 'typesnotifspdos',
						'alias'      => 'Typenotifpdo',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propopdo.typenotifpdo_id = Typenotifpdo.id' )
					),
					array(
						'table'      => 'decisionspdos',
						'alias'      => 'Decisionpdo',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propopdo.decisionpdo_id = Decisionpdo.id' )
					),
					array(
						'table'      => 'typespdos',
						'alias'      => 'Typepdo',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Propopdo.typepdo_id = Typepdo.id' )
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							'Adressefoyer.id IN (
								SELECT adressesfoyers.id
									FROM adressesfoyers
									WHERE
										adressesfoyers.foyer_id = Adressefoyer.foyer_id
										AND adressesfoyers.rgadr = \'01\'
									ORDER BY adressesfoyers.dtemm DESC
									LIMIT 1
							)'
							///FIXME: à revoir car ça ne fonctionne pas mais pourquoi ???? là est la question
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest = \'RSA\'',
	//                             '( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
							'( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
						)
					)
				),
				'order' => 'Propopdo.datedecisionpdo ASC',
			)
		);

		/**
		*
		*/

		public function prepare( $type, $params = array() ) {
			$types = array_keys( $this->_types );
			if( !in_array( $type, $types ) ) {
				trigger_error( 'Invalid parameter "'.$type.'" for '.$this->name.'::prepare()', E_USER_WARNING );
			}
			else {
				$query = $this->_types[$type];

				switch( $type ) {
					case 'etat':
						$query = Set::merge( $query, $params );
						break;
					case 'propopdo':
						$query = Set::merge( $query, $params );
						break;
				}

				return $query;
			}
		}

		/**
		*
		*/

		public function etatPdo( $pdo ) {
			$pdo = XSet::bump( Set::filter( /*Set::flatten*/( $pdo ) ) );

			$typepdo_id = Set::classicExtract( $pdo, 'Propopdo.typepdo_id' );
			$decision = Set::classicExtract( $pdo, 'Propopdo.decision' );
		}

		/**
		* FIXME: bcp trop de nombres magiques
		*/

		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			if( Configure::read( 'nom_form_pdo_cg' ) == 'cg66' ) {
				$typepdo_id = Set::extract( $this->data, 'Propopdo.typepdo_id' );
				$iscomplet = Set::extract( $this->data, 'Propopdo.iscomplet' );
				$decisionpdo_id = Set::extract( $this->data, 'Propopdo.decisionpdo_id' );
				$isvalidation = Set::extract( $this->data, 'Propopdo.isvalidation' );
				$isdecisionop = Set::extract( $this->data, 'Propopdo.isdecisionop' );

				if( !empty( $typepdo_id ) && empty( $iscomplet ) && empty( $decisionpdo_id ) && empty( $isvalidation ) && empty( $isdecisionop ) ){
					$etat = '1';
					$this->data['Propopdo']['etatdossierpdo'] = $etat;
				}
				else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && empty( $decisionpdo_id ) && empty( $isvalidation ) && empty( $isdecisionop ) ){
					$etat = '2';
					$this->data['Propopdo']['etatdossierpdo'] = $etat;
				}
				else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && !empty( $decisionpdo_id ) && empty( $isvalidation ) && empty( $isdecisionop ) ){
					$etat = '3';
					$this->data['Propopdo']['etatdossierpdo'] = $etat;
				}
				else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && !empty( $decisionpdo_id ) && ( $isvalidation == 'O' ) && empty( $isdecisionop ) ){
					$etat = '4';
					$this->data['Propopdo']['etatdossierpdo'] = $etat;
				}
				else if ( !empty( $typepdo_id ) && ( $iscomplet == 'COM' ) && !empty( $decisionpdo_id ) && !empty( $isvalidation ) && !empty( $isdecisionop ) ){
					$etat = '5';
					$this->data['Propopdo']['etatdossierpdo'] = $etat;
				}
				else if ( !empty( $typepdo_id ) && ( $iscomplet == 'INC' ) && !empty( $decisionpdo_id ) && !empty( $isvalidation ) && !empty( $isdecisionop ) ){
					$etat = '6';
					$this->data['Propopdo']['etatdossierpdo'] = $etat;
				}
			}

			return $return;
		}

	}
?>
