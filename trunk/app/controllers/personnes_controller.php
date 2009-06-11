<?php
    class PersonnesController extends AppController
    {
        var $name = 'Personnes';
        var $uses = array( 'Personne', 'Option' );

        function __construct() {
            parent::__construct();
            $this->components[] = 'Jetons';
        }


        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'nationalite', $this->Option->nationalite() );
            $this->set( 'typedtnai', $this->Option->typedtnai() );
            $this->set( 'pieecpres', $this->Option->pieecpres() );
            $this->set( 'sexe', $this->Option->sexe() );
            return $return;
        }

        /**
            Voir les personnes d'un foyer
        */
        function index( $foyer_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'error404' );

            // Recherche des personnes du foyer
            $personnes = $this->Personne->find(
                'all',
                array(
                    'conditions' => array( 'Personne.foyer_id' => $foyer_id ),
                    'recursive' => 2
                )
            );

            // Assignations à la vue
            $this->set( 'foyer_id', $foyer_id );
            $this->set( 'personnes', $personnes );
        }

        /**
            Voir une personne en particulier
        */
        function view( $id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'error404' );

            // Recherche de la personne
            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array( 'Personne.id' => $id ),
                    'recursive' => 2
                )
            );

            // Mauvais paramètre
            $this->assert( !empty( $personne ), 'error404' );

            // Assignation à la vue
            $this->set( 'personne', $personne );
        }

        /**
            Ajout d'une personne au foyer
        */

            function add( $foyer_id = null ){
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'error404' );

                if( !empty( $this->data ) ){
                    if( $this->Personne->save( $this->data ) ){
                        $this->Session->setFlash( 'Enregistrement réussi', 'flash/success' );
                        $this->redirect( array( 'controller' => 'personnes', 'action' => 'index', $foyer_id ) );
                    }
                }
                else {
                    $roles = $this->Personne->find(
                        'list',
                        array(
                            'fields' => array(
                                'Personne.id',
                                'Personne.rolepers',
                            ),
                            'conditions' => array(
                                'Personne.foyer_id' => $foyer_id,
                                'Personne.rolepers' => array( 'DEM', 'CJT' )
                            ),
                        )
                    );
                    // One ne fait apparaître les roles de demandeur et de conjoint que
                    // si ceux-ci n'existent pas encore dans le foyer
                    $rolepersPermis = $this->Option->rolepers();
                    foreach( $rolepersPermis as $key => $rPP ) {
                        if( in_array( $key, $roles ) ) {
                            unset( $rolepersPermis[$key] );
                        }
                    }

                    $this->set( 'rolepers', $rolepersPermis );

                }

                $this->set( 'foyer_id', $foyer_id );
                $this->data['Personne']['foyer_id'] = $foyer_id;

                $this->render( $this->action, null, 'add_edit' );

            }


        /**
            Éditer une personne spécifique d'un foyer
        */
        function edit( $id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'error404' );

            $this->Personne->begin();
            if( !$this->Jetons->check( array( 'Personne.id' => $id ) ) ) {
                $this->Personne->rollback();
            }

            $this->assert( $this->Jetons->get( array( 'Personne.id' => $id ) ), 'error500' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                $this->Jetons->has( array( 'Personne.id' => $id ) );
                if( $this->Personne->save( $this->data ) ) {
                    $this->Jetons->release( array( 'Personne.id' => $id ) );
                    $this->Personne->commit();
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array(  'controller' => 'personnes','action' => 'index', $this->data['Personne']['foyer_id'] ) );
                }
            }
            // Afficage des données
            else {
                $personne = $this->Personne->find(
                    'first',
                    array(
                        'conditions' => array( 'Personne.id' => $id ),
                        'recursive' => 2
                    )
                );
                $this->assert( !empty( $personne ), 'error404' );

                // Assignation au formulaire
                $this->data = $personne;
            }

            $this->Personne->commit();
            $this->render( $this->action, null, 'add_edit' );
        }

//         function delete( $id = null ) {
//             // Vérification du format de la variable
//             if( !valid_int( $id ) ) {
//                 $this->cakeError( 'error404' );
//             }
//
//             // Recherche de la personne
//             $personne = $this->Personne->find(
//                 'first',
//                 array( 'conditions' => array( 'Personne.id' => $id )
//                 )
//             );
//
//             // Mauvais paramètre
//             if( empty( $personne ) ) {
//                 $this->cakeError( 'error404' );
//             }
//
//             // Tentative de suppression ... FIXME
//             if( $this->Personne->delete( array( 'Personne.id' => $id ) ) ) {
//                 $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
//                 $this->redirect( array( 'controller' => 'personnes', 'action' => 'index', $personne['Personne']['foyer_id'] ) );
//             }
//         }
    }
?>