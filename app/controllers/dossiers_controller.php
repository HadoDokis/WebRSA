<?php
	/**
	 * Fichier source de la classe DossiersController.
	 *
	 * PHP 5.3
	 *
	 * @package       app.controllers
	 */
	App::import( 'Sanitize' );

	class DossiersController extends AppController
	{
		public $name = 'Dossiers';

		public $uses = array( 'Dossier', 'Option', 'Informationpe' );
		public $helpers = array( 'Csv' , 'Search', 'Default2', 'Gestionanomaliebdd' );
		public $components = array( 'Gestionzonesgeos', 'Prg2' => array( 'actions' => array( 'index' ) ), 'Jetons2' );

		public $aucunDroit = array( 'menu' );

		public $commeDroit = array( 'view' => 'Dossiers:index' );

		/**
		 * @return void
		 */
		protected function _setOptions() {
			$this->set( 'natpf', $this->Option->natpf() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'decision_ci', $this->Option->decision_ci() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'natfingro', $this->Option->natfingro() );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'statudemrsa', $this->Option->statudemrsa() );
			$this->set( 'moticlorsa', $this->Option->moticlorsa() );
			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa(true) );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'sitfam', $this->Option->sitfam() );
			$this->set( 'act', $this->Option->act() );
			$this->set( 'couvsoc', $this->Option->couvsoc() ); // INFO: pas dans view
			$this->set( 'categorie', $this->Option->categorie() );
			$this->set(
				'trancheage',
				array(
					'< 25',
					'25 - 30',
					'31 - 55',
					'56 - 65',
					'> 65'
				)
			);

			// à intégrer à la fonction view pour ne pas avoir d'énormes variables
			if( $this->action == 'view' ) {
				$this->set( 'numcontrat', $this->Dossier->Foyer->Personne->Contratinsertion->allEnumLists() );
				$this->set( 'enumcui', $this->Dossier->Foyer->Personne->Cui->allEnumLists() );
				$this->set( 'etatpe', $this->Informationpe->Historiqueetatpe->allEnumLists() );
				$this->set( 'relance', $this->Dossier->Foyer->Personne->Orientstruct->Nonrespectsanctionep93->allEnumLists() );
				$this->set( 'dossierep', $this->Dossier->Foyer->Personne->Dossierep->allEnumLists() );
				$this->set( 'options', $this->Dossier->Foyer->Personne->Orientstruct->enums() );
			}
			else if( $this->action == 'exportcsv' ) {
				$typesorient = $this->Dossier->Foyer->Personne->Orientstruct->Typeorient->find( 'list', array( 'fields' => array( 'id', 'lib_type_orient' ) ) );
				$this->set( 'typesorient', $typesorient );
			}
			else if( $this->action == 'index' ) {
				$this->set( 'typeservice', $this->Dossier->Foyer->Personne->Orientstruct->Serviceinstructeur->listOptions() );

				$referents = $this->Dossier->Foyer->Personne->PersonneReferent->Referent->find( 'list', array( 'order' => array( 'Referent.nom' ) ) );
				$this->set( compact( 'referents') );
			}
			else if( $this->action == 'edit' ) {
				$optionsDossier = array(
					'Dossier' => array(
						'statudemrsa' => $this->Option->statudemrsa(),
						'fonorgcedmut' => $this->Option->fonorgcedmut(),
						'fonorgprenmut' => $this->Option->fonorgprenmut()
					)
				);
				$this->set( 'optionsDossier', $optionsDossier );
			}
			$this->set( 'fonorg', array( 'CAF' => 'CAF', 'MSA' => 'MSA' ) );

		}

		/**
		 * Les action index et exportcsv peuvent être consommatrices, donc on augmeta la mémoire
		 * maximale et le temps d'exécution maximal du script PHP.
		 *
		 * @return voir
		 */
		public function beforeFilter() {
			if( in_array( $this->action, array( 'index', 'exportcsv' ) ) ) {
				ini_set( 'max_execution_time', 0 );
				ini_set( 'memory_limit', '512M' );
			}
			parent::beforeFilter();
		}

		/**
		 * Moteur de recherche par dossier/allocataire
		 *
		 * @return void
		 */
		public function index() {
			$this->Gestionzonesgeos->setCantonsIfConfigured();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->data ) ) {
				$paginate = $this->Dossier->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );
				$paginate = $this->_qdAddFilters( $paginate );
				$paginate['fields'][] = $this->Jetons2->sqLocked( 'Dossier', 'locked' );

				$this->paginate = $paginate;
				$dossiers = $this->paginate( 'Dossier' );

				$this->set( 'dossiers', $dossiers );
			}
			else {
				$filtresdefaut = Configure::read( "Filtresdefaut.{$this->name}_{$this->action}" );
				$this->data = Set::merge( $this->data, $filtresdefaut );
			}

			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			$this->_setOptions();
		}

		/**
		 * Retourne les données permettant de peupler le menu d'un dossier.
		 * Doit être systématiquement utilisé via un requestAction.
		 *
		 * @return array
		 */
		public function menu() {
			$this->assert( isset( $this->params['requested'] ), 'error404' );

			// Quel paramètre avons-nous pour trouver le bon dossier ?
			$conditions = array();
			if( !empty( $this->params['id'] ) && is_numeric( $this->params['id'] ) ) {
				$conditions['Dossier.id'] = $this->params['id'];
			}
			else if( !empty( $this->params['foyer_id'] ) && is_numeric( $this->params['foyer_id'] ) ) {
				$conditions['Foyer.id'] = $this->params['foyer_id'];
			}
			else if( !empty( $this->params['personne_id'] ) && is_numeric( $this->params['personne_id'] ) ) {
				$conditions['Dossier.id'] = $this->Dossier->Foyer->Personne->dossierId( $this->params['personne_id'] );
			}
			$this->assert( !empty( $conditions ), 'invalidParameter' );

			// Données du dossier RSA.
			$dossier = $this->Dossier->find(
				'first',
				array(
					'fields' => array(
						'Dossier.id',
						'Dossier.matricule',
						'Dossier.fonorg',
						'Dossier.numdemrsa',
						'Foyer.id',
						$this->Dossier->Foyer->sqVirtualField( 'enerreur' ),
						$this->Dossier->Foyer->sqVirtualField( 'sansprestation' ),
						'Situationdossierrsa.etatdosrsa',
						$this->Jetons2->sqLocked( 'Dossier', 'locked' )
					),
					'contain' => array(
						'Foyer',
						'Situationdossierrsa'
					),
					'conditions' => $conditions
				)
			);

			// Les personnes du foyer
			$personnes = $this->Dossier->Foyer->Personne->find(
				'all',
				array(
					'fields' => array(
						'Personne.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Prestation.rolepers'
					),
					'conditions' => array(
						'Personne.foyer_id' => Set::classicExtract( $dossier, 'Foyer.id' ),
						'Prestation.natprest' => 'RSA'
					),
					'contain' => array(
						'Prestation'
					),
					'order' => array(
						'( CASE WHEN Prestation.rolepers = \'DEM\' THEN 0 WHEN Prestation.rolepers = \'CJT\' THEN 1 WHEN Prestation.rolepers = \'ENF\' THEN 2 ELSE 3 END ) ASC',
						'Personne.nom ASC',
						'Personne.prenom ASC'
					)
				)
			);

			// Reformattage pour la vue
			$dossier['Foyer']['Personne'] = Set::classicExtract( $personnes, '{n}.Personne' );
			foreach( Set::classicExtract( $personnes, '{n}.Prestation' ) as $i => $prestation ) {
				$dossier['Foyer']['Personne'] = Set::insert( $dossier['Foyer']['Personne'], "{$i}.Prestation", $prestation );
			}

			return $dossier;
		}

		/**
		 * Visualisation du dossier (écran de synthèse).
		 *
		 * @param integer $id
		 * @return void
		 */
		public function view( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$details = array();
			$details = $this->Dossier->find(
				'first',
				array(
					'fields' => array(
						'Dossier.id',
						'Dossier.matricule',
						'Dossier.numdemrsa',
						'Dossier.statudemrsa',
						'Dossier.dtdemrsa',
						'Foyer.id',
						'Foyer.sitfam',
						'Situationdossierrsa.id',
						'Situationdossierrsa.dtclorsa',
						'Situationdossierrsa.etatdosrsa',
						'Situationdossierrsa.moticlorsa',
					),
					'contain' => array(
						'Foyer',
						'Situationdossierrsa'
					),
					'conditions' => array(
						'Dossier.id' => $id
					)
				)
			);

			// Dernière créance
			$tCreance = $this->Dossier->Foyer->Creance->find(
				'first',
				array(
					'fields' => array(
						'Creance.motiindu'
					),
					'contain' => false,
					'conditions' => array(
						'Creance.foyer_id' => $details['Foyer']['id']
					),
					'order' => array(
						'Creance.dtdercredcretrans DESC',
					),
				)
			);
			$details = Set::merge( $details, $tCreance );

			$tDetaildroitrsa = $this->Dossier->Detaildroitrsa->find(
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
							'order' => array(
								'Detailcalculdroitrsa.ddnatdro DESC',
							),
							'limit' => 1
						)
					),
					'conditions' => array(
						'Detaildroitrsa.dossier_id' => $id
					)
				)
			);
			$details = Set::merge( $details, $tDetaildroitrsa );

			// Dernier suivi d'instruction
			$tSuiviinstruction = $this->Dossier->Suiviinstruction->find(
				'first',
				array(
					'fields' => array(
						'Suiviinstruction.typeserins'
					),
					'conditions' => array(
						'Suiviinstruction.dossier_id' => $id
					),
					'contain' => false,
					'order' => array(
						'Suiviinstruction.date_etat_instruction DESC'
					)
				)
			);
			$details = Set::merge( $details, $tSuiviinstruction );

			// Dernière info financière
			$tInfofinanciere = $this->Dossier->Infofinanciere->find(
				'first',
				array(
					'fields' => array(
						'Infofinanciere.mtmoucompta'
					),
					'conditions' => array(
						'Infofinanciere.dossier_id' => $id,
						'Infofinanciere.type_allocation' => 'IndusConstates'
					),
					'contain' => false,
					'order' => array( 'Infofinanciere.moismoucompta DESC' )
				)
			);
			$details = Set::merge( $details, $tInfofinanciere );

			// Dernière adresse foyer
			$adresseFoyer = $this->Dossier->Foyer->Adressefoyer->find(
				'first',
				array(
					'fields' => array(
						'Adressefoyer.id'
					),
					'conditions' => array(
						'Adressefoyer.foyer_id' => $details['Foyer']['id'],
						'Adressefoyer.rgadr'    => '01'
					),
					'order' => array( 'Adressefoyer.dtemm DESC' ),
					'contain' => array(
						'Adresse' => array(
							'fields' => array(
								'Adresse.numvoie',
								'Adresse.typevoie',
								'Adresse.nomvoie',
								'Adresse.locaadr',
							)
						)
					)
				)
			);
			$details = Set::merge( $details, array( 'Adresse' => $adresseFoyer['Adresse'] ) );

			// Personnes
			$personnesFoyer = $this->Dossier->Foyer->Personne->find(
				'all',
				array(
					'fields' => array(
						'Personne.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Personne.sexe',
						'Personne.dtnai',
						'Personne.nir',
						'Dsp.id',
						'Activite.act',
						'Dossiercaf.ddratdos',
						'Dossiercaf.dfratdos',
						'Calculdroitrsa.toppersdrodevorsa',
						'Prestation.rolepers',
						'Grossesse.ddgro',
						'Grossesse.dfgro',
						'Grossesse.dtdeclgro',
						'Grossesse.natfingro'
					),
					'conditions' => array(
						'Personne.foyer_id' => $details['Foyer']['id'],
						'Prestation.natprest' => 'RSA',
						'Prestation.rolepers' => array( 'DEM', 'CJT' ),
						'OR' => array(
							'Activite.id IS NULL',
							'Activite.id IN ('
								.$this->Dossier->Foyer->Personne->Activite->sq(
									array(
										'alias' => 'activites',
										'fields' => array( 'activites.id' ),
										'conditions' => array( 'activites.personne_id = Personne.id' ),
										'order' => array( 'activites.ddact DESC' ),
										'limit' => 1
									)
								)
							.')'
						)
					),
					'joins' => array(
						$this->Dossier->Foyer->Personne->join( 'Prestation' ),
						$this->Dossier->Foyer->Personne->join( 'Dossiercaf' ),
						$this->Dossier->Foyer->Personne->join( 'Dsp' ),
						$this->Dossier->Foyer->Personne->join( 'Calculdroitrsa' ),
						$this->Dossier->Foyer->Personne->join( 'Activite', array( 'type' => 'LEFT OUTER' ) ),
						$this->Dossier->Foyer->Personne->join( 'Grossesse', array( 'type' => 'LEFT OUTER' ) )
					),
					'contain' => false,
					'recursive' => -1
				)
			);

			$optionsep = array(
				'Passagecommissionep' => $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->allEnumLists()
			);
			$roles = Set::extract( '{n}.Prestation.rolepers', $personnesFoyer );
			foreach( $roles as $index => $role ) {
				$tPersReferent = $this->Dossier->Foyer->Personne->PersonneReferent->find(
					'first',
					array(
						'fields' => array(
							'Referent.id',
							'Referent.qual',
							'Referent.nom',
							'Referent.prenom'
						),
						'contain' => array(
							'Referent'
						),
						'conditions' => array( 'PersonneReferent.personne_id' => $personnesFoyer[$index]['Personne']['id'], 'PersonneReferent.dfdesignation IS NULL' ),
						'order' => array( 'PersonneReferent.dddesignation DESC' )
					)
				);
				$personnesFoyer[$index]['Referent'] = $tPersReferent['Referent'];

				$tContratinsertion = $this->Dossier->Foyer->Personne->Contratinsertion->find(
					'first',
					array(
						'fields' => array(
							'Contratinsertion.dd_ci',
							'Contratinsertion.df_ci',
							'Contratinsertion.num_contrat',
							'Contratinsertion.decision_ci',
							'Contratinsertion.datevalidation_ci'
						),
						'conditions' => array( 'Contratinsertion.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
						'contain' => false,
						'order' => array( 'Contratinsertion.rg_ci DESC' )
					)
				);
				$personnesFoyer[$index]['Contratinsertion'] = $tContratinsertion['Contratinsertion'];

				$tCui = $this->Dossier->Foyer->Personne->Cui->find(
					'first',
					array(
						'fields' => array(
							'Cui.secteur',
							'Cui.datecontrat',
							'Cui.decisioncui',
							'Cui.datevalidationcui'
						),
						'conditions' => array( 'Cui.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
						'contain' => false,
						'order' => array( 'Cui.datecontrat DESC' )
					)
				);
				$personnesFoyer[$index]['Cui'] = $tCui['Cui'];

				// Dernière orientation
				$tOrientstruct = $this->Dossier->Foyer->Personne->Orientstruct->find(
					'first',
					array(
						'fields' => array(
							'Orientstruct.origine',
							'Orientstruct.date_valid',
							'Orientstruct.statut_orient',
							'Orientstruct.referent_id',
							'Orientstruct.rgorient',
							'Typeorient.lib_type_orient',
							'Structurereferente.lib_struc'

						),
						'contain' => array(
							'Typeorient',
							'Structurereferente'
						),
						'conditions' => array(
							'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => "Orientstruct.date_valid DESC",
					)
				);
				$personnesFoyer[$index]['Orientstruct']['derniere'] = $tOrientstruct;


				// Dernière relance effective
				$tRelance = $this->Dossier->Foyer->Personne->Contratinsertion->Nonrespectsanctionep93->Relancenonrespectsanctionep93->find(
					'first',
					array(
						'fields' => array(
							'Nonrespectsanctionep93.created',
							'Nonrespectsanctionep93.origine',
							'Nonrespectsanctionep93.rgpassage',
							'Relancenonrespectsanctionep93.daterelance',
							'Relancenonrespectsanctionep93.numrelance'
						),
						'contain' => false,
						'joins' => array(
							array(
								'table'      => 'nonrespectssanctionseps93',
								'alias'      => 'Nonrespectsanctionep93',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( 'Nonrespectsanctionep93.id = Relancenonrespectsanctionep93.nonrespectsanctionep93_id' )
							),
							array(
								'table'      => 'orientsstructs',
								'alias'      => 'Orientstruct',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array( 'Orientstruct.id = Nonrespectsanctionep93.orientstruct_id' )
							),
							array(
								'table'      => 'contratsinsertion',
								'alias'      => 'Contratinsertion',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array( 'Contratinsertion.id = Nonrespectsanctionep93.contratinsertion_id' )
							),
							array(
								'table'      => 'personnes',
								'alias'      => 'Personne',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array(
									'OR' => array(
										array(
											'Contratinsertion.personne_id = Personne.id'
										),
										array(
											'Orientstruct.personne_id = Personne.id'
										)
									)
								)
							)
						),
						'conditions' => array(
							'OR' => array(
								array(
									'Nonrespectsanctionep93.orientstruct_id IN ( '.$this->Dossier->Foyer->Personne->Orientstruct->sq( array( 'fields' => array( 'Orientstruct.id' ), 'conditions' => array( 'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id'] ) ) ).' )'
								),
								array(
									'Nonrespectsanctionep93.contratinsertion_id IN ( '.$this->Dossier->Foyer->Personne->Contratinsertion->sq( array( 'fields' => array( 'id' ), 'conditions' => array( 'personne_id' => $personnesFoyer[$index]['Personne']['id'] ) ) ).' )'
								)
							)
						),
						'order' => 'Relancenonrespectsanctionep93.daterelance DESC'
					)
				);
				$personnesFoyer[$index]['Nonrespectsanctionep93']['derniere'] = $tRelance;

				// EP: dernier passage effectif (lié à un passagecommissionep)
				$tdossierEp = $this->Dossier->Foyer->Personne->Dossierep->find(
					'first',
					array(
						'fields' => array(
							'Dossierep.themeep',
							'Commissionep.dateseance',
							'Passagecommissionep.id',
							'Passagecommissionep.etatdossierep',
						),
						'joins' => array(
							array(
								'table'      => 'passagescommissionseps',
								'alias'      => 'Passagecommissionep',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( 'Passagecommissionep.dossierep_id = Dossierep.id' )
							),
							array(
								'table'      => 'commissionseps',
								'alias'      => 'Commissionep',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( 'Passagecommissionep.commissionep_id = Commissionep.id' )
							),
						),
						'conditions' => array(
							'Dossierep.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => array(
							'Commissionep.dateseance DESC'
						),
						'contain' => false,
					)
				);

				$dateDerniereCommissionep = Set::classicExtract( $tdossierEp, 'Commissionep.dateseance' );
				$dateDuJour = date( 'Y-m-d' );

				//Si la date de la dernière commission est > à 1 an, on masque l'information d'EP (CG93)
				$displayingInfoEp = true;
				if( Configure::read( 'Cg.departement' ) == 93 ) {
					if( !empty( $dateDerniereCommissionep ) ) {
						$dateCommissionEPPlusUnAn = date( 'Y-m-d', strtotime( '+1 year', strtotime( $dateDerniereCommissionep ) ) );

						$dateduJourMoinsUnAn = date( 'Y-m-d', strtotime( '-1 year', strtotime( $dateDuJour ) ) );
						if( $dateDuJour > $dateCommissionEPPlusUnAn  ) {
							$displayingInfoEp = false;
						}
					}
				}
				$this->set( 'displayingInfoEp', $displayingInfoEp );


				$decisionEP = array();
				if( !empty( $tdossierEp ) ) {
					$themeEP = Set::classicExtract( $tdossierEp, 'Dossierep.themeep' );
					$modelTheme = Inflector::classify( Inflector::singularize( $themeEP ) );
					$modelDecision = 'Decision'.Inflector::singularize( $themeEP );

					if( !isset( $optionsep[$modelDecision] ) ) {
						$optionsep[$modelDecision] = $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->{$modelDecision}->allEnumLists();
					}

					$decisionEP = $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->{$modelDecision}->find(
						'first',
						array(
							'conditions' => array(
								"{$modelDecision}.passagecommissionep_id" => $tdossierEp['Passagecommissionep']['id']
							),
							'order' => array( "{$modelDecision}.etape DESC" ),
							'contain' => false
						)
					);
				}

				$personnesFoyer[$index]['Dossierep']['derniere'] = Set::merge( $tdossierEp, $decisionEP );

				// Utilisation des nouvelles tables de stockage des infos Pôle Emploi
				$tInfope = $this->Informationpe->derniereInformation($personnesFoyer[$index]);
				$personnesFoyer[$index]['Informationpe'] = $tInfope['Historiqueetatpe'];

				//  Liste des anciens dossiers par demandeurs et conjoints
				$nir13 = trim( $personnesFoyer[$index]['Personne']['nir'] );
				$nir13 = ( empty( $nir13 ) ? null : substr( $nir13, 0, 13 ) );

				$autreNumdemrsaParAllocataire = $this->Dossier->find(
					'all',
					array(
						'fields' => array(
							'DISTINCT Dossier.id',
							'Dossier.numdemrsa',
							'Dossier.dtdemrsa',
							'Situationdossierrsa.etatdosrsa'
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
							),
							array(
								'table'      => 'prestations',
								'alias'      => 'Prestation',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array(
									'Personne.id = Prestation.personne_id',
									'Prestation.natprest = \'RSA\'',
									'Prestation.rolepers' => array( 'DEM', 'CJT' )
								)
							),
							array(
								'table'      => 'situationsdossiersrsa',
								'alias'      => 'Situationdossierrsa',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
							),
						),
						'conditions' => array(
							'OR' => array(
								array(
									'nir_correct13( Personne.nir )',
									'nir_correct13( \''.$nir13.'\'  )',
									'SUBSTRING( TRIM( BOTH \' \' FROM Personne.nir ) FROM 1 FOR 13 )' => $nir13,
									'Personne.dtnai' => $personnesFoyer[$index]['Personne']['dtnai']
								),
								array(
									'UPPER(Personne.nom)' => strtoupper( replace_accents( $personnesFoyer[$index]['Personne']['nom'] ) ),
									'UPPER(Personne.prenom)' => strtoupper( replace_accents( $personnesFoyer[$index]['Personne']['prenom'] ) ),
									'Personne.dtnai' => $personnesFoyer[$index]['Personne']['dtnai']
								)
							),
							'Dossier.id NOT' => $details['Dossier']['id']
						),
						'contain' => false,
						'order' => 'Dossier.id DESC',
						'recursive' => -1
					)
				);
				$personnesFoyer[$index]['Dossiermultiple'] = $autreNumdemrsaParAllocataire;
				//Fin Ajout Arnaud

				$details[$role] = $personnesFoyer[$index];
			}

			$this->set( 'details', $details );

			$this->_setOptions();
			$this->set( 'optionsep', $optionsep );

		}

		/**
		 * Modification du dossier.
		 *
		 * @param integer $id
		 * @return void
		 */
		public function edit( $id ){
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$dossier = $this->Dossier->find(
				'first',
				array(
					'conditions' => array(
						'Dossier.id' => $id
					),
					'contain' => false
				)
			);


			if( empty( $dossier ) ) {
				$this->cakeError( 'error404' );
			}

			$this->Jetons2->get( $id );

			if( !empty( $this->data ) ) {
				if( $this->Dossier->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => true ) ) ) {
					$this->Jetons2->release( $id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'dossiers', 'action' => 'view', $id ) );
				}
				else{
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/error' );
				}
			}
			else {
				$this->data = $dossier;
			}
			$this->_setOptions();
			$this->set( 'id', $id );
		}

		/**
		 * Export du tableau de résultats de recherche au format CSV.
		 *
		 * @return void
		 */
		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Dossier->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), Xset::bump( $this->params['named'], '__' ) );
			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$dossiers = $this->Dossier->find( 'all', $querydata );

			$this->layout = '';
			$this->set( compact( 'headers', 'dossiers' ) );
			$this->_setOptions();
		}
	}
?>
