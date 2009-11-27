<?php
    App::import('Sanitize');
    class CohortescomitesapresController extends AppController
    {
        var $name = 'Cohortescomitesapres';
        var $uses = array( 'Apre', 'Option', 'Personne', 'ApreComiteapre', 'Cohortecomiteapre', 'Comiteapre', 'Participantcomite', 'Apre', 'Referentapre' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );

        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'aviscomite', 'notificationscomite' ) ) ) );
            parent::__construct();
        }


        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'referentapre', $this->Referentapre->find( 'list' ) );

            // FIXME
            //$options = $this->Apre->ApreComiteapre->allEnumLists();
            $options = array(
                'decisioncomite' => array(
                    'ACC' => __d( 'apre', 'ENUM::DECISIONCOMITE::ACC', true ),
                    'AJ' => __d( 'apre', 'ENUM::DECISIONCOMITE::AJ', true ),
                    'REF' => __d( 'apre', 'ENUM::DECISIONCOMITE::REF', true ),
                )
            );
            $this->Apre->ApreComiteapre->validate = array(
                'decisioncomite' => array(
                    array(
                        'rule'      => array( 'inList', array( 'AJ', 'ACC', 'REF' ) ),
                        'message'   => 'Veuillez choisir une valeur.',
                        'allowEmpty' => false
                    )
                ),
                'montantattribue' => array(
                    array(
                        'rule' => 'numeric',
                        'message' => 'Veuillez entrer une valeur numérique.',
                        'allowEmpty' => false,
                        'required' => false
                    ),
                    array(
                        'rule' => array( 'between', 0, 4 ),
                        'message' => 'Veuillez entrer une valeur entre 0 et 9999.',
                        'allowEmpty' => false,
                        'required' => false
                    ),
                ),
            );
            $this->set( 'options', $options );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function aviscomite() {
            $this->_index( 'Cohortecomiteapre::aviscomite' );
        }

        //---------------------------------------------------------------------

        function notificationscomite() {
            $this->_index( 'Cohortecomiteapre::notificationscomite' );
        }
        /** ********************************************************************
        *
        *** *******************************************************************/

        function _index( $avisComite = null ){
            $this->set( 'comitesapre', $this->Comiteapre->find( 'list' ) );

            $this->Dossier->begin(); // Pour les jetons
            if( !empty( $this->data ) ) {
                if( !empty( $this->data['ApreComiteapre'] ) ) {
                    $data = Set::extract( $this->data, '/ApreComiteapre' );
                    if( $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                        $saved = $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) );
                        if( $saved && empty( $this->Apre->ApreComiteapre->validationErrors ) ) {
                            $this->ApreComiteapre->commit();
                            $this->redirect( array( 'action' => 'aviscomite' ) ); // FIXME
                        }
                        else {
                            $this->ApreComiteapre->rollback();
                        }
                    }
                }

                $comitesapres = $this->Cohortecomiteapre->search( $avisComite, $this->data );
                $comitesapres['limit'] = 10;
                $this->paginate = $comitesapres;
                $comitesapres = $this->paginate( 'Comiteapre' );

                $this->set( 'comitesapres', $comitesapres );
            }

            switch( $avisComite ) {
                case 'Cohortecomiteapre::aviscomite':
                    $this->set( 'pageTitle', 'Décisions des comités' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Cohortecomiteapre::notificationscomite':
                    $this->set( 'pageTitle', 'Notifications décisions comités' );
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }

            $this->Dossier->commit(); //FIXME
        }

        function exportcsv() {

            $querydata = $this->Cohortecomiteapre->search( null, array_multisize( $this->params['named'] ) );
            unset( $querydata['limit'] );
            $decisionscomites = $this->Comiteapre->find( 'all', $querydata );

            $this->layout = '';
            $this->set( compact( 'decisionscomites' ) );

        }

    }
?>