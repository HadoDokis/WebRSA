<?php
    class IndusController  extends AppController
    {
        var $name = 'Indus';
        var $uses = array( 'Infofinanciere', 'Indu', 'Option', 'Dossier', 'Personne', 'Foyer', 'Cohorteindu' );
        var $helpers = array( /*'Paginator', */'Locale' );

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'type_allocation', $this->Option->type_allocation() );
            $this->set( 'natpfcre', $this->Option->natpfcre() );
            $this->set( 'typeopecompta', $this->Option->typeopecompta() );
            $this->set( 'sensopecompta', $this->Option->sensopecompta() );
            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
        }

        function index( $dossier_rsa_id = null) {
            //Vérification du format de la variable
            $this->assert( valid_int( $dossier_rsa_id ), 'invalidParameter' );

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $params = $this->Indu->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array( 'Dossier.id' => $dossier_rsa_id ) );
            $infofinanciere = $this->Infofinanciere->find( 'first',  $params );


            $this->set('infofinanciere', $infofinanciere );
            $this->set( 'dossier_rsa_id', $dossier_rsa_id );

        }

        function view( $dossier_rsa_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $dossier_rsa_id ), 'error404' );


            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $params = $this->Indu->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array( 'Dossier.id' => $dossier_rsa_id ) );
            $infofinanciere = $this->Infofinanciere->find( 'first',  $params );
            $this->assert( !empty( $infofinanciere ), 'error404' );

            // Assignations à la vue
            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'infofinanciere', $infofinanciere );

        }
}
