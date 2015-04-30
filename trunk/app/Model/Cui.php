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
		public $name = 'Cui';
		
		public $recursive = -1;

        public $belongsTo = array(
			'Partenairecui' => array(
				'className' => 'Partenairecui',
				'foreignKey' => 'partenairecui_id',
				//'dependent' => false,
			),
			'Partenaire' => array(
				'className' => 'Partenaire',
				'foreignKey' => 'partenaire_id',
				//'dependent' => false,
			),
			'Personnecui' => array( // Fait un instantané de la personne, qui ne sera lié qu'au CUI
				'className' => 'Personnecui',
				'foreignKey' => 'personnecui_id',
				//'dependent' => true, 
			),
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				//'dependent' => true,
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
        );
		
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
		
		public $hasMany = array(
			'Emailcui' => array(
				'className' => 'Emailcui',
				'foreignKey' => 'cui_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
		);
		
		/**
		 *
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
			//'Conditionnable',
			'Formattable'/* => array(
				'phone' => array( 'prestatairefp93_tel', 'prestatairefp93_fax' )
			)*/,
			//'Gedooo.Gedooo',
			/*'ModelesodtConditionnables' => array(
				93 => array(
					'Ficheprescription93/ficheprescription.odt',
				)
			),*/
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
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
//                    $this->Personne->join( 'PersonneReferent', array( 'type' => 'LEFT OUTER' ) ),
//                    $this->Personne->PersonneReferent->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
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
//                    array(
//						'OR' => array(
//                            array(
//                                'PersonneReferent.personne_id' =>  $personne_id,
//                                'PersonneReferent.dfdesignation IS NULL'
//                            ),
//							'PersonneReferent.personne_id IS NULL'
//						)
//					)
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

	}
?>