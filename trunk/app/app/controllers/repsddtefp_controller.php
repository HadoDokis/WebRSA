<?php
    @set_time_limit( 0 );
    @ini_set( 'memory_limit', '128M' );
    App::import('Sanitize');
    class RepsddtefpController extends AppController
    {
        var $name = 'Repsddtefp';
        var $uses = array( 'Apre', 'Repddtefp', 'Option', 'Budgetapre', 'Etatliquidatif' );
        var $helpers = array( 'Xform', 'Paginator', 'Locale', 'Xpaginator' );

        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'suivicontrole' ) ) ) );
            parent::__construct();
        }


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'sexe', $this->Option->sexe() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
            $options = $this->Apre->allEnumLists();
            $this->set( 'options', $options );
            $this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );

            $this->set( 'quinzaine', $this->Option->quinzaine() );
        }

        /**
        *   Données pour le premier reporting bi mensuel ddtefp
        **/
        function index() {

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            if( !empty( $this->data ) ) {
                $annee = Set::classicExtract( $this->data, 'Repddtefp.annee' );
                $semestre = Set::classicExtract( $this->data, 'Repddtefp.semestre' );
                $numcomptt = Set::classicExtract( $this->data, 'Repddtefp.numcomptt' );

                $listeSexe = $this->Repddtefp->listeSexe( $annee, $semestre, $numcomptt );
                $listeAge = $this->Repddtefp->listeAge( $annee, $semestre, $numcomptt );

                $this->set( compact( 'listeSexe', 'listeAge', 'numcomptt' ) );
            }

            if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
                $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
            }
            else {
                $this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
            }
        }

        /**
        *   Données à envoyer pour afficehr reporting du suivi et controle de l'enveloppe apre
        **/
        function suivicontrole() {

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            if( !empty( $this->data ) ) {

                $queryData = $this->Repddtefp->search( $this->data );
                $queryData['limit'] = 10;
                $this->paginate['Apre'] = $queryData;
                $apres = $this->paginate( 'Apre' );

                ///Détails de l'enveloppe APRE
                $detailsEnveloppe = $this->Repddtefp->detailsEnveloppe( $this->data );
                $this->set( 'detailsEnveloppe', $detailsEnveloppe );


                $this->set( 'apres', $apres );
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