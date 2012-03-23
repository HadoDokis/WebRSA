<?php
	define( 'DATE_DECISION_FACULTATIVE', Configure::read( 'Cg.departement' ) != 66 );

	class Contratinsertion extends AppModel
	{
		public $name = 'Contratinsertion';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'type_demande' => array( 'type' => 'type_demande', 'domain' => 'contratinsertion' ),
					'num_contrat' => array( 'type' => 'num_contrat', 'domain' => 'contratinsertion' ),
					'typeinsertion' => array( 'type' => 'insertion', 'domain' => 'contratinsertion' ),
					'positioncer' => array( 'domain' => 'contratinsertion' ),
					'haspiecejointe' => array( 'domain' => 'contratinsertion' ),
					'cmu',
					'cmuc',
					'emploi_act',
					'objetcerprec'
				)
			),
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id' ),
			),
			'Autovalidate',
			'ValidateTranslate',
			'Gedooo.Gedooo',
			'ModelesodtConditionnables' => array(
				58 => '%s/contratinsertioncg58.odt',
				66 => array(
					'%s/notificationop.odt',
					'%s/notifbenefSimplevalide.odt',
					'%s/notifbenefParticuliervalide.odt',
					'%s/notifbenefSimplenonvalide.odt',
					'%s/notifbenefParticuliernonvalide.odt',
					'%s/contratinsertion.odt',
					'%s/contratinsertionold.odt',
					'%s/ficheliaisoncerParticulier.odt',
					'%s/ficheliaisoncerSimpleoa.odt',
					'%s/ficheliaisoncerSimplemsp.odt'
				),
				93 => '%s/contratinsertion.odt'
			),
			'StorablePdf' => array(
				'afterSave' => 'deleteAll'
			)
		);

		public $validate = array(
			'actions_prev' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'structurereferente_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'dd_ci' => array(
				'notEmpty' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			),
			'df_ci' => array(
				'notEmpty' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			),
			'aut_expr_prof' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'forme_ci' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'emp_trouv' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'sect_acti_emp' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'emp_occupe' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'duree_hebdo_emp' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'nat_cont_trav' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'duree_cdd' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'duree_engag' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'numeric',
							'message' => 'Veuillez entrer une valeur numérique.'
				)
			),
			'nature_projet' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'decision_ci' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'datevalidation_ci' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'decision_ci', true, array( 'V' ) ),
					'message' => 'Veuillez entrer une date valide',
				),
				'notEmpty' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide',
					'allowEmpty'    => true
				)
			),
			'lieu_saisi_ci' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'niveausalaire' => array(
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.'
				),
				array(
					'rule' => array( 'comparison', '>=', 0 ),
					'message' => 'Veuillez entrer un nombre positif.'
				)
			),
			'date_saisi_ci' => array(
				array(
					'rule' => array('datePassee'),
					'message' => 'Merci de choisir une date antérieure à la date du jour',
					'on' => 'create'
				),
				array(
					'rule' => 'date',
					'message' => 'Merci de rentrer une date valide',
					'allowEmpty' => false,
					'required' => true,
					'on' => 'create'
				)
			),
			'datedecision' => array(
				'rule' => 'date',
				'message' => 'Veuillez entrer une date valide',
				'allowEmpty'  => DATE_DECISION_FACULTATIVE
			),
			/**
			* Régle ajoutée suite à la demande du CG66
			*/
			'nature_projet' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
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
			'Typocontrat' => array(
				'className' => 'Typocontrat',
				'foreignKey' => 'typocontrat_id',
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
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'foreignKey' => 'zonegeographique_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Actioninsertion' => array(
				'className' => 'Actioninsertion',
				'foreignKey' => 'contratinsertion_id',
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
			'Autreavissuspension' => array(
				'className' => 'Autreavissuspension',
				'foreignKey' => 'contratinsertion_id',
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
			'Autreavisradiation' => array(
				'className' => 'Autreavisradiation',
				'foreignKey' => 'contratinsertion_id',
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
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
				'foreignKey' => 'contratinsertion_id',
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
					'Fichiermodule.modele = \'Contratinsertion\'',
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
			'Nonrespectsanctionep93' => array(
				'className' => 'Nonrespectsanctionep93',
				'foreignKey' => 'contratinsertion_id',
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
			'Signalementep93' => array(
				'className' => 'Signalementep93',
				'foreignKey' => 'contratinsertion_id',
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
			'Contratcomplexeep93' => array(
				'className' => 'Contratcomplexeep93',
				'foreignKey' => 'contratinsertion_id',
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
			'Sanctionep58' => array(
				'className' => 'Sanctionep58',
				'foreignKey' => 'contratinsertion_id',
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
				'foreignKey' => 'contratinsertion_id',
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
			/*'Objetcontratinsertion' => array(
				'className' => 'Objetcontratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),*/
		);

		public $hasAndBelongsToMany = array(
			'User' => array(
				'className' => 'User',
				'joinTable' => 'contratsinsertion_users',
				'foreignKey' => 'contratinsertion_id',
				'associationForeignKey' => 'user_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ContratinsertionUser'
			)
		);

		public $hasOne = array(
			'Propodecisioncer66' => array(
				'className' => 'Propodecisioncer66',
				'foreignKey' => 'contratinsertion_id',
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
		
		public $virtualFields = array(
			'nbjours' => array(
				'type'      => 'integer',
				'postgres'  => 'DATE_PART( \'day\', NOW() - "%s"."df_ci" )'
			),
			'present' => array(
				'type'      => 'boolean',
				'postgres'  => '(CASE WHEN "%s"."id" IS NOT NULL THEN true ELSE false END )'
			),
		);

		/**
		*
		*/

		public $queries = array(
			'criteresci' => array(
				'fields' => array(
					'"Contratinsertion"."id"',
					'"Contratinsertion"."personne_id"',
					'"Contratinsertion"."num_contrat"',
					'"Contratinsertion"."structurereferente_id"',
					'"Contratinsertion"."rg_ci"',
					'"Contratinsertion"."decision_ci"',
					'"Contratinsertion"."dd_ci"',
					'"Contratinsertion"."df_ci"',
					'"Contratinsertion"."datevalidation_ci"',
					'"Contratinsertion"."date_saisi_ci"',
					'"Contratinsertion"."pers_charg_suivi"',
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
						'conditions' => array( 'Personne.id = Contratinsertion.personne_id' )
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
		* Recalcul des rangs des contrats pour une personne donnée ou pour
		* l'ensemble des personnes.
		*/

		protected function _updateRangsContrats( $personne_id = null ) {
			$condition = ( is_null( $personne_id ) ? "" : "contratsinsertion.personne_id = {$personne_id}" );

			$sql = "UPDATE contratsinsertion
						SET rg_ci = NULL".( !empty( $condition ) ? " WHERE {$condition}" : "" ).";";
			$success = ( $this->query( $sql ) !== false );

			$sql = "UPDATE contratsinsertion
						SET rg_ci = (
							SELECT ( COUNT(contratsinsertionpcd.id) + 1 )
								FROM contratsinsertion AS contratsinsertionpcd
								WHERE contratsinsertionpcd.personne_id = contratsinsertion.personne_id
									AND contratsinsertionpcd.id <> contratsinsertion.id
									AND contratsinsertionpcd.decision_ci = 'V'
									AND contratsinsertionpcd.dd_ci IS NOT NULL
									AND contratsinsertionpcd.dd_ci < contratsinsertion.dd_ci
									AND (
										contratsinsertion.positioncer IS NULL
										OR contratsinsertion.positioncer <> 'annule'
									)
						)
						WHERE
							contratsinsertion.dd_ci IS NOT NULL
							".( !empty( $condition ) ? " AND {$condition}" : "" )."
							AND contratsinsertion.decision_ci = 'V'
							AND (
								contratsinsertion.positioncer IS NULL
								OR contratsinsertion.positioncer <> 'annule'
							);";

			$success = ( $this->query( $sql ) !== false ) && $success;

			return $success;
		}

		/**
		* Recalcul des rangs des contrats pour une personne donnée.
		* afterSave, afterDelete, valider, annuler
		*/

		public function updateRangsContratsPersonne( $personne_id ) {
			return $this->_updateRangsContrats( $personne_id  );
		}

		/**
		* Recalcul des rangs des contrats pour l'ensemble des personnes.
		*/

		public function updateRangsContrats() {
			return $this->_updateRangsContrats();
		}

		/**
		* BeforeSave
		*/

		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			if( array_key_exists( $this->name, $this->data ) && array_key_exists( 'structurereferente_id', $this->data[$this->name] ) ) {
				$this->data[$this->name]['structurereferente_id'] = suffix( $this->data[$this->name]['structurereferente_id'] ) ;
			}

			///Ajout pour obtenir referent lié à structure
			$hasMany = ( array_depth( $this->data ) > 2 );

			if( !$hasMany ) { // INFO: 1 seul enregistrement
				if( array_key_exists( 'referent_id', $this->data[$this->name] ) ) {
					$this->data[$this->name]['referent_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data[$this->name]['referent_id'] );
				}
			}
			else { // INFO: plusieurs enregistrements
				foreach( $this->data[$this->name] as $key => $value ) {
					if( is_array( $value ) && array_key_exists( 'referent_id', $value ) ) {
						$this->data[$this->name][$key]['referent_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $value['referent_id'] );
					}
				}
			}
			///Fin ajout pour récupération referent lié a structure

			/// FIXME: faire un behavior
			foreach( array( 'actions_prev' ) as $key ) {
				if( isset( $this->data[$this->name][$key] ) ) {
					$this->data[$this->name][$key] = Set::enum( $this->data[$this->name][$key], array( 'O' => '1', 'N' => '0' ) );
				}
			}

			// Si aucun emploi trouvé, alors il faut éventuellement vider certaines valeurs
			if( isset( $this->data[$this->name]['emp_trouv'] ) && $this->data[$this->name]['emp_trouv'] != 'O' ) {
				$champs = array( 'sect_acti_emp', 'emp_occupe', 'duree_hebdo_emp', 'nat_cont_trav', 'duree_cdd' );
				foreach( $champs as $champ ) {
					$this->data[$this->name][$champ] = null;
				}
			}

			foreach( array( 'emp_trouv' ) as $key ) {
				if( isset( $this->data[$this->name][$key] ) ) {
					$this->data[$this->name][$key] = Set::enum( $this->data[$this->name][$key], array( 'O' => true, 'N' => false ) );
				}
			}

			//Modification décision cer
			if( isset( $this->data[$this->name]['decision_ci'] ) && $this->data[$this->name]['decision_ci'] != 'V' ){
				$this->data[$this->name]['datevalidation_ci'] = NULL;
			}

			//Pour le CG66, la date de validation est datedecision
			if( isset( $this->data[$this->name]['decision_ci'] ) && ( Configure::read( 'Cg.departement' ) == 66 ) ){
				if( $this->data[$this->name]['decision_ci'] == 'V' ){
					$this->data[$this->name]['datevalidation_ci'] = $this->data[$this->name]['datedecision'];
				}
				else if( $this->data[$this->name]['decision_ci'] == 'E' ) {
					$this->data[$this->name]['datedecision'] = NULL;
				}
			}

			// Si la nature du contrat de travail n'est pas un CDD, alors il faut éventuellement vider la valeur de la durée du CDD
			if( isset( $this->data[$this->name]['nat_cont_trav'] ) && $this->data[$this->name]['nat_cont_trav'] != 'TCT3' ){
				$this->data[$this->name]['duree_cdd'] = NULL;
			}

			//  Calcul de la position du cER
			if( Configure::read( 'Cg.departement' ) == '66' ) {
				$this->data[$this->alias]['positioncer'] = $this->calculPosition( $this->data );
			}
			return $return;
		}

		/**
		*
		*/

		public function valider( $data ) {
			$this->begin();
			$success = $this->saveAll( $data, array( 'atomic' => false ) );

			// Sortie de la procédure de relances / sanctions 93 en cas de validation d'un nouveau contrat ?
			if( $success && Configure::read( 'Cg.departement' ) == '93' ) {
				$success = $this->Nonrespectsanctionep93->calculSortieProcedureRelanceParValidationCer( $data ) && $success;
			}

			if( $success ) {
				$this->commit();
			}
			else {
				$this->rollback();
			}

			return $success;
		}

		/**
		*   AfterSave
		*/

		public function afterSave( $created ) {
			$return = parent::afterSave( $created );

			// Mise à jour des APREs
			$return = $this->query( "UPDATE apres SET eligibiliteapre = 'O' WHERE apres.personne_id = ".$this->data[$this->name]['personne_id']." AND apres.etatdossierapre = 'COM';" ) && $return;
			$return = $this->query( "UPDATE apres SET eligibiliteapre = 'N' WHERE apres.personne_id = ".$this->data[$this->name]['personne_id']." AND apres.etatdossierapre = 'INC';" ) && $return;

			return $return;
		}

		/**
		*
		*/

		public function calculPosition( $data ) {
			$formeCi = Set::classicExtract( $data, 'Contratinsertion.forme_ci' );
			$sitproCi = Set::classicExtract( $data, 'Contratinsertion.sitpro_ci' );
			$decision_ci = Set::classicExtract( $data, 'Contratinsertion.decision_ci' );
			$positioncer = Set::classicExtract( $data, 'Contratinsertion.positioncer' );
			$datenotif = Set::classicExtract( $data, 'Contratinsertion.datenotification' );
			$id = Set::classicExtract( $data, 'Contratinsertion.id' );
// debug($datenotif);
			$personne_id = Set::classicExtract( $data, 'Contratinsertion.personne_id' );

			$conditions = array( 'Contratinsertion.personne_id' => $personne_id, );
			if( !empty( $id ) ) {
				$conditions['Contratinsertion.id <>'] = $id;
			}

			$dernierContrat = $this->find(
				'first',
				array(
					'conditions' => $conditions,
					'order' => 'Contratinsertion.rg_ci DESC',
					'contain' => false
				)
			);
			$decisionprecedente = Set::classicExtract( $dernierContrat, 'Contratinsertion.decision_ci' );

			//FIXME: la position a périmé ne devrait aps figurer ici
			if ( ( is_null( $positioncer ) || in_array( $positioncer , array( 'attvalid', 'perime' ) ) ) && !empty( $decision_ci ) ) {

				if ( $decision_ci == 'V' ){
					if( empty( $datenotif ) ) {
						$positioncer = 'valid';
					}
					else {
						$positioncer = 'validnotifie';
					}
				}
				else if ( $decision_ci == 'N' ){
					if( empty( $datenotif ) ) {
						$positioncer = 'nonvalid';
					}
					else {
						$positioncer = 'nonvalidnotifie';
					}
				}
			}

			// Lors de l'ajout d'un nouveau CER, on passe la position du précédent à fin de contrat, sauf pour les non validés
			if( !empty( $dernierContrat ) && ( $decisionprecedente != 'N' ) ) {
				$this->updateAll(
					array( 'Contratinsertion.positioncer' => '\'fincontrat\'' ),
					array(
						'"Contratinsertion"."personne_id"' => $personne_id,
						'"Contratinsertion"."id"' => $dernierContrat['Contratinsertion']['id']
					)
				);
			}

			return $positioncer;
		}

		/**
		*   Liste des anciennes demandes d'ouverture de droit pour un allocataire
		*   TODO
		*/

		public function checkNumDemRsa( $personne_id ) {

			$personne = $this->Personne->find(
				'first',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'contain' => array(
						'Foyer' => array(
							'Dossier'
						)
					)
				)
			);
			$this->set( compact( 'personne' ) );

                        $nir13 = trim( $personne['Personne']['nir'] );
                        $nir13 = ( empty( $nir13 ) ? null : substr( $nir13, 0, 13 ) );

			$autreNumdemrsa = $this->Personne->Foyer->Dossier->find(
				'all',
				array(
					'fields' => array(
						'COUNT(DISTINCT "Dossier"."id") AS "count"'
					),
					'joins' => array(
                                            $this->Personne->Foyer->Dossier->join( 'Foyer', array( 'type' => 'INNER' ) ),
                                            $this->Personne->Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
                                            $this->Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
						/*array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
						),
						array(
							'table'      => 'personnes',
							'alias'      => 'Personne',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Personne.foyer_id = Foyer.id' )
						)*/
					),
					'conditions' => array(
                                                'Prestation.rolepers' => array( 'DEM', 'CJT' ),
						'OR' => array(
							array(
								'nir_correct13( Personne.nir  )',
								'nir_correct13( \''.$nir13.'\'  )',
								'SUBSTRING( TRIM( BOTH \' \' FROM Personne.nir ) FROM 1 FOR 13 )' => $nir13,
								'Personne.dtnai' => $personne['Personne']['dtnai']
							),
							array(
								'UPPER(Personne.nom)' => strtoupper( replace_accents( $personne['Personne']['nom'] ) ),
								'UPPER(Personne.prenom)' => strtoupper( replace_accents( $personne['Personne']['prenom'] ) ),
								'Personne.dtnai' => $personne['Personne']['dtnai']
							)
						)
					),
					'contain' => false,
					'recursive' => -1
				)
			);

			return $autreNumdemrsa[0][0]['count'];
		}

		/**
		* Vérifie l'intervalle, par-rapport à la date de fin d'un CER, en deçà duquel
		* un CER sera positionné « En cours:Bilan à réaliser » grâce au shell
		* positioncer66.
		*/

		public function checkConfigUpdateEncoursbilanCg66() {
			return $this->_checkSqlIntervalSyntax( Configure::read( 'Contratinsertion.Cg66.updateEncoursbilan' ) );
		}

		/**
		* Vérifie l'intervalle, entre la date de fin d'un CER et la date du jour, en deçà duquel
		* le CER sera détecté afin de retrouver les cERs arrivant à échéance
		*/

		public function checkConfigCriterecerDelaiavantecheance() {
			return $this->_checkSqlIntervalSyntax( Configure::read( 'Criterecer.delaiavanteecheance' ) );
		}

		public function checkPostgresqlIntervals() {
			$keys = array( 'Criterecer.delaiavanteecheance' );

			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$keys[] = 'Contratinsertion.Cg66.updateEncoursbilan';
			}

			return $this->_checkPostgresqlIntervals( $keys );
		}

		/**
		* Permet de récupérer le dernièr CER d'une personne
		*/

		public function sqDernierContrat( $personneIdFied = 'Personne.id' ) {
			return $this->sq(
				array(
					'fields' => array(
						'contratsinsertion.id'
					),
					'alias' => 'contratsinsertion',
					'conditions' => array(
						"contratsinsertion.personne_id = {$personneIdFied}"
					),
					'order' => array( 'contratsinsertion.dd_ci DESC' ),
					'limit' => 1
				)
			);
		}





		/**
		*	Récupération des Datas pour le stockage du PDF de la fiche de liaison du référent
		*	uniquement en cas de non validation du CER
		*/

		public function getPdfFicheliaisoncer( $contratinsertion_id ) {

			$queryData = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Structurereferente->fields(),
					array(
						'Referent.qual',
						'Referent.nom',
						'Referent.prenom',
						'Referent.numero_poste',
						'Referent.email',
						'Dossier.matricule',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Personne.dtnai',
						'Personne.typedtnai',
						'Personne.nir',
						'Personne.idassedic',
						'Personne.numfixe',
						'Personne.numport',
						'Personne.email',
						'Adresse.numvoie',
						'Adresse.typevoie',
						'Adresse.nomvoie',
						'Adresse.compladr',
						'Adresse.locaadr',
						'Adresse.numcomptt',
						'Adresse.codepos'
					)
				),
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Contratinsertion.personne_id = Personne.id" ),
					),
					array(
						'table'      => 'referents',
						'alias'      => 'Referent',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Referent.id = Contratinsertion.referent_id' ),
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Structurereferente',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Structurereferente.id = Contratinsertion.structurereferente_id' ),
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
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							'Adressefoyer.id IN (
								'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
							)'
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					)
				),
				'conditions' => array(
					'Contratinsertion.id' => $contratinsertion_id
				),
				'recursive' => -1
			);

			$options = array(
				'Referent' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ),
				'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ),
				'Adresse' => array( 'typevoie' => ClassRegistry::init( 'Option' )->typevoie() ),
				'type' => array( 'voie' => ClassRegistry::init( 'Option' )->typevoie() )
			);
			$options = Set::merge( $options, $this->enums() );


			$contratinsertion = $this->find( 'first', $queryData );
			$referents = $this->Referent->find( 'list' );


			$forme_ci = array( 'S' => 'Simple', 'C' => 'Particulier' );
			$formeci = Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' );

			$typestructure = Set::classicExtract( $contratinsertion, 'Structurereferente.typestructure' );


			if( $formeci == 'C' ) {
				$modeleodt = "Contratinsertion/ficheliaisoncerParticulier.odt";
			}
			else{
				$modeleodt = "Contratinsertion/ficheliaisoncerSimple{$typestructure}.odt";
			}

			return $this->ged(
				array( $contratinsertion ),
				$modeleodt,
				true,
				$options
			);
		}


		/**
		*	Récupération des Datas pour le stockage du PDF de la notification au bénéficiaire
		*/

		public function getPdfNotifbenef( $contratinsertion_id, $user_id ) {

			$queryData = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Structurereferente->fields(),
					array(
						'Referent.qual',
						'Referent.nom',
						'Referent.prenom',
						'Referent.numero_poste',
						'Referent.email',
						'Dossier.matricule',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Personne.dtnai',
						'Personne.typedtnai',
						'Personne.nir',
						'Personne.idassedic',
						'Personne.numfixe',
						'Personne.numport',
						'Personne.email',
						'Adresse.numvoie',
						'Adresse.typevoie',
						'Adresse.nomvoie',
						'Adresse.compladr',
						'Adresse.locaadr',
						'Adresse.numcomptt',
						'Adresse.codepos'
					)
				),
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Contratinsertion.personne_id = Personne.id" ),
					),
					array(
						'table'      => 'referents',
						'alias'      => 'Referent',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Referent.id = Contratinsertion.referent_id' ),
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Structurereferente',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Structurereferente.id = Contratinsertion.structurereferente_id' ),
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
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							'Adressefoyer.id IN (
								'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
							)'
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					)
				),
				'conditions' => array(
					'Contratinsertion.id' => $contratinsertion_id
				),
				'recursive' => -1
			);

			$options = array(
				'Referent' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ),
				'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ),
				'duree' => array( 'engag' => ClassRegistry::init( 'Option' )->duree_engag_cg66() ),
				'Adresse' => array( 'typevoie' => ClassRegistry::init( 'Option' )->typevoie() ),
				'type' => array( 'voie' => ClassRegistry::init( 'Option' )->typevoie() )
			);


			$options = Set::merge( $options, $this->enums() );


			$contratinsertion = $this->find( 'first', $queryData );
			$referents = $this->Referent->find( 'list' );

			$datesaisici = Set::classicExtract( $contratinsertion, 'Contratinsertion.date_saisi_ci' );
			$contratinsertion['Contratinsertion']['delairegularisation'] = date( 'Y-m-d', strtotime( '+1 month', strtotime( $datesaisici ) ) );

			$user = $this->User->find(
				'first',
				array(
					'fields' => array( 'numtel', 'nom', 'prenom' ),
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => false
				)
			);
			$contratinsertion['User']['numtel'] = $user['User']['numtel'];
			$contratinsertion['User']['nom'] = $user['User']['nom'];
			$contratinsertion['User']['prenom'] = $user['User']['prenom'];


			$modelenotifdecision = '';
			$decision = Set::classicExtract( $contratinsertion, 'Contratinsertion.decision_ci' );

			if( !empty( $decision ) ) {
				if( $decision == 'V' ) {
					$modelenotifdecision = "valide";
				}
				else if( $decision == 'N' ) {
					$modelenotifdecision = "nonvalide";
				}
			}


			$forme_ci = array( 'S' => 'Simple', 'C' => 'Particulier' );
			$formeci = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' ), $forme_ci );


			return $this->ged(
				array( $contratinsertion ),
				"Contratinsertion/notifbenef{$formeci}{$modelenotifdecision}.odt",
				true,
				$options
			);
		}

		/**
		 *
		 * @param array $data
		 * @return string
		 */
		public function modeleOdt( $data ) {
			$cgDepartement = Configure::read( 'Cg.departement' );

			if( $cgDepartement == 58 ) {
				return 'Contratinsertion/contratinsertioncg58.odt';
			}
			else if ( $cgDepartement == 66 ) {
				if( strtotime($data['Contratinsertion']['date_saisi_ci']) >= strtotime('2012-02-10' ) ) {
					return 'Contratinsertion/contratinsertion.odt';
				}
				else {
					return 'Contratinsertion/contratinsertionold.odt';
				}
			}

			return 'Contratinsertion/contratinsertion.odt';
		}

		/**
		 * TODO: Structurereferente.parent_id, detaildroitrsa_oridemrsa, personnereferent
		 * User.id <=> Connecté ou non ?
		 * FIXME: detailsdroitsrsa.dossier_id a l'air d'e pouvoir'être unique, rajouter un index unique.
		 *
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf( $id, $user_id ) {
			$contratinsertion = $this->find(
				'first',
				array(
					'fields' => array_merge(
						$this->fields(),
						$this->Personne->fields(),
						$this->Referent->fields(),
						$this->Structurereferente->fields(),
						$this->Typocontrat->fields(),
						$this->Zonegeographique->fields(),
						$this->Personne->Activite->fields(),
						$this->Personne->Dsp->fields(),
						$this->Personne->Foyer->fields(),
						$this->Personne->Prestation->fields(),
						$this->Personne->Foyer->Dossier->fields(),
						$this->Personne->Foyer->Modecontact->fields(),
						$this->Personne->Foyer->Adressefoyer->Adresse->fields(),
						$this->Personne->Foyer->Dossier->Detaildroitrsa->fields(),
//						$this->Personne->Foyer->Dossier->Infofinanciere->fields(),
						$this->Personne->Foyer->Dossier->Situationdossierrsa->fields(),
						$this->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->fields(),
						array(
							'( '.$this->Personne->Foyer->Dossier->Detaildroitrsa->vfRsaMajore( '"Dossier"."id"' ).' ) AS "Infofinanciere__rsamaj"'
						)
					),
					'joins' => array(
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
						$this->join( 'Typocontrat', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Zonegeographique', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join( 'Activite', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join( 'Dsp', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->join( 'Prestation', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Modecontact', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->Dossier->join( 'Detaildroitrsa', array( 'type' => 'LEFT OUTER' ) ),
//						$this->Personne->Foyer->Dossier->join( 'Infofinanciere', array( 'type' => 'LEFT OUTER' ) ), // FIXME: laquelle ? -> à Virer et à remplacer par Detailcalculdroitrsa->vfRsaMajore
						$this->Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->Dossier->Situationdossierrsa->join( 'Suspensiondroit', array( 'type' => 'LEFT OUTER' ) ),
					),
					'contain' => false,
					'conditions' => array(
						'Contratinsertion.id' => $id,
						array(
							'OR' => array(
								'Activite.id IS NULL',
								'Activite.id IN ('
									.$this->Personne->Activite->sqDerniere( 'Personne.id' )
								.')',
							)
						),
						array(
							'OR' => array(
								'Adressefoyer.id IS NULL',
								'Adressefoyer.id IN ('
									.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' )
								.')',
							)
						),
						array(
							'OR' => array(
								'Dsp.id IS NULL',
								'Dsp.id IN ('
									.$this->Personne->Dsp->sqDerniereDsp( 'Personne.id' )
								.')',
							)
						),
						array(
							'OR' => array(
								'Suspensiondroit.id IS NULL',
								'Suspensiondroit.id IN ('
									.$this->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->sqDerniere( 'Situationdossierrsa.id' )
								.')',
							)
						)
					),
				)
			);

			// Recherche, traduction et ajout de champs virtuels concernant Autreavissuspension et Autreavisradiation
			$Option = ClassRegistry::init( 'Option' );
			$options = Set::merge(
				array(
					'Contratinsertion' => array(
						'decision_ci' => $Option->decision_ci(),
					)
				),
				$this->Autreavisradiation->enums(),
				$this->Autreavissuspension->enums()
			);

			$autresModeles = array( 'Autreavissuspension', 'Autreavisradiation' );
			foreach( $autresModeles as $autreModele ) {
				$fieldName = Inflector::underscore( $autreModele );
				$contratinsertion['Contratinsertion'][$fieldName] = '';
				if( isset( $contratinsertion['Contratinsertion']['id'] ) && !empty( $contratinsertion['Contratinsertion']['id'] ) ) {
					$items = $this->{$autreModele}->find(
						'all',
						array(
							"{$autreModele}.contratinsertion_id" => $contratinsertion['Contratinsertion']['id'],
							'contain' => false
						)
					);
					if( !empty( $items ) ) {
						$values = Set::extract( $items, "/{$autreModele}/{$fieldName}" );
						foreach( $values as $i => $value ) {
							$values[$i] = Set::enum( $value, $options[$autreModele][$fieldName] );
						}
						$contratinsertion['Contratinsertion'][$fieldName] = "\n" .'  - '.implode( "\n  - ", $values )."\n";
					}
				}
			}

			// Données Référent lié à la personne récupérées
			if( empty( $contratinsertion['Contratinsertion']['referent_id'] ) ) {
				// 1°) Une personne désignée comme référent, juste avant la date de début du contrat ,dans la même structure
				$personne_referent = $this->Personne->PersonneReferent->find(
					'first',
					array(
						'fields' => Set::merge(
							$this->Personne->PersonneReferent->fields(),
							$this->Personne->PersonneReferent->Referent->fields()
						),
						'conditions' => array(
							'PersonneReferent.personne_id' => $contratinsertion['Personne']['id'],
							'PersonneReferent.structurereferente_id' => $contratinsertion['Contratinsertion']['structurereferente_id'],
							'PersonneReferent.dddesignation <=' => $contratinsertion['Contratinsertion']['dd_ci'],
							'PersonneReferent.id IN ('
								.$this->Personne->PersonneReferent->sq(
									array(
										'alias' => 'personnes_referents',
										'fields' =>array( 'personnes_referents.id' ),
										'conditions' => array(
											'personnes_referents.personne_id = PersonneReferent.personne_id',
											'personnes_referents.dddesignation <=' => $contratinsertion['Contratinsertion']['dd_ci'],
										),
										'order' => array( 'personnes_referents.dddesignation DESC' ),
										'limit' => 1
									)
								)
							.')'
						),
						'contain' => false,
						'joins' => array(
							$this->Personne->PersonneReferent->join( 'Referent', array( 'type' => 'LEFT OUTER' ) )
						)
					)
				);

				// 2°) Une personne référente (n'importe laquelle) liée à la personne (comme avant)
				if( empty( $personne_referent ) ) {
					$personne_referent = $this->Personne->PersonneReferent->find(
						'first',
						array(
							'fields' => Set::merge(
								$this->Personne->PersonneReferent->fields(),
								$this->Personne->PersonneReferent->Referent->fields()
							),
							'conditions' => array(
								'PersonneReferent.personne_id' => $contratinsertion['Personne']['id'],
							),
							'contain' => false,
							'joins' => array(
								$this->Personne->PersonneReferent->join( 'Referent', array( 'type' => 'LEFT OUTER' ) )
							)
						)
					);
				}

				// 3°) Un référent (actif, sinon n'importe lequel) lié à la structure de mon contrat
				if( empty( $personne_referent ) ) {
					$personne_referent = $this->Personne->PersonneReferent->Referent->find(
						'first',
						array(
							'conditions' => array(
								'Referent.actif' => 'O',
								'Referent.structurereferente_id' => $contratinsertion['Contratinsertion']['structurereferente_id'],
							),
							'contain' => false,
						)
					);
				}

				if( !empty( $personne_referent ) ) {
					$contratinsertion = Set::merge( $contratinsertion, $personne_referent );
				}
			}

			// Traductions restantes (avec création de champs virtuels)
			$contratinsertion['Contratinsertion']['tc'] = $contratinsertion['Typocontrat']['lib_typo'];
			$contratinsertion['Contratinsertion']['datevalidation_ci'] = strftime( __( 'Locale->date', true ), strtotime( $contratinsertion['Contratinsertion']['datevalidation_ci'] ) );
			$contratinsertion['Contratinsertion']['actions_prev'] = ( $contratinsertion['Contratinsertion']['actions_prev']  ? 'Oui' : 'Non' );
			$contratinsertion['Contratinsertion']['emp_trouv'] = ( $contratinsertion['Contratinsertion']['emp_trouv']  ? 'Oui' : 'Non' );

			// Données Dsp récupérées
			$contratinsertion['Dsp']['topcouvsoc'] = ( ( isset( $contratinsertion['Dsp']['topcouvsoc'] ) && ( $contratinsertion['Dsp']['topcouvsoc'] == '1' ) ) ? 'Oui' : 'Non' );

			// Ajout suite à la demande d'amélioration du CG66 sur la Forge du 02/02/2012 (#5578)
			$contratinsertion['Contratinsertion']['type_deci']=$contratinsertion['Contratinsertion']['decision_ci'];

			// Affichage de la date seulement en cas de " Validation à compter de "
			if( $contratinsertion['Contratinsertion']['decision_ci'] == 'V' ){
				$contratinsertion['Contratinsertion']['decision_ci'] = "{$options['Contratinsertion']['decision_ci'][$contratinsertion['Contratinsertion']['decision_ci']]} {$contratinsertion['Contratinsertion']['datevalidation_ci']}";
			}
			else{
				$contratinsertion['Contratinsertion']['decision_ci'] = $options['Contratinsertion']['decision_ci'][$contratinsertion['Contratinsertion']['decision_ci']];
			}

			// L'objet de l'engagement est un code à traduire pour le CG 93
			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$options['Contratinsertion']['engag_object'] = ClassRegistry::init( 'Action' )->find( 'list', array( 'fields' => array( 'code', 'libelle' ), 'contain' => false ) );
				$contratinsertion['Contratinsertion']['engag_object'] = @$options['Contratinsertion']['engag_object'][$contratinsertion['Contratinsertion']['engag_object']];
			}

			///Utilisé pour savoir si le contrat est pour une insertion vers le social / emploi
			if( $contratinsertion['Contratinsertion']['typeinsertion'] == 'SOC' ){
				$contratinsertion['Contratinsertion']['issociale'] = 'X';
			}
			else if( $contratinsertion['Contratinsertion']['typeinsertion'] == 'EMP' ){
				$contratinsertion['Contratinsertion']['isemploi'] = 'X';
			}

			///Utilisé pour savoir si la personne est demandeur ou ayant droit
			if( $contratinsertion['Prestation']['rolepers'] == 'Demandeur du RSA' ){
				$contratinsertion['Contratinsertion']['isallocataire'] = 'X';
			}
			else if( $contratinsertion['Prestation']['rolepers'] != 'Demandeur du RSA' ){
				$contratinsertion['Contratinsertion']['isayantdroit'] = 'X';
			}

			///Utilisé pour savoir si la personne est demandeur ou ayant droit
			if( $contratinsertion['Contratinsertion']['num_contrat'] == 'PRE' ){
				$contratinsertion['Contratinsertion']['ispremier'] = 'X';
			}
			else if( $contratinsertion['Contratinsertion']['num_contrat'] == 'REN' ){
				$contratinsertion['Contratinsertion']['isrenouvel'] = 'X';
			}
			else if( !in_array( $contratinsertion['Contratinsertion']['num_contrat'], array( 'REN', 'PRE' ) ) ){
				$contratinsertion['Contratinsertion']['isavenant'] = 'X';
			}

			// Permet d'afficher le nom de la zone géographique liée au contrat du cg58
			$contratinsertion['Contratinsertion']['zonegeographique_id'] = $contratinsertion['Zonegeographique']['libelle'];

			///Permet d'afficher le nb d'ouverture de droit de la personne
			$contratinsertion['Contratinsertion']['nbouv'] = $this->checkNumDemRsa( $contratinsertion['Personne']['id'] );

			$contratinsertion['Contratinsertion']['rg_ci'] = $contratinsertion['Contratinsertion']['rg_ci'] - 1;

			// Récupération de l'utilisateur connecté
			$user = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => array(
						'Serviceinstructeur'
					)
				)
			);
			$contratinsertion = Set::merge( $contratinsertion, $user );

			return $contratinsertion;
		}
	}
?>