<?php

    App::import('Sanitize');

    class CriterescuisController extends AppController
    {
        var $name = 'Criterescuis';
        var $uses = array( 'Canton', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Structurereferente', 'Contratinsertion', 'Option', 'Serviceinstructeur', 'Criterecui', 'Cui', 'Referent', 'Zonegeographique' );
        var $aucunDroit = array( 'exportcsv' );

        var $helpers = array( 'Csv', 'Ajax' );

        /**
        */
        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }


        protected function _setOptions(){
            $options = array();
            $struct = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
            $this->set( 'struct', $struct );

            $qual = $this->Option->qual();
            $this->set( 'qual', $qual );
            $options = $this->Cui->allEnumLists();
            $this->set( 'options', $options );

        }
/*
        function beforeFilter() {
            $return = parent::beforeFilter();
            $options = array();
            $struct = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
            $this->set( 'struct', $struct );

            $qual = $this->Option->qual();
            $this->set( 'qual', $qual );
            $options = $this->Cui->allEnumLists();
            $this->set( 'options', $options );

            return $return;
        }*/

        function index() {
            if( Configure::read( 'CG.cantons' ) ) {
                $this->set( 'cantons', $this->Canton->selectList() );
            }

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $params = $this->data;
            if( !empty( $params ) ) {

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Criterecui->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $criterescuis = $this->paginate( 'Cui' );

                $this->Dossier->commit();


                $this->set( 'criterescuis', $criterescuis );
            }
            $this->_setOptions();
            if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
                $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
            }
            else {
                $this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
            }

        }

        /// Export du tableau en CSV
        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $querydata = $this->Criterecui->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
            unset( $querydata['limit'] );
            $cuis = $this->Cui->find( 'all', $querydata );

// debug($contrats);
// die();
            $this->_setOptions();
            $this->layout = ''; // FIXME ?
            $this->set( compact( 'cuis' ) );
        }
    }
?>
