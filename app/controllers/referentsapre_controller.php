<?php
    class ReferentsapreController extends AppController
    {

        var $name = 'Referentsapre';
        var $uses = array( 'Referentapre', 'Option' );


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'qual', $this->Option->qual() );
        }


        function index() {

            $referentsapre = $this->Referentapre->find(
                'all',
                array(
                    'recursive' => -1
                )

            );

            $this->set('referentsapre', $referentsapre);
        }

        function add() {

            if( !empty( $this->data ) ) {
                if( $this->Referentapre->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'referentsapre', 'action' => 'index' ) );
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $referentapre_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $referentapre_id ), 'invalidParameter' );

            if( !empty( $this->data ) ) {
                if( $this->Referentapre->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'referentsapre', 'action' => 'index' ) );
                }
            }
            else {
                $referentapre = $this->Referentapre->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Referentapre.id' => $referentapre_id,
                        )
                    )
                );
                $this->data = $referentapre;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $referentapre_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $referentapre_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $referentapre = $this->Referentapre->find(
                'first',
                array( 'conditions' => array( 'Referentapre.id' => $referentapre_id )
                )
            );

            // Mauvais paramètre
            if( empty( $referentapre_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Referentapre->delete( array( 'Referentapre.id' => $referentapre_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'referentsapre', 'action' => 'index' ) );
            }
        }
    }

?>