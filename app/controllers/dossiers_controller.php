<?php
	App::import( 'Sanitize' );

	class DossiersController extends AppController
	{
		public $name = 'Dossiers';
		public $uses = array( 'Dossier', 'Option', 'Informationpe' );
		public $aucunDroit = array( 'menu' );
		public $helpers = array( 'Csv' );

		public $paginate = array(
			// FIXME
			'limit' => 20
		);

		public $commeDroit = array(
			'view' => 'Dossiers:index'
		);

		/**
		*
		*/

		function __construct() {
			$this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
			parent::__construct();
		}

		/**
		*
		*/

		protected function _setOptions() {
			$this->set( 'natpf', $this->Option->natpf() );
			$this->set( 'decision_ci', $this->Option->decision_ci() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'statudemrsa', $this->Option->statudemrsa() );
			$this->set( 'moticlorsa', $this->Option->moticlorsa() );
			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'sitfam', $this->Option->sitfam() );
			$this->set( 'couvsoc', $this->Option->couvsoc() ); // INFO: pas dans view
			$this->set( 'categorie', $this->Option->categorie() );
			///FIXME:
			$this->set(
				'trancheAge',
				array(
					'< 25',
					'25 - 30',
					'31 - 55',
					'56 - 65',
					'> 65'
				)
			); // INFO: pas dans view

			// FIXME: à intégrer à la fonction view pour ne pas avoir d'énormes variables
			if( $this->action == 'view' ) {
				$this->set( 'numcontrat', $this->Dossier->Foyer->Personne->Contratinsertion->allEnumLists() );
				$this->set( 'enumcui', $this->Dossier->Foyer->Personne->Cui->allEnumLists() );
				$this->set( 'etatpe', $this->Informationpe->Historiqueetatpe->allEnumLists() );
				$this->set( 'relance', $this->Dossier->Foyer->Personne->Orientstruct->Nonrespectsanctionep93->allEnumLists() );
				$this->set( 'dossierep', $this->Dossier->Foyer->Personne->Dossierep->allEnumLists() );
			}
			else if( $this->action == 'exportcsv' ) {
				$typesorient = $this->Dossier->Foyer->Personne->Orientstruct->Typeorient->find( 'list', array( 'fields' => array( 'id', 'lib_type_orient' ) ) );
				$this->set( 'typesorient', $typesorient );
			}
			else if( $this->action == 'index' ) {
				/// Mise en cache de la liste des services instructeurs
				/// TODO: nettoyer ce cache lors de l'ajout/modification/suppression d'un service instructeur
				$typeservice = Cache::read( 'servicesinstructeurs_liste' );
				if( $typeservice === false ) {
					$typeservice = $this->Dossier->Foyer->Personne->Orientstruct->Serviceinstructeur->find( 'list', array( 'fields' => array( 'lib_service' ) ) );
					Cache::write( 'servicesinstructeurs_liste', $typeservice );
				}
				$this->set( 'typeservice', $typeservice );
			}

		}

		/**
		*/
		function beforeFilter() {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '512M');
			$return = parent::beforeFilter();
			return $return;
		}

		/**
		*/
		function index() {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->loadModel( 'Canton' );
				$this->set( 'cantons', $this->Canton->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

			$params = $this->data;
			if( !empty( $params ) ) {
				$this->paginate = $this->Dossier->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );

				$this->paginate = $this->_qdAddFilters( $this->paginate );

				$dossiers = $this->paginate( 'Dossier' );

				// Les dossiers que l'on a obtenus sont-ils lockés ?
				$lockedList = $this->Jetons->lockedList( Set::extract( $dossiers, '/Dossier/id' ) );
				foreach( $dossiers as $key => $dossier ) {
					$dossiers[$key]['Dossier']['locked'] = in_array( $dossier['Dossier']['id'], $lockedList );
				}

				$this->set( 'dossiers', $dossiers );
			}

			/// Mise en cache (session) de la liste des codes Insee pour les selects
			/// TODO: Une fonction ?
			/// TODO: Voir où l'utiliser ailleurs
			if( !$this->Session->check( 'Cache.mesCodesInsee' ) ) {
				if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
					$listeCodesInseeLocalites = $this->Dossier->Foyer->Personne->Cui->Structurereferente->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) );
				}
				else {
					$listeCodesInseeLocalites = $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee();
				}
				$this->Session->write( 'Cache.mesCodesInsee', $listeCodesInseeLocalites );
			}
			else {
				$listeCodesInseeLocalites = $this->Session->read( 'Cache.mesCodesInsee' );
			}
			$this->set( 'mesCodesInsee', $listeCodesInseeLocalites );

			$this->_setOptions();
		}

		/**
		*
		*/

		function menu() {
			$this->assert( isset( $this->params['requested'] ), 'error404' );
			$conditions = array();

			// Quel paramètre avons-nous pour trouver le bon dossier ?
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

			$dossier = $this->Dossier->find(
				'first',
				array(
					'fields' => array(
						'Dossier.id',
						'Dossier.matricule',
						'Dossier.numdemrsa',
						'Foyer.id',
						'Situationdossierrsa.etatdosrsa'
					),
					'contain' => array(
						'Foyer',
						'Situationdossierrsa'
					),
					'conditions' => $conditions
				)
			);

			$dossier['Dossier']['locked'] = $this->Jetons->locked( $dossier['Dossier']['id'] );

			// FIXME: bizzarre qu'il ne soit plus bindé
			$this->Dossier->Foyer->Personne->bindModel( array( 'hasOne' => array( 'Prestation' ) ) );

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
		*/
		function view( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			/** Tables necessaire à l'ecran de synthèse

				OK -> Dossier
				OK -> Foyer
				OK -> Situationdossierrsa
				OK -> Adresse
				OK -> Detaildroitrsa
					OK -> Detailcalculdroitrsa
				OK -> Suiviinstruction
				OK -> Infofinanciere
				OK -> Creance
				OK -> Dossiercaf
				OK -> Personne (DEM/CJT)
					OK -> Personne
					OK -> Prestation
					OK -> Orientstruct (premier/dernier)
						//Typeorient
					OK -> Dsp
					OK -> Contratinsertion
					Calculsdroitrsa
			*/

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

			/**
				Personnes
			*/

			$personnesFoyer = $this->Dossier->Foyer->Personne->find(
				'all',
				array(
					'fields' => array(
						'Personne.id',
						'Personne.nom',
						'Personne.prenom',
						'Personne.dtnai',
						'Personne.nir',
						'Dsp.id',
						'Dossiercaf.ddratdos',
						'Dossiercaf.dfratdos',
// 						'Infopoleemploi.dateinscription',
// 						'Infopoleemploi.categoriepe',
// 						'Infopoleemploi.datecessation',
// 						'Infopoleemploi.motifcessation',
// 						'Infopoleemploi.dateradiation',
// 						'Infopoleemploi.motifradiation',
						'Calculdroitrsa.toppersdrodevorsa',
						'Prestation.rolepers'
					),
					'conditions' => array(
						'Personne.foyer_id' => $details['Foyer']['id'],
						'Prestation.natprest' => 'RSA',
						'Prestation.rolepers' => array( 'DEM', 'CJT' )
					),
					'contain' => array(
						'Prestation',
						'Dossiercaf',
						'Dsp',
						'Calculdroitrsa',
					),
					'recursive' => 0
				)
			);

            $options = array(
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
							'Referent.prenom',
						),
						'contain' => array(
							'Referent'
						),
						'conditions' => array( 'PersonneReferent.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
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
							'Cui.convention',
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
							'Orientstruct.date_valid',
							'Orientstruct.statut_orient',
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
							'Relancenonrespectsanctionep93.daterelance'
						),
						'contain' => false,
						'joins' => array(
                            array(
                                'table'      => 'nonrespectssanctionseps93',
                                'alias'      => 'Nonrespectsanctionep93',
                                'type'       => 'LEFT OUTER',
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
						'order' => "Nonrespectsanctionep93.created DESC",
					)
				);
				$personnesFoyer[$index]['Nonrespectsanctionep93']['derniere'] = $tRelance;

// debug($tRelance);
                // EP

                // Dernier passage effectif (lié à un passagecommissionep)
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

                $decisionEP = array();
                if( !empty( $tdossierEp ) ) {
                    $themeEP = Set::classicExtract( $tdossierEp, 'Dossierep.themeep' );
                    $modelTheme = Inflector::classify( Inflector::singularize( $themeEP ) );
                    $modelDecision = 'Decision'.Inflector::singularize( $themeEP );

                    if( !isset( $options[$modelDecision] ) ) {
                        $options[$modelDecision] = $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->{$modelDecision}->allEnumLists();
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
// debug( $personnesFoyer[$index]['Dossierep']['derniere'] );
                /*$tdossierEp = $this->Dossier->Foyer->Personne->Dossierep->find(
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
                );*/


				/*$tdossierEp = $this->Dossier->Foyer->Personne->Dossierep->find(
					'first',
					array(
						'fields' => array(
							'id',
							'created',
							'themeep',
						),
						'conditions' => array(
							'Dossierep.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'contain' => false,
						'order' => "Dossierep.created DESC",
						'recursive' => -1
					)
				);
// debug($tdossierEp);
                if( !empty( $tdossierEp ) ){
                    $themeEP = Set::classicExtract( $tdossierEp, 'Dossierep.themeep' );
                    $modelTheme = Inflector::classify( Inflector::singularize( $themeEP ) );
                    $modelThemeLieName = Inflector::singularize( $themeEP );

                    $decisionEP = $this->Dossier->Foyer->Personne->Dossierep->{$modelTheme}->find(
                        'first',
                        array(
                            'conditions' => array(
                                "{$modelTheme}.dossierep_id" => $tdossierEp['Dossierep']['id']
                            )
                        )
                    );
    // debug($decisionEP);


                    $tCommissionEp = $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->find(
                        'first',
                        array(
                            'conditions' => array(
                                'Passagecommissionep.dossierep_id' => $tdossierEp['Dossierep']['id']
                            ),
                            'contain' => array(
                                'Commissionep' => array(
                                    'fields' => array(
                                        'dateseance'
                                    )
                                ),
                                "Decision{$modelThemeLieName}" => array(
                                    'order' => "Decision{$modelThemeLieName}.etape DESC"
                                )
                            )
                        )
                    );
    debug($tCommissionEp);

                    $tdossierEp = Set::merge( $tdossierEp, $tCommissionEp );
                    $decisionEP = $tdossierEp["Decision$modelThemeLieName"][0]['decision'];

                    $this->set( 'decisionEP', $decisionEP );
//                     debug($decisionEP);
//                     debug( $tdossierEp);
                }
                $personnesFoyer[$index]['Dossierep']['derniere'] = $tdossierEp;

// debug($tdossierEp);*/


				/**
				*   Utilisation des nouvelles tables de stockage des infos Pôle Emploi
				*/

				$tInfope = $this->Informationpe->find(
					'first',
					array(
						'contain' => array(
							'Historiqueetatpe' => array(
								'order' => "Historiqueetatpe.date DESC",
								'limit' => 1
							)
						),
						'conditions' => array(
							'OR' => array(
								array(
									'Informationpe.nir' => Set::classicExtract( $personnesFoyer[$index], 'Personne.nir' ),
									'Informationpe.nir IS NOT NULL',
									'LENGTH(Informationpe.nir) = 15',
									'Informationpe.dtnai' => Set::classicExtract( $personnesFoyer[$index], 'Personne.dtnai' ),
								),
								array(
									'Informationpe.nom' => Set::classicExtract( $personnesFoyer[$index], 'Personne.nom' ),
									'Informationpe.prenom' => Set::classicExtract( $personnesFoyer[$index], 'Personne.prenom' ),
									'Informationpe.dtnai' => Set::classicExtract( $personnesFoyer[$index], 'Personne.dtnai' ),
								)
							)

						)
					)
				);
				$personnesFoyer[$index]['Informationpe'] = $tInfope['Historiqueetatpe'];



				/**
				*   Liste des anciens dossiers par demandeurs et conjoints
				*   TODO
				*/

				$autreNumdemrsaParAllocataire = $this->Dossier->find(
					'all',
					array(
						'fields' => array(
							'DISTINCT Dossier.id',
							'Dossier.numdemrsa',
							'Dossier.dtdemrsa',
//                             'Prestation.rolepers'
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
						),
						'conditions' => array(
                            'OR' => array(
                                array(
                                    'Personne.nir' => $personnesFoyer[$index]['Personne']['nir'],
                                    //FIXME
                                    'nir_correct( Personne.nir  ) = true',
                                    'Personne.nir IS NOT NULL',
                                    'Personne.dtnai' => $personnesFoyer[$index]['Personne']['dtnai']
                                ),
                                array(
                                    'Personne.nom' => $personnesFoyer[$index]['Personne']['nom'],
                                    'Personne.prenom' => $personnesFoyer[$index]['Personne']['prenom'],
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

// debug($details);
// debug($details['DEM']['Dossiermultiple']);
// debug($details['CJT']['Dossiermultiple']);
			$this->set( 'details', $details );
            $this->set( 'options', $options );
			$this->_setOptions();

		}

		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

			$querydata = $this->Dossier->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ) );
			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$dossiers = $this->Dossier->find( 'all', $querydata );

			$this->layout = ''; // FIXME ?
			$this->set( compact( 'headers', 'dossiers' ) );
			$this->_setOptions();
		}
	}
?>
