<?php
    App::import('Sanitize');

    class CriteresController extends AppController
    {
        var $name = 'Criteres';
        var $uses = array( 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Typeorient', 'Structurereferente', 'Contratinsertion', 'Option', 'Serviceinstructeur', 'Orientstruct' );
        //var $aucunDroit = array('index', 'menu', 'constReq');
        var $aucunDroit = array( 'constReq' );

        /**
        *
        *
        *
        */

        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }

        /**
        *
        *
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();

            $typeservice = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        'Serviceinstructeur.id',
                        'Serviceinstructeur.lib_service'
                    ),
                )
            );
            $this->set( 'typeservice', $typeservice );

            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );


            $this->set( 'typeorient', $this->Typeorient->listOptions() );
            $this->set( 'statuts', $this->Option->statut_orient() );
            $this->set( 'statuts_contrat', $this->Option->statut_contrat_insertion() );
            $this->set( 'typeservice', $this->Serviceinstructeur->listOptions());

            return $return;
        }

        /**
            INFO: ILIKE et EXTRACT sont spécifiques à PostgreSQL
        */
        function index() {
//             $params = $this->data;
            if( !empty( $this->data ) ) {
//                 $conditions = array();

//                 // INFO: seulement les personnes qui sont dans ma zone géographique
//                 $conditions['Orientstruct.personne_id'] = $this->Personne->findByZones( $this->Session->read( 'Auth.Zonegeographique' ), $this->Session->read( 'Auth.User.filtre_zone_geo' ) );
//
//                //Critères sur la date d'ouverture d'orientation
//                 if( dateComplete( $this->data, 'Dossier.dtdemrsa' ) ) {
//                     $dtdemrsa = $this->data['Dossier']['dtdemrsa'];
//                     $conditions['Dossier.dtdemrsa'] = $dtdemrsa['year'].'-'.$dtdemrsa['month'].'-'.$dtdemrsa['day'];
//                 }
//
//                 //Critère recherche par Contrat insertion: localisation de la personne rattachée au contrat
//                 if( isset( $params['Adresse']['locaadr'] ) && !empty( $params['Adresse']['locaadr'] ) ){
//                     $conditions[] = "Adresse.locaadr ILIKE '%".Sanitize::paranoid( $params['Adresse']['locaadr'] )."%'";
//                 }
//
//                 //Critère recherche par Type orientation: localisation de la personne rattachée au contrat
//                 if( isset( $params['Typeorient']['id'] ) && !empty( $params['Typeorient']['id'] ) ){
//                     $conditions['Orientstruct.typeorient_id'] = $params['Typeorient']['id'];
//                 }
//
//                 //Critère recherche par Orientation : Structure referente
//                 if( isset( $params['Structurereferente']['id'] ) && !empty( $params['Structurereferente']['id'] ) ){
//                     $conditions['Orientstruct.structurereferente_id'] = $params['Structurereferente']['id'];
//                 }
//
//                 //Critère recherche par Orientation: par statut_orient
//                 if( isset( $params['Orientstruct']['statut_orient'] ) && !empty( $params['Orientstruct']['statut_orient'] ) ) {
//                     $conditions['Orientstruct.statut_orient'] = $params['Orientstruct']['statut_orient'];
//                 }
//
//                 //Critère recherche par Orientation : par service instructeur
//                 if( isset( $params['Serviceinstructeur']['id'] ) && !empty( $params['Serviceinstructeur']['id'] ) ){
//                     $conditions['Serviceinstructeur.id'] = $params['Serviceinstructeur']['id'];
//                 }
//                 $query = $this->Orientstruct->queries['criteres'];
//                 $query['limit'] = 10;
//
//                 $this->paginate = $query;
//                 $orients = $this->paginate( 'Orientstruct', $conditions );


                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Orientstruct->search(
                    $mesCodesInsee,
                    $this->Session->read( 'Auth.User.filtre_zone_geo' ),
                    $this->data,
                    $this->Jetons->ids()
                );

                $this->paginate['limit'] = 10;
                $orients = $this->paginate( 'Orientstruct' );

                $this->Dossier->commit();
// debug( $orients );
                $this->set( 'orients', $orients );
                $this->data['Search'] = $this->data;
            }
        }
    }
?>
