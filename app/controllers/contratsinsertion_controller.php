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

        /** ********************************************************************
        *
        *** *******************************************************************/
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
                $this->set( 'lib_action', $this->Option->lib_action() );
                $this->set( 'typo_aide', $this->Option->typo_aide() );
                $this->set( 'soclmaj', $this->Option->natpfcre( 'soclmaj' ) );
                $this->set( 'rolepers', $this->Option->rolepers() );

                $this->set( 'zoneprivilegie', $this->Zonegeographique->find( 'list' ) );

//                 $this->set( 'sr', $this->Contratinsertion->Structurereferente->listeParType( array( 'contratengagement' => true ) ) );
//                 $this->set( 'referents', $this->Contratinsertion->Structurereferente->Referent->find( 'list' ) );

                $this->set( 'actions', $this->Action->grouplist( 'prest' ) );
            }

        }
        // FIXME -> à nettoyer
//         function beforeFilter() {
//             parent::beforeFilter();
// 
// 
// 
//             }
//         }

        /** ********************************************************************
        *
        *** *******************************************************************/

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

        /** ********************************************************************
        *
        *** *******************************************************************/

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

        /** ********************************************************************
        *   Ajax pour les coordonnées du référent APRE
        *** *******************************************************************/

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

        /** ********************************************************************
        *   Ajax pour les coordonnées de la structure référente liée
        *** *******************************************************************/

        function ajaxstruct( $structurereferente_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
            $this->set( 'typesorients', $this->Typeorient->find( 'list', array( 'fields' => array( 'lib_type_orient' ) ) ) );

            $dataStructurereferente_id = Set::extract( $this->data, 'Contratinsertion.structurereferente_id' );
            $structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );

            $struct = $this->Contratinsertion->Structurereferente->findbyId( $structurereferente_id, null, null, -1 );
            $this->set( 'struct', $struct );
            $this->render( 'ajaxstruct', 'ajax' );
        }


        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ){
            $nbrPersonnes = $this->Contratinsertion->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
            $this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

            /**
            *   Recherche du nombre de référent lié au parcours de la personne
            *   Si aucun alors message d'erreur signalant l'absence de référent (cg66)
            **/
            $persreferent = $this->PersonneReferent->find( 'count', array('conditions' => array( 'PersonneReferent.personne_id' => $personne_id ), 'recursive' => -1 ) );
            $this->set( compact( 'persreferent' ) );

            $contratsinsertion = $this->Contratinsertion->find(
                'all',
                array(
                    'conditions' => array(
                        'Contratinsertion.personne_id' => $personne_id
                    )
                )
            );


            ///S'il n'y a pas d'orientation, IMPOSSIBLE de créer un contrat
            $orientstruct = $this->Contratinsertion->Structurereferente->Orientstruct->find(
                'first',
                array(
                    'conditions' => array(
                        'Orientstruct.personne_id' => $personne_id,
                        'Orientstruct.typeorient_id IS NOT NULL',
                        'Orientstruct.statut_orient' => 'Orienté'
                    ),
                    'order' => 'Orientstruct.date_valid DESC'
                )
            );

            if( !empty( $orientstruct ) ){
                ///S'il n'y a pas de référents, IMPOSSIBLE de créer un contrat
                $referents = $this->Referent->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Referent.structurereferente_id' => Set::classicExtract( $orientstruct, 'Orientstruct.structurereferente_id' )
                        ),
                        'recursive' => -1
                    )
                );

                $this->set( 'referents', $referents );
                $sr = $this->Contratinsertion->Structurereferente->listeParType( array( 'contratengagement' => true ) );
                $struct = Set::enum( Set::classicExtract( $orientstruct, 'Orientstruct.structurereferente_id' ), $sr );
                $this->set( 'struct', $struct );
            }
            else if( empty( $orientstruct ) ) {
                $sr = $this->Contratinsertion->Structurereferente->listeParType( array( 'contratengagement' => true ) );
            }





            $this->_setOptions();
            $this->set( compact( 'orientstruct', 'contratsinsertion' ) );
            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, '/contratsinsertion/index_'.Configure::read( 'nom_form_ci_cg' ) ); ///FIXME: pas propre, mais pr le moment ça marche afin d'eviter de tout renommer

        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function view( $contratinsertion_id = null ){
            $contratinsertion = $this->Contratinsertion->findById( $contratinsertion_id );
            $this->assert( !empty( $contratinsertion ), 'invalidParameter' );

            $codesaction = $this->Action->find( 'list', array( 'fields' => array( 'code', 'libelle' ) ) );
            $codesaction = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.engag_object' ), $codesaction );
            $structures = $this->Structurereferente->find( 'list', array( 'fields' => array( 'lib_struc' ) ) );
            $referents = $this->Referent->find( 'list', array( 'fields' => array( 'nom' ) ) );
            $typesorients = $this->Typeorient->find( 'list', array( 'fields' => array( 'lib_type_orient' ) ) );
//             $codesaction = empty( $contratinsertion['Contratinsertion']['engag_object'] ) ? null : $codesaction[$contratinsertion['Contratinsertion']['engag_object']];
            $this->set( 'codesaction', $codesaction );

            $this->set( 'contratinsertion', $contratinsertion );
            $this->set( 'structures', $structures );
            $this->set( 'referents', $referents );
            $this->set( 'typesorients', $typesorients );
            $this->_setOptions();
            $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

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
            $dsp = $this->Contratinsertion->Personne->Dsp->findByPersonneId( $personne_id, null, null, 1 );

            $dspData = Set::filter( $dsp['Dsp'] );
            if( empty( $dsp ) && !empty( $dspData )  ){
                $dsp = array( 'Dsp' => array( 'personne_id' => $personne_id ) );

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

            return $return;
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

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
                    'conditions' => array(
                        'Contratinsertion.personne_id' => $personne_id
                    ),
                    'recursive' => -1,
                    'order' => 'Contratinsertion.date_saisi_ci DESC'
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


            $situationdossierrsa = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->findByDossierRsaId( $dossier_id, null, null, -1 );
            $this->assert( !empty( $situationdossierrsa ), 'error500' );
            $this->set( 'situationdossierrsa_id', $situationdossierrsa['Situationdossierrsa']['id'] );

            //On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
            $user = $this->User->findById( $this->Session->read( 'Auth.User.id' ), null, null, 0 );
            $user_id = Set::classicExtract( $user, 'User.id' );
            $personne = $this->Contratinsertion->Personne->detailsCi( $personne_id, $user_id );

            /// Calcul du numéro du contrat d'insertion
            $nbrCi = $this->Contratinsertion->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );
            $this->set( 'nbrCi', $nbrCi );
            $this->set( 'personne', $personne );
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
                        $this->data['Contratinsertion']['datevalidation_ci'] = date( 'Y-m-d' );
                    }
                }
                /// Validation
                $this->Contratinsertion->set( $this->data );
                $valid = $this->Contratinsertion->validates();
                $valid = $this->Contratinsertion->Actioninsertion->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;

/*
                $dspData = Set::filter( $this->data['Dsp'] );
                if( !empty( $dspData ) ){
                    $this->Contratinsertion->Personne->Dsp->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
                }*/
                $this->data = Set::merge( $this->data, $this->_getDsp( $personne_id ) );
                //FIXME
                    $valid = $this->Contratinsertion->Structurereferente->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
                //
                $valid = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
                $valid = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;


                if( $valid ) {
                    $saved = true;

//                     if( !empty( $dspData ) ){
//                         $this->Contratinsertion->Personne->Dsp->create();
//                     }
                    $this->Contratinsertion->Actioninsertion->create();
                    //FIXME
                        $this->Contratinsertion->Structurereferente->create();
                    //
                    $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->create();
                    $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->create();

//                     if( !empty( $dspData ) ){
//                         $saved = $this->Contratinsertion->Personne->Dsp->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;
//                     }
                    $saved = $this->Contratinsertion->Actioninsertion->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;
                    //FIXME
                        $saved = $this->Contratinsertion->Structurereferente->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;
                    //
					$this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->unbindModel( array( 'hasMany' => array( 'Suspensiondroit' ) ) );
                    $saved = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;

                    $saved = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;

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
                    $situationdossierrsa = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->findByDossierRsaId( $dossier_id, null, null, -1 );
                    $this->data['Situationdossierrsa']['dtclorsa'] = Set::classicExtract( $situationdossierrsa, 'Situationdossierrsa.dtclorsa' );
                }

                /*/// Récupération des données socio pro (notamment Niveau etude) lié au contrat
                $this->Contratinsertion->Personne->Dsp->unbindModelAll();
                $dsp = $this->Contratinsertion->Personne->Dsp->findByPersonneId( $personne_id, null, null, 1 );

                $dspData = Set::filter( $dsp['Dsp'] );
                if( empty( $dsp ) && !empty( $dspData )  ){
                    $dsp = array( 'Dsp' => array( 'personne_id' => $personne_id ) );

                    $this->Contratinsertion->Personne->Dsp->set( $dsp );
                    if( $this->Contratinsertion->Personne->Dsp->save( $dsp ) ) {
                        $dsp = $this->Contratinsertion->Personne->Dsp->findByPersonneId( $personne_id, null, null, 1 );
                    }
                    else {
                        $this->cakeError( 'error500' );
                    }
                    $this->assert( !empty( $dsp ), 'error500' );
                }
                $this->data['Dsp'] = array(
                    'id' => $dsp['Dsp']['id'],
                    'personne_id' => $dsp['Dsp']['personne_id']
                );
                $this->data['Dsp']['nivetu'] = ( ( isset( $dsp['Dsp']['nivetu'] ) ) ? $dsp['Dsp']['nivetu'] : null );*/
                $this->data = Set::merge( $this->data, $this->_getDsp( $personne_id ) );

                /// Récupération du services instructeur lié au contrat
                $user = $this->User->findById( $this->Session->read( 'Auth.User.id' ), null, null, 1 );
                $this->assert( !empty( $user ), 'error500' ); // FIXME

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
                $struct = $this->Contratinsertion->Structurereferente->findbyId( Set::extract( $this->data, 'Contratinsertion.structurereferente_id' ), null, null, -1 );
                $this->set( 'StructureAdresse', $struct['Structurereferente']['num_voie'].' '.$struct['Structurereferente']['type_voie'].' '.$struct['Structurereferente']['nom_voie'].'<br/>'.$struct['Structurereferente']['code_postal'].' '.$struct['Structurereferente']['ville'] );
            }

            $referent_id = Set::classicExtract( $this->data, 'Contratinsertion.referent_id' );
            $referent_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $referent_id );
            $this->set( 'referent_id', $referent_id );

            if( !empty( $referent_id ) && !empty( $this->data['Contratinsertion']['referent_id'] ) ) {
                $contratinsertionReferentId = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Contratinsertion']['referent_id'] );
                $referent = $this->Contratinsertion->Structurereferente->Referent->findbyId( $contratinsertionReferentId, null, null, -1 );
                $this->set( 'ReferentEmail', $referent['Referent']['email']. '<br/>' .$referent['Referent']['numero_poste'] );
                $this->set( 'ReferentFonction', $referent['Referent']['fonction'] );
                $this->set( 'ReferentNom', $referent['Referent']['nom'].' '.$referent['Referent']['prenom'] );

            }

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

        /** ********************************************************************
        *
        *** *******************************************************************/

        function valider( $contratinsertion_id = null ) {

            $contratinsertion = $this->Contratinsertion->findById( $contratinsertion_id );
            $this->assert( !empty( $contratinsertion ), 'invalidParameter' );

            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id'] ) );
            }

            $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );

            if( !empty( $this->data ) ) {
                if( $this->Contratinsertion->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                   $this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id']) );
                }
            }
            else {
                $this->data = $contratinsertion;
            }
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
                        'Dossier.id' => $contratinsertion['Foyer']['dossier_rsa_id']
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
