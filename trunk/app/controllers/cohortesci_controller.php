<?php
    App::import('Sanitize');

    class CohortesciController extends AppController
    {
        var $name = 'Cohortesci';
        var $uses = array( 'Cohorteci', 'Option', 'Contratinsertion', 'Typeorient', 'Orientstruct', 'Accoemploi', 'Adresse', 'Serviceinstructeur', 'Suiviinstruction', 'Referent', 'Structurereferente' );
        var $aucunDroit = array( 'constReq', 'ajaxreferent' );

        var $helpers = array( 'Csv', 'Ajax' );

        var $paginate = array(
            // FIXME
            'limit' => 20,
        );

        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'valides' ) ) ) );
            parent::__construct();
            $this->components[] = 'Jetons';
        }

        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'oridemrsa', $this->Option->oridemrsa() );
            $this->set( 'typeserins', $this->Option->typeserins() );
            $this->set( 'accoemplois', $this->Accoemploi->find( 'list' ) );
            $this->set( 'printed', $this->Option->printed() );
            $this->set( 'decision_ci', $this->Option->decision_ci() );
            $struct = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
            $this->set( 'struct', $struct );
            return $return;
        }


        //*********************************************************************

        function nouveaux() {
            $this->_index( 'Decisionci::nonvalide' );
        }

        //---------------------------------------------------------------------

        function valides() {
            $this->_index( 'Decisionci::valides' );
        }

        //---------------------------------------------------------------------

        function enattente() {
            $this->_index( 'Decisionci::enattente' );
        }

        //*********************************************************************

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


        function _index( $statutValidation = null ) {
            $this->assert( !empty( $statutValidation ), 'invalidParameter' );

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );
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

            $params = $this->data;

            if( !empty( $params ) ) {
                /**
                *
                * Sauvegarde
                *
                */

                // On a renvoyé  le formulaire de la cohorte
                if( !empty( $this->data['Contratinsertion'] ) ) {
                    $valid = $this->Dossier->Foyer->Personne->Contratinsertion->saveAll( $this->data['Contratinsertion'], array( 'validate' => 'only', 'atomic' => false ) );
                    if( $valid ) {
                        $this->Dossier->begin();
                        $saved = $this->Dossier->Foyer->Personne->Contratinsertion->saveAll( $this->data['Contratinsertion'], array( 'validate' => 'first', 'atomic' => false ) );
                        if( $saved ) {
                            // FIXME ?
                            foreach( array_unique( Set::extract( $this->data, 'Contratinsertion.{n}.dossier_id' ) ) as $dossier_id ) {
                                $this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
                            }
                            $this->Dossier->commit();
                        }
                        else {
                            $this->Dossier->rollback();
                        }
                    }
                }

                /**
                *
                * Filtrage
                *
                */

                if( ( $statutValidation == 'Decisionci::nonvalide' ) || ( ( $statutValidation == 'Decisionci::valides' ) && !empty( $this->data ) ) || ( ( $statutValidation == 'Decisionci::enattente' ) && !empty( $this->data ) ) ) {
                    $this->Dossier->begin(); // Pour les jetons

                    $this->paginate = $this->Cohorteci->search( $statutValidation, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                    $this->paginate['limit'] = 10;
                    $cohorteci = $this->paginate( 'Contratinsertion' );

                    $this->Dossier->commit();

                    foreach( $cohorteci as $key => $value ) {
                        if( $value['Contratinsertion']['decision_ci'] == 'E' ) {
                            $cohorteci[$key]['Contratinsertion']['proposition_decision_ci'] = 'V';
                        }
                        else {
                            $cohorteci[$key]['Contratinsertion']['proposition_decision_ci'] = $value['Contratinsertion']['decision_ci'];
                        }

                        if( empty( $value['Contratinsertion']['datevalidation_ci'] ) ) {
                            $cohorteci[$key]['Contratinsertion']['proposition_datevalidation_ci'] = date( 'Y-m-d' );
                        }
                        else {
                            $cohorteci[$key]['Contratinsertion']['proposition_datevalidation_ci'] = $value['Contratinsertion']['datevalidation_ci'];
                        }
                    }

                    $this->set( 'cohorteci', $cohorteci );
                }

            }

            $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee ) );

            /// Population du select référents liés aux structures
            $structurereferente_id = Set::classicExtract( $this->data, 'Filtre.structurereferente_id' );
            $referents = $this->Referent->_referentsListe( $structurereferente_id );
            $this->set( 'referents', $referents );

            switch( $statutValidation ) {
                case 'Decisionci::nonvalide':
                    $this->set( 'pageTitle', 'Contrats à valider' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Decisionci::enattente':
                    $this->set( 'pageTitle', 'Contrats en attente' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Decisionci::valides':
                    $this->set( 'pageTitle', 'Contrats validés' );
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }
        }


        /// Export du tableau en CSV
        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $querydata = $this->Cohorteci->search( 'Decisionci::valides', $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
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