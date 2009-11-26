<?php
    App::import('Sanitize');
    class CohortescomitesapresController extends AppController
    {
        var $name = 'Cohortescomitesapres';
        var $uses = array( 'Apre', 'Option', 'Personne', 'Cohortecomiteapre', 'Comiteapre', 'Participantcomite', 'Apre', 'Referentapre' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'referentapre', $this->Referentapre->find( 'list' ) );

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

            if( !empty( $this->data ) ) {
                $this->Dossier->begin(); // Pour les jetons
                $comitesapres = $this->Cohortecomiteapre->search( $avisComite, $this->data );
                $comitesapres['limit'] = 10;
                $this->paginate = $comitesapres;
                $comitesapres = $this->paginate( 'Comiteapre' );
// debug($comitesapres);
                $this->Dossier->commit();
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

        }

    }
?>