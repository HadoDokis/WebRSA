<?php
    @ini_set( 'max_execution_time', 0 );
    class InfosfinancieresController  extends AppController
    {
        var $name = 'Infosfinancieres';
        var $uses = array( 'Infofinanciere', 'Option', 'Dossier', 'Personne', 'Foyer', 'Cohorteindu' );
        var $helpers = array( 'Paginator', 'Locale', 'Csv' );

//         var $paginate = array(
//             // FIXME
//             'limit' => 20,
//         );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'indexdossier' ) ) ) );
            parent::__construct();
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'type_allocation', $this->Option->type_allocation() );
            $this->set( 'natpfcre', $this->Option->natpfcre() );
            $this->set( 'typeopecompta', $this->Option->typeopecompta() );
            $this->set( 'sensopecompta', $this->Option->sensopecompta() );
            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function indexdossier() {
            if( !empty( $this->data ) ) {
                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Infofinanciere->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );
                $this->paginate['limit'] = 15;
                $infosfinancieres = $this->paginate( 'Infofinanciere' );

                $this->Dossier->commit();

                $this->set( 'infosfinancieres', $infosfinancieres );
                $this->data['Search'] = $this->data;
            }
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $dossier_rsa_id = null ) {
            //Vérification du format de la variable
            $this->assert( valid_int( $dossier_rsa_id ), 'invalidParameter' );

             //Recherche des adresses du foyer
            $infosfinancieres = $this->Infofinanciere->find(
                'all',
                array(
                    'conditions' => array( 'Infofinanciere.dossier_rsa_id' => $dossier_rsa_id ),
                    'recursive' => 2
                )
            );

            $foyer = $this->Dossier->Foyer->findByDossierRsaId( $dossier_rsa_id, null, null, -1 );

            $personne = $this->Dossier->Foyer->Personne->find(
                'first',
                array(
                    'conditions' => array( 'Personne.foyer_id' => $foyer['Foyer']['id'] ,
//                     'Prestation.natprest = \'RSA\'',
                        '( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
                            '( Prestation.rolepers = \'DEM\' )',
                )
                )
            );

            $this->assert( !empty( $personne ), 'invalidParameter' );
            $this->set( 'personne', $personne );


            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'infosfinancieres', $infosfinancieres );

        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function view( $infofinanciere_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $infofinanciere_id ), 'error404' );

            $infofinanciere = $this->Infofinanciere->find(
                'first',
                array(
                    'conditions' => array(
                        'Infofinanciere.id' => $infofinanciere_id
                    ),
                'recursive' => -1
                )

            );

            $this->assert( !empty( $infofinanciere ), 'error404' );

            // Assignations à la vue
            $this->set( 'dossier_id', $infofinanciere['Infofinanciere']['dossier_rsa_id'] );
            $this->set( 'infofinanciere', $infofinanciere );

        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $options = $this->Infofinanciere->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );
            unset( $options['limit'] );
            $infos = $this->Infofinanciere->find( 'all', $options );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'headers', 'infos' ) );
        }
    }
?>