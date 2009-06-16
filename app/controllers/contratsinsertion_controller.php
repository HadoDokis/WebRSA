<?php
    class ContratsinsertionController extends AppController
    {

        var $name = 'Contratsinsertion';
        var $uses = array( 'Contratinsertion', 'Referent', 'Personne', 'Dossier', 'Option', 'Structurereferente', 'Typocontrat', 'Nivetu', 'Dspp', 'Typeorient', 'Orientstruct', 'Serviceinstructeur', 'Action', 'Actioninsertion' );


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'decision_ci', $this->Option->decision_ci() );
            $this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
            $this->set( 'emp_occupe', $this->Option->emp_occupe() );
            $this->set( 'duree_hebdo_emp', $this->Option->duree_hebdo_emp() );
            $this->set( 'nat_cont_trav', $this->Option->nat_cont_trav() );
            $this->set( 'duree_cdd', $this->Option->duree_cdd() );
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

            //$this->Contratinsertion->recursive = -1;
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

        function view( $contratinsertion_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            if( !valid_int( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

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
            if( !valid_int( $personne_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Calcul du numéro du contrat d'insertion
            $nbrCi = $this->Contratinsertion->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );

            $contrat = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $personne_id
                    ),
                    'recursive' => 1
                )
            );
            $this->set( 'contrat', $contrat );

// debug( $contrat );
            $typeservice = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        'Serviceinstructeur.lib_service'
                    ),
                )
            );
            $this->set( 'typeservice', $typeservice );


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
                        'Typocontrat.id',
                        'Typocontrat.lib_typo'
                    ),
                    'order'  => array( 'Typocontrat.id ASC' )
                )
            );
            $this->set( 'tc', $tc );



            $personne = $this->Personne->find( 
                'first', 
                array( 
                    'conditions'=> array( 
                        'Personne.id' => $personne_id 
                    ),
                    'recursive' => 2
                )
            );
            $this->set( 'foyer_id', $personne['Personne']['foyer_id'] );

// debug( $this->data);


            $conditions = array( 'Personne.id' => $personne_id );
            $personne = $this->Personne->find( 'first', array( 'conditions' => $conditions ) );

            // Assignation à la vue
            $this->set( 'personne', $personne );
            $this->set( 'personne_id', $personne_id );


            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Contratinsertion->saveAll( $this->data ) ) {
                    $this->data['Contratinsertion']['rg_ci'] = $nbrCi + 1;
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index/', $personne_id ) );
                }
                else {
                    $this->Session->setFlash( 'Impossible d\'enregistrer.', 'flash/error' );
                }
            }
            else{
             // Récupération des données socio pro (notamment Niveau etude) lié au contrat
                $dspp = $this->Dspp->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Dspp.personne_id' => $personne_id
                        )
                    )
                );
                $this->data['Nivetu'] = $dspp['Nivetu'];


                // Récupération du services instructeur lié au contrat
                $user = $this->User->find( 'first', array( 'conditions' => array( 'User.id' => $this->Session->read( 'Auth.User.id' ) ) ) );
                $this->data['Contratinsertion']['serviceinstructeur_id'] = $user['Serviceinstructeur']['id'];


                // Récupération des données utilisateurs lié au contrat
                $this->data['Contratinsertion']['pers_charg_suivi'] = $user['User']['nom'].' '.$user['User']['prenom'];
                $this->data['Contratinsertion']['service_soutien'] = $user['Serviceinstructeur']['lib_service'].', '.$user['Serviceinstructeur']['num_rue'].' '.$user['Serviceinstructeur']['type_voie'].' '.$user['Serviceinstructeur']['nom_rue'].', '.$user['User']['numtel'];


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

                $tc = $this->Typocontrat->find(
                    'list',
                    array(
                        'fields' => array(
                            'Typocontrat.id',
                            'Typocontrat.lib_typo'
                        )
                    )
                );

                if( $this->data['Contratinsertion']['rg_ci'] > 1 ){
                     $this->data['Contratinsertion']['typocontrat_id'] = 2/*$tc['Typocontrat']['id']*/;
                }
                else {
                    $this->data['Contratinsertion']['typocontrat_id'] = 1;
                }
            }


            $this->render( $this->action, null, 'add_edit' );
        }


        function edit( $contratinsertion_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }


            $typeservice = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        'Serviceinstructeur.lib_service'
                    ),
                )
            );
            $this->set( 'typeservice', $typeservice );


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
                // Récupération du services instructeur lié au contrat
                $user = $this->User->find( 'first', array( 'conditions' => array( 'User.id' => $this->Session->read( 'Auth.User.id' ) ), 'recursive' => 0 ) );

                $this->data = $contratinsertion;

                $dspp = $this->Dspp->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Dspp.personne_id' => $contratinsertion['Personne']['id']
                        )
                    )
                );

                $this->data['Nivetu'] = $dspp['Nivetu'];
                $this->data['Contratinsertion']['serviceinstructeur_id'] = $user['Serviceinstructeur']['id'];
            }
            $this->render( $this->action, null, 'add_edit' );
        }

/*
        function delete( $contratinsertion_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array( 'conditions' => array( 'Contratinsertion.id' => $contratinsertion_id )
                )
            );

            // Mauvais paramètre
            if( empty( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Contratinsertion->delete( array( 'Contratinsertion.id' => $contratinsertion_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée' );
                $this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
            }
        }*/

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
