<?php
    @set_time_limit( 0 );
    @ini_set( 'memory_limit', '128M' );
    App::import('Sanitize');

    class CriteresrdvController extends AppController
    {
        var $name = 'Criteresrdv';
        var $uses = array(  'Dossier', 'Foyer', 'Adresse', 'Personne', 'Rendezvous', 'Critererdv', 'Structurereferente', 'Option', 'Typerdv', 'Referent', 'Permanence' );
        var $aucunDroit = array( 'constReq', 'ajaxreferent', 'ajaxperm' );

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
            $this->set( 'permanences', $this->Permanence->find( 'list' ) );
            $this->set( 'natpf', $this->Option->natpf() );
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
        *   Ajax pour la permanence liée à la structure référente
        *** *******************************************************************/
        function _selectPermanences( $structurereferente_id ) {
            $permanences = $this->Rendezvous->Structurereferente->Permanence->find(
                'all',
                array(
                    'conditions' => array(
                        'Permanence.structurereferente_id' => $structurereferente_id
                    ),
                    'recursive' => -1
                )
            );

            return $permanences;

        }

        function ajaxperm() { // FIXME
            Configure::write( 'debug', 0 );
            $permanences = $this->_selectPermanences( Set::classicExtract( $this->data, 'Critererdv.structurereferente_id' ) );

            $options = array( '<option value=""></option>' );
            foreach( $permanences as $permanence ) {
                $options[] = '<option value="'.$permanence['Permanence']['id'].'">'.$permanence['Permanence']['libpermanence'].'</option>';
            }
            echo implode( '', $options );
            $this->render( null, 'ajax' );
        }

        /** ********************************************************************
        *
        ** ********************************************************************/

        function index() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            if( !empty( $this->data ) ) {
                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Critererdv->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );
                $this->paginate['limit'] = 10;
                $rdvs = $this->paginate( 'Rendezvous' );

                $this->Dossier->commit();
                $this->set( 'rdvs', $rdvs );
            }
            $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );

            // Population du select référents liés aux structures
            $structurereferente_id = Set::classicExtract( $this->data, 'Critererdv.structurereferente_id' );
            $referents = $this->Referent->_referentsListe( $structurereferente_id );
            $this->set( 'referents', $referents );
        }

        /// Export du tableau en CSV
        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );
            $querydata = $this->Critererdv->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
            unset( $querydata['limit'] );
            $rdvs = $this->Rendezvous->find( 'all', $querydata );

            // Population du select référents liés aux structures
            $structurereferente_id = Set::classicExtract( $this->data, 'Critererdv.structurereferente_id' );
            $referents = $this->Referent->_referentsListe( $structurereferente_id );
            $this->set( 'referents', $referents );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'rdvs' ) );
        }
    }
?>
