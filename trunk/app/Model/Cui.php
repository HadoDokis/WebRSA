<?php
	/**
	 * Fichier source de la classe Cui.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe Cui est la classe contenant le CERFA CUI.
	 *
	 * @package app.Model
	 */
	class Cui extends AppModel
	{
		/**
		 * Alias de la table et du model
		 * @var string
		 */
		public $name = 'Cui';

		/**
		 * Recurcivité du model 
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Possède des clefs étrangères vers d'autres models
		 * @var array
		 */
        public $belongsTo = array(
			'Partenairecui' => array(
				'className' => 'Partenairecui',
				'foreignKey' => 'partenairecui_id',
			),
			'Partenaire' => array(
				'className' => 'Partenaire',
				'foreignKey' => 'partenaire_id',
			),
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
			),
			'Entreeromev3' => array(
				'className' => 'Entreeromev3',
				'foreignKey' => 'entreeromev3_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id'
			)
        );

		/**
		 * Ces models possèdent une clef étrangère vers ce model
		 * @var array
		 */
		public $hasOne = array(
			'Cui66' => array(
				'className' => 'Cui66',
				'foreignKey' => 'cui_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
		);

		/**
		 * Ces models possèdent une clef étrangère vers ce model
		 * @var array
		 */
		public $hasMany = array(
			'Emailcui' => array(
				'className' => 'Emailcui',
				'foreignKey' => 'cui_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Cui\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);

		/**
		 * Champs suplémentaire virtuel (n'existe pas en base)
		 * @var array
		 */
		public $virtualFields = array(
			'dureecontrat' => array(
				'type'      => 'string',
				'postgres'  => '(( "%s"."findecontrat" - "%s"."dateembauche") / 30)'
			),
		);

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Allocatairelie',
			'Formattable',
			'Gedooo.Gedooo',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);
		
		/**
		 * Valeur des checkbox du champ beneficiairede
		 * @var array
		 */
		public $beneficiairede = array(
			'ASS',
			'AAH',
			'ATA',
			'RSA'
		);

		/**
		 * Recherche des données CAF liées à l'allocataire dans le cadre du CUI.
		 *
		 * @param integer $personne_id
		 * @return array
		 * @throws NotFoundException
		 * @throws InternalErrorException
		 */
		public function dataCafAllocataire( $personne_id ) {
			$Informationpe = ClassRegistry::init( 'Informationpe' );
            $sqDernierReferent = $this->Personne->PersonneReferent->sqDerniere( 'Personne.id', false );

			$querydataCaf = array(
				'fields' => array_merge(
					$this->Personne->fields(),
					$this->Personne->Prestation->fields(),
					$this->Personne->Foyer->fields(),
					$this->Personne->Foyer->Adressefoyer->Adresse->fields(),
					$this->Personne->Foyer->Dossier->fields(),
                    $this->Personne->PersonneReferent->Referent->fields(),
					array(
						'Historiqueetatpe.identifiantpe',
						'Historiqueetatpe.etat',
                        '( '.$this->Personne->Foyer->vfNbEnfants().' ) AS "Foyer__nbenfants"',
                        'Titresejour.dftitsej'
					)
				),
				'joins' => array(
					$Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' ),
					$Informationpe->join( 'Historiqueetatpe', array( 'type' => 'LEFT OUTER' ) ),
					$this->Personne->join( 'Foyer', array( 'type' => 'INNER' )),
					$this->Personne->join( 'Prestation', array( 'type' => 'LEFT OUTER'  )),
					$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
                    $this->Personne->join(
                        'PersonneReferent',
                        array(
                            'type' => 'LEFT OUTER',
                            'conditions' => array(
                                "PersonneReferent.id IN ( {$sqDernierReferent} )"
                            )
                        )
                    ),
                    $this->Personne->PersonneReferent->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
                    $this->Personne->join( 'Titresejour', array( 'type' => 'LEFT OUTER' ) )
				),
				'conditions' => array(
					'Personne.id' => $personne_id,
					array(
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'Informationpe.id IS NULL',
							'Informationpe.id IN( '.$Informationpe->sqDerniere( 'Personne' ).' )'
						)
					),
					array(
						'OR' => array(
							'Historiqueetatpe.id IS NULL',
							'Historiqueetatpe.id IN( '.$Informationpe->Historiqueetatpe->sqDernier( 'Informationpe' ).' )'
						)
					),
				),
				'contain' => false
			);
			$dataCaf = $this->Personne->find( 'first', $querydataCaf );


			// On s'assure d'avoir trouvé l'allocataire
			if( empty( $dataCaf ) ) {
				throw new NotFoundException();
			}

			// Et que celui-ci soit bien demandeur ou conjoint
			if( !in_array( $dataCaf['Prestation']['rolepers'], array( 'DEM', 'CJT' ) ) ) {
				throw new InternalErrorException( "L'allocataire \"{$personne_id}\" doit être demandeur ou conjont" );
			}

			return $dataCaf;
		}

		/**
		 * Permet de savoir si une personne lié au CUI possède un RSA Socle
		 * 
		 * @param numeric $personne_id
		 * @return boolean
		 */
		public function isRsaSocle( $personne_id ){
			$vfRsaSocle = $this->Personne->Foyer->Dossier->Detaildroitrsa->vfRsaSocle();
			$result = $this->Personne->find(
				'first',
				array(
					'fields' => array(
						"( {$vfRsaSocle} ) AS \"Dossier__rsasocle\""
					),
					'joins' => array(
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->Dossier->join( 'Detaildroitrsa' )
					),
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'recursive' => -1
				)
			);			
			$isRsaSocle = isset($result['Dossier']['rsasocle']) && $result['Dossier']['rsasocle'] === true ? true : false;
			return $isRsaSocle;
		}
		
		public function options(){
			$options = $this->enums();
			
			foreach( $this->beneficiairede as $key => $value ){
				$options['Cui']['beneficiairede'][] = $value;
			}
			
			return $options;
		}
	}
?>