<?php
	/**
	 * Fichier source de la classe Personne.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 */
	class Personne extends AppModel
	{

		public $name = 'Personne';
		public $displayField = 'nom_complet';
		public $actsAs = array(
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'haspiecejointe'
				)
			),
			'Formattable'
		);
		public $validate = array(
			// Qualité
			'qual' => array( array( 'rule' => 'notEmpty' ) ),
			'nom' => array( array( 'rule' => 'notEmpty' ) ),
			'prenom' => array( array( 'rule' => 'notEmpty' ) ),
			'nir' => array(
				array(
					'rule' => array( 'between', 13, 15 ),
					'message' => 'Le NIR doit être compris entre 13 et 15 caractères',
					'allowEmpty' => true
				),
				array(
					'rule' => 'alphaNumeric',
					'message' => 'Veuillez entrer une valeur alpha-numérique.',
					'allowEmpty' => true
				)
			),
			'dtnai' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'rgnai' => array(
				array(
					'rule' => array( 'comparison', '>', 0 ),
					'message' => 'Veuillez entrer un nombre positif.',
					'allowEmpty' => true
				),
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.',
					'allowEmpty' => true
				)
			)
		);
		public $belongsTo = array(
			'Foyer' => array(
				'className' => 'Foyer',
				'foreignKey' => 'foyer_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		public $hasOne = array(
			'Calculdroitrsa' => array(
				'className' => 'Calculdroitrsa',
				'foreignKey' => 'personne_id',
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
			'Dossiercaf' => array(
				'className' => 'Dossiercaf',
				'foreignKey' => 'personne_id',
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
			'Dsp' => array(
				'className' => 'Dsp',
				'foreignKey' => 'personne_id',
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
			'Prestation' => array(
				'className' => 'Prestation',
				'foreignKey' => 'personne_id',
				'dependent' => true,
				'conditions' => array( 'Prestation.natprest' => 'RSA' ),
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Nonoriente66' => array(
				'className' => 'Nonoriente66',
				'foreignKey' => 'personne_id',
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
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
				'foreignKey' => 'personne_id',
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
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'personne_id',
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
			'Activite' => array(
				'className' => 'Activite',
				'foreignKey' => 'personne_id',
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
			'Allocationsoutienfamilial' => array(
				'className' => 'Allocationsoutienfamilial',
				'foreignKey' => 'personne_id',
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
			'Avispcgpersonne' => array(
				'className' => 'Avispcgpersonne',
				'foreignKey' => 'personne_id',
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
			'Creancealimentaire' => array(
				'className' => 'Creancealimentaire',
				'foreignKey' => 'personne_id',
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
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'personne_id',
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
			'Dossiercov58' => array(
				'className' => 'Dossiercov58',
				'foreignKey' => 'personne_id',
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
				'foreignKey' => 'personne_id',
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
			'Grossesse' => array(
				'className' => 'Grossesse',
				'foreignKey' => 'personne_id',
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
			'Informationeti' => array(
				'className' => 'Informationeti',
				'foreignKey' => 'personne_id',
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
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'personne_id',
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
				'foreignKey' => 'personne_id',
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
			'Memo' => array(
				'className' => 'Memo',
				'foreignKey' => 'personne_id',
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
			'Orientation' => array(
				'className' => 'Orientation',
				'foreignKey' => 'personne_id',
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
			'Parcours' => array(
				'className' => 'Parcours',
				'foreignKey' => 'personne_id',
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
			'Infoagricole' => array(
				'className' => 'Infoagricole',
				'foreignKey' => 'personne_id',
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
			'Rattachement' => array(
				'className' => 'Rattachement',
				'foreignKey' => 'personne_id',
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
			'Suiviappuiorientation' => array(
				'className' => 'Suiviappuiorientation',
				'foreignKey' => 'personne_id',
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
			'Ressource' => array(
				'className' => 'Ressource',
				'foreignKey' => 'personne_id',
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
				'foreignKey' => 'personne_id',
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
				'foreignKey' => 'personne_id',
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
			'Titresejour' => array(
				'className' => 'Titresejour',
				'foreignKey' => 'personne_id',
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
				'foreignKey' => 'personne_id',
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
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Personne\'',
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
			'Personnepcg66' => array(
				'className' => 'Personnepcg66',
				'foreignKey' => 'personne_id',
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
			'Traitementpdo' => array(
				'className' => 'Traitementpdo',
				'foreignKey' => 'personne_id',
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
			'Conditionactiviteprealable' => array(
				'className' => 'Conditionactiviteprealable',
				'foreignKey' => 'personne_id',
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
			'DspRev' => array(
				'className' => 'DspRev',
				'foreignKey' => 'personne_id',
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
			'Historiquedroit' => array(
				'className' => 'Historiquedroit',
				'foreignKey' => 'personne_id',
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
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'joinTable' => 'actionscandidats_personnes',
				'foreignKey' => 'personne_id',
				'associationForeignKey' => 'actioncandidat_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ActioncandidatPersonne'
			),
			'Dossier' => array(
				'className' => 'Dossier',
				'joinTable' => 'derniersdossiersallocataires',
				'foreignKey' => 'personne_id',
				'associationForeignKey' => 'dossier_id',
				'unique' => true,
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'finderQuery' => null,
				'deleteQuery' => null,
				'insertQuery' => null,
				'with' => 'Dernierdossierallocataire'
			),
			'Referent' => array(
				'className' => 'Referent',
				'joinTable' => 'personnes_referents',
				'foreignKey' => 'personne_id',
				'associationForeignKey' => 'referent_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'PersonneReferent'
			)
		);

		public $virtualFields = array(
			'nom_complet' => array(
				'type' => 'string',
				'postgres' => '( COALESCE( "%s"."qual", \'\' ) || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
			),
			'nom_complet_court' => array(
				'type' => 'string',
				'postgres' => '( "%s"."nom" || \' \' || "%s"."prenom" )'
			),
			'age' => array(
				'type' => 'integer',
				'postgres' => '( EXTRACT ( YEAR FROM AGE( "%s"."dtnai" ) ) )'
			)
		);

		/**
		 *
		 * @param array $options
		 * @return mixed
		 */
		public function beforeSave( $options = array( ) ) {
			$return = parent::beforeSave( $options );

			// Mise en majuscule de nom, prénom, nomnai
			foreach( array( 'nom', 'prenom', 'prenom2', 'prenom3', 'nomnai' ) as $field ) {
				if( isset( $this->data['Personne'][$field] ) ) {
					if( !empty( $this->data['Personne'][$field] ) ) {
						$this->data['Personne'][$field] = strtoupper( replace_accents( $this->data['Personne'][$field] ) );
					}
				}
			}

			// Champs déduits
			if( isset( $this->data['Personne']['qual'] ) ) {
				if( !empty( $this->data['Personne']['qual'] ) ) {
					$this->data['Personne']['sexe'] = ( $this->data['Personne']['qual'] == 'MR' ) ? 1 : 2;
				}

				if( $this->data['Personne']['qual'] != 'MME' ) {
					$this->data['Personne']['nomnai'] = $this->data['Personne']['nom'];
				}
			}

			if( isset( $this->data['Personne']['nir'] ) ) {
				$this->data['Personne']['nir'] = trim( $this->data['Personne']['nir'] );
				if( !empty( $this->data['Personne']['nir'] ) ) {
					if( strlen( $this->data['Personne']['nir'] ) == 13 ) {
						$this->data['Personne']['nir'] = $this->data['Personne']['nir'].cle_nir( $this->data['Personne']['nir'] );
					}
				}
			}
			return $return;
		}

		/**
		 * Retourne l'id du dossier à partir de l'id de la personne
		 *
		 * @param integer $personne_id
		 * @return integer
		 */
		public function dossierId( $personne_id ) {
			$querydata = array(
				'fields' => array( 'Foyer.dossier_id' ),
				'joins' => array(
					$this->join( 'Foyer', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					'Personne.id' => $personne_id
				),
				'recursive' => -1
			);

			$personne = $this->find( 'first', $querydata );

			if( !empty( $personne ) ) {
				return $personne['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		 *
		 */
		public function soumisDroitsEtDevoirs( $personne_id ) {
			$qd_personne = array(
				'conditions' => array(
					'Personne.id' => $personne_id
				),
				'fields' => null,
				'order' => null,
				'contain' => array(
					'Ressource' => array(
						'order' => array( 'dfress DESC' )
					),
					'Dsp',
					'Prestation',
					'Calculdroitrsa'
				)
			);
			$personne = $this->find( 'first', $qd_personne );

			if( isset( $personne['Prestation'] ) && ( $personne['Prestation']['rolepers'] == 'DEM' || $personne['Prestation']['rolepers'] == 'CJT' ) ) {
				$montant = Set::classicExtract( $personne, 'Calculdroitrsa.mtpersressmenrsa' );

				if( $montant < 500 ) {
					return true;
				}
				else {
					$montantForfaitaire = $this->Foyer->montantForfaitaire( $personne['Personne']['foyer_id'] );
					if( $montantForfaitaire ) {
						return $montantForfaitaire;
					}
				}

				$dsp = array_filter( array( 'Dsp' => $personne['Dsp'] ) );
				$hispro = Set::extract( $dsp, 'Dsp.hispro' );
				if( $hispro !== NULL ) {
					// Passé professionnel ? -> Emploi
					//     1901 : Vous avez toujours travaillé
					//     1902 : Vous travaillez par intermittence
					if( $dsp['Dsp']['hispro'] == '1901' || $dsp['Dsp']['hispro'] == '1902' ) {
						return false;
					}
					else {
						return true;
					}
				}
			}
			return false;
		}


		/**
		 * Détails propres à la personne pour l'APRE
		 */
		public function detailsApre( $personne_id, $user_id = null ) {

			$Informationpe = ClassRegistry::init( 'Informationpe' );
			$personne = $this->find(
				'first',
				array(
					'fields' => array_merge(
						$this->fields(),
						$this->Prestation->fields(),
						$this->Foyer->fields(),
						$this->Foyer->Dossier->fields(),
						$this->Foyer->Adressefoyer->Adresse->fields(),
						$this->Orientstruct->fields(),
						$this->Orientstruct->Typeorient->fields(),
						$this->Orientstruct->Structurereferente->fields(),
						array(
							'( '.$this->Foyer->vfNbEnfants().' ) AS "Foyer__nbenfants"',
							'Historiqueetatpe.id',
							'Historiqueetatpe.etat',
							'Historiqueetatpe.date',
							'Historiqueetatpe.identifiantpe',
							'Canton.id',
							'Canton.canton',
							'PersonneReferent.referent_id',
							'Titresejour.dftitsej'
						)
					),
					'joins' => array(
						$this->join( 'Prestation', array( 'type' => 'INNER' ) ),
						$this->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->join( 'PersonneReferent', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Titresejour', array( 'type' => 'LEFT OUTER' ) ),
						$this->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Foyer->join( 'Adressefoyer', array( 'type' => 'INNER' ) ),
						$this->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
						$this->join( 'Orientstruct', array( 'type' => 'LEFT OUTER' ) ),
						$this->Orientstruct->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$this->Orientstruct->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) ),
						$Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' ),
						$Informationpe->join( 'Historiqueetatpe', array( 'type' => 'LEFT OUTER' ) ),
						ClassRegistry::init( 'Canton' )->joinAdresse()
					),
					'conditions' => array(
						'Personne.id' => $personne_id,
						'Prestation.natprest' => 'RSA',
						'Prestation.rolepers' => array( 'DEM', 'CJT' ),
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					'contain' => false,
					'recursive' => -1
				)
			);



			///Récupération des données propres au contrat d'insertion, notammenrt le premier contrat validé ainsi que le dernier.
			$contrat = $this->Contratinsertion->find(
				'first',
				array(
					'fields' => array( 'Contratinsertion.datevalidation_ci' ),
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne_id,
						'Contratinsertion.decision_ci' => 'V'
					),
					'contain' => false,
					'order' => 'Contratinsertion.datevalidation_ci DESC',
					'recursive' => -1
				)
			);
			if( !empty( $contrat ) ) {
				$personne['Contratinsertion']['dernier'] = $contrat['Contratinsertion'];
			}

// debug( $personne );
			/// Récupération du service instructeur
			$suiviinstruction = $this->Foyer->Dossier->Suiviinstruction->find(
				'first',
				array(
					'fields' => array_keys( // INFO: champs des tables Suiviinstruction et Serviceinstructeur
							Set::merge(
									Hash::flatten( array( 'Suiviinstruction' => Set::normalize( array_keys( $this->Foyer->Dossier->Suiviinstruction->schema() ) ) ) ), Hash::flatten( array( 'Serviceinstructeur' => Set::normalize( array_keys( ClassRegistry::init( 'Serviceinstructeur' )->schema() ) ) ) )
							)
					),
					'recursive' => -1,
					'contain' => false,
					'conditions' => array(
						'Suiviinstruction.dossier_id' => $personne['Foyer']['dossier_id']
					),
					'joins' => array(
						array(
							'table' => 'servicesinstructeurs',
							'alias' => 'Serviceinstructeur',
							'type' => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Suiviinstruction.numdepins = Serviceinstructeur.numdepins AND Suiviinstruction.typeserins = Serviceinstructeur.typeserins AND Suiviinstruction.numcomins = Serviceinstructeur.numcomins AND Suiviinstruction.numagrins = Serviceinstructeur.numagrins' )
						)
					)
				)
			);

			$personne = Set::merge( $personne, $suiviinstruction );

			$User = ClassRegistry::init( 'User' );
			$user = $User->find(
					'first', array(
				'fields' => array_merge(
						$User->fields(), $User->Serviceinstructeur->fields()
				),
				'conditions' => array(
					'User.id' => $user_id
				),
				'joins' => array(
					$User->join( 'Serviceinstructeur' )
				),
				'contain' => false,
				'recursive' => -1
					)
			);
			$personne = Set::merge( $personne, $user );

// debug($personne);
			return $personne;
		}

		/**
		 *
		 */
		public function newDetailsCi( $personne_id, $user_id = null ) {

			$sqDernierReferent = $this->PersonneReferent->sqDerniere( 'Personne.id', false );

			///Recup personne
			$personne = $this->find(
				'first',
				array(
					'fields' => array(
						'Dossier.id',
						'Dossier.numdemrsa',
						'Dossier.dtdemrsa',
						'Dossier.fonorg',
						'Dossier.matricule',
						'Personne.id',
						'Personne.foyer_id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Personne.dtnai',
						'Personne.nir',
						'Personne.numfixe',
						'Personne.numport',
						'Personne.email',
						'Personne.idassedic',
						'Prestation.rolepers',
						'Adresse.numvoie',
						'Adresse.typevoie',
						'Adresse.nomvoie',
						'Adresse.locaadr',
						'Adresse.codepos',
						'Adresse.nomvoie',
						'Adresse.numcomptt',
						'Serviceinstructeur.lib_service',
						'Serviceinstructeur.numdepins',
						'Serviceinstructeur.typeserins',
						'Serviceinstructeur.numcomins',
						'Serviceinstructeur.numagrins',
						'Suiviinstruction.typeserins',
						ClassRegistry::init( 'Detaildroitrsa' )->vfRsaMajore().' AS "Detailcalculdroitrsa__majore"',
						$this->PersonneReferent->Referent->sqVirtualField( 'nom_complet'),
						'Referent.numero_poste'
					),
					'conditions' => array(
						'Personne.id' => $personne_id,
						'Prestation.rolepers' => array( 'DEM', 'CJT' ),
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					'joins' => array(
						$this->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->join( 'Prestation', array( 'type' => 'INNER' ) ),
						$this->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
						$this->Foyer->Dossier->join( 'Suiviinstruction', array( 'type' => 'LEFT OUTER' ) ),
						array(
							'table' => 'servicesinstructeurs',
							'alias' => 'Serviceinstructeur',
							'type' => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Suiviinstruction.numdepins = Serviceinstructeur.numdepins AND Suiviinstruction.typeserins = Serviceinstructeur.typeserins AND Suiviinstruction.numcomins = Serviceinstructeur.numcomins AND Suiviinstruction.numagrins = Serviceinstructeur.numagrins' )
						),
						$this->join(
							'PersonneReferent',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									"PersonneReferent.id IN ( {$sqDernierReferent} )"
								)
							)
						),
						$this->PersonneReferent->join( 'Referent', array( 'type' => 'LEFT OUTER' ) )
					),
					'recursive' => -1
				)
			);


			// FIXME -> comment distinguer ? + FIXME autorutitel / autorutiadrelec
			$modecontact = $this->Foyer->Modecontact->find(
				'all',
				array(
					'conditions' => array(
						'Modecontact.foyer_id' => $personne['Personne']['foyer_id']
					),
					'recursive' => -1,
					'order' => 'Modecontact.nattel ASC'
				)
			);

			foreach( $modecontact as $index => $value ) {
				$personne = Set::merge( $personne, array( 'Modecontact' => Set::extract( $modecontact, '{n}.Modecontact' ) ) );
			}

			$activite = $this->Activite->find(
				'first',
				array(
					'fields' => array(
						'Activite.act'
					),
					'conditions' => array(
						'Activite.personne_id' => $personne_id
					),
					'recursive' => -1,
					'order' => 'Activite.dfact DESC'
				)
			);
			if( !empty( $activite ) ) {
				$personne = Set::merge( $personne, $activite );
			}

			return $personne;
		}

		/**
		 * Fonction permettant de récupérer le responsable du dossier (DEM + RSA)
		 * Dans les cas où un dossier possède plusieurs Demandeurs
		 * on s'assure de n'en prendre qu'un seul des 2
		 *
		 * @param string $field Le champ Foyer.id de la requête principale.
		 */
		public function sqResponsableDossierUnique( $foyerId = 'Foyer.id' ) {
			return $this->sq(
							array(
								'alias' => 'personnes',
								'fields' => array( 'personnes.id' ),
								'conditions' => array(
									'personnes.foyer_id = '.$foyerId,
									'prestations.rolepers' => 'DEM'
								),
								'joins' => array(
									array_words_replace(
											$this->join( 'Prestation' ), array(
										'Personne' => 'personnes',
										'Prestation' => 'prestations'
											)
									)
								),
								'contain' => false,
								'limit' => 1
							)
			);
		}

		/**
		 * Retourne une sous-requête permettant de connaître la structure chargée
		 * de l'évaluation d'un allocataire (CG 58).
		 *
		 * @param string $personneIdPath Le chemin désignant le champ personne_id
		 *	(ex.: Dossiercov58.personne_id)
		 * @param string $structureFieldPath Le chemin vers l'information de la
		 *	structure orientante que l'on veut obtenir (ex.: Structureorientante.lib_struc)
		 * @param boolean $alias Doit-on aliaser la sous-requête ?
		 * @return string
		 */
		public function sqStructureorientante( $personneIdPath, $structureFieldPath, $alias = true ) {
			list( $personneModelName, $personneFieldName ) = model_field( $personneIdPath );
			list( $soModelName, $soFieldName ) = model_field( $structureFieldPath );

			$sql = "SELECT
					structuresreferentes.{$soFieldName}
				FROM proposorientationscovs58
					INNER JOIN dossierscovs58 ON ( proposorientationscovs58.dossiercov58_id = dossierscovs58.id )
					LEFT OUTER JOIN structuresreferentes ON ( proposorientationscovs58.structureorientante_id = structuresreferentes.id )
				WHERE dossierscovs58.personne_id = \"{$personneModelName}\".\"{$personneFieldName}\"
				ORDER BY dossierscovs58.created DESC
                LIMIT 1";

			if( $alias ) {
				return "( {$sql} ) AS \"{$soModelName}__{$soFieldName}\"";
			}

			return $sql;
		}

        /**
		 *   Calcul du nombre d emois restant avant la fin du titre de séjour de l'alcoataire
		 *   @params integer personne_id
         *  return integer Nb de mois avant la fin du titre de séjour
		 */

		public function nbMoisAvantFinTitreSejour( $personne_id = null ) {
            $date1 = '"Titresejour"."dftitsej"';
            $date2 = '"Cui"."datefintitresejour"';
            $date3 = 'NOW()';
            $vfNbMoisAvantFin = "EXTRACT( YEAR FROM AGE( {$date1}, {$date3} ) ) * 12 +  EXTRACT( MONTH FROM AGE( {$date1}, {$date3} ) )";
            $vfNbMoisAvantFinCui = "EXTRACT( YEAR FROM AGE( {$date2}, {$date3} ) ) * 12 +  EXTRACT( MONTH FROM AGE( {$date2}, {$date3} ) )";

			$result = $this->find(
				'first',
				array(
					'fields' => array(
                        'Titresejour.dftitsej',
						"( {$vfNbMoisAvantFin} ) AS \"Titresejour__nbMoisAvantFin\"",
                        'Cui.datefintitresejour',
                        "( {$vfNbMoisAvantFinCui} ) AS \"Cui__nbMoisAvantFinCui\"",
					),
					'joins' => array(
						$this->join( 'Titresejour', array( 'type' => 'LEFT OUTER' ) ),
                        $this->join( 'Cui', array( 'type' => 'LEFT OUTER' ) )
					),
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'recursive' => -1
				)
			);

            return $result;
		}
	}
?>