<?php
    class RegroupementszonesgeoController extends AppController
    {

        var $name = 'Regroupementszonesgeo';
        var $uses = array( 'Regroupementzonegeo', 'Zonegeographique', 'User', 'Adresse', 'Structurereferente');
        
		var $commeDroit = array(
			'add' => 'Regroupementszonesgeo:edit'
		);

        function index() {

            $rgpts = $this->Regroupementzonegeo->find(
                'all',
                array(
                    'recursive' => -1
                )
            );
            $this->set('rgpts', $rgpts);
        }

        function add() {
            $zg = $this->Zonegeographique->find(
                'list',
                array(
                    'fields' => array(
                        'Zonegeographique.id',
                        'Zonegeographique.libelle'
                    )
                )
            );
            $this->set( 'zglist', $zg );

            if( !empty( $this->data ) ) {
                if( $this->Regroupementzonegeo->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'regroupementszonesgeo', 'action' => 'index' ) );
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $rgpt_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $rgpt_id ), 'invalidParameter' );

            $zg = $this->Zonegeographique->find(
                'list',
                array(
                    'fields' => array(
                        'Zonegeographique.id',
                        'Zonegeographique.libelle'
                    )
                )
            );
            $this->set( 'zglist', $zg );

            if( !empty( $this->data ) ) {
                if( $this->Regroupementzonegeo->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'regroupementszonesgeo', 'action' => 'index' ) );
                }
            }
            else {
                $rgpt = $this->Regroupementzonegeo->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Regroupementzonegeo.id' => $rgpt_id,
                        )
                    )
                );
                $this->data = $rgpt;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $rgpt_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $rgpt_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $rgpt = $this->Regroupementzonegeo->find(
                'first',
                array( 'conditions' => array( 'Regroupementzonegeo.id' => $rgpt_id )
                )
            );

            // Mauvais paramètre
            if( empty( $rgpt_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Regroupementzonegeo->delete( array( 'Regroupementzonegeo.id' => $rgpt_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'regroupementszonesgeo', 'action' => 'index' ) );
            }
        }
    }

?>
