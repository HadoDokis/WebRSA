<?php
    @set_time_limit( 0 );
    @ini_set( 'memory_limit', '128M' );
    App::import('Sanitize');

    class CriteresrdvController extends AppController
    {
        var $name = 'Criteresrdv';
        var $uses = array(  'Dossier', 'Foyer', 'Adresse', 'Personne', 'Rendezvous', 'Critererdv', 'Structurereferente', 'Option', 'Typerdv', 'Referent' );
        var $aucunDroit = array( 'constReq', 'ajaxreferent' );

        var $helpers = array( 'Csv', 'Ajax' );
        /**
            INFO: ILIKE et EXTRACT sont spécifiques à PostgreSQL
        */


//         var $paginate = array(
//             // FIXME
//             'limit' => 20,
// //             'order' => array(
// //                 'Criteresci.locaadr' => 'asc'
// //             )
//         );

        /** ********************************************************************
        *
        ** ********************************************************************/

        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }

        /** ********************************************************************
        *
        ** ********************************************************************/

        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'statutrdv', $this->Option->statutrdv() );
            $struct = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
            $this->set( 'struct', $struct );
            $typerdv = $this->Typerdv->find( 'list', array( 'fields' => array( 'id', 'libelle' ) ) );
            $this->set( 'typerdv', $typerdv );
/*
            $referents = $this->_selectReferents( Set::classicExtract( $this->data, 'Structurereferente.id' ) );
            $this->set( 'referents', $referents );*/
        }


        /** ********************************************************************
        *   Ajax pour lien référent - structure référente
        ********************************************************************/

        function _selectReferents( $structurereferente_id ) {
            $conditions = array();

            if( !empty( $structurereferente_id ) ) {
                $conditions['Referent.structurereferente_id'] = $structurereferente_id;
            }

            $referents = $this->Referent->find(
                'all',
                array(
                    'conditions' => $conditions,
                    'recursive' => -1
                )
            );
            return $referents;

        }

        /** ********************************************************************
        *
        ** ********************************************************************/

        function ajaxreferent() { // FIXME
            Configure::write( 'debug', 0 );
            $referents = $this->_selectReferents( Set::classicExtract( $this->data, 'Critererdv.structurereferente_id' ) );
            $options = array( '<option value=""></option>' );
            foreach( $referents as $referent ) {
                $options[] = '<option value="'.$referent['Referent']['id'].'">'.$referent['Referent']['nom'].' '.$referent['Referent']['prenom'].'</option>';
            } ///FIXME: à mettre dans la vue
            echo implode( '', $options );
            $this->render( null, 'ajax' );
        }

        /** ********************************************************************
        *
        ** ********************************************************************/

        function index() {
            if( !empty( $this->data ) ) {
                $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
                $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );


                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Critererdv->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );
                $this->paginate['limit'] = 10;
                $rdvs = $this->paginate( 'Rendezvous' );

                $this->Dossier->commit();
                $this->set( 'rdvs', $rdvs );
            }

            // Population du select référents liés aux structures
            $conditions = array();
            $structurereferente_id = Set::classicExtract( $this->data, 'Critererdv.structurereferente_id' );
            if( !empty( $structurereferente_id ) ) {
                $conditions['Referent.structurereferente_id'] = Set::classicExtract( $this->data, 'Critererdv.structurereferente_id' );
            }

            $referents = $this->Rendezvous->Structurereferente->Referent->find(
                'all',
                array(
                    'recursive' => -1,
                    'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom' ),
                    'conditions' => $conditions
                )
            );

            if( !empty( $referents ) ) {
                $ids = Set::extract( $referents, '/Referent/id' );
                $values = Set::format( $referents, '{0} {1} {2}', array( '{n}.Referent.qual', '{n}.Referent.nom', '{n}.Referent.prenom' ) );
                $referents = array_combine( $ids, $values );
            }

            $this->set( 'referents', $referents );
        }

        /// Export du tableau en CSV
        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );
            $querydata = $this->Critererdv->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
            unset( $querydata['limit'] );
            $rdvs = $this->Rendezvous->find( 'all', $querydata );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'rdvs' ) );
        }
    }
?>
