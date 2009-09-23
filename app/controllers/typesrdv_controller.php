<?php

    class TypesrdvController extends Appcontroller
    {

        var $name = 'Typesrdv';
        var $uses = array( 'Rendezvous', 'Option', 'Typerdv' );


        function index() {
            $typesrdv = $this->Typerdv->find(
                'all',
                array(
                    'recursive' => 1,
                    'order' => 'Typerdv.libelle ASC'
                )
            );
            $this->set( 'typesrdv', $typesrdv );
        }

        function add() {
            if( !empty( $this->data ) ) {
                $this->Typerdv->begin();
                if( $this->Typerdv->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
                    $this->Typerdv->commit();
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typesrdv', 'action' => 'index' ) );
                }
                else {
                    $this->Typerdv->rollback();
                    $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                }
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $typerdv_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $typerdv_id ), 'invalidParameter' );

            $typerdv = $this->Typerdv->find(
                'first',
                array(
                    'conditions' => array(
                        'Typerdv.id' => $typerdv_id
                    ),
                    'recursive' => 1
                )
            );

            // Si action n'existe pas -> 404
            if( empty( $typerdv ) ) {
                $this->cakeError( 'error404' );
            }

            if( !empty( $this->data ) ) {
                if( $this->Typerdv->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typesrdv', 'action' => 'index', $typerdv['Typerdv']['id']) );
                }
            }
            else {
                $this->data = $typerdv;
            }
            $this->render( $this->action, null, 'add_edit' );
        }


        /** ********************************************************************
        *
        *** *******************************************************************/

        function delete( $typerdv_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $typerdv_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $typerdv = $this->Typerdv->find(
                'first',
                array( 'conditions' => array( 'Typerdv.id' => $typerdv_id )
                )
            );

            // Mauvais paramètre
            if( empty( $typerdv ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Typerdv->deleteAll( array( 'Typerdv.id' => $typerdv_id ), true ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'typesrdv', 'action' => 'index' ) );
            }
        }
    }
?>