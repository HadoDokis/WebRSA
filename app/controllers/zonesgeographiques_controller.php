<?php
    class ZonesgeographiquesController extends AppController
    {

        var $name = 'Zonesgeographiques';
        var $uses = array( 'Zonegeographique', 'User', 'Adresse', 'Structurereferente');

        function index() {

            $zones = $this->Zonegeographique->find(
                'all',
                array(
                    'recursive' => -1
                )

            );

            $this->set('zones', $zones);
        }

        function add() {

            if( !empty( $this->data ) ) {
                if( $this->Zonegeographique->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'zonesgeographiques', 'action' => 'index' ) );
                }
            }
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $zone_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $zone_id ), 'error404' );

            if( !empty( $this->data ) ) {
                if( $this->Zonegeographique->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'zonesgeographiques', 'action' => 'index' ) );
                }
            }
            else {
                $zone = $this->Zonegeographique->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Zonegeographique.id' => $zone_id,
                        )
                    )
                );
                $this->data = $zone;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

    }

?>