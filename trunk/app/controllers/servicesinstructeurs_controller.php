<?php
    class ServicesinstructeursController extends AppController
    {

        var $name = 'Servicesinstructeurs';
        var $uses = array( 'Serviceinstructeur', 'Option' );
        var $helpers = array( 'Xform' );
        
		var $commeDroit = array(
			'add' => 'Servicesinstructeurs:edit'
		);

         function beforeFilter() {
            parent::beforeFilter();
                $this->set( 'typeserins', $this->Option->typeserins() );
                $this->set( 'typevoie', $this->Option->typevoie() );
        }

        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }


            $querydata = $this->Serviceinstructeur->prepare( 'list' );
            $servicesinstructeurs = $this->Serviceinstructeur->find( 'all', $querydata );
            $this->set( compact( 'servicesinstructeurs') );
        }

        function add() {
            // Retour à l'index en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }
            if( !empty( $this->data ) ) {
                if( $this->Serviceinstructeur->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'servicesinstructeurs', 'action' => 'index' ) );
                }
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $serviceinstructeur_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $serviceinstructeur_id ), 'error404' );

            // Retour à l'index en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }
            if( !empty( $this->data ) ) {
                if( $this->Serviceinstructeur->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'servicesinstructeurs', 'action' => 'index' ) );
                }
            }
            else {
                $serviceinstructeur = $this->Serviceinstructeur->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Serviceinstructeur.id' => $serviceinstructeur_id,
                        )
                    )
                );
                $this->data = $serviceinstructeur;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $serviceinstructeur_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $serviceinstructeur_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $serviceinstructeur = $this->Serviceinstructeur->find(
                'first',
                array( 'conditions' => array( 'Serviceinstructeur.id' => $serviceinstructeur_id )
                )
            );

            // Mauvais paramètre
            if( empty( $serviceinstructeur_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Serviceinstructeur->delete( array( 'Serviceinstructeur.id' => $serviceinstructeur_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'servicesinstructeurs', 'action' => 'index' ) );
            }
        }
    }

?>
