<?php

    class ComitesexamenapresController extends AppController
    {
        var $name = 'Comitesexamenapres';
        var $uses = array( 'Comiteexamenapre' );

        function beforeFilter() {
            $return = parent::beforeFilter();
            return $return;
        }

        function indexMenu() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            if( !empty( $this->data ) ) {
                
            }

        }
    }
?>
