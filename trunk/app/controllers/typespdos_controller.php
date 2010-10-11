<?php
    class TypespdosController extends AppController
    {

        var $name = 'Typespdos';
        var $uses = array( 'Typepdo', 'Propopdo' );
        var $helpers = array( 'Xform' );
        
        var $commeDroit = array(
			'add' => 'Typespdos:edit'
		);

        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'pdos', 'action' => 'index' ) );
            }

            $typespdos = $this->Typepdo->find(
                'all',
                array(
                    'recursive' => -1
                )

            );

            $this->set('typespdos', $typespdos);
        }

        function add() {

            if( !empty( $this->data ) ) {
                if( $this->Typepdo->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typespdos', 'action' => 'index' ) );
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $typepdo_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $typepdo_id ), 'invalidParameter' );

            if( !empty( $this->data ) ) {
                if( $this->Typepdo->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'typespdos', 'action' => 'index' ) );
                }
            }
            else {
                $typepdo = $this->Typepdo->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Typepdo.id' => $typepdo_id,
                        )
                    )
                );
                $this->data = $typepdo;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $typepdo_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $typepdo_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $typepdo = $this->Typepdo->find(
                'first',
                array( 'conditions' => array( 'Typepdo.id' => $typepdo_id )
                )
            );

            // Mauvais paramètre
            if( empty( $typepdo_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Typepdo->delete( array( 'Typepdo.id' => $typepdo_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'typespdos', 'action' => 'index' ) );
            }
        }
    }

?>
