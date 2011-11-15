<?php
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
			'Gedooo'
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
			)/*,
			'Objetcontratprecedent' => array(
				'className' => 'Objetcontratprecedent',
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

			// Si la nature du contrat de travail n'est pas un CDD, alors il faut éventuellement vider la valeur de la durée du CDD
			if( isset( $this->data[$this->name]['nat_cont_trav'] ) && $this->data[$this->name]['nat_cont_trav'] != 'TCT3' ){
				$this->data[$this->name]['duree_cdd'] = NULL;
			}

			//  Calcul de la position du cER
			if( Configure::read( 'Cg.departement' ) == '66' ) {
				$this->data[$this->alias]['positioncer'] = $this->_calculPosition( $this->data );
			}

			return $return;
		}

		/**
		*
		*/

		public function valider( $data ) {
			$this->begin();
			$success = $this->saveAll( $data, array( 'atomic' => false ) );
			// Sortie de la procédure de relances / sanctions 93 en cas de validation d'un nouveau contrat
			if( $success && Configure::read( 'Cg.departement' ) == '93' && isset( $data[$this->alias]['decision_ci'] ) && $data[$this->alias]['decision_ci'] == 'V' ) {
				$nonrespectssanctionseps93 = $this->Nonrespectsanctionep93->find(
					'all',
					array(
						'fields' => array(
							'Nonrespectsanctionep93.id'
						),
						'conditions' => array(
							'Nonrespectsanctionep93.dossierep_id IS NULL',
							'Nonrespectsanctionep93.sortienvcontrat <>' => '1',
							'Nonrespectsanctionep93.active' => '1',
							'Nonrespectsanctionep93.created <' => "{$data['Contratinsertion']['datevalidation_ci']['year']}-{$data['Contratinsertion']['datevalidation_ci']['month']}-{$data['Contratinsertion']['datevalidation_ci']['day']}",
							'OR' => array(
								'Nonrespectsanctionep93.propopdo_id IN (
									SELECT propospdos.id
										FROM propospdos
										WHERE propospdos.personne_id = \''.$data['Contratinsertion']['personne_id'].'\'
								)',
								'Nonrespectsanctionep93.orientstruct_id IN (
									SELECT orientsstructs.id
										FROM orientsstructs
										WHERE orientsstructs.personne_id = \''.$data['Contratinsertion']['personne_id'].'\'
								)',
								'Nonrespectsanctionep93.contratinsertion_id IN (
									SELECT contratsinsertion.id
										FROM contratsinsertion
										WHERE contratsinsertion.personne_id = \''.$data['Contratinsertion']['personne_id'].'\'
								)',
							)
						),
					)
				);

				if( !empty( $nonrespectssanctionseps93 ) ) {
					$ids = Set::extract( $nonrespectssanctionseps93, '/Nonrespectsanctionep93/id' );

					$success = $this->Nonrespectsanctionep93->updateAll(
						array(
							'"Nonrespectsanctionep93"."sortienvcontrat"' => '\'1\'',
							'"Nonrespectsanctionep93"."active"' => '\'0\''
						),
						array( '"Nonrespectsanctionep93"."id"' => $ids )
					) && $success;
				}
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

		protected function _calculPosition( $data ) {
			$formeCi = Set::classicExtract( $data, 'Contratinsertion.forme_ci' );
			$sitproCi = Set::classicExtract( $data, 'Contratinsertion.sitpro_ci' );

			$personne_id = Set::classicExtract( $data, 'Contratinsertion.personne_id' );
			$dernierContrat = $this->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne_id
					),
					'order' => 'Contratinsertion.rg_ci DESC',
					'contain' => false
				)
			);

			$positioncer = null;
			// 'encours', 'attvalid', 'annule', 'fincontrat', 'encoursbilan', 'attrenouv', 'perime'

			if ( $formeCi == 'S' )
				$positioncer = 'attvalid';
			elseif ( $formeCi == 'C' )
				$positioncer = 'attvalid';
			elseif ( !empty( $sitproCi ) )
				$positioncer = 'annule';

			if( !empty( $dernierContrat ) ) {
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

			$autreNumdemrsa = $this->Personne->Foyer->Dossier->find(
				'all',
				array(
					'fields' => array(
						'COUNT(DISTINCT "Dossier"."id") AS "count"'
					),
					'joins' => array(
						array(
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
						)
					),
					'conditions' => array(
						'OR' => array(
							array(
								'Personne.nir' => $personne['Personne']['nir'],
								//FIXME
								'nir_correct( Personne.nir  ) = true',
								'Personne.nir IS NOT NULL',
								'Personne.dtnai' => $personne['Personne']['dtnai']
							),
							array(
								'Personne.nom' => $personne['Personne']['nom'],
								'Personne.prenom' => $personne['Personne']['prenom'],
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
	}
?>