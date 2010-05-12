<?php
    class TiersprestatairesapresController extends AppController
    {

        var $name = 'Tiersprestatairesapres';
        var $uses = array( 'Tiersprestataireapre', 'Option', 'Apre' );
        var $helpers = array( 'Xform' );

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'typevoie', $this->Option->typevoie() );
            $options = $this->Tiersprestataireapre->allEnumLists();
            $this->set( 'options', $options );
            $this->set( 'aidesApres', $this->Apre->aidesApre );
            $this->set( 'natureAidesApres', $this->Option->natureAidesApres() );

        }


        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'apres', 'action' => 'indexparams' ) );
            }

            $tiersprestatairesapres = $this->Tiersprestataireapre->find(
                'all',
                array(
                    'recursive' => -1
                )

            );

            $this->set('tiersprestatairesapres', $tiersprestatairesapres);
        }

        function add() {
            // Retour à l'index en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }

            if( !empty( $this->data ) ) {
                if( $this->Tiersprestataireapre->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'tiersprestatairesapres', 'action' => 'index' ) );
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $tiersprestataireapre_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $tiersprestataireapre_id ), 'invalidParameter' );

            // Retour à l'index en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index' ) );
            }

            if( !empty( $this->data ) ) {
                if( $this->Tiersprestataireapre->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'tiersprestatairesapres', 'action' => 'index' ) );
                }
            }
            else {
                $tiersprestataireapre = $this->Tiersprestataireapre->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Tiersprestataireapre.id' => $tiersprestataireapre_id,
                        )
                    )
                );
                $this->data = $tiersprestataireapre;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $tiersprestataireapre_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $tiersprestataireapre_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $tiersprestataireapre = $this->Tiersprestataireapre->find(
                'first',
                array( 'conditions' => array( 'Tiersprestataireapre.id' => $tiersprestataireapre_id )
                )
            );

            // Mauvais paramètre
            if( empty( $tiersprestataireapre_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Tiersprestataireapre->delete( array( 'Tiersprestataireapre.id' => $tiersprestataireapre_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'tiersprestatairesapres', 'action' => 'index' ) );
            }
        }
    }

?>