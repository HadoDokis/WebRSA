<?php
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
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.',
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
					'rule' => array('comparison', '>', 0 ),
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
				'type'		=> 'string',
				'postgres'	=> '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
			),
			'nom_complet_court' => array(
				'type'		=> 'string',
				'postgres'	=> '( "%s"."nom" || \' \' || "%s"."prenom" )'
			)
		);

		/**
		*
		*/

		public function beforeSave( $options = array() ) {
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
		* Recherche de l'id du dossier à partir de l'id de la personne
		*/

		public function dossierId( $personne_id ) {
			$this->unbindModelAll();
			$this->bindModel( array( 'belongsTo' => array( 'Foyer' ) ) );
			$personne = $this->find(
				'first',
				array(
					'fields' => array( 'Foyer.dossier_id' ),
					'conditions' => array( 'Personne.id' => $personne_id ),
					'recursive' => 0
				)
			);

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
			$this->unbindModelAll();
			$this->bindModel(
				array(
					'hasMany' => array(
						'Ressource' => array(
							'order' => array( 'dfress DESC' )
						)
					),
					'hasOne' => array(
						'Dsp',
						'Prestation' => array(
							'foreignKey' => 'personne_id',
							'conditions' => array (
								'Prestation.natprest' => array( 'RSA' )
							)
						),
						'Calculdroitrsa'
					)
				)
			);

			$personne = $this->findById( $personne_id, null, null, 1 );
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
		*
		*/

		public function findByZones( $zonesGeographiques = array(), $filtre_zone_geo = true ) { // TODO
			$this->unbindModelAll();

			$this->bindModel(
				array(
					'hasOne'=>array(
						'Adressefoyer' => array(
							'foreignKey'    => false,
							'type'          => 'LEFT',
							'conditions'    => array(
								'"Adressefoyer"."foyer_id" = "Personne"."foyer_id"',
								'"Adressefoyer"."rgadr" = \'01\''
							)
						),
						'Adresse' => array(
							'foreignKey'    => false,
							'type'          => 'LEFT',
							'conditions'    => array(
								'"Adressefoyer"."adresse_id" = "Adresse"."id"'
							)
						)
					)
				)
			);

			$conditions = array();
			if( $filtre_zone_geo ) {
				$conditions = array( 'Adresse.numcomptt' => ( !empty( $zonesGeographiques ) ? array_values( $zonesGeographiques ) : array() ) );
			}

			$personnes = $this->find( 'all', array ( 'conditions' => $conditions ) );

			$return = Set::extract( $personnes, '{n}.Personne.id' );
			return ( !empty( $return ) ? $return : null );
		}

		/**
		*    Détails propre à la personne pour le contrat d'insertion
		*/

		public function detailsCi( $personne_id, $user_id = null ){
			// TODO: début dans le modèle
			///Recup personne
			$this->unbindModel(
				array(
					'hasOne' => array( 'Avispcgpersonne', 'Dossiercaf' ),
					'hasMany' => array( 'Rendezvous', 'Activite', 'Contratinsertion', 'Orientstruct' )
				)
			);
			$this->bindModel( array( 'belongsTo' => array( 'Foyer' ) ) ); // FIXME
			$this->Foyer->unbindModel(
				array(
					'hasMany' => array( 'Personne', 'Modecontact', 'Adressefoyer' ),
					'hasAndBelongsToMany' => array( 'Creance' )
				)
			);
			$this->Foyer->Dossier->unbindModelAll();
			$this->Prestation->unbindModelAll();
			$this->Dsp->unbindModelAll();

			$personne = $this->findById( $personne_id, null, null, 2 );

			// Récupération du service instructeur
			$suiviinstruction = $this->Foyer->Dossier->Suiviinstruction->find(
				'first',
				array(
					'fields' => array_keys( // INFO: champs des tables Suiviinstruction et Serviceinstructeur
						Set::merge(
							Set::flatten( array( 'Suiviinstruction' => Set::normalize( array_keys( $this->Foyer->Dossier->Suiviinstruction->schema() ) ) ) ),
							Set::flatten( array( 'Serviceinstructeur' => Set::normalize( array_keys( ClassRegistry::init( 'Serviceinstructeur' )->schema() ) ) ) )
						)
					),
					'recursive' => -1,
					'conditions' => array(
						'Suiviinstruction.dossier_id' => $personne['Foyer']['dossier_id']
					),
					'joins' => array(
						array(
							'table'      => 'servicesinstructeurs',
							'alias'      => 'Serviceinstructeur',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Suiviinstruction.numdepins = Serviceinstructeur.numdepins AND Suiviinstruction.typeserins = Serviceinstructeur.typeserins AND Suiviinstruction.numcomins = Serviceinstructeur.numcomins AND Suiviinstruction.numagrins = Serviceinstructeur.numagrins' )
						)
					)
				)
			);

			$personne = Set::merge( $personne, $suiviinstruction );
			//On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
			if( empty( $suiviinstruction ) && is_int( $user_id ) ) {
				$user = $this->Contratinsertion->User->findById( $user_id, null, null, 0 );
				$personne = Set::merge( $personne, $user );
			}

			// FIXME -> comment distinguer ? + FIXME autorutitel / autorutiadrelec
			$modecontact = $this->Foyer->Modecontact->find(
				'all',
				array(
					'conditions' => array(
						'Modecontact.foyer_id' => $personne['Foyer']['id']
					),
					'recursive' => -1,
					'order' => 'Modecontact.nattel ASC'
				)
			);

			foreach( $modecontact as $index => $value ) {
				if( ( ( Set::extract( $value, 'Modecontact.autorutitel' ) != 'R' ) ) && ( Set::extract( $value, 'Modecontact.nattel' ) == 'D' ) ) {
					$personne['Foyer'] = Set::merge( $personne['Foyer'], array( 'Modecontact' => Set::extract( $modecontact, '{n}.Modecontact' ) ) );
				}
			}

			/// Récupération de l'adresse lié à la personne
			$this->Foyer->Adressefoyer->bindModel(
				array(
					'belongsTo' => array(
						'Adresse' => array(
							'className'     => 'Adresse',
							'foreignKey'    => 'adresse_id'
						)
					)
				)
			);

			$detaildroitrsa = $this->Foyer->Dossier->Detaildroitrsa->find(
				'first',
				array(
					'conditions' => array(
						'Detaildroitrsa.dossier_id' => $personne['Foyer']['Dossier']['id']
					),
					'recursive' => -1
				)
			);//Detaildroitrsa.oridemrsa
			if( !empty( $detaildroitrsa ) ) {
				$personne = Set::merge( $personne, $detaildroitrsa );
			}

			$activite = $this->Activite->find(
				'first',
				array(
					'conditions' => array(
						'Activite.personne_id' => $personne_id
					),
					'recursive' => -1,
					'order' => 'Activite.dfact DESC'
				)
			);//Activite.act
			if( !empty( $activite ) ) {
				$personne = Set::merge( $personne, $activite );
			}

			$adresse = $this->Foyer->Adressefoyer->find(
				'first',
				array(
					'conditions' => array(
						'Adressefoyer.foyer_id' => $personne['Personne']['foyer_id'],
						'Adressefoyer.rgadr' => '01',
					)
				)
			);
			$personne['Adresse'] = $adresse['Adresse'];

			// Recherche de la structure référente
			$this->Orientstruct->unbindModelAll();
			$this->Orientstruct->bindModel( array( 'belongsTo' => array( 'Structurereferente' ) ) );
			$orientstruct = $this->Orientstruct->find(
				'first',
				array(
					'conditions' => array(
						'Orientstruct.personne_id' => $personne_id,
						'Orientstruct.date_propo IS NOT NULL'
					),
					'order' => 'Orientstruct.date_propo DESC',
					'recursive' => 0
				)
			);

			if( !empty( $orientstruct ) ) {
				$personne = Set::merge( $personne, $orientstruct );
			}

			return $personne;
		}

		/**
		* Détails propres à la personne pour l'APRE
		*/

		public function detailsApre( $personne_id, $user_id = null ){
			$personne = $this->find(
				'first',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'contain' => array(
						'Prestation' => array(
							'conditions' => array(
								'natprest' => 'RSA'
							)
						),
						'Foyer' => array(
							'Dossier',
							'Modecontact' => array(
								'order' => 'Modecontact.id ASC'
							),
							'Adressefoyer' => array(
								'conditions' => array(
									'Adressefoyer.rgadr' => '01',
								),
								'Adresse' => array(
									'limit' => 1
								)
							)
						),
						'Orientstruct' => array(
							'conditions' => array(
								'Orientstruct.personne_id' => $personne_id,
								'Orientstruct.date_propo IS NOT NULL'
							),
							'order' => 'Orientstruct.date_propo DESC',
							'limit' => 1,
							'Structurereferente'
						),
						'Activite' => array(
							'order' => 'Activite.dfact DESC',
							'limit' => 1
						)
					)
				)
			);

			if( isset( $personne['Foyer']['Adressefoyer'][0]['Adresse'] ) ) {
				$personne['Adresse'] = @$personne['Foyer']['Adressefoyer'][0]['Adresse'];
				unset( $personne['Foyer']['Adressefoyer'][0]['Adresse'] );
			}

			// Recherche de la structure référente
			if( isset( $personne['Orientstruct'][0] ) ) {
				$personne['Orientstruct'] = $personne['Orientstruct'][0];
				unset( $personne['Orientstruct'][0] );
			}

			if( isset( $personne['Orientstruct']['Structurereferente'] ) ) {
				$personne['Structurereferente'] = $personne['Orientstruct']['Structurereferente'];
				unset( $personne['Orientstruct']['Structurereferente'] );
			}

			///Récupération des données propres au contrat d'insertion, notammenrt le premier contrat validé ainsi que le dernier.
			$contrat = $this->Contratinsertion->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne['Personne']['id']
					),
					'order' => 'Contratinsertion.datevalidation_ci ASC',
					'recursive' => -1
				)
			);
			$personne['Contratinsertion']['premier'] = $contrat['Contratinsertion'];

			$contrat = $this->Contratinsertion->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne['Personne']['id']
					),
					'order' => 'Contratinsertion.datevalidation_ci DESC',
					'recursive' => -1
				)
			);
			$personne['Contratinsertion']['dernier'] = $contrat['Contratinsertion'];

			///Récupération des données Dsp
			$dsp = $this->Dsp->find(
				'first',
				array(
					'conditions' => array(
						'Dsp.personne_id' => $personne_id
					),
					'recursive' => -1
				)
			);
			$dsp_id = Set::classicExtract( $dsp, 'Dsp.id' );

			if( !empty( $dsp_id ) ){
				$dspRevModel = ClassRegistry::init( 'DspRev' );
				$dspRev = $dspRevModel->find(
					'first',
					array(
						'conditions' => array(
							'DspRev.dsp_id' => $dsp_id,
							'DspRev.personne_id' => $personne_id
						),
						'recursive' => -1,
						'order' => 'DspRev.modified DESC'
					)
				);

				if( !empty( $dspRev ) ) {
					$dsp['Dsp'] = $dspRev['DspRev'];
				}
			}
			$personne['Dsp'] = $dsp['Dsp'];

			// Récupération du service instructeur
			$suiviinstruction = $this->Foyer->Dossier->Suiviinstruction->find(
				'first',
				array(
					'fields' => array_keys( // INFO: champs des tables Suiviinstruction et Serviceinstructeur
						Set::merge(
							Set::flatten( array( 'Suiviinstruction' => Set::normalize( array_keys( $this->Foyer->Dossier->Suiviinstruction->schema() ) ) ) ),
							Set::flatten( array( 'Serviceinstructeur' => Set::normalize( array_keys( ClassRegistry::init( 'Serviceinstructeur' )->schema() ) ) ) )
						)
					),
					'recursive' => -1,
					'conditions' => array(
						'Suiviinstruction.dossier_id' => $personne['Foyer']['dossier_id']
					),
					'joins' => array(
						array(
							'table'      => 'servicesinstructeurs',
							'alias'      => 'Serviceinstructeur',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Suiviinstruction.numdepins = Serviceinstructeur.numdepins AND Suiviinstruction.typeserins = Serviceinstructeur.typeserins AND Suiviinstruction.numcomins = Serviceinstructeur.numcomins AND Suiviinstruction.numagrins = Serviceinstructeur.numagrins' )
						)
					)
				)
			);

			$personne = Set::merge( $personne, $suiviinstruction );

			//On ajout l'ID de l'utilisateur connecté afin de récupérer son service instructeur
			if( empty( $suiviinstruction ) && is_int( $user_id ) ) {
				$user = $this->Contratinsertion->User->findById( $user_id, null, null, 0 );
				$personne = Set::merge( $personne, $user );
			}

			return $personne;
		}

		/**
		*
		*/

		public function newDetailsCi( $personne_id, $user_id = null ){
			///Recup personne
			$this->Prestation->unbindModelAll();
			$this->Dsp->unbindModelAll();

			$this->unbindModelAll();
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
						ClassRegistry::init( 'Detaildroitrsa' )->vfRsaMajore().' AS "Detailcalculdroitrsa__majore"'
					),
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'joins' => array(
						array(
							'table'      => 'foyers', // FIXME
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
						),
						array(
							'table'      => 'dossiers', // FIXME
							'alias'      => 'Dossier',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
						),
						array(
							'table'      => 'prestations',
							'alias'      => 'Prestation',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Prestation.personne_id',
								'( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
								'Prestation.natprest = \'RSA\''
							)
						),
						array(
							'table'      => 'adressesfoyers',
							'alias'      => 'Adressefoyer',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
						),
						array(
							'table'      => 'adresses',
							'alias'      => 'Adresse',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
						),
						array(
							'table'      => 'suivisinstruction',
							'alias'      => 'Suiviinstruction',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Suiviinstruction.dossier_id = Dossier.id' )
						),
						array(
							'table'      => 'servicesinstructeurs',
							'alias'      => 'Serviceinstructeur',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Suiviinstruction.numdepins = Serviceinstructeur.numdepins AND Suiviinstruction.typeserins = Serviceinstructeur.typeserins AND Suiviinstruction.numcomins = Serviceinstructeur.numcomins AND Suiviinstruction.numagrins = Serviceinstructeur.numagrins' )
						),
					),
					'recursive' => 1
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
// 				if( ( ( Set::extract( $value, 'Modecontact.autorutitel' ) != 'R' ) ) && ( Set::extract( $value, 'Modecontact.nattel' ) == 'D' ) ) {
					$personne = Set::merge( $personne, array( 'Modecontact' => Set::extract( $modecontact, '{n}.Modecontact' ) ) );
// 				}
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
							$this->join( 'Prestation' ),
							array(
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
	}
?>