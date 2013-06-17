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
	 * La classe Cui est la classe de base concernant les CUIs (CG 58, 66 et 93).
	 *
	 * @package app.Model
	 */
	class Cui extends AppModel
	{
		public $name = 'Cui';

		public $actsAs = array(
			'Enumerable',
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id', 'metieraffectation_id', 'metieremploipropose_id', 'actioncandidat_id' ),
			),
			'Gedooo.Gedooo',
			'Pgsqlcake.PgsqlAutovalidate'
		);

		/**
		* Chemin relatif pour les modèles de documents .odt utilisés lors des
		* impressions. Utiliser %s pour remplacer par l'alias.
		*/
		public $modelesOdt = array(
			'CUI/cui.odt',
		);



		public $validate = array(
			'typecui' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'secteur' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'datedebprisecharge' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'datefinprisecharge' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $belongsTo = array(
            'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'foreignKey' => 'actioncandidat_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			 'Partenaire' => array(
				'className' => 'Partenaire',
				'foreignKey' => 'partenaire_id',
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
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
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
			'Orgsuivi' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'orgsuivi_id',
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
			'Secteurcui' => array(
				'className' => 'Secteurcui',
				'foreignKey' => 'secteurcui_id',
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
			'Periodeimmersion' => array(
				'className' => 'Periodeimmersion',
				'foreignKey' => 'cui_id',
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
			'Propodecisioncui66' => array(
				'className' => 'Propodecisioncui66',
				'foreignKey' => 'cui_id',
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
			/*'Decisioncui66' => array(
				'className' => 'Decisioncui66',
				'foreignKey' => 'cui_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),*/
			'Suspensioncui66' => array(
				'className' => 'Suspensioncui66',
				'foreignKey' => 'cui_id',
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
			'Accompagnementcui66' => array(
				'className' => 'Accompagnementcui66',
				'foreignKey' => 'cui_id',
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
				'foreignKey' => 'cui_id',
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
			'Defautinsertionep66' => array(
				'className' => 'Defautinsertionep66',
				'foreignKey' => 'cui_id',
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
        
        public $hasOne = array(
			'Decisioncui66' => array(
				'className' => 'Decisioncui66',
				'foreignKey' => 'cui_id',
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

		/**
		*   Précondition: La personne est-elle bien en Rsa Socle ?
		*   @default: false --> si Rsa Socle pas de msg d'erreur
		*/

		public function _prepare( $personne_id = null ) {
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
			return !$result['Dossier']['rsasocle'];
		}


		/**
		*   BeforeValidate
		*/
//		public function beforeValidate( $options = array() ) {
//			$return = parent::beforeValidate( $options );
//
//			foreach( array( 'iscie' ) as $key ) {
//				if( isset( $this->data[$this->name][$key] ) ) {
//					$this->data[$this->name][$key] = Set::enum( $this->data[$this->name][$key], array( '1' => 'O', '0' => 'N' ) );
//				}
//			}
//
//			return $return;
//		}

		/**
		 * Recalcul de la position du CUI avant l'enregistrement (CG 66).
		 *
		 * @param array $options
		 * @return boolean
		 */
		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			//  Calcul de la position du CUI
			if( Configure::read( 'Cg.departement' ) == '66' ) {
				$this->data[$this->alias]['positioncui66'] = $this->calculPosition( $this->data );
				//On unset pour prendre la valeur par défaut de la base de données
				if( is_null( $this->data[$this->alias]['positioncui66'] ) ) {
					unset( $this->data[$this->alias]['positioncui66'] );
				}
			}
			return $return;
		}

		/**
		* Recalcul des rangs des CUIs pour une personne donnée ou pour l'ensemble des personnes.
		 *
		 * @param integer $personne_id
		 * @return boolean
		 */
		protected function _updateRangsCuis( $personne_id = null ) {
			$condition = ( is_null( $personne_id ) ? "" : "cuis.personne_id = {$personne_id}" );

			$sql = "UPDATE cuis
						SET rangcui = NULL".( !empty( $condition ) ? " WHERE {$condition}" : "" ).";";
			$success = ( $this->query( $sql ) !== false );

			$sql = "UPDATE cuis
						SET rangcui = (
							SELECT ( COUNT(cuispcd.id) + 1 )
								FROM cuis AS cuispcd
								WHERE cuispcd.personne_id = cuis.personne_id
									AND cuispcd.id <> cuis.id
									AND cuispcd.decisioncui = 'V'
									AND cuispcd.datedebprisecharge IS NOT NULL
									AND cuispcd.datedebprisecharge < cuis.datedebprisecharge
									AND (
										cuis.positioncui66 IS NULL
										OR cuis.positioncui66 <> 'annule'
									)
						)
						WHERE
							cuis.datedebprisecharge IS NOT NULL
							".( !empty( $condition ) ? " AND {$condition}" : "" )."
							AND cuis.decisioncui = 'V'
							AND (
								cuis.positioncui66 IS NULL
								OR cuis.positioncui66 <> 'annule'
							);";

			$success = ( $this->query( $sql ) !== false ) && $success;

			return $success;
		}

		/**
		 * Recalcul des rangs des CUIs pour une personne donnée.
		 * afterSave, afterDelete, valider, annuler
		 *
		 * @return boolean
		 */
		public function updateRangsCuisPersonne( $personne_id ) {
			return $this->_updateRangsCuis( $personne_id  );
		}

		/**
		 * Recalcul des rangs des CUIs pour l'ensemble des personnes.
		 *
		 * @return boolean
		 */
		public function updateRangsCuis() {
			return $this->_updateRangsCuis();
		}

		/**
		 * Calcul de la position du CUI (CG 66).
		 *
		 * @param array $data
		 * @return string
		 */
		public function calculPosition( $data ) {
			$decisioncui = Set::classicExtract( $data, 'Cui.decisioncui' );
			$positioncui66 = Set::classicExtract( $data, 'Cui.positioncui66' );
			$datenotif = Set::classicExtract( $data, 'Cui.datenotification' );
			$id = Set::classicExtract( $data, 'Cui.id' );
			$personne_id = Set::classicExtract( $data, 'Cui.personne_id' );

			// Données dernier CUI
			$conditions = array( 'Cui.personne_id' => $personne_id, );
			if( !empty( $id ) ) {
				$conditions['Cui.id <>'] = $id;
			}

			$dernierCui = $this->find(
				'first',
				array(
					'conditions' => $conditions,
					'order' => 'Cui.rangcui DESC',
					'contain' => false
				)
			);
			$decisionprecedente = Set::classicExtract( $dernierCui, 'Cui.decisioncui' );
			$positioncui66Precedent = Set::classicExtract( $dernierCui, 'Cui.positioncui66' );



			if ( ( is_null( $positioncui66 ) || in_array( $positioncui66 , array( 'attdecision', 'perime' ) ) ) && !empty( $decisioncui ) ) {
				if ( $decisioncui == 'accord' ){
					if( empty( $datenotif ) ) {
						$positioncui66 = 'valid';
					}
					else {
						$positioncui66 = 'validnotifie';
					}
				}
				else if ( $decisioncui == 'refus' ){
					if( empty( $datenotif ) ) {
						$positioncui66 = 'nonvalide';
					}
					else {
						$positioncui66 = 'nonvalidnotifie';
					}
				}
			}

			// Lors de l'ajout d'un nouveau CUI, on passe la position du précédent à fin de contrat, sauf pour les non validés
			if( !empty( $dernierCui ) && ( $decisionprecedente != 'N' ) && ( $positioncui66Precedent != 'annule' ) ) {
				$this->updateAllUnBound(
					array( 'Cui.positioncui66' => '\'fincontrat\'' ),
					array(
						'"Cui"."personne_id"' => $personne_id,
						'"Cui"."id"' => $dernierCui['Cui']['id']
					)
				);
			}

			return $positioncui66;
		}

		/**
		 * Mise à jour de la position du cui selon la proposition de décision émise
		 *
		 * @param $propodeicsioncui66_id, identifiant de la proposition de décision du CUI
		 * @return string
		 */
		public function updatePositionFromPropodecisioncui66( $propodecisioncui66_id ) {
			$propodecisioncui66 = $this->Propodecisioncui66->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Propodecisioncui66->fields(),
						array(
							'Cui.id',
							'Cui.positioncui66'
						)
					),
					'conditions' => array(
						'Propodecisioncui66.id' => $propodecisioncui66_id
					),
					'contain' => array(
						'Cui'
					)
				)
			);

			$isaviselu = Set::classicExtract( $propodecisioncui66, 'Propodecisioncui66.isaviselu' );
			$isavisreferent = Set::classicExtract( $propodecisioncui66, 'Propodecisioncui66.isavisreferent' );
			$positioncui66 = Set::classicExtract( $propodecisioncui66, 'Cui.positioncui66' );

			// Mise à jour de la position du CUI
			if( !empty( $positioncui66 ) ) {
				if( $positioncui66 == 'attavismne' ) {
					$positioncui66 = 'attdecision';
				}
//                if( ( $isavisreferent == '0' ) && ( $isaviselu == '0' ) && $positioncui66 == 'attavismne' ) {
//					$positioncui66 = 'attdecision';
//				}
//				else if( ( $isavisreferent == '1' ) && ( $isaviselu == '0' ) && $positioncui66 =='attavisreferent' ) {
//					$positioncui66 = 'attaviselu';
//				}
//				else if( ( $isavisreferent == '1' ) && ( $isaviselu == '1' ) && $positioncui66 =='attaviselu' ) {
//					$positioncui66 = 'attdecision';
//				}
				else if( ( $positioncui66 =='attdecision' ) ) {
					$positioncui66 = 'attdecision';
				}
			}

			$this->id = $propodecisioncui66['Cui']['id'];
			$return = $this->saveField( 'positioncui66', $positioncui66 );

			return $return;
		}

        
        /**
		 * Mise à jour de la position du cui selon la décision émise
		 *
		 * @param $decisioncui66_id, identifiant de la décision du CUI
		 * @return string
		 */
		public function updatePositionFromDecisioncui66( $decisioncui66_id ) {
			$decisioncui66 = $this->Decisioncui66->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Decisioncui66->fields(),
						array(
							'Cui.id',
                            'Cui.personne_id',
							'Cui.positioncui66'
						)
					),
					'conditions' => array(
						'Decisioncui66.id' => $decisioncui66_id
					),
					'contain' => array(
						'Cui'
					)
				)
			);
//debug($decisioncui66);
//die();
			$decisioncui = Set::classicExtract( $decisioncui66, 'Decisioncui66.decisioncui' );
			$positioncui66 = Set::classicExtract( $decisioncui66, 'Cui.positioncui66' );
            //,encours,annule,fincontrat,attrenouv,perime,nonvalide,valid,validnotifie,nonvalidnotifie}

			// Mise à jour de la position du CUI
			if( !empty( $positioncui66 ) ) {
				if( ( $decisioncui == 'accord' ) ) {
					$positioncui66 = 'encours';
				}
				else if( ( $decisioncui == 'refus' ) ) {
					$positioncui66 = 'nonvalide';
				}
				else if( ( $decisioncui == 'enattente' ) ) {
					$positioncui66 = 'attdecision';
				}
                else if( ( $decisioncui == 'annule' ) ) {
					$positioncui66 = 'annule';
				}
			}

			$this->id = $decisioncui66['Cui']['id'];
            
//			$return = $this->saveField( 'positioncui66', $positioncui66 );
			$return = $this->updateAllUnBound(
                array(
                    'Cui.decisioncui' => '\''.$decisioncui.'\'',
                    'Cui.positioncui66' => '\''.$positioncui66.'\''
                ),
                array(
                    '"Cui"."personne_id"' => $decisioncui66['Cui']['personne_id'],
                    '"Cui"."id"' => $decisioncui66['Cui']['id']
                )
            );

			return $return;
		}

		/**
		 * Récupération des données servant à construire le PDF de notification du CUI.
		 *
		 * @see Cui::getDefaultPdf
		 *
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf( $id, $user_id ) {
			$cui = $this->find(
				'first',
				array(
					'fields' => array_merge(
						$this->fields(),
						$this->Personne->fields(),
						$this->Referent->fields(),
						$this->Structurereferente->fields(),
						$this->Personne->Foyer->fields(),
						$this->Personne->Foyer->Dossier->fields(),
						$this->Personne->Foyer->Adressefoyer->Adresse->fields()
					),
					'joins' => array(
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					),
					'conditions' => array(
						'Cui.id' => $id,
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					'contain' => false
				)
			);

			$user = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => false
				)
			);
			$cui = Set::merge( $cui, $user );


			return $cui;
		}

		/**
		 * Retourne le PDF de notification du CUI.
		 *
		 * @see Cui::getDataForPdf
		 *
		 * @param integer $id L'id du CUI pour lequel on veut générer l'impression
		 * @return string
		 */
		public function getDefaultPdf( $id, $user_id ) {
			$cui = $this->getDataForPdf( $id, $user_id );
			///Traduction pour les données de la Personne/Contact/Partenaire/Référent
			$Option = ClassRegistry::init( 'Option' );
			$options = array(
				'Adresse' => array(
					'typevoie' => $Option->typevoie()
				),
				'Personne' => array(
					'qual' => $Option->qual()
				),
				'Referent' => array(
					'qual' => $Option->qual()
				),
				'Structurereferente' => array(
					'type_voie' => $Option->typevoie()
				),
				'Type' => array(
					'voie' => $Option->typevoie()
				),
			);

			return $this->ged(
				$cui,
				'CUI/cui.odt',
				false,
				$options
			);
		}

		/**
		 * Retourne l'id du dossier à partir de l'id du CUI
		 *
		 * @param integer $id
		 * @return integer
		 */
		public function dossierId( $id ) {
			$cui = $this->find(
				'first',
				array(
					'fields' => array(
						'Foyer.dossier_id'
					),
					'joins' => array(
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					),
					'conditions' => array(
						'Cui.id' => $id
					),
					'contain' => false
				)
			);

			if( !empty( $cui ) ) {
				return $cui['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		 * Sous-requête permettant de récupérer le dernier contrat d'un allocataire.
		 *
		 * @param string $personneIdFied Le champ où trouver l'id de la personne.
		 * @return string
		 */
		public function sqDernierContrat( $personneIdFied = 'Personne.id' ) {
			return $this->sq(
				array(
					'fields' => array(
						'cuis.id'
					),
					'alias' => 'cuis',
					'conditions' => array(
						"cuis.personne_id = {$personneIdFied}"
					),
					'order' => array( 'cuis.datedebprisecharge DESC' ),
					'limit' => 1
				)
			);
		}

		/**
		 * Retourne l'id de la personne à laquelle est lié un enregistrement.
		 *
		 * @param integer $id L'id de l'enregistrement
		 * @return integer
		 */
		public function personneId( $id ) {
			$querydata = array(
				'fields' => array( "{$this->alias}.personne_id" ),
				'conditions' => array(
					"{$this->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $this->find( 'first', $querydata );

			if( !empty( $result ) ) {
				return $result[$this->alias]['personne_id'];
			}
			else {
				return null;
			}
		}
        
        
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
        
        /**
		 * Préparation des données du formulaire d'ajout ou de modification d'un
		 * CUI
		 *
		 * @param integer $personne_id
		 * @param integer $cui_id
		 * @param integer $user_id
		 * @return array
		 * @throws InternalErrorException
		 * @throws NotFoundException
		 */
		public function prepareFormDataAddEdit( $personne_id, $cui_id, $user_id ) {

            // Recherche des données CAF.
			$dataCaf = $this->dataCafAllocataire( $personne_id );
			// Recherche des données de la personne
//			$data['Personne'] = $this->Personne->detailsApre( $personne_id, $user_id );

            $querydataCui = array(
                'contain' => array(
                    'Partenaire'
                )
            );
            
            // Données de l'utilisateur
			$querydataUser = array(
				'conditions' => array(
					'User.id' => $user_id
				),
				'contain' => array(
					'Structurereferente',
					'Referent' => array(
						'Structurereferente'
					)
				)
			);
			$dataUser = $this->User->find( 'first', $querydataUser );
     
			// On s'assure que l'utilisateur existe
			if( empty( $dataUser ) ) {
				throw new InternalErrorException( "Utilisateur non trouvé \"{$user_id}\"" );
			}
            
            // On ajoute d'autres données de l'utilisateur connecté
			// TODO: du coup, on peut faire on delete set null (+la structure ?)
			$data['Cui']['user_id'] = $user_id;
            
            // Si c'est une modification, on lit l'enregistrement, on actualise
			// les données et on renvoit.
			if( !empty( $cui_id ) ) {
				$querydataCuiActuel = $querydataCui;
				$querydataCuiActuel['conditions'] = array(
					'Cui.id' => $cui_id
				);
				$dataCuiActuel = $this->find( 'first', $querydataCuiActuel );

				// Il faut que l'enregistrement à modifier existe
				if( empty( $dataCuiActuel ) ) {
					throw new NotFoundException();
				}

                // Remplissage des listes déroulantes dépendantes:
                // Action selon le partenaire
                if( !empty( $dataCuiActuel['Cui']['actioncandidat_id'] ) ) {
                    $dataCuiActuel['Cui']['actioncandidat_id'] = $dataCuiActuel['Cui']['partenaire_id'].'_'.$dataCuiActuel['Cui']['actioncandidat_id'];
                }
                // Métier selon le secteur 
                if( !empty( $dataCuiActuel['Cui']['secteuremploipropose_id'] ) ) {
                    $dataCuiActuel['Cui']['metieremploipropose_id'] = $dataCuiActuel['Cui']['secteuremploipropose_id'].'_'.$dataCuiActuel['Cui']['metieremploipropose_id'];
                }

				$data = $dataCuiActuel;
			}
			// Sinon, on construit un nouvel enregistrement vide, on y met les
			// données CAF et ancien CUI.
			else {
				// Création d'un "enregistrement type" vide.
				$data = array(
					'Cui' => array(
						'id' => null,
                        'personne_id' => $personne_id,
                        'user_id' => $user_id,
						'rangcui' => null
					)
				);
  
			}
            
            // Remplissage et récupération de la composition familiale
            if (!isset($data['Cui']['compofamiliale']) || empty($data['Cui']['compofamiliale'])) {
				$compofamiliale = $this->Personne->Foyer->find(
					'first',
					array(
						'fields' => array(
							'Foyer.id',
							'Foyer.sitfam'
						),
						'joins' => array(
							$this->Personne->Foyer->join( 'Personne', array( 'type' => 'INNER' ) )
						),
						'conditions' => array(
							'Personne.foyer_id = Foyer.id',
							'Personne.id' => $personne_id
						),
						'contain'=>false
					)
				);
				$nbenfant = $this->Personne->Foyer->nbEnfants($compofamiliale['Foyer']['id']);
				if (in_array($compofamiliale['Foyer']['sitfam'], array('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU'))) {
					if ($nbenfant==0) {
						$data['Cui']['compofamiliale']='isole';
					}
					else {
						$data['Cui']['compofamiliale']='isoleenfant';
					}
				}
				elseif (in_array($compofamiliale['Foyer']['sitfam'], array('MAR', 'PAC', 'RPA', 'RVC', 'VIM'))) {
					if ($nbenfant==0) {
						$data['Cui']['compofamiliale']='couple';
					}
					else {
						$data['Cui']['compofamiliale']='coupleenfant';
					}
				}
			}
            
            
            // Date de l'inscription au Pôle Emploi
            $dateInscritpe = array();
            if( isset( $data['Personne']['Historiqueetatpe'] ) && !empty( $data['Personne']['Historiqueetatpe'] ) ){
                if( $data['Personne']['Historiqueetatpe']['etat'] == 'inscription' ){
                    $dateInscritpe = $data['Personne']['Historiqueetatpe']['date'];
                }
            }
            $data['Personne']['dateInscritpe'] = $dateInscritpe;
//            $this->set( compact( 'dateInscritpe' ) );
            
            // On affiche la valeur de la convention annuelle définie en paramétrage
            $data['Cui']['numconventionobj'] = Configure::read( 'Cui.Numeroconvention' );

			// On récupère la valeur du montant rsa perçu au moment de l'enregistrement
            $dossier_id = $this->Personne->dossierId( $personne_id );
			$tDetaildroitrsa = $this->Personne->Foyer->Dossier->Detaildroitrsa->find(
				'first',
				array(
					'fields' => array(
						'Detaildroitrsa.id',
						'Detaildroitrsa.dossier_id',
					),
                    'contain' => array(
						'Detailcalculdroitrsa' => array(
							'fields' => array(
								'Detailcalculdroitrsa.mtrsavers',
								'Detailcalculdroitrsa.dtderrsavers',
								'Detailcalculdroitrsa.natpf',
							),
						)
					),
					'conditions' => array(
						'Detaildroitrsa.dossier_id' => $dossier_id
					)
				)
			);

			$listeMontant = null;
            $listeNatureRSA = null;
			if( !empty( $tDetaildroitrsa ) ) {
                $listeNatureRSA = serialize( Hash::extract( $tDetaildroitrsa, 'Detailcalculdroitrsa.{n}.natpf' ) );
                $listeMontant = serialize( Hash::extract( $tDetaildroitrsa, 'Detailcalculdroitrsa.{n}.mtrsavers' ) );
			}
            $data['Cui']['naturersa'] = $listeNatureRSA;
            $data['Cui']['montantrsapercu'] = $listeMontant;

			// ------------------------------------------------------------------------------------------

            // Fusion avec les données CAF
			$data = Set::merge( $data, $dataCaf );
			
			$data['taux_cgs_cuis'] = $this->Secteurcui->Tauxcgcui->find( 'all' );

			/// Calcul du numéro du contrat d'insertion
			$data['Cui']['nbCui'] = $this->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );
//    debug( $data );
            return $data;
        }
	}
?>