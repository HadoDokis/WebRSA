<?php
    class PiecespdosController extends AppController
    {

        var $name = 'Piecespdos';
        var $uses = array( 'Piecepdo', 'Propopdo' );
        
		var $commeDroit = array(
			'view' => 'Piecespdos:index',
			'add' => 'Piecespdos:edit'
		);

        function index(  ) {

        }

        function add( $pdo_id = null ) {
            $this->assert( valid_int( $pdo_id ), 'invalidParameter' );

            $pdo = $this->Propopdo->find( 'first', array( 'conditions' => array( 'Propopdo.id' => $pdo_id ) ) );
            $this->set( 'pdo', $pdo );

            $dossier_id = Set::extract( $pdo, 'Propopdo.dossier_id' );

            if( !empty( $this->data ) ) {

                if( $this->Piecepdo->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'dossierspdo', 'action' => 'index', $dossier_id ) );
                }
            }
            $this->render( $this->action, null, 'add_edit' );

        }

        function edit( $piecepdo_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $piecepdo_id ), 'invalidParameter' );

            if( !empty( $this->data ) ) {
                if( $this->Piecepdo->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'piecespdos', 'action' => 'index' ) );
                }
            }
            else {
                $piecepdo = $this->Piecepdo->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Piecepdo.id' => $piecepdo_id,
                        )
                    )
                );
                $this->data = $piecepdo;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $piecepdo_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $piecepdo_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $piecepdo = $this->Piecepdo->find(
                'first',
                array( 'conditions' => array( 'Piecepdo.id' => $piecepdo_id )
                )
            );

            // Mauvais paramètre
            if( empty( $piecepdo_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Piecepdo->delete( array( 'Piecepdo.id' => $piecepdo_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'piecespdos', 'action' => 'index' ) );
            }
        }
    }

?>
