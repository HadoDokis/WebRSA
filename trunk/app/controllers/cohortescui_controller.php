<?php
    App::import('Sanitize');

    class CohortescuiController extends AppController
    {
        var $name = 'Cohortescui';
        var $uses = array( 'Canton', 'Cohortecui', 'Personne', 'Option', 'Cui', 'Typeorient', 'Action', 'Orientstruct', 'Accoemploi', 'Adresse', 'Serviceinstructeur', 'Suiviinstruction', 'Referent', 'Structurereferente' );
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
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '512M');
            $return = parent::beforeFilter();
            $this->set( 'oridemrsa', $this->Option->oridemrsa() );
            $this->set( 'typeserins', $this->Option->typeserins() );
            $this->set( 'accoemplois', $this->Accoemploi->find( 'list' ) );
            $this->set( 'printed', $this->Option->printed() );
            $struct = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
            $this->set( 'struct', $struct );

            $this->set( 'options', $this->Cui->allEnumLists() );
            return $return;
        }


        //*********************************************************************

        function nouveaux() {
            $this->_index( 'Decisioncui::nonvalide' );
        }

        //---------------------------------------------------------------------

        function valides() {
            $this->_index( 'Decisioncui::valides' );
        }

        //---------------------------------------------------------------------

        function enattente() {
            $this->_index( 'Decisioncui::enattente' );
        }

        //*********************************************************************


        function _index( $statutValidation = null ) {
            $this->assert( !empty( $statutValidation ), 'invalidParameter' );

            if( Configure::read( 'CG.cantons' ) ) {
                $this->set( 'cantons', $this->Canton->selectList() );
            }

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $params = $this->data;

            if( !empty( $params ) ) {
                /**
                *
                * Sauvegarde
                *
                */

                // On a renvoyé  le formulaire de la cohorte
                if( !empty( $this->data['Cui'] ) ) {
                    $valid = $this->Dossier->Foyer->Personne->Cui->saveAll( $this->data['Cui'], array( 'validate' => 'only', 'atomic' => false ) );
                    if( $valid ) {
                        $this->Dossier->begin();
                        $saved = $this->Dossier->Foyer->Personne->Cui->saveAll( $this->data['Cui'], array( 'validate' => 'first', 'atomic' => false ) );
                        if( $saved ) {
                            // FIXME ?
                            foreach( array_unique( Set::extract( $this->data, 'Cui.{n}.dossier_id' ) ) as $dossier_id ) {
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

                if( ( $statutValidation == 'Decisioncui::nonvalide' ) || ( ( $statutValidation == 'Decisioncui::valides' ) && !empty( $this->data ) ) || ( ( $statutValidation == 'Decisioncui::enattente' ) && !empty( $this->data ) ) ) {
                    $this->Dossier->begin(); // Pour les jetons

                    $this->paginate = $this->Cohortecui->search( $statutValidation, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                    $this->paginate['limit'] = 10;
                    $cohortecui = $this->paginate( 'Cui' );

                    $this->Dossier->commit();

                    foreach( $cohortecui as $key => $value ) {
                        if( $value['Cui']['decisioncui'] == 'E' ) {
                            $cohortecui[$key]['Cui']['proposition_decisioncui'] = 'V';
                        }
                        else {
                            $cohortecui[$key]['Cui']['proposition_decisioncui'] = $value['Cui']['decisioncui'];
                        }

                        if( empty( $value['Cui']['datevalidationcui'] ) ) {
                            $cohortecui[$key]['Cui']['proposition_datevalidationcui'] = date( 'Y-m-d' );
                        }
                        else {
                            $cohortecui[$key]['Cui']['proposition_datevalidationcui'] = $value['Cui']['datevalidationcui'];
                        }
                    }

                    $this->set( 'cohortecui', $cohortecui );
                }

            }

            if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
                $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
            }
            else {
                $this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
            }

            /// Population du select référents liés aux structures
            $structurereferente_id = Set::classicExtract( $this->data, 'Filtre.structurereferente_id' );
            $referents = $this->Referent->referentsListe( $structurereferente_id );
            $this->set( 'referents', $referents );

            switch( $statutValidation ) {
                case 'Decisioncui::nonvalide':
                    $this->set( 'pageTitle', 'CUIs à valider' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Decisioncui::enattente':
                    $this->set( 'pageTitle', 'CUIs en attente' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Decisioncui::valides':
                    $this->set( 'pageTitle', 'CUIs validés' );
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }
        }


        /// Export du tableau en CSV
        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $querydata = $this->Cohortecui->search( 'Decisioncui::valides', $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
            unset( $querydata['limit'] );
            $cuis = $this->Cui->find( 'all', $querydata );

            /// Population du select référents liés aux structures
            $structurereferente_id = Set::classicExtract( $this->data, 'Cui.structurereferente_id' );
            $referents = $this->Referent->referentsListe( $structurereferente_id );
            $this->set( 'referents', $referents );

// debug($cuis);
// die();
            $this->layout = ''; // FIXME ?
            $this->set( compact( 'cuis' ) );
        }
    }
?>
