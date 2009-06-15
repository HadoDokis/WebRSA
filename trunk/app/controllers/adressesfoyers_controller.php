<?php
    class AdressesFoyersController extends AppController
    {
        var $name = 'Adressesfoyers';
        var $uses = array( 'Adressefoyer', 'Option' );

        /**
            Commun à toutes les fonctions
        */
        function beforeFilter() {
            $return = parent::beforeFilter();
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

            return $return;
        }

        /**
            Voir les adresses d'un foyer
        */
        function index( $foyer_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'invalidParameter' );

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
            $this->assert( valid_int( $id ), 'invalidParameter' );

            // Recherche de l'adresse
            $adresse = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array( 'Adressefoyer.id' => $id ),
                    'recursive' => 2
                )
            );

            // Mauvais paramètre
            $this->assert( !empty( $adresse ), 'invalidParameter' );

            // Assignation à la vue
            $this->set( 'adresse', $adresse );
        }

        /**
            Éditer une adresse spécifique d'un foyer
        */
        function edit( $id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'invalidParameter' );

            $dossier_id = $this->Adressefoyer->dossierId( $id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Adressefoyer->begin();

            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Adressefoyer->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Adressefoyer->saveAll( $this->data, array( 'validate' => 'only' ) ) ) {
                    if( $this->Adressefoyer->saveAll( $this->data ) ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Adressefoyer->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'adressesfoyers', 'action' => 'index', $this->data['Adressefoyer']['foyer_id'] ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
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
                $this->assert( !empty( $adresse ), 'invalidParameter' );

                // Assignation au formulaire
                $this->data = $adresse;
            }

            $this->Adressefoyer->commit();

            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        */
        function add( $foyer_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'invalidParameter' );

            $dossier_id = $this->Adressefoyer->Foyer->dossierId( $foyer_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Adressefoyer->begin();

            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Adressefoyer->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Adressefoyer->saveAll( $this->data, array( 'validate' => 'only' ) ) ) {
                    if( $this->Adressefoyer->saveAll( $this->data ) ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Adressefoyer->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'adressesfoyers', 'action' => 'index', $foyer_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }

            $this->Adressefoyer->commit();

            // Assignation à la vue
            $this->set( 'foyer_id', $foyer_id );
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>