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

    class ContratsinsertionController extends AppController
    {

        var $name = 'Contratsinsertion';
        var $uses = array( 'Contratinsertion', 'Option', 'Action', 'Referent', 'Personne', 'Dossier', 'Structurereferente', 'Typocontrat', 'Nivetu', 'Dspp', 'Typeorient', 'Orientstruct', 'Serviceinstructeur', 'Action', 'Adressefoyer', 'Actioninsertion', 'AdresseFoyer', 'Prestform', 'Refpresta', 'DsppNivetu', 'PersonneReferent' );
        var $helpers = array( 'Ajax' );
        var $components = array( 'RequestHandler' );
        var $aucunDroit = array( 'ajax', 'ajaxreffonct', 'ajaxrefcoord', 'ajaxreferent', 'ajaxstructadr', 'ajaxraisonci' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        // FIXME -> à nettoyer
        function beforeFilter() {
            parent::beforeFilter();
            if( in_array( $this->action, array( 'index', 'add', 'edit', 'view', 'valider' ) ) ) {
                $this->set( 'tc', $this->Contratinsertion->Typocontrat->find( 'list' ) );
                $this->set( 'decision_ci', $this->Option->decision_ci() );
            }

            if( in_array( $this->action, array( 'add', 'edit', 'view' ) ) ) {
                $this->set( 'formeci', $this->Option->formeci() );
            }

            if( in_array( $this->action, array( 'add', 'edit'/*, 'view'*/ ) ) ) {

                $forme_ci = array( 'S' => 'Simple', 'C' => 'Complexe' );
                $this->set( 'forme_ci', $forme_ci );
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
                $this->set( 'typevoie', $this->Option->typevoie() );
                $this->set( 'fonction_pers', $this->Option->fonction_pers() );
                $this->set( 'nivetus', $this->Contratinsertion->Personne->Dspp->Nivetu->find( 'list' ) );
                $this->set( 'lib_action', $this->Option->lib_action() );
                $this->set( 'typo_aide', $this->Option->typo_aide() );
                $this->set( 'soclmaj', $this->Option->natpfcre( 'soclmaj' ) );
                $this->set( 'rolepers', $this->Option->rolepers() );
//                  $this->set( 'typeservice', $this->Serviceinstructeur->find( 'first' ) );
                $this->set( 'sr', $this->Contratinsertion->Structurereferente->listeParType( array( 'contratengagement' => true ) ) );
                $this->set( 'referents', $this->Contratinsertion->Structurereferente->Referent->find( 'list' ) );
                //$this->set( 'actions', $this->Action->grouplist( 'aide' ) );
                $this->set( 'actions', $this->Action->grouplist( 'prest' ) );
            }
        }

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
        *   Ajax pour les coordonnées du référent
        *** *******************************************************************/

        function ajaxrefcoord() { // FIXME
            Configure::write( 'debug', 0 );
            $this->data['Contratinsertion']['referent_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Contratinsertion']['referent_id'] );
            $referent = $this->Contratinsertion->Structurereferente->Referent->findbyId( Set::extract( $this->data, 'Contratinsertion.referent_id' ), null, null, -1 );
            echo $referent['Referent']['email']. '<br/>' .$referent['Referent']['numero_poste'];
            $this->render( null, 'ajax' );
        }

        /** ********************************************************************
        *   Ajax pour la fonction du référent
        *** *******************************************************************/

        function ajaxreffonct() { // FIXME
            Configure::write( 'debug', 0 );
            $this->data['Contratinsertion']['referent_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Contratinsertion']['referent_id'] );
            $referent = $this->Contratinsertion->Structurereferente->Referent->findbyId( Set::extract( $this->data, 'Contratinsertion.referent_id' ), null, null, -1 );
            echo $referent['Referent']['fonction'];
            $this->render( null, 'ajax' );
        }

        /** ********************************************************************
        *   Ajax pour le nom du référent
        *** *******************************************************************/

        function ajaxreferent() { // FIXME
            Configure::write( 'debug', 0 );
            $qual = $this->Option->qual();
            $this->data['Contratinsertion']['referent_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Contratinsertion']['referent_id'] );
            $referent = $this->Contratinsertion->Structurereferente->Referent->findbyId( Set::extract( $this->data, 'Contratinsertion.referent_id' ), null, null, -1 );
            echo Set::enum( Set::classicExtract( $referent, 'Referent.qual') , $qual ).' '.$referent['Referent']['nom'].' '.$referent['Referent']['prenom'];
            $this->render( null, 'ajax' );
        }

        /** ********************************************************************
        *   Ajax pour les coordonnées de la structure référente
        *** *******************************************************************/

        function ajaxstructadr() { // FIXME
            Configure::write( 'debug', 0 );
            $struct = $this->Contratinsertion->Structurereferente->findbyId( Set::extract( $this->data, 'Contratinsertion.structurereferente_id' ), null, null, -1 );
            echo $struct['Structurereferente']['num_voie'].' '.$struct['Structurereferente']['type_voie'].' '.$struct['Structurereferente']['nom_voie'].'<br/>'.$struct['Structurereferente']['code_postal'].' '.$struct['Structurereferente']['ville'];
            $this->render( null, 'ajax' );
        }


        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ){
            $nbrPersonnes = $this->Contratinsertion->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
            $this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );


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


            $this->set( compact( 'orientstruct', 'contratsinsertion' ) );
            $this->set( 'personne_id', $personne_id );

        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function view( $contratinsertion_id = null ){
            $contratinsertion = $this->Contratinsertion->findById( $contratinsertion_id );
            $this->assert( !empty( $contratinsertion ), 'invalidParameter' );

            $this->set( 'tc', $this->Contratinsertion->Typocontrat->find( 'list' ) );

            $codesaction = $this->Action->find( 'list', array( 'fields' => array( 'code', 'libelle' ) ) );
            $codesaction = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.engag_object' ), $codesaction );
//             $codesaction = empty( $contratinsertion['Contratinsertion']['engag_object'] ) ? null : $codesaction[$contratinsertion['Contratinsertion']['engag_object']];
            $this->set( 'codesaction', $codesaction );

            $this->set( 'contratinsertion', $contratinsertion );
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

        /** ********************************************************************
        *
        *** *******************************************************************/

        function _add_edit( $id = null ) {
            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index', $id ) );
            }

// 			$tc = $this->Contratinsertion->Typocontrat->find( 'list' );
// 			$premierContratKey = array_search( 'Premier contrat', $tc );
//
// 			if( !empty( $premierContratKey ) ) {
// 				$tcPremierContrat = array( $premierContratKey => $tc[$premierContratKey] );
// 				$tcAutresContrats = $tc;
// 				unset( $tcAutresContrats[$premierContratKey] );
// 			}

            $valueFormeci = null;
            if( $this->action == 'add' ) {
                $contratinsertion_id = null;
                $personne_id = $id;
                $nbrPersonnes = $this->Contratinsertion->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
                $this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );
                $valueFormeci = 'S';

				$nbContratsPrecedents = $this->Contratinsertion->find( 'count', array( 'recursive' => -1, 'conditions' => array( 'Contratinsertion.personne_id' => $personne_id ) ) );
				if( $nbContratsPrecedents >= 1 ) {
// 					$tc = $tcAutresContrats;
                    $tc = 'Renouvellement';
				}
				else {
// 					$tc = $tcPremierContrat;
                    $tc = 'Premier contrat';
				}
            }
            else if( $this->action == 'edit' ) {
                $contratinsertion_id = $id;
                $contratinsertion = $this->Contratinsertion->findById( $contratinsertion_id, null, null, -1 );
                $this->assert( !empty( $contratinsertion ), 'invalidParameter' );
                $personne_id = Set::classicExtract( $contratinsertion, 'Contratinsertion.personne_id' );
                $valueFormeci = Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' );

// 				if( Set::classicExtract( $contratinsertion, 'Contratinsertion.rg_ci' ) > 1 ) {
// // 					$tc = $tcAutresContrats;
//                     $tc = 'Renouvellement';
// 				}
// 				else {
// // 					$tc = $tcPremierContrat;
//                     $tc = 'Premier contrat';
// 				}
                $tc = Set::classicExtract( $contratinsertion, 'Contratinsertion.numcontrat' );

            }

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

            $personne = $this->Contratinsertion->Personne->detailsCi( $personne_id );
// debug($personne);
            /// Recherche du type d'orientation
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
            $this->set( 'orientstruct', $orientstruct );

            if( !empty( $orientstruct ) ) {
                $struct = $this->Contratinsertion->Structurereferente->findById( Set::classicExtract( $orientstruct, 'Orientstruct.structurereferente_id' ), null, null, -1 );
                $this->set( 'struct', $struct );

                $referents = $this->_referentStruct( Set::classicExtract( $struct, 'Structurereferente.id' ) );
                $this->set( 'referents', $referents );


                /// Recherche du type d'orientation de premier niveau
                $this->set( 'typeOrientation', $this->_libelleTypeorientNiv0( Set::classicExtract( $orientstruct, 'Orientstruct.typeorient_id' ) ) );

            }



            ///Récupération du référent si ce dernier existe déjà
            $personne_referent = $this->PersonneReferent->findByPersonneId( $personne_id, null, null, -1 );
            $this->set( 'personne_referent', $personne_referent );
            $this->set( 'refs', $this->Referent->find( 'list') );
            if( !empty($personne_referent) ){
                $struct = $this->Structurereferente->find( 'first', array( 'conditions' => array( 'Structurereferente.id' => Set::classicExtract( $personne_referent, 'PersonneReferent.structurereferente_id' ) ) ) );
                $this->set( 'struct', $struct );

                $referent = $this->Referent->find( 'first', array( 'conditions' => array( 'Referent.id' => Set::classicExtract( $personne_referent, 'PersonneReferent.referent_id' ) ) ) );
                $this->set( 'referent', $referent );
            }
            $personne['PersonneReferent'] = $personne_referent['PersonneReferent'];


            if( empty( $personne_referent ) ){
                $refstruct = $this->Referent->listOptions();
                $this->set( 'refstruct', $refstruct );
            }
// debug($personne);
            /// Calcul du numéro du contrat d'insertion
            $nbrCi = $this->Contratinsertion->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );
            $this->set( 'nbrCi', $nbrCi );

            $this->set( 'personne', $personne );
            $this->set( 'valueFormeci', $valueFormeci );
            /// Essai de sauvegarde
            if( !empty( $this->data ) ) {
                $this->data['Contratinsertion']['rg_ci'] = $nbrCi + 1;

                $contratinsertionRaisonCi = Set::classicExtract( $this->data, 'Contratinsertion.raison_ci' );
                if( $contratinsertionRaisonCi == 'S' ) {
                    $this->data['Contratinsertion']['avisraison_ci'] = Set::classicExtract( $this->data, 'Contratinsertion.avisraison_suspension_ci' );
                }
                else if( $contratinsertionRaisonCi == 'R' ){
                    $this->data['Contratinsertion']['avisraison_ci'] = Set::classicExtract( $this->data, 'Contratinsertion.avisraison_radiation_ci' );
                }

                /*if( $this->data['Contratinsertion']['raison_ci'] == 'S' ) {
                    $this->data['Contratinsertion']['avisraison_ci'] = $this->data['Contratinsertion']['avisraison_suspension_ci'];
                }
                else if( $this->data['Contratinsertion']['raison_ci'] == 'R' ){
                    $this->data['Contratinsertion']['avisraison_ci'] =  $this->data['Contratinsertion']['avisraison_radiation_ci'];
                }*/

                /// Validation
                $this->Contratinsertion->set( $this->data );
                $valid = $this->Contratinsertion->validates();
                $valid = $this->Contratinsertion->Actioninsertion->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
                $valid = $this->Contratinsertion->Personne->Dspp->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
                //FIXME
                    $valid = $this->Contratinsertion->Structurereferente->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
                //
                $valid = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
                $valid = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;

                if( $valid ) {
                    $saved = true;
                    $this->Contratinsertion->Personne->Dspp->create();
                    $this->Contratinsertion->Actioninsertion->create();
                    //FIXME
                        $this->Contratinsertion->Structurereferente->create();
                    //
                    $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->create();
                    $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->create();

                    $saved = $this->Contratinsertion->Personne->Dspp->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;
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
                if( !empty( $contratinsertion ) ) {
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

                    ///Situation dossier rsa
                    $situationdossierrsa = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->findByDossierRsaId( $dossier_id, null, null, -1 );
                    $this->data['Situationdossierrsa']['dtclorsa'] = Set::classicExtract( $situationdossierrsa, 'Situationdossierrsa.dtclorsa' );
                }

                /// Récupération des données socio pro (notamment Niveau etude) lié au contrat
                $this->Contratinsertion->Personne->Dspp->unbindModelAll();
                $this->Contratinsertion->Personne->Dspp->bindModel(
                    array(
                        'hasAndBelongsToMany' => array(
                            'Nivetu' => array(
                                'classname' => 'Nivetu',
                                'joinTable' => 'dspps_nivetus',
                                'foreignKey' => 'dspp_id',
                                'associationForeignKey' => 'nivetu_id'
                            )
                        )
                    )
                );
                $dspp = $this->Contratinsertion->Personne->Dspp->findByPersonneId( $personne_id, null, null, 1 );

                if( empty( $dspp ) ) {
                    $dspp = array( 'Dspp' => array( 'personne_id' => $personne_id ) );
                    $this->Contratinsertion->Personne->Dspp->set( $dspp );
                    if( $this->Contratinsertion->Personne->Dspp->save( $dspp ) ) {
                        $dspp = $this->Contratinsertion->Personne->Dspp->findByPersonneId( $personne_id, null, null, 1 );
                    }
                    else {
                        $this->cakeError( 'error500' );
                    }
                    $this->assert( !empty( $dspp ), 'error500' );
                }
                //$this->assert( !empty( $dspp ), 'error500' ); // FIXME -> error code

                $this->data['Dspp'] = array( 'id' => $dspp['Dspp']['id'], 'personne_id' => $dspp['Dspp']['personne_id'] );
                $this->data['Nivetu'] = ( ( isset( $dspp['Nivetu'] ) ) ? $dspp['Nivetu'] : array() );

                /// Récupération du services instructeur lié au contrat
                $user = $this->User->findById( $this->Session->read( 'Auth.User.id' ), null, null, 1 );
                $this->assert( !empty( $user ), 'error500' ); // FIXME

                ///FIXME Suppression du blocage du Contrat donc modif des données à récupérer pour la struct referente
//                 $this->data['Contratinsertion']['structurereferente_id'] = $personne['Orientstruct']['structurereferente_id'];
//                 $this->data['Contratinsertion']['structurereferente_id'] = $personne['Structurereferente']['id'];
//                 $this->set( 'StructureAdresse', $referent['Structurereferente']['numvoie'].' '. );


                /// Si on est en présence d'un deuxième contrat -> Alors renouvellement
                $this->data['Contratinsertion']['rg_ci'] = $nbrCi + 1;
/*
                if( $this->data['Contratinsertion']['rg_ci'] > 1 ){

                     $this->data['Contratinsertion']['typocontrat_id'] = 2; ///FIXME: nb magique !!!
                }
                else {
                    $this->data['Contratinsertion']['typocontrat_id'] = 1;
                }*/
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

            if( !empty( $referent_id ) ) {
                $contratinsertionReferentId = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Contratinsertion']['referent_id'] );
                $referent = $this->Contratinsertion->Structurereferente->Referent->findbyId( $contratinsertionReferentId, null, null, -1 );
                $this->set( 'ReferentEmail', $referent['Referent']['email']. '<br/>' .$referent['Referent']['numero_poste'] );
                $this->set( 'ReferentFonction', $referent['Referent']['fonction'] );
                $this->set( 'ReferentNom', $referent['Referent']['nom'].' '.$referent['Referent']['prenom'] );

            }
// debug($this);
            $this->Contratinsertion->commit();
            $this->render( $this->action, null, 'add_edit' );
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
        }

		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}
    }
?>
