<?php

    class CriterescomitesexamenapresController extends AppController
    {
        var $name = 'Criterescomitesexamenapres';
        var $uses = array( 'Comiteexamenapre', 'Criterecomiteexamenapre', 'Apre' );
        var $helpers = array( 'Xform' );

        function beforeFilter() {
            $return = parent::beforeFilter();
            return $return;
        }


        /** ********************************************************************
        *
        ** ********************************************************************/

       function index() {
            if( Configure::read( 'CG.cantons' ) ) {
                $this->set( 'cantons', $this->Canton->selectList() );
            }
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            if( !empty( $this->data ) ) {
                $this->Dossier->begin(); // Pour les jetons
                $comitesapres = $this->Criterecomiteexamenapre->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );
                $comitesapres['limit'] = 10;
                $this->paginate = $comitesapres;
                $comitesapres = $this->paginate( 'Comiteexamenapre' );

                $this->Dossier->commit();
                $this->set( 'comitesapres', $comitesapres );
            }

            if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
                $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
            }
            else {
                $this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
            }

        }
    }
?>
