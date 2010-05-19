<?php
    @set_time_limit( 0 );
    @ini_set( 'memory_limit', '128M' );
    App::import('Sanitize');
    class Repsddtefp2Controller extends AppController
    {
        var $name = 'Repsddtefp2';
        var $uses = array( 'Apre', 'Repddtefp2', 'Option', 'Budgetapre', 'Etatliquidatif' );
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
                $annee = Set::classicExtract( $this->data, 'Repddtefp2.annee' );
                $semestre = Set::classicExtract( $this->data, 'Repddtefp2.semestre' );
                $numcomptt = Set::classicExtract( $this->data, 'Repddtefp2.numcomptt' );

                $listeSexe = $this->Repddtefp2->listeSexe( $annee, $semestre, $numcomptt );
                $listeAge = $this->Repddtefp2->listeAge( $annee, $semestre, $numcomptt );

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

                /*$queryData = $this->Repddtefp2->search( $this->data );
                $queryData['limit'] = 10;
                $this->paginate['Apre'] = $queryData;
                $apres = $this->paginate( 'Apre' );*/

				$queryData = $this->Repddtefp2->search2( $this->data );
                $queryData['limit'] = 10;
                $this->paginate['Etatliquidatif'] = $queryData;
                $apres = $this->paginate( 'Etatliquidatif' );

                ///Détails de l'enveloppe APRE
                $detailsEnveloppe = $this->Repddtefp2->detailsEnveloppe2( $this->data );
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