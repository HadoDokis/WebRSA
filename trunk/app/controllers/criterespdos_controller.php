<?php

    App::import('Sanitize');

    class CriterespdosController extends AppController
    {
        var $name = 'Criterespdos';
        var $uses = array( 'Canton', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Typenotifpdo', 'Typepdo', 'Option', 'Situationpdo', 'Criterepdo', 'Propopdo', 'Referent', 'Decisionpdo', 'Originepdo', 'Statutpdo', 'Statutdecisionpdo' );
        var $aucunDroit = array( 'exportcsv' );

        var $helpers = array( 'Csv', 'Ajax' );

        /**
        */
        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }


        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'pieecpres', $this->Option->pieecpres() );
            $this->set( 'commission', $this->Option->commission() );
            $this->set( 'motidempdo', $this->Option->motidempdo() );
            $this->set( 'motifpdo', $this->Option->motifpdo() );
            $this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
            $this->set( 'typeserins', $this->Option->typeserins() );
            $this->set( 'typepdo', $this->Typepdo->find( 'list' ) );
            $this->set( 'decisionpdo', $this->Decisionpdo->find( 'list' ) );
            $this->set( 'originepdo', $this->Originepdo->find( 'list' ) );

            $this->set( 'statutlist', $this->Statutpdo->find( 'list' ) );
            $this->set( 'situationlist', $this->Situationpdo->find( 'list' ) );
            $this->set( 'statutdecisionlist', $this->Statutdecisionpdo->find( 'list' ) );

            $options = $this->Propopdo->allEnumLists();
            $options = Set::insert( $options, 'Suiviinstruction.typeserins', $this->Option->typeserins() );
            $this->set( compact( 'options' ) );

            return $return;
        }

        function index() {
            if( Configure::read( 'CG.cantons' ) ) {
                $this->set( 'cantons', $this->Canton->selectList() );
            }

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $params = $this->data;
            if( !empty( $params ) ) {

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Criterepdo->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $criterespdos = $this->paginate( 'Propopdo' );

                $this->Dossier->commit();

                $this->set( 'criterespdos', $criterespdos );
            }

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

            $querydata = $this->Criterepdo->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
            unset( $querydata['limit'] );
            $pdos = $this->Propopdo->find( 'all', $querydata );

// debug($contrats);
// die();
            $this->layout = ''; // FIXME ?
            $this->set( compact( 'pdos' ) );
        }
    }
?>
