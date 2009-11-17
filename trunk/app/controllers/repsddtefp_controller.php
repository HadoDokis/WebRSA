<?php
    class RepsddtefpController extends AppController
    {
        var $name = 'Repsddtefp';

        function beforeFilter() {
            parent::beforeFilter();

        }

        function index() {

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            if( !empty( $this->data ) ) {
                $annee = Set::classicExtract( $this->data, 'Repddtefp.annee' );
                $semestre = Set::classicExtract( $this->data, 'Repddtefp.semestre' );
                $numcomptt = Set::classicExtract( $this->data, 'Repddtefp.numcomptt' );

                $listeSexe = $this->Repddtefp->listeSexe( $annee, $semestre, $numcomptt );
                $listeAge = $this->Repddtefp->listeAge( $annee, $semestre, $numcomptt );
//                 $listetotale = $this->Repddtefp->listeTotale();
                $this->set( compact( 'listeSexe', 'listeAge', 'numcomptt' ) );
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