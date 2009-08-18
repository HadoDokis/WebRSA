<?php
    class ContratsinsertionController extends AppController
    {

        var $name = 'Contratsinsertion';
        var $uses = array( 'Contratinsertion', 'Referent', 'Personne', 'Dossier', 'Option', 'Structurereferente', 'Typocontrat', 'Nivetu', 'Dspp', 'Typeorient', 'Orientstruct', 'Serviceinstructeur', 'Action', 'Adressefoyer', 'Actioninsertion', 'AdresseFoyer', 'Prestform', 'Refpresta', 'DsppNivetu' );


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'decision_ci', $this->Option->decision_ci() );
            $this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
            $this->set( 'emp_occupe', $this->Option->emp_occupe() );
            $this->set( 'duree_hebdo_emp', $this->Option->duree_hebdo_emp() );
            $this->set( 'nat_cont_trav', $this->Option->nat_cont_trav() );
            $this->set( 'duree_cdd', $this->Option->duree_cdd() );
            $this->set( 'duree_engag', $this->Option->duree_engag() );
            $this->set( 'legend_sitfam', $this->Option->sitfam() );
            $this->set( 'legend_typeocclog', $this->Option->typeocclog() );
            $this->set( 'legend_couvsoc', $this->Option->couvsoc() );
            $this->set( 'legend_oridemrsa', $this->Option->oridemrsa() );
            $this->set( 'typevoie', $this->Option->typevoie() );

            $this->set( 'referents', $this->Referent->find( 'list' ) );
            $this->set( 'nivetus', $this->Nivetu->find( 'list' ) );

//             $this->set( 'actions_prev', $this->Option->actions_prev() );

            $this->set( 'lib_action', $this->Option->lib_action() );
            $this->set( 'actions', $this->Action->grouplist( 'aide' ) );
            $this->set( 'actions', $this->Action->grouplist( 'prest' ) );
            $this->set( 'typo_aide', $this->Option->typo_aide() );
        }

        function index( $personne_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            if( !valid_int( $personne_id ) ) {
                $this->cakeError( 'error404' );
            }

            $contratsinsertion = $this->Contratinsertion->find(
                'all',
                array(
                    'conditions' => array(
                        'Contratinsertion.personne_id' => $personne_id
                    )
                )
            ) ;

            // TODO: si personne n'existe pas -> 404
            $this->set( 'contratsinsertion', $contratsinsertion );
            $this->set( 'personne_id', $personne_id );

        }

        function test2() { ///Test Unitaire afin de vérifier le bon fonctionnement des enregistrements
            $data = Array (
                'Contratinsertion' => Array (
                    'id' => 1,
                    'personne_id' => 1,
                    'typocontrat_id' => 2,
                    'structurereferente_id' => 1,
                    'dd_ci' => Array (
                            'day' => '01',
                            'month' => '01',
                            'year' => '2019',
                        ),

                    'duree_engag' => '1',
                    'df_ci' => Array (
                            'day' => '01',
                            'month' => '01',
                            'year' => '2019'
                        ),

                    'diplomes' => 'l!khp',
                    'expr_prof' => 'ùpjho$',
                    'form_compl' => 'ùpjo$j',
                    'actions_prev' => '1',
                    'obsta_renc' => '',
                    'serviceinstructeur_id' => 1,
                    'service_soutien' => 'Service 2, 14 rue la bas 30900 nimes, 04 67 66 66 66',
                    'pers_charg_suivi' => 'auzolat arnaud',
                    'objectifs_fixes' => 'ùpho',
                    'engag_object' => 'ùphầ',
                    'emp_trouv' => '0',
                    'nature_projet' => 'opjầçh',
                    'lieu_saisi_ci' => 'ihiĥ',
                    'date_saisi_ci' => Array (
                            'day' => '30',
                            'month' => '06',
                            'year' => '2009'
                        ),

                    'Le bénéficiaire : ' => 'prosper minnie'
                ),
                'Actioninsertion' => Array (
                    array(
                        'id'            => 1,
                        'lib_action'   => 'P',
                        'dd_action'     => Array (
                            'day' => '30',
                            'month' => '06',
                            'year' => '2009'
                        ),
                        'df_action' => Array (
                            'day' => '30',
                            'month' => '06',
                            'year' => '2009'
                        ),
                    )
                ),
                'Refpresta' => array (
                    'id'                =>  1,
                    'nomrefpresta'      => 'arno auzeolat',
                    'prenomrefpresta'   => 'moihùp'
                ),
                'Prestform' => Array (
                    'id'                    => 1,
                    'actioninsertion_id'    => 1,
                    'refpresta_id'          => 1,
                    'lib_presta'            => '06',
                    'date_presta' => Array (
                        'day'   => '01',
                        'month' => '01',
                        'year'  => '2019'
                    )
                )
            );

            // TODO: validation
            $this->Contratinsertion->begin();

            $this->Refpresta->set( $data['Refpresta'] );
            $saved = $this->Refpresta->save();

            $saved = $this->Contratinsertion->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) ) && $saved;

            $data['Prestform']['actioninsertion_id'] = $this->Contratinsertion->Actioninsertion->id;
            $data['Prestform']['refpresta_id'] = $this->Refpresta->id;
            $this->Prestform->set( $data['Prestform'] );
            $saved = $this->Prestform->save() && $saved;

            if( $saved ) {
                $this->Contratinsertion->commit();
            }
            else {
                $this->Contratinsertion->rollback();
            }
            debug( $data );
        }


        function view( $contratinsertion_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $contratinsertion_id ), 'invalidParameter' );


            $tc = $this->Typocontrat->find(
                'list',
                array(
                    'fields' => array(
                        'Typocontrat.lib_typo'
                    ),
                )
            );
            $this->set( 'tc', $tc );

            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $contratinsertion_id
                    )
                )
            ) ;

            // TODO: si personne n'existe pas -> 404
            $this->set( 'contratinsertion', $contratinsertion );
            $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );
        }

        /**
            Ajout
        */
        function add( $personne_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

            $personne = $this->Personne->findById( $personne_id, null, null, 2 );
            $this->assert( !empty( $personne ), 'invalidParameter' );

            // Peut-on prendre le jeton ?
            $this->Contratinsertion->begin();
            $dossier_id = $this->Personne->dossierId( $personne_id );
            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Contratinsertion->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            $this->set( 'qual', $personne['Personne']['qual'] );
            $this->set( 'dtnai', $personne['Personne']['dtnai'] );
            $this->set( 'nom', $personne['Personne']['nom'] );
            $this->set( 'prenom', $personne['Personne']['prenom'] );

            $dspp_id =  $personne['Dspp']['id'] ;
            $dspp = $this->Dspp->read( null, $dspp_id );
            $this->set( 'couvsoc', $dspp['Dspp']['couvsoc'] );
            $this->set( 'diplomes', $dspp['Dspp']['diplomes'] );

            $foyer_id =  $personne['Personne']['foyer_id'] ;
            $foyer  = $this->Foyer->read( null, $foyer_id );
            $this->set( 'sitfam',  $foyer['Foyer']['sitfam'] );
            $this->set( 'typeocclog',  $foyer['Foyer']['typeocclog'] );

            $dossier_id =  $personne['Foyer']['dossier_rsa_id'] ;
            $dossier = $this->Dossier->read( null, $dossier_id );
            $this->set( 'oridemrsa', $dossier['Detaildroitrsa']['oridemrsa'] );
            $this->set( 'dtdemrsa', $dossier['Dossier']['dtdemrsa'] );
            $this->set( 'matricule', $dossier['Dossier']['matricule'] );

            // Calcul du numéro du contrat d'insertion
            $nbrCi = $this->Contratinsertion->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );

            // Assignation à la vue
            $this->set( 'typeservice', $this->Serviceinstructeur->find( 'list' ) );
            $this->set( 'sr', $this->Structurereferente->list1Options() );
            $this->set( 'tc', $this->Typocontrat->find( 'list' ) );
            $this->set( 'personne', $personne );
            $this->set( 'personne_id', $personne_id );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                $this->data['Contratinsertion']['rg_ci'] = $nbrCi + 1;

//                 debug( $this->data );

                $this->Contratinsertion->set( $this->data );
                $valid = $this->Contratinsertion->validates();

                if( isset( $this->data['Refpresta'] ) ) {
                    $this->Refpresta->create();
                    $this->Refpresta->set( $this->data['Refpresta'] );
                    $valid = $this->Refpresta->validates();
                }

                $valid = $this->Actioninsertion->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
                $valid = $this->Dspp->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;

                if( $valid ) {
                    $saved = true;
                    $this->Actioninsertion->create();
                    if( isset( $this->data['Prestform'] ) ) {
                        $saved = $this->Refpresta->save( $this->data['Refpresta'] ) && $saved;
                        unset( $this->data['Refpresta'] );
                        $this->data['Prestform'][0]['refpresta_id'] = $this->Refpresta->id;
                    }
                    $saved = $this->Dspp->saveAll( $this->data, array( 'validate' => 'first' ) ) && $saved;
                    $saved = $this->Actioninsertion->saveAll( $this->data, array( 'validate' => 'first' ) ) && $saved;

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

                // Récupération des données socio pro (notamment Niveau etude) lié au contrat
                $this->Dspp->unbindModelAll();
                $this->Dspp->bindModel(
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
                $dspp = $this->Dspp->findByPersonneId( $personne_id, null, null, 1 );

                if( empty( $dspp ) ) {
                    $dspp = array(
                        'Dspp' => array(
                            'personne_id' => $personne_id
                        )
                    );
                    $this->Dspp->set( $dspp );
                    if( $this->Dspp->save( $dspp ) ) {
                        $dspp = $this->Dspp->findByPersonneId( $personne_id, null, null, 1 );
                    }
                    else {
                        $this->cakeError( 'error500' );
                    }
                    $this->assert( !empty( $dspp ), 'error500' );
                }
                //$this->assert( !empty( $dspp ), 'error500' ); // FIXME -> error code

                $this->data['Dspp'] = array( 'id' => $dspp['Dspp']['id'] );
                $this->data['Nivetu'] = ( ( isset( $dspp['Nivetu'] ) ) ? $dspp['Nivetu'] : array() );
                if( !empty( $dspp ) ){
                    $this->data['Contratinsertion']['diplomes'] = $dspp['Dspp']['diplomes'];
                }
                // Récupération du services instructeur lié au contrat
                $user = $this->User->findById( $this->Session->read( 'Auth.User.id' ), null, null, 1 );
                $this->assert( !empty( $user ), 'error500' ); // FIXME

                $typevoie = $this->Option->typevoie();
                $type_voie = empty( $user['Serviceinstructeur']['type_voie'] ) ? null : $typevoie[$user['Serviceinstructeur']['type_voie']];

                // Récupération des données utilisateurs lié au contrat
                $this->data['Contratinsertion']['serviceinstructeur_id'] = $user['Serviceinstructeur']['id'];
                $this->data['Contratinsertion']['pers_charg_suivi'] = $user['User']['nom'].' '.$user['User']['prenom'];
                $this->data['Contratinsertion']['service_soutien'] = $user['Serviceinstructeur']['lib_service'].', '.$user['Serviceinstructeur']['num_rue'].' '.$type_voie.' '.$user['Serviceinstructeur']['nom_rue'].' '.$user['Serviceinstructeur']['code_insee'].' '.$user['Serviceinstructeur']['ville'].', '.$user['User']['numtel'];


                // Récupération de la dernière structure referente liée au contrat
                $orientstruct = $this->Orientstruct->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Orientstruct.personne_id' => $personne_id
                        ),
                        'order' => 'Orientstruct.date_propo DESC',
                        'recursive' => -1
                    )
                );

                if( !empty( $orientstruct ) ) {
                    $this->data['Structurereferente']['id'] = $orientstruct['Orientstruct']['structurereferente_id'];
                }

                // Si on est en présence d'un deuxième contrat -> Alors renouvellement
                $this->data['Contratinsertion']['rg_ci'] = $nbrCi + 1;

                if( $this->data['Contratinsertion']['rg_ci'] > 1 ){
                     $this->data['Contratinsertion']['typocontrat_id'] = 2;
                }
                else {
                    $this->data['Contratinsertion']['typocontrat_id'] = 1;
                }

            }

            $this->Contratinsertion->commit();
            $this->render( $this->action, null, 'add_edit' );
        }


        function edit( $contratinsertion_id = null ) {

            // Vérification du format de la variable
            if( !valid_int( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

            $this->set( 'typeservice', $this->Serviceinstructeur->find( 'list' ) );

            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );



            $tc = $this->Typocontrat->find(
                'list',
                array(
                    'fields' => array(
                        'Typocontrat.lib_typo'
                    ),
                )
            );
            $this->set( 'tc', $tc );

            // TODO -> 404
            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                    'Contratinsertion.id' => $contratinsertion_id
                    ),
                    'recursive' => 0
                )
            );
// debug( $contratinsertion );

            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions'=> array(
                        'Personne.id' => $contratinsertion['Personne']['id']
                    ),
                    'recursive' => 2
                )
            );

            $action = $this->Actioninsertion->find(
                'first',
                array(
                    'conditions'=> array(
                        'Actioninsertion.contratinsertion_id' => $contratinsertion['Contratinsertion']['id']
                    ),
                    'recursive' => 1
                )
            );
            $this->set( 'action', $action );


            // Assignation à la vue
            $this->set( 'personne', $personne );

            $conditions = array( );

            if ( empty( $contratinsertion )){
                $this->cakeError( 'error404' );
            }

            $personne_id = $contratinsertion['Personne']['id'];
            $personne = $this->Personne->read( null, $personne_id );
            $this->set( 'qual', $personne['Personne']['qual'] );
            $this->set( 'dtnai', $personne['Personne']['dtnai'] );
            $this->set( 'nom', $personne['Personne']['nom'] );
            $this->set( 'prenom', $personne['Personne']['prenom'] );

            $dspp_id =  $personne['Dspp']['id'] ;
            $dspp = $this->Dspp->read( null, $dspp_id );
            $this->set( 'couvsoc', $dspp['Dspp']['couvsoc'] );

            $foyer_id =  $personne['Personne']['foyer_id'] ;
            $foyer  = $this->Foyer->read( null, $foyer_id );
            $this->set( 'sitfam',  $foyer['Foyer']['sitfam'] );
            $this->set( 'typeocclog',  $foyer['Foyer']['typeocclog'] );

            $dossier_id =  $personne['Foyer']['dossier_rsa_id'] ;
            $dossier = $this->Dossier->read( null, $dossier_id );
            $this->set( 'oridemrsa', $dossier['Detaildroitrsa']['oridemrsa'] );
            $this->set( 'dtdemrsa', $dossier['Dossier']['dtdemrsa'] );
            $this->set( 'matricule', $dossier['Dossier']['matricule'] );

            $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );

            if( !empty( $this->data ) ) {
                $this->Dspp->create();
                $this->Dspp->set( $this->data );
                $valid = $this->Dspp->validates( $this->data );

                $this->Nivetu->create();
                $this->Nivetu->set( $this->data );
//                 $valid = $this->Nivetu->validates( $this->data );
// debug( $valid );
//                 $valid = $this->DsppNivetu->validateErrors;

                $valid = $this->Contratinsertion->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) && $valid;

                if( $valid ) {
                    $this->Dspp->begin();
                    $saved = $this->Contratinsertion->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );
                    $saved = $this->Dspp->save( $this->data ) && $saved;
                    if( $saved ) {
                        $this->Dspp->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id']) );
                    }
                    else {
                        $this->Dspp->rollback();
                    }
                }
            }
            else {
                // Récupération du services instructeur lié au contrat
                $user = $this->User->find( 'first', array( 'conditions' => array( 'User.id' => $this->Session->read( 'Auth.User.id' ) ), 'recursive' => 0 ) );
                $contratinsertion['Contratinsertion']['serviceinstructeur_id'] = $user['Serviceinstructeur']['id'];
                $this->data = $contratinsertion;


                $dspp = $this->Dspp->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Dspp.personne_id' => $contratinsertion['Personne']['id']
                        )
                    )
                );

                if( !empty( $dspp ) ){
                    $this->data['Dspp']['id'] = $dspp['Dspp']['id'];
                    $this->data['Nivetu'] = $dspp['Nivetu'];
                }
                else{
                    //TODO : cakeError

                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }


        function valider( $contratinsertion_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $contratinsertion_id
                    )
                )
            );

            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions'=> array(
                        'Personne.id' => $contratinsertion['Personne']['id']
                    ),
                    'recursive' => 2
                )
            );

            // Assignation à la vue
            $this->set( 'personne', $personne );

            if ( empty( $contratinsertion )){
                $this->cakeError( 'error404' );
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

}
?>
