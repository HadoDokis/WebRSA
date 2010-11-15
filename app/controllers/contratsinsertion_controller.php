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

        var $name = 'Contratsinsertion';
        var $uses = array( 'Contratinsertion', 'Option', 'Action', 'Referent', 'Personne', 'Dossier', 'Structurereferente', 'Dsp', 'Typeorient', 'Orientstruct', 'Serviceinstructeur', 'Action', 'Adressefoyer', 'Actioninsertion', 'AdresseFoyer', 'Prestform', 'Refpresta', 'PersonneReferent' );
        var $helpers = array( 'Ajax' );
        var $components = array( 'RequestHandler', 'Gedooo' );
        var $aucunDroit = array( 'ajax', 'ajaxref', 'ajaxstruct', 'ajaxraisonci', 'notificationsop' );

		var $commeDroit = array(
			'view' => 'Contratsinsertion:index',
			'add' => 'Contratsinsertion:edit'
		);

        /**
        *
        */
        protected function _setOptions() {
            $options = $this->Contratinsertion->allEnumLists();
            $this->set( 'options', $options );

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

            if( in_array( $this->action, array( 'add', 'edit'/*, 'view'*/ ) ) ) {
                $this->set( 'qual', $this->Option->qual() );
                $this->set( 'raison_ci', $this->Option->raison_ci() );
                $this->set( 'avisraison_ci', $this->Option->avisraison_ci() );
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
            }
        }

        /**
        *
        */

        function _libelleTypeorientNiv0( $typeorient_id ) {
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

        function _referentStruct( $structurereferente_id ) {
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

        function ajaxref( $referent_id = null ) { // FIXME
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

        function ajaxstruct( $structurereferente_id = null ) { // FIXME
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
			if( Configure::read( 'nom_form_ci_cg' ) ) {
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
						'Contratinsertion.df_ci',
						'Contratinsertion.datevalidation_ci',
					),
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne_id
					),
					'recursive' => -1
				)
			);

			$this->_setOptions();
			$this->set( compact( 'contratsinsertion' ) );
			$this->set( 'personne_id', $personne_id );
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
						'Contratinsertion.nat_cont_trav',
						'Contratinsertion.duree_cdd',
						'Contratinsertion.duree_engag',
						'Contratinsertion.nature_projet',
						'Contratinsertion.engag_object',
						'Action.libelle',
						'Structurereferente.lib_struc',
						'Structurereferente.typeorient_id',
						'Referent.nom_complet',
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

        function _add_edit( $id = null ) {
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
                $contratinsertion = $this->Contratinsertion->findById( $contratinsertion_id, null, null, -1 );
                $this->assert( !empty( $contratinsertion ), 'invalidParameter' );
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
            //$this->set( 'personne_referent', $personne_referent );

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


            $situationdossierrsa = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->find(
                'first',
                array(
                    'fields' => array(
                        'Situationdossierrsa.id',
                        'Situationdossierrsa.dtclorsa'
                    ),
                    'conditions' => array(
                        'Situationdossierrsa.dossier_id' => $dossier_id
                    ),
                    'recursive' => -1
                )
            );
            $this->assert( !empty( $situationdossierrsa ), 'error500' );
            $this->set( 'situationdossierrsa_id', $situationdossierrsa['Situationdossierrsa']['id'] );

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
                /// Validation
//                 $this->Contratinsertion->set( $this->data );
//                 $valid = $this->Contratinsertion->validates();
// 
// 
//                 ///FIXME
//                 if( isset( $this->data['Actioninsertion'] ) ){
//                     $valid = $this->Contratinsertion->Actioninsertion->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
//                 }

/*
                $dspData = Set::filter( $this->data['Dsp'] );
                if( !empty( $dspData ) ){
                    $this->Contratinsertion->Personne->Dsp->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
                }*/


                ///FIXME
//                     $valid = $this->Contratinsertion->Structurereferente->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
                //

//                 $valid = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
// 
//                 $valid = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
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
// debug($success);
                if( $success ) {
                    $saved = true;
/*
//                     if( !empty( $dspData ) ){
//                         $this->Contratinsertion->Personne->Dsp->create();
//
// }

                    $this->Contratinsertion->Actioninsertion->create();

                    ///FIXME
//                         $this->Contratinsertion->Structurereferente->create();
                    //
                    $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->create();
                    $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->create();

//                     if( !empty( $dspData ) ){
//                         $saved = $this->Contratinsertion->Personne->Dsp->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;
//                     }
                    ///FIXME
                    if( isset( $this->data['Actioninsertion'] ) ){
                        $saved = $this->Contratinsertion->Actioninsertion->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;
                    }
                    ///FIXME
//                         $saved = $this->Contratinsertion->Structurereferente->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;
                    //
					$this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->unbindModel( array( 'hasMany' => array( 'Suspensiondroit' ) ) );
                    $saved = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;

                    $saved = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) && $saved; */
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
// debug( $this->data['Personne']['Dsp'] );
                    $suspensiondroit = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->find(
                        'first',
                        array(
                            'fields' => array(
                                'Suspensiondroit.ddsusdrorsa'
                            ),
                            'conditions' => array(
                                'Suspensiondroit.situationdossierrsa_id' => $situationdossierrsa['Situationdossierrsa']['id']
                            ),
                            'recursive' => -1,
                            'order' => array( 'Suspensiondroit.ddsusdrorsa DESC' )
                        )
                    );
                    if( !empty( $suspensiondroit ) ) {
                        $contratinsertion = Set::merge( $contratinsertion, $suspensiondroit );
                    }

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

                    ///Situation dossier rsa
                    $situationdossierrsa = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->find(
                        'first',
                        array(
                            'fields' => array(
                                'Situationdossierrsa.dtclorsa'
                            ),
                            'conditions' => array(
                                'Situationdossierrsa.dossier_id' => $dossier_id
                            ),
                            'recursive' => -1
                        )
                    );
                    $this->data['Situationdossierrsa']['dtclorsa'] = Set::classicExtract( $situationdossierrsa, 'Situationdossierrsa.dtclorsa' );
                }

                $this->data = Set::merge( $this->data, $this->_getDsp( $personne_id ) );


                /// Si on est en présence d'un deuxième contrat -> Alors renouvellement
                $this->data['Contratinsertion']['rg_ci'] = $nbrCi + 1;
            }

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

        function valider( $contratinsertion_id = null ) {

            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'fields' => array(
                        'Contratinsertion.id',
                        'Contratinsertion.personne_id',
                        'Contratinsertion.structurereferente_id',
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

            $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );


            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id'] ) );
            }

            if( !empty( $this->data ) ) {
                if( $this->Contratinsertion->saveAll( $this->data ) ) {
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
        *
        */

        function notificationsop( $id = null ) {
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
            $foyer = $this->Foyer->find(
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
// debug($contratinsertion);
// die();
            $this->_setOptions();
            $this->Gedooo->generate( $contratinsertion, 'Contratinsertion/notificationop.odt' );
        }

    }
?>
