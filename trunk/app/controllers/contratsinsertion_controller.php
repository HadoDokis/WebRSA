<?php
	/**
		FIXME / TODO
		* Passer en dur:
			* Suivi d'insertion / CI en alternance

		* Prend une mauvaise valeur:
			* Suivi d'insertion -> CI en alternance
	**/
//     @ini_set( 'max_execution_time', 0 );
//     @ini_set( 'memory_limit', '512M' );
//     App::import( 'Sanitize' );
	App::import( 'Helper', 'Locale' );

	class ContratsinsertionController extends AppController
	{

		public $name = 'Contratsinsertion';
		public $uses = array( 'Contratinsertion', 'Option', 'Action', 'Referent', 'Personne', 'Dossier', 'Structurereferente', 'Dsp', 'Typeorient', 'Orientstruct', 'Serviceinstructeur', 'Action', 'Adressefoyer', 'Actioninsertion', 'Prestform', 'Refpresta', 'PersonneReferent' );
		public $helpers = array( 'Default2', 'Ajax' );
		public $components = array( 'RequestHandler', 'Gedooo' );
		public $aucunDroit = array( 'ajax', 'ajaxref', 'ajaxstruct', 'ajaxraisonci', 'notificationsop' );

		public $commeDroit = array(
			'view' => 'Contratsinsertion:index',
			'add' => 'Contratsinsertion:edit'
		);

		/**
		*
		*/
		protected function _setOptions() {
			$options = $this->Contratinsertion->allEnumLists();
// debug($options);

			if( in_array( $this->action, array( 'index', 'add', 'edit', 'view', 'valider' ) ) ) {
				$this->set( 'decision_ci', $this->Option->decision_ci() );
				$forme_ci = array();
				if( Configure::read( 'nom_form_ci_cg' ) == 'cg93' ) {
					$forme_ci = array( 'S' => 'Simple', 'C' => 'Complexe' );
				}
				else if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) {
					$forme_ci = array( 'S' => 'Simple', 'C' => 'Particulier' );
				}
				$this->set( 'forme_ci', $forme_ci );
			}

			if( in_array( $this->action, array( 'add', 'edit', 'view' ) ) ) {
				$this->set( 'formeci', $this->Option->formeci() );
			}

			if( in_array( $this->action, array( 'add', 'edit', 'view' ) ) ) {
				$this->set( 'qual', $this->Option->qual() );
				$this->set( 'raison_ci', $this->Option->raison_ci() );
				if( Configure::read( 'Cg.departement' ) == 66 ){
                    $this->set( 'avisraison_ci', $this->Option->avisraison_ci() );
                }
                else if( Configure::read( 'Cg.departement' ) == 93 ){
                    $this->set( 'avisraison_ci', array( 'D' => 'Defaut de conclusion', 'N' => 'Non respect du contrat' ) );
                }
				$this->set( 'aviseqpluri', $this->Option->aviseqpluri() );
				$this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
				$this->set( 'emp_occupe', $this->Option->emp_occupe() );
				$this->set( 'duree_hebdo_emp', $this->Option->duree_hebdo_emp() );
				$this->set( 'nat_cont_trav', $this->Option->nat_cont_trav() );
				$this->set( 'duree_cdd', $this->Option->duree_cdd() );
				$this->set( 'duree_engag_cg66', $this->Option->duree_engag_cg66() );
				$this->set( 'duree_engag_cg93', $this->Option->duree_engag_cg93() );
				$this->set( 'duree_engag_cg58', $this->Option->duree_engag_cg58() );

				$this->set( 'typevoie', $this->Option->typevoie() );
				$this->set( 'fonction_pers', $this->Option->fonction_pers() );
				$this->set( 'nivetus', $this->Contratinsertion->Personne->Dsp->enumList( 'nivetu' ) );
				$this->set( 'nivdipmaxobt', $this->Contratinsertion->Personne->Dsp->enumList( 'nivdipmaxobt' ) );

				$this->set( 'lib_action', $this->Option->lib_action() );
				$this->set( 'typo_aide', $this->Option->typo_aide() );
				$this->set( 'soclmaj', $this->Option->natpfcre( 'soclmaj' ) );
				$this->set( 'rolepers', $this->Option->rolepers() );
				$this->set( 'zoneprivilegie', ClassRegistry::init( 'Zonegeographique' )->find( 'list' ) );
				$this->set( 'actions', $this->Action->grouplist( 'prest' ) );
				$optionsautreavissuspension = $this->Contratinsertion->Autreavissuspension->allEnumLists();
				$optionsautreavisradiation = $this->Contratinsertion->Autreavisradiation->allEnumLists();
				$options = array_merge( $options, $optionsautreavissuspension );
				$options = array_merge( $options, $optionsautreavisradiation );
			}
            $this->set( 'options', $options );
		}

		/**
		*
		*/

		protected function _libelleTypeorientNiv0( $typeorient_id ) {
			$typeorient_niv1_id = $this->Contratinsertion->Personne->Orientstruct->Typeorient->getIdLevel0( $typeorient_id );
			$typeOrientation = $this->Contratinsertion->Personne->Orientstruct->Typeorient->find(
				'first',
				array(
					'fields' => array( 'Typeorient.lib_type_orient' ),
					'recursive' => -1,
					'conditions' => array(
						'Typeorient.id' => $typeorient_niv1_id
					)
				)
			);

			//$this->assert( !empty( $typeOrientation ), 'error500' );
			return Set::classicExtract( $typeOrientation, 'Typeorient.lib_type_orient' );
		}

		/**
		*
		*/

		protected function _referentStruct( $structurereferente_id ) {
			$referents = $this->Contratinsertion->Structurereferente->Referent->find(
				'all',
				array(
					'recursive' => -1,
					'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom', 'Referent.fonction' ),
					'conditions' => array( 'structurereferente_id' => $structurereferente_id )
				)
			);
			if( !empty( $referents ) ) {
				$ids = Set::extract( $referents, '/Referent/id' );
				$values = Set::format( $referents, '{0} {1}', array( '{n}.Referent.nom', '{n}.Referent.prenom' ) );
				$referents = array_combine( $ids, $values );
			}
			return $referents;
		}

		/**
		*   Ajax pour les coordonnées du référent APRE
		*/

		public function ajaxref( $referent_id = null ) { // FIXME
			Configure::write( 'debug', 0 );

			if( !empty( $referent_id ) ) {
				$referent_id = suffix( $referent_id );
			}
			else {
				$referent_id = suffix( Set::extract( $this->data, 'Contratinsertion.referent_id' ) );
			}

			$referent = array();
			if( is_int( $referent_id ) ) {
				$referent = $this->Contratinsertion->Structurereferente->Referent->findbyId( $referent_id, null, null, -1
				);
			}

			$this->set( 'referent', $referent );
			$this->render( 'ajaxref', 'ajax' );
		}

		/**
		*   Ajax pour les coordonnées de la structure référente liée
		*/

		public function ajaxstruct( $structurereferente_id = null ) { // FIXME
			Configure::write( 'debug', 0 );
			$this->set( 'typesorients', $this->Typeorient->find( 'list', array( 'fields' => array( 'lib_type_orient' ) ) ) );

			$dataStructurereferente_id = Set::extract( $this->data, 'Contratinsertion.structurereferente_id' );
			$structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );

			$struct = $this->Contratinsertion->Structurereferente->findbyId( $structurereferente_id, null, null, -1 );
			$this->set( 'struct', $struct );
			$this->render( 'ajaxstruct', 'ajax' );
		}

		/**
		*
		*/

		public function index( $personne_id = null ){
			// On s'assure que la personne existe
			$nbrPersonnes = $this->Contratinsertion->Personne->find(
				'count',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id ),
						'recursive' => -1
				)
			);
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			// Recherche du nombre de référent lié au parcours de la personne
			// Si aucun alors message d'erreur signalant l'absence de référent (cg66)
			if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) {
                $orientstruct = $this->Orientstruct->find(
                    'count',
                    array(
                        'conditions' => array(
                            'Orientstruct.personne_id' => $personne_id,
                            'Orientstruct.statut_orient' => 'Orienté'
                        ),
                        'recursive' => -1
                    )
                );
                $this->set( compact( 'orientstruct' ) );

				$persreferent = $this->PersonneReferent->find(
					'count',
					array(
						'conditions' => array(
							'PersonneReferent.personne_id' => $personne_id
						),
						'recursive' => -1
					)
				);
				$this->set( compact( 'persreferent' ) );
			}

			$contratsinsertion = $this->Contratinsertion->find(
				'all',
				array(
					'fields' => array(
						'Contratinsertion.id',
						'Contratinsertion.forme_ci',
						'Contratinsertion.decision_ci',
						'Contratinsertion.num_contrat',
						'Contratinsertion.dd_ci',
						'Contratinsertion.positioncer',
						'Contratinsertion.df_ci',
						'Contratinsertion.date_saisi_ci',
						'Contratinsertion.datevalidation_ci',
					),
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne_id
					),
					'order' => array(
						'Contratinsertion.df_ci DESC'
					),
					'recursive' => -1
				)
			);

			$this->_setOptions();
			$this->set( compact( 'contratsinsertion' ) );
			$this->set( 'personne_id', $personne_id );

			if ( Configure::read('Cg.departement') == 58 ) {
				$nbdossiersnonfinalisescovs = $this->Contratinsertion->Personne->Dossiercov58->find(
					'count',
					array(
						'conditions' => array(
							'Dossiercov58.personne_id' => $personne_id,
							'Dossiercov58.etapecov <>' => 'finalise'
						),
						'joins' => array(
							array(
								'table' => 'proposcontratsinsertioncovs58',
								'alias' => 'Propocontratinsertioncov58',
								'type' => 'INNER',
								'conditions' => array(
									'Propocontratinsertioncov58.dossiercov58_id = Dossiercov58.id'
								)
							)
						)
					)
				);
				$this->set( 'nbdossiersnonfinalisescovs', $nbdossiersnonfinalisescovs );
			}

			///FIXME: pas propre, mais pr le moment ça marche afin d'eviter de tout renommer
			$this->render( $this->action, null, '/contratsinsertion/index_'.Configure::read( 'nom_form_ci_cg' ) );
		}

		/**
		*
		*/

		public function view( $contratinsertion_id = null ) {

			// On a seulement besoin des modèles liés Structurereferente, Referent et Typeorient
			$binds = array(
				'belongsTo' => array(
					'Structurereferente' => $this->Contratinsertion->belongsTo['Structurereferente'],
					'Referent' => $this->Contratinsertion->belongsTo['Referent'],
					'Personne' => $this->Contratinsertion->belongsTo['Personne'],
					'Typeorient' => array(
						'table'      => $this->Typeorient->getDataSource()->fullTableName( $this->Typeorient, false ),
						'alias'      => 'Typeorient',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Structurereferente.typeorient_id = Typeorient.id' )
					)
				)
			);
			$this->Contratinsertion->unbindModelAll();
			$this->Contratinsertion->bindModel( $binds );
			$this->Contratinsertion->forceVirtualFields = true;
			$contratinsertion = $this->Contratinsertion->find(
				'first',
				array(
					'fields' => array(
						'Contratinsertion.id',
						'Contratinsertion.personne_id',
						'Contratinsertion.forme_ci',
						'Contratinsertion.num_contrat',
						'Contratinsertion.dd_ci',
						'Contratinsertion.df_ci',
						'Contratinsertion.diplomes',
						'Contratinsertion.expr_prof',
						'Contratinsertion.form_compl',
						'Contratinsertion.rg_ci',
						'Contratinsertion.actions_prev',
						'Contratinsertion.obsta_renc',
						'Contratinsertion.service_soutien',
						'Contratinsertion.pers_charg_suivi',
						'Contratinsertion.objectifs_fixes',
						'Contratinsertion.sect_acti_emp',
						'Contratinsertion.emp_occupe',
						'Contratinsertion.duree_hebdo_emp',
						'Contratinsertion.sitfam_ci',
                        'Contratinsertion.sitpro_ci',
                        'Contratinsertion.observ_benef',
                        'Contratinsertion.nature_projet',
                        'Contratinsertion.engag_object',
                        'Contratinsertion.current_action',
						'Contratinsertion.nat_cont_trav',
						'Contratinsertion.duree_cdd',
						'Contratinsertion.duree_engag',
						'Contratinsertion.nature_projet',
						'Contratinsertion.engag_object',
						'Contratinsertion.date_saisi_ci',
						'Contratinsertion.lieu_saisi_ci',
						'Action.libelle',
						'Structurereferente.lib_struc',
						'Structurereferente.typeorient_id',
						'Referent.nom_complet',
						'Personne.nom_complet',
						'Typeorient.lib_type_orient',
					),
					'conditions' => array(
						'Contratinsertion.id' => $contratinsertion_id
					),
					'joins' => array(
						array(
							'table'      => 'actions', // FIXME
							'alias'      => 'Action',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Contratinsertion.engag_object = Action.code' )
						)
					),
					'recursive' => 0
				)
			);
			$this->Contratinsertion->forceVirtualFields = false;
			$this->assert( !empty( $contratinsertion ), 'invalidParameter' );

			$this->set( 'contratinsertion', $contratinsertion );

			$this->_setOptions();
			$this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );

            // Retour à la liste en cas d'annulation
            if(  isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id'] ) );
            }
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}


		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		protected function _getDsp( $personne_id ) {
			/// Récupération des données socio pro (notamment Niveau etude) lié au contrat
			$this->Contratinsertion->Personne->Dsp->unbindModelAll();
			$dsp = $this->Contratinsertion->Personne->Dsp->find(
				'first',
				array(
					'fields' => array(
						'Dsp.id',
						'Dsp.personne_id',
						'Dsp.nivetu',
						'Dsp.nivdipmaxobt',
						'Dsp.annobtnivdipmax',
					),
					'conditions' => array(
						'Dsp.personne_id' => $personne_id
					),
					'recursive' => -1
				)
			);

//             $dspData = Set::filter( $dsp['Dsp'] );

			if( empty( $dsp )/* && !empty( $dspData )*/  ){
				$dsp = array( 'Dsp' => array( 'personne_id' => $personne_id ) );

// debug($dsp);
				$this->Contratinsertion->Personne->Dsp->set( $dsp );
				if( $this->Contratinsertion->Personne->Dsp->save( $dsp ) ) {
					$dsp = $this->Contratinsertion->Personne->Dsp->findByPersonneId( $personne_id, null, null, 1 );

				}
				else {
					$this->cakeError( 'error500' );
				}
				$this->assert( !empty( $dsp ), 'error500' );
			}

			$return = array();
			$return['Dsp'] = array(
				'id' => $dsp['Dsp']['id'],
				'personne_id' => $dsp['Dsp']['personne_id']
			);
			$return['Dsp']['nivetu'] = ( ( isset( $dsp['Dsp']['nivetu'] ) ) ? $dsp['Dsp']['nivetu'] : null );
			$return['Dsp']['nivdipmaxobt'] = ( ( isset( $dsp['Dsp']['nivdipmaxobt'] ) ) ? $dsp['Dsp']['nivdipmaxobt'] : null );
			$return['Dsp']['annobtnivdipmax'] = ( ( isset( $dsp['Dsp']['annobtnivdipmax'] ) ) ? $dsp['Dsp']['annobtnivdipmax'] : null );

			return $return;
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				if( $this->action == 'edit' ) {
					$id = $this->Contratinsertion->field( 'personne_id', array( 'id' => $id ) );
				}
				$this->redirect( array( 'action' => 'index', $id ) );
			}

			$valueFormeci = null;
			if( $this->action == 'add' ) {
				$contratinsertion_id = null;
				$personne_id = $id;
				$nbrPersonnes = $this->Contratinsertion->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
				$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );
				$valueFormeci = 'S';

				$nbContratsPrecedents = $this->Contratinsertion->find( 'count', array( 'recursive' => -1, 'conditions' => array( 'Contratinsertion.personne_id' => $personne_id ) ) );
				if( $nbContratsPrecedents >= 1 ) {
					$tc = 'REN';
				}
				else {
					$tc = 'PRE';
				}

			}
			else if( $this->action == 'edit' ) {
				$contratinsertion_id = $id;
				$contratinsertion = $this->Contratinsertion->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Contratinsertion.id' => $contratinsertion_id
                        ),
                        'contain' => array(
                            'Autreavissuspension',
                            'Autreavisradiation'
                        )
                    )
                );
				$this->assert( !empty( $contratinsertion ), 'invalidParameter' );
// debug($contratinsertion);


                $personne_id = Set::classicExtract( $contratinsertion, 'Contratinsertion.personne_id' );
				$valueFormeci = Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' );

				$nbContratsPrecedents = $this->Contratinsertion->find( 'count', array( 'recursive' => -1, 'conditions' => array( 'Contratinsertion.personne_id' => $personne_id ) ) );

				$tc = Set::classicExtract( $contratinsertion, 'Contratinsertion.num_contrat' );
			}
			$this->set( 'nbContratsPrecedents',  $nbContratsPrecedents );

			/**
			*   Détails des précédents contrats
			*/
			$lastContrat = $this->Contratinsertion->find(
				'all',
				array(
					'fields' => array(
						'Contratinsertion.rg_ci',
						'Contratinsertion.dd_ci',
						'Contratinsertion.df_ci',
						'Contratinsertion.structurereferente_id',
						'Structurereferente.lib_struc',
						'Contratinsertion.engag_object',
						'Contratinsertion.observ_ci',
						//thematique du contrat,
						'Contratinsertion.decision_ci',
					),
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne_id
					),
					'order' => 'Contratinsertion.date_saisi_ci DESC',
					'limit' => 5
				)
			);
			$this->set( 'lastContrat',  $lastContrat );

			/// Recherche du type d'orientation
			$orientstruct = $this->Contratinsertion->Structurereferente->Orientstruct->find(
				'first',
				array(
					'conditions' => array(
						'Orientstruct.personne_id' => $personne_id,
						'Orientstruct.typeorient_id IS NOT NULL',
						'Orientstruct.statut_orient' => 'Orienté'
					),
					'order' => 'Orientstruct.date_valid DESC',
					'recursive' => -1
				)
			);
			$this->set( 'orientstruct', $orientstruct );

			///Personne liée au parcours
			$personne_referent = $this->Contratinsertion->Personne->PersonneReferent->find(
				'first',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id' => $personne_id,
						'PersonneReferent.dfdesignation IS NULL'
					),
					'recursive' => -1
				)
			);

			$structures = $this->Structurereferente->listOptions();
			$referents = $this->Referent->listOptions();

			$this->set( 'tc', $tc );


			/// Peut-on prendre le jeton ?
			$this->Contratinsertion->begin();
			$dossier_id = $this->Contratinsertion->Personne->dossierId( $personne_id );
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Contratinsertion->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );
			$this->set( 'dossier_id', $dossier_id );



            /**
            *   Utilisé pour les dates de suspension et de radiation
            *   Si les dates ne sont pas présentes en base, elles ne seront pas affichées
            *   Situation dossier rsa : dtclorsa -> date de radiation
            *   Suspension droit : ddsusdrorsa -> date de suspension
            */
            $situationdossierrsa = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Situationdossierrsa.dossier_id' => $dossier_id
                    ),
                    'recursive' => -1
                )
            );
            $situationdossierrsa_id = Set::classicExtract( $situationdossierrsa, 'Situationdossierrsa.id' );
            $suspension = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->find(
                'all',
                array(
                    'fields' => array(
                        'Suspensiondroit.ddsusdrorsa'
                    ),
                    'conditions' => array(
                        'Suspensiondroit.situationdossierrsa_id' => $situationdossierrsa_id
                    ),
                    'recursive' => -1,
                    'order' => 'Suspensiondroit.ddsusdrorsa DESC'
                )
            );
            $this->set( compact( 'situationdossierrsa', 'suspension' ) );

// debug($suspension);

            //On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
			$personne = $this->Contratinsertion->Personne->newDetailsCi( $personne_id, $this->Session->read( 'Auth.User.id' ) );
			$this->set( 'personne', $personne );


			/// Calcul du numéro du contrat d'insertion
			$nbrCi = $this->Contratinsertion->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );
			$this->set( 'nbrCi', $nbrCi );

			$this->set( 'valueFormeci', $valueFormeci );



			/// Essai de sauvegarde
			if( !empty( $this->data ) ) {

				$this->data['Contratinsertion']['rg_ci'] = $nbrCi + 1;


				if( Configure::read( 'nom_form_ci_cg' ) == 'cg58' ) {
					$this->data['Contratinsertion']['forme_ci'] = 'S';
				}

				$contratinsertionRaisonCi = Set::classicExtract( $this->data, 'Contratinsertion.raison_ci' );
				if( $contratinsertionRaisonCi == 'S' ) {
					$this->data['Contratinsertion']['avisraison_ci'] = Set::classicExtract( $this->data, 'Contratinsertion.avisraison_suspension_ci' );
				}
				else if( $contratinsertionRaisonCi == 'R' ){
					$this->data['Contratinsertion']['avisraison_ci'] = Set::classicExtract( $this->data, 'Contratinsertion.avisraison_radiation_ci' );
				}

				if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) {
					$contratinsertionDecisionCi = Set::classicExtract( $this->data, 'Contratinsertion.forme_ci' );
					if( $contratinsertionDecisionCi == 'S' ) {
						///Validation si le contrat est simple (CG66)
						$this->data['Contratinsertion']['decision_ci'] = 'V';
						$this->data['Contratinsertion']['datevalidation_ci'] = $this->data['Contratinsertion']['date_saisi_ci'];
					}
				}

				/**
                *   Utilisé pour les dates de suspension et de radiation
                *   Si les dates ne sont pas présentes en base, elles ne seront pas affichées
                *   Situation dossier rsa : dtclorsa -> date de radiation
                *   Suspension droit : ddsusdrorsa -> date de suspension
                */
                if( isset( $situationdossierrsa ) ){
                    $this->data['Contratinsertion']['dateradiationparticulier'] = $situationdossierrsa['Situationdossierrsa']['dtclorsa'];
                }
                if( isset( $suspension ) && !empty( $suspension ) ){
                    $this->data['Contratinsertion']['datesuspensionparticulier'] = $suspension[0]['Suspensiondroit']['ddsusdrorsa'];
                }
				$success = $this->Contratinsertion->save( $this->data );

				if( Configure::read( 'nom_form_ci_cg' ) != 'cg66' ) {
					$dspStockees = $this->_getDsp( $personne_id );
					$this->data['Dsp'] = Set::merge(
						isset( $dspStockees['Dsp'] ) ? Set::filter( $dspStockees['Dsp'] ) : array(),
						isset( $this->data['Dsp'] ) ? Set::filter( $this->data['Dsp'] ) : array()
					);

					$isDsp = Set::filter( $this->data['Dsp'] );
					if( !empty( $isDsp ) ){
						$success = $this->Contratinsertion->Personne->Dsp->save( array( 'Dsp' => $this->data['Dsp'] ) ) && $success;
					}
				}


				$models = array( 'Autreavissuspension', 'Autreavisradiation' );
				foreach( $models as $model ) {
                    if( $this->action == 'add' ) {
                        $this->{$this->modelClass}->{$model}->set( 'contratinsertion_id', $this->{$this->modelClass}->id );
                    }
                    else if ( $this->action == 'edit' ){
                        $this->Contratinsertion->{$model}->deleteAll( array( "{$model}.contratinsertion_id" => $this->Contratinsertion->id ) );
                    }


                    if( isset( $this->data[$model] ) ){
                        $is{$model} = Set::filter( $this->data[$model] );
                        if( !empty( $is{$model} ) ){
                            $Autresavis = Set::extract( $is{$model}, "/{$model}" );
                            $data = array( $model => array() );

                            foreach( $Autresavis as $i => $Autreavis ){
                                $data[$model][] = array(
                                    'contratinsertion_id' => $this->Contratinsertion->id,
                                    strtolower($model) => $Autreavis
                                );
                            }
                            $success = $this->Contratinsertion->{$model}->saveAll( $data[$model], array( 'atomic' => false ) ) && $success;
                        }
                    }
                }

                if( isset( $this->data['Actioninsertion'] ) ) {
                    $isActioninsertion = Set::filter( $this->data['Actioninsertion'] );
                    if( $this->action == 'add' ) {
                        $this->{$this->modelClass}->Actioninsertion->set( 'contratinsertion_id', $this->{$this->modelClass}->id );
                    }

                    if( !empty( $isActioninsertion ) ){
                        $success = $this->Contratinsertion->Actioninsertion->save( array( 'Actioninsertion' => $this->data['Actioninsertion'] ) ) && $success;
                    }
                }



// debug($this->data);
// debug($success);

				if( $success ) {
					$saved = true;

					$lastrdvorient = $this->Contratinsertion->Referent->Rendezvous->find(
						'first',
						array(
							'fields'=>array(
								'Rendezvous.id'
							),
							'conditions'=>array(
								'Rendezvous.typerdv_id' => 1,
								'Rendezvous.personne_id' => $this->data['Contratinsertion']['personne_id'],
								'Rendezvous.statutrdv_id' => 17
							),
							'contain'=>false
						)
					);
                    if( !empty( $lastrdvorient ) ){
                        $lastrdvorient['Rendezvous']['statutrdv_id'] = 1;
                        $saved = $this->Contratinsertion->Referent->Rendezvous->save($lastrdvorient) && $saved;
                    }

					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Contratinsertion->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Contratinsertion->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else {
				if( $this->action == 'edit' ) {

					$this->data = $contratinsertion;

					/// FIXME
					$actioninsertion = $this->Contratinsertion->Actioninsertion->find(
						'first',
						array(
							'conditions' => array(
								'Actioninsertion.contratinsertion_id' => $contratinsertion['Contratinsertion']['id'],
								'Actioninsertion.dd_action IS NOT NULL'
							),
							'recursive' => -1,
							'order' => array( 'Actioninsertion.dd_action DESC' )
						)
					);
					$this->data['Actioninsertion'] = $actioninsertion['Actioninsertion'];

					///Suspension / Radiation
					if( $this->data['Contratinsertion']['raison_ci'] == 'S' ) {
						$this->data['Contratinsertion']['avisraison_suspension_ci'] = $this->data['Contratinsertion']['avisraison_ci'];
					}
					else if( $this->data['Contratinsertion']['raison_ci'] == 'R' ){
						$this->data['Contratinsertion']['avisraison_radiation_ci'] =  $this->data['Contratinsertion']['avisraison_ci'];
					}

				}

				$this->data = Set::merge( $this->data, $this->_getDsp( $personne_id ) );

				/// Si on est en présence d'un deuxième contrat -> Alors renouvellement
				$this->data['Contratinsertion']['rg_ci'] = $nbrCi + 1;

			}
// debug( $this->data );
			// Doit-on setter les valeurs par défault ?
			$dataStructurereferente_id = Set::classicExtract( $this->data, "{$this->Contratinsertion->alias}.structurereferente_id" );
			$dataReferent_id = Set::classicExtract( $this->data, "{$this->Contratinsertion->alias}.referent_id" );

			// Si le formulaire ne possède pas de valeur pour ces champs, on met celles par défaut
			if( empty( $dataStructurereferente_id ) && empty( $dataReferent_id ) ) {
				$structurereferente_id = $referent_id = null;
				// Valeur par défaut préférée: à partir de personnes_referents
				if( !empty( $personne_referent ) ){
					$structurereferente_id = Set::classicExtract( $personne_referent, "{$this->PersonneReferent->alias}.structurereferente_id" );
					$referent_id = Set::classicExtract( $personne_referent, "{$this->PersonneReferent->alias}.referent_id" );
				}
				// Valeur par défaut de substitution: à partir de orientsstructs
				else if( !empty( $orientstruct ) ) {
					$structurereferente_id = Set::classicExtract( $orientstruct, "{$this->Orientstruct->alias}.structurereferente_id" );
					$referent_id = Set::classicExtract( $orientstruct, "{$this->Orientstruct->alias}.referent_id" );
				}


				if( !empty( $structurereferente_id ) ) {
					$this->data = Set::insert( $this->data, "{$this->Contratinsertion->alias}.structurereferente_id", $structurereferente_id );
				}
				if( !empty( $structurereferente_id ) && !empty( $referent_id ) ) {
					$this->data = Set::insert( $this->data, "{$this->Contratinsertion->alias}.referent_id", preg_replace( '/^_$/', '', "{$structurereferente_id}_{$referent_id}" ) );
				}
			}


			$struct_id = Set::classicExtract( $this->data, 'Contratinsertion.structurereferente_id' );
			$this->set( 'struct_id', $struct_id );

			if( !empty( $struct_id ) ) {
				$struct = $this->Contratinsertion->Structurereferente->find(
					'first',
					array(
						'fields' => array(
							'Structurereferente.num_voie',
							'Structurereferente.type_voie',
							'Structurereferente.nom_voie',
							'Structurereferente.code_postal',
							'Structurereferente.ville',
						),
						'conditions' => array(
							'Structurereferente.id' => Set::extract( $this->data, 'Contratinsertion.structurereferente_id' )
						),
						'recursive' => -1
					)
				);
				$this->set( 'StructureAdresse', $struct['Structurereferente']['num_voie'].' '.$struct['Structurereferente']['type_voie'].' '.$struct['Structurereferente']['nom_voie'].'<br/>'.$struct['Structurereferente']['code_postal'].' '.$struct['Structurereferente']['ville'] );
			}

			$referent_id = Set::classicExtract( $this->data, 'Contratinsertion.referent_id' );
			$referent_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $referent_id );
			$this->set( 'referent_id', $referent_id );

			if( !empty( $referent_id ) && !empty( $this->data['Contratinsertion']['referent_id'] ) ) {
				$contratinsertionReferentId = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Contratinsertion']['referent_id'] );
				$referent = $this->Contratinsertion->Structurereferente->Referent->find(
					'first',
					array(
						'fields' => array(
							'Referent.email',
							'Referent.fonction',
							'Referent.nom',
							'Referent.prenom',
							'Referent.numero_poste',
						),
						'conditions' => array(
							'Referent.id' => $contratinsertionReferentId
						),
						'recursive' => -1
					)
				);
				$this->set( 'ReferentEmail', $referent['Referent']['email']. '<br/>' .$referent['Referent']['numero_poste'] );
				$this->set( 'ReferentFonction', $referent['Referent']['fonction'] );
				$this->set( 'ReferentNom', $referent['Referent']['nom'].' '.$referent['Referent']['prenom'] );
// debug($contratinsertion);
			}
// debug($this->data);
			$this->Contratinsertion->commit();
            $this->_setOptions();
			$this->set( compact( 'structures', 'referents' ) );

			if( Configure::read( 'nom_form_ci_cg' ) == 'cg58' ) {
				$this->render( $this->action, null, 'add_edit_specif_cg58' );
			}
			else {
				$this->render( $this->action, null, 'add_edit' );
			}
		}

		/**
		*
		*/

		public function valider( $contratinsertion_id = null ) {

			$contratinsertion = $this->Contratinsertion->find(
				'first',
				array(
					'fields' => array(
						'Contratinsertion.id',
						'Contratinsertion.personne_id',
						'Contratinsertion.structurereferente_id',
						'Contratinsertion.forme_ci',
						'Contratinsertion.observ_ci',
						'Contratinsertion.datevalidation_ci',
						'Contratinsertion.decision_ci',
					),
					'conditions' => array(
						'Contratinsertion.id' => $contratinsertion_id
					),
					'recursive' => -1
				)
			);
			$this->assert( !empty( $contratinsertion ), 'invalidParameter' );
			$this->set( 'contratinsertion', $contratinsertion );

			$this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );


			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id'] ) );
			}

			if( !empty( $this->data ) ) {
				if( $this->Contratinsertion->valider( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id']) );
				}
			}
			else {

				$this->data = $contratinsertion;
			}
//                         debug($this->data);
			$this->_setOptions();
		}

		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}


        /**
        *   Fonction pour annuler le CER pour le CG66
        */

        public function cancel( $id ) {
            $contrat = $this->{$this->modelClass}->findById( $id, null, null, -1 );

            $this->{$this->modelClass}->updateAll(
                array( 'Contratinsertion.positioncer' => '\'annule\'' ),
                array(
                    '"Contratinsertion"."personne_id"' => $contrat['Contratinsertion']['personne_id'],
                    '"Contratinsertion"."id"' => $contrat['Contratinsertion']['id']
                )
            );
            $this->redirect( array( 'action' => 'index', $contrat['Contratinsertion']['personne_id'] ) );
        }


		/**
		*
		*/

		public function notificationsop( $id = null ) {
			$qual = $this->Option->qual();
			$typevoie = $this->Option->typevoie();
			$duree_engag_cg66 = $this->Option->duree_engag_cg66();
			$duree_engag_cg93 = $this->Option->duree_engag_cg93();

			$contratinsertion = $this->{$this->modelClass}->find(
				'first',
				array(
					'conditions' => array(
						"{$this->modelClass}.id" => $id
					),
					'recursive' => 0
				)
			);

			$this->Adressefoyer->bindModel(
				array(
					'belongsTo' => array(
						'Adresse' => array(
							'className'     => 'Adresse',
							'foreignKey'    => 'adresse_id'
						)
					)
				)
			);

			$adresse = $this->Adressefoyer->find(
				'first',
				array(
					'conditions' => array(
						'Adressefoyer.foyer_id' => Set::classicExtract( $contratinsertion, 'Personne.foyer_id' ),
						'Adressefoyer.rgadr' => '01',
					)
				)
			);
			$contratinsertion['Adresse'] = $adresse['Adresse'];

			/// Récupération du dossier lié à la personne
			$foyer = $this->Adressefoyer->Foyer->find(
				'first',
				array(
					'conditions' => array(
						'Foyer.id' => $contratinsertion['Personne']['foyer_id']
					)
				)
			);
			$contratinsertion['Foyer'] = $foyer['Foyer'];
			$dossier = $this->Dossier->find(
				'first',
				array(
					'conditions' => array(
						'Dossier.id' => $contratinsertion['Foyer']['dossier_id']
					)
				)
			);
			$contratinsertion['Dossier'] = $dossier['Dossier'];

			$contratinsertion_id = Set::classicExtract( $contratinsertion, 'Actioncandidat.id' );


			$LocaleHelper = new LocaleHelper();
			///Traduction pour les données de la Personne/Contact/Partenaire/Référent
			$contratinsertion['Personne']['qual'] = Set::enum( Set::classicExtract( $contratinsertion, 'Personne.qual' ), $qual );
			$contratinsertion['Personne']['dtnai'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $contratinsertion, 'Personne.dtnai' ) );
			$contratinsertion['Referent']['qual'] = Set::enum( Set::classicExtract( $contratinsertion, 'Referent.qual' ), $qual );
			$contratinsertion['Adresse']['typevoie'] = Set::enum( Set::classicExtract( $contratinsertion, 'Adresse.typevoie' ), $typevoie );
			$contratinsertion['Contratinsertion']['datevalidation_ci'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $contratinsertion, 'Contratinsertion.datevalidation_ci' ) );
			$contratinsertion['Contratinsertion']['dd_ci'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $contratinsertion, 'Contratinsertion.dd_ci' ) );
			$contratinsertion['Contratinsertion']['df_ci'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $contratinsertion, 'Contratinsertion.df_ci' ) );
			$contratinsertion['Dossier']['dtdemrsa'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $contratinsertion, 'Dossier.dtdemrsa' ) );

			if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) {
				$contratinsertion['Contratinsertion']['duree_engag'] = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.duree_engag' ), $duree_engag_cg66 );
			}
			else if( Configure::read( 'nom_form_ci_cg' ) == 'cg93' ) {
				$contratinsertion['Contratinsertion']['duree_engag'] = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.duree_engag' ), $duree_engag_cg93 );
			}

			///Utilisé pour savoir si le contrat est en accord de validation dans le modèle odt
			if( $contratinsertion['Contratinsertion']['decision_ci'] == 'V' ){
				$contratinsertion['Contratinsertion']['accord'] = 'X';
			}

			///Utilisé pour savoir si le contrat est en premier contrat ou renouvellement
			if( $contratinsertion['Contratinsertion']['num_contrat'] == 'PRE' ){
				$contratinsertion['Contratinsertion']['premier'] = 'X';
			}
			else if( $contratinsertion['Contratinsertion']['num_contrat'] == 'REN' ){
				$contratinsertion['Contratinsertion']['renouvel'] = 'X';
			}

			$this->_setOptions();

			$pdf = $this->Contratinsertion->ged( $contratinsertion, 'Contratinsertion/notificationop.odt' );
			$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'notificationop-%s.pdf', date( 'Y-m-d' ) ) );
		}
	}
?>