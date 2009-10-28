<?php

    App::import('Sanitize');

    class CriteresciController extends AppController
    {
        var $name = 'Criteresci';
        var $uses = array(  'Dossier', 'Foyer', 'Adresse', 'Personne', 'Typocontrat', 'Structurereferente', 'Contratinsertion', 'Option', 'Serviceinstructeur', 'Cohorteci', 'Referent' );
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

        /**
        */
        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
            parent::__construct();
        }


        function beforeFilter() {
            $return = parent::beforeFilter();
            $struct = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
            $this->set( 'struct', $struct );

            $personne_suivi = $this->Contratinsertion->find(
                'list',
                array(
                    'fields' => array(
                        'Contratinsertion.pers_charg_suivi',
                        'Contratinsertion.pers_charg_suivi'
                    ),
                    'order' => 'Contratinsertion.pers_charg_suivi ASC',
                    'group' => 'Contratinsertion.pers_charg_suivi',
                )
            );
            $this->set( 'personne_suivi', $personne_suivi );
            $this->set( 'natpf', $this->Option->natpf() );

            $this->set( 'decision_ci', $this->Option->decision_ci() );
            return $return;
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
            $referents = $this->_selectReferents( Set::classicExtract( $this->data, 'Filtre.structurereferente_id' ) );
            $options = array( '<option value=""></option>' );
            foreach( $referents as $referent ) {
                $options[] = '<option value="'.$referent['Referent']['id'].'">'.$referent['Referent']['nom'].' '.$referent['Referent']['prenom'].'</option>';
            } ///FIXME: à mettre dans la vue
            echo implode( '', $options );
            $this->render( null, 'ajax' );
        }

        function index() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $params = $this->data;
            if( !empty( $params ) ) {

                $this->Dossier->begin(); // Pour les jetons

                $this->paginate = $this->Cohorteci->search( null, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                $this->paginate['limit'] = 10;
                $contrats = $this->paginate( 'Contratinsertion' );

                $this->Dossier->commit();

                $this->set( 'contrats', $contrats );
            }
            $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );

            /// Population du select référents liés aux structures
            $conditions = array();
            $structurereferente_id = Set::classicExtract( $this->data, 'Filtre.structurereferente_id' );

            if( !empty( $structurereferente_id ) ) {
                $conditions['Referent.structurereferente_id'] = Set::classicExtract( $this->data, 'Filtre.structurereferente_id' );
            }

            $referents = $this->Contratinsertion->Structurereferente->Referent->find(
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

            $querydata = $this->Cohorteci->search( null, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
            unset( $querydata['limit'] );
            $contrats = $this->Contratinsertion->find( 'all', $querydata );

            /// Population du select référents liés aux structures
            $structurereferente_id = Set::classicExtract( $this->data, 'Contratinsertion.structurereferente_id' );
            $referents = $this->Referent->_referentsListe( $structurereferente_id );
            $this->set( 'referents', $referents );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'contrats' ) );
        }
    }
?>
