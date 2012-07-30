<?php
	class Cui extends AppModel
	{
		public $name = 'Cui';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'haspiecejointe' => array( 'domain' => 'cui' ),
					'secteur' => array( 'domain' => 'cui' ),
					'isaci' => array( 'domain' => 'cui' ),
					'statutemployeur' => array( 'domain' => 'cui' ),
					'niveauformation' => array( 'domain' => 'cui' ),
					'avenant' => array( 'domain' => 'cui' ),
					'avenantcg' => array( 'domain' => 'cui' ),
					'orgrecouvcotis' => array( 'domain' => 'cui' ),
					'formation' => array( 'domain' => 'cui' ),
					'orgapayeur' => array( 'domain' => 'cui' ),
					'isadresse2' => array( 'domain' => 'cui' ),
					'iscie' => array( 'domain' => 'cui' ),
					'dureesansemploi' => array( 'domain' => 'cui' ),
					'isinscritpe' => array( 'domain' => 'cui' ),
					'dureeinscritpe' => array( 'type' => 'dureesansemploi', 'domain' => 'cui' ),
					'isbeneficiaire' => array( 'domain' => 'cui', 'type' => 'beneficiaire' ),
					'rsadeptmaj' => array( 'domain' => 'cui' ),
					'dureebenefaide' => array( 'type' => 'dureesansemploi', 'domain' => 'cui' ),
					'isbeneficiaire' => array( 'domain' => 'cui' ),
					'handicap' => array( 'domain' => 'cui' ),
					'typecontrat' => array( 'domain' => 'cui' ),
					'modulation' => array( 'domain' => 'cui' ),
					'isaas' => array( 'domain' => 'cui' ),
					'remobilisation' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'aidereprise' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'elaboprojetpro' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'evaluation' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'aiderechemploi' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'adaptation' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'remiseniveau' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'prequalification' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'nouvellecompetence' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'formqualif' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'isperiodepro' => array( 'domain' => 'cui' ),
					'validacquis' => array( 'domain' => 'cui' ),
					'iscae' => array( 'domain' => 'cui' ),
					'financementexclusif' => array( 'domain' => 'cui' ),
					'orgapayeur' => array( 'domain' => 'cui' ),
					'decisioncui' => array( 'domain' => 'cui' )
				)
			),
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id', 'prestataire_id', 'metieraffectation_id', 'metieremploipropose_id' ),
			),
			'Autovalidate',
			'Gedooo.Gedooo'
		);

		/**
		* Chemin relatif pour les modèles de documents .odt utilisés lors des
		* impressions. Utiliser %s pour remplacer par l'alias.
		*/
		public $modelesOdt = array(
			'CUI/cui.odt',
		);



		public $validate = array(
			'secteur' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'siret' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Ce numéro SIRET existe déjà'
				),
				array(
					'rule' => 'numeric',
					'message' => 'Le numéro SIRET est composé de 14 chiffres'
				)
			),
			/*'nomemployeur' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'statutemployeur' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			
			'orgrecouvcotis' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'typevoieemployeur' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'numvoieemployeur' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'nomvoieemployeur' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'codepostalemployeur' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'villeemployeur' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'numtelemployeur' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => array( 'between', 10, 14 ),
					'message' => 'Le numéro de téléphone est composé de 10 chiffres'
				)
			),
			'emailemployeur' => array(
				'rule' => 'email',
				'message' => 'Email non valide',
				'allowEmpty' => true
			),
			'niveauformation' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'dureesansemploi' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'isisncritpe' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'typecontrat' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'ass' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'rsadept' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'rsadeptmaj' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'aah' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'ata' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureebenefaide' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'handicap'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dateembauche'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'codeemploi'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'salairebrut'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureehebdosalarieheure'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureehebdosalarieminute'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'modulation'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'fonctiontuteur'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'isaas'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureecollhebdominute'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureecollhebdoheure'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'remobilisation'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'aidereprise'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'elaboprojetpro'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'evaluation'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'aiderechemploi'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'adaptation'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'remiseniveau'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'prequalification'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'nouvellecompetence'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'formqualif'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'formation'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'isperiodepro'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'niveauqualif' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'validacquis' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'datedebprisecharge' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'datefinprisecharge' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureehebdoretenueheure' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureehebdoretenueminute' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'opspeciale' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'tauxfixe' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'orgapayeur' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			)*/
		); 

		public $belongsTo = array(
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
			'Prestataire' => array(
				'className' => 'Referent',
				'foreignKey' => 'prestataire_id',
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
			'Decisioncui66' => array(
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
			),
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
		);

		/**
		*
		*/

		public $queries = array(
			'criteresci' => array(
				'fields' => array(
					'"Cui"."id"',
					'"Cui"."personne_id"',
					'"Cui"."referent_id"',
					'"Cui"."structurereferente_id"',
					'"Cui"."datecontrat"',
					'"Cui"."datedebprisecharge"',
					'"Cui"."datefinprisecharge"',
					'"Dossier"."numdemrsa"',
					'"Dossier"."dtdemrsa"',
					'"Dossier"."matricule"',
					'"Personne"."id"',
					'"Personne"."nom"',
					'"Personne"."prenom"',
					'"Personne"."dtnai"',
					'"Personne"."qual"',
					'"Personne"."nomcomnai"',
					'"Adresse"."locaadr"',
					'"Adresse"."numcomptt"'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Cui.personne_id' )
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest = \'RSA\'',
							'( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
						)
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					)
				)
			)
		);

		/**
		*   Précondition: La personne est-elle bien en Rsa Socle ?
		*   @default: false --> si Rsa Socle pas de msg d'erreur
		*/

		public function _prepare( $personne_id = null ) {

			$alerteRsaSocle = false;
			$personne = $this->Personne->findById( $personne_id, null, null, 0 );
			$dossier_id = Set::classicExtract( $personne, 'Foyer.dossier_id' );

			/// FIXME: on regarde le rsa socle sur les infos financieres OU BIEN sur les calculs droits rsa
			$infosfinancieres = $this->Personne->Foyer->Dossier->Infofinanciere->find(
				'all',
				array(
					'conditions' => array(
						'Infofinanciere.dossier_id' => $dossier_id
					),
					'recursive' => -1,
					'order' => 'Infofinanciere.moismoucompta DESC'
				)
			);

			$detaildroitrsa = $this->Personne->Foyer->Dossier->Detaildroitrsa->findByDossierId( $dossier_id, null, null, -1 );
			if( !empty( $detaildroitrsa ) ){
				$detaildroitrsa_id = Set::classicExtract( $detaildroitrsa, 'Detaildroitrsa.id' );
				$detailscalculsdroitrsa = $this->Personne->Foyer->Dossier->Detaildroitrsa->Detailcalculdroitrsa->find(
					'all',
					array(
						'conditions' => array(
							'Detailcalculdroitrsa.detaildroitrsa_id' => $detaildroitrsa_id
						),
						'recursive' => -1,
						'order' => 'Detailcalculdroitrsa.dtderrsavers DESC'
					)
				);
			}

			$rsaSocleValues = array();
			if( !empty( $infosfinancieres ) ) {
				$rsaSocleValues = array_unique( Set::extract( $infosfinancieres, '0/Infofinanciere/natpfcre' ) );
			}
			else if( !empty( $detailscalculsdroitrsa ) ) {
				$rsaSocleValues = array_unique( Set::extract( $detailscalculsdroitrsa, '0/Detailcalculdroitrsa/natpf' ) );
			}
			else {
				$alerteRsaSocle = true;
			}

			$rsaSocle = array( 'RSB', 'RSD', 'RSI', 'RSU' ); // valeurs possibles pour les RSA Socles
			if( array_intersects( $rsaSocleValues, array_keys( $rsaSocle ) ) ) {
				$alerteRsaSocle = false;
			}
			return $alerteRsaSocle;
		}


		/**
		*   BeforeValidate
		*/
		public function beforeValidate( $options = array() ) {
			$return = parent::beforeValidate( $options );

			foreach( array( 'iscie' ) as $key ) {
				if( isset( $this->data[$this->name][$key] ) ) {
					$this->data[$this->name][$key] = Set::enum( $this->data[$this->name][$key], array( '1' => 'O', '0' => 'N' ) );
				}
			}

			return $return;
		}

		
		/**
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

	}
?>