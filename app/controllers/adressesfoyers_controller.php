<?php
    class AdressesFoyersController extends AppController
    {
        var $name = 'AdressesFoyers';
        var $uses = array( 'Adressefoyer', 'Option' );

        /**
            Commun à toutes les fonctions
        */
        function beforeFilter() {
            // FIXME: pourquoi ? à priori parce que notre table a des underscore dans son nom!
            // INFO: http://book.cakephp.org/view/24/Model-and-Database-Conventions pour corriger mes erreurs
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

            $this->set( 'pays', $this->Option->pays() );
            $this->set( 'rgadr', $this->Option->rgadr() );
            $this->set( 'typeadr', $this->Option->typeadr() );
        }

        /**
            Voir les adresses d'un foyer
        */
        function index( $foyer_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'error404' );

            // Recherche des adresses du foyer
            $adresses = $this->Adressefoyer->find(
                'all',
                array(
                    'conditions' => array( 'Adressefoyer.foyer_id' => $foyer_id ),
                    'recursive' => 2
                )
            );

            // Assignations à la vue
            $this->set( 'foyer_id', $foyer_id );
            $this->set( 'adresses', $adresses );
        }

        /**
            Voir une adresse spécifique d'un foyer
        */
        function view( $id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'error404' );

            // Recherche de l'adresse
            $adresse = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array( 'Adressefoyer.id' => $id ),
                    'recursive' => 2
                )
            );

            // Mauvais paramètre
            $this->assert( !empty( $adresse ), 'error404' );

            // Assignation à la vue
            $this->set( 'adresse', $adresse );
        }

        /**
            Éditer une adresse spécifique d'un foyer
        */
        function edit( $id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'error404' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Adressefoyer->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'adressesfoyers', 'action' => 'index', $this->data['Adressefoyer']['foyer_id'] ) );
                }
            }
            // Afficage des données
            else {
                $adresse = $this->Adressefoyer->find(
                    'first',
                    array(
                        'conditions' => array( 'Adressefoyer.id' => $id ),
                        'recursive' => 2
                    )
                );

                // Mauvais paramètre
                $this->assert( !empty( $adresse ), 'error404' );

                // Assignation au formulaire
                $this->data = $adresse;
            }
            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        */
        function add( $id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'error404' );

            // Essai de sauvegarde
            if( !empty( $this->data ) && $this->Adressefoyer->saveAll( $this->data ) ) {
                $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                $this->redirect( array( 'controller' => 'adressesfoyers', 'action' => 'index', $id ) );
            }

            // Assignation à la vue
            $this->set( 'foyer_id', $id );
            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        */
//         function delete( $id = null ) {
//             // Vérification du format de la variable
//             if( !valid_int( $id ) ) {
//                 $this->cakeError( 'error404' );
//             }
//
//             // Recherche de l'adresse
//             $adresse = $this->Adressefoyer->find(
//                 'first',
//                 array(
//                     'conditions' => array( 'Adressefoyer.id' => $id )
//                 )
//             );
//
//             // Mauvais paramètre
//             if( empty( $adresse ) ) {
//                 $this->cakeError( 'error404' );
//             }
//
//             // Tentative de suppression ... FIXME
//             if( $this->Adressefoyer->delete( array( 'Adressefoyer.id' => $id ) ) &&
//                 $this->Adresse->delete( array( 'Adresse.id' => $adresse['Adresse']['id'] ) )
//             ) {
//                 $this->Session->setFlash( 'Suppression effectuée.' );
//                 $this->redirect( array( 'controller' => 'adressesfoyers', 'action' => 'index', $adresse['Adressefoyer']['foyer_id'] ) );
//             }
//         }
    }
?>