<?php
    class ComitesapresController extends AppController
    {

        var $name = 'Comitesapres';
        var $uses = array( 'Apre', 'Option', 'Personne', 'Comiteapre'/*, 'ComiteapreParticipantcomite'*/, 'Participantcomite', 'Apre', 'Referent' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
        var $components = array( 'Prg' => array( 'actions' => array( 'index', 'liste' ) ) );

        /** ********************************************************************
        *
        *** *******************************************************************/
        protected function _setOptions() {
            $this->set( 'referent', $this->Referent->find( 'list' ) );
            $options = $this->Comiteapre->ApreComiteapre->allEnumLists();
            $options = Set::merge( $options, $this->Comiteapre->ComiteapreParticipantcomite->allEnumLists() );
            $this->set( 'options', $options );
        }

/*
        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'referent', $this->Referent->find( 'list' ) );
            $options = $this->Comiteapre->ApreComiteapre->allEnumLists();
            $options = Set::merge( $options, $this->Comiteapre->ComiteapreParticipantcomite->allEnumLists() );
            $this->set( 'options', $options );
        }*/

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index() {
            $this->_index( 'Comiteapre::index' );
        }

        //---------------------------------------------------------------------

        function liste() {
            $this->_index( 'Comiteapre::liste' );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function _index( $display = null ){

            if( !empty( $this->data ) ) {
                $this->Dossier->begin(); // Pour les jetons
                $comitesapres = $this->Comiteapre->search( $display, $this->data );
                $comitesapres['limit'] = 10;
                $this->paginate = $comitesapres;
                $comitesapres = $this->paginate( 'Comiteapre' );
// debug($comitesapres);
                $this->Dossier->commit();
                $this->_setOptions();
                $this->set( 'comitesapres', $comitesapres );
            }

            switch( $display ) {
                case 'Comiteapre::index':
                    $this->set( 'pageTitle', 'Recherche de comités' );
                    $this->render( $this->action, null, 'index' );
                    break;
                case 'Comiteapre::liste':
                    $this->set( 'pageTitle', 'Liste des comités' );
                    $this->render( $this->action, null, 'liste' );
                    break;
            }

        }

        /** **************************************************************************************
        *   Affichage du Comité après sa création permettant ajout des APREs et des Participants
        *** *************************************************************************************/

        function view( $comiteapre_id = null ){
            $comiteapre = $this->Comiteapre->find(
                'first',
                array(
                    'conditions' => array( 'Comiteapre.id' => $comiteapre_id ),
                    'recursive' => 2
                )
            );
            $this->assert( !empty( $comiteapre ), 'invalidParameter' );

            foreach( $comiteapre['Apre'] as $key => $apre ) {
                // Personne
                $personne = $this->Apre->Personne->findById( $apre['personne_id'], null, null, -1 );
                $comiteapre['Apre'][$key] = Set::merge( $comiteapre['Apre'][$key], $personne );

                // Foyer
                $foyer = $this->Apre->Personne->Foyer->findById( $personne['Personne']['foyer_id'], null, null, -1 );
                $comiteapre['Apre'][$key] = Set::merge( $comiteapre['Apre'][$key], $foyer );

                // Dossier
                $dossier = $this->Apre->Personne->Foyer->Dossier->findById( $foyer['Foyer']['dossier_rsa_id'], null, null, -1 );
                $comiteapre['Apre'][$key] = Set::merge( $comiteapre['Apre'][$key], $dossier );

                // Adresse
                $adresse = $this->Apre->Personne->Foyer->Adressefoyer->Adresse->findById( $foyer['Foyer']['id'], null, null, -1 );
                $comiteapre['Apre'][$key] = Set::merge( $comiteapre['Apre'][$key], $adresse );
            }

            $this->set( 'comiteapre', $comiteapre );
            $this->_setOptions();
            $participants = $this->Participantcomite->find( 'list' );
            $this->set( 'participants', $participants );

        }
        /** **********************************************************************************************
        *   Affichage du rapport suite au Comité ( présence / absence des participants + décision APREs)
        *** **********************************************************************************************/

        function rapport( $comiteapre_id = null ){
            $this->assert( valid_int( $comiteapre_id ), 'invalidParameter' );

            $comiteapre = $this->Comiteapre->find(
                'first',
                array(
                    'conditions' => array( 'Comiteapre.id' => $comiteapre_id ),
                    'recursive' => 2
                )
            );

            foreach( $comiteapre['Apre'] as $key => $apre ) {
                // Personne
                $personne = $this->Apre->Personne->findById( $apre['personne_id'], null, null, -1 );
                $comiteapre['Apre'][$key] = Set::merge( $comiteapre['Apre'][$key], $personne );

                // Foyer
                $foyer = $this->Apre->Personne->Foyer->findById( $personne['Personne']['foyer_id'], null, null, -1 );
                $comiteapre['Apre'][$key] = Set::merge( $comiteapre['Apre'][$key], $foyer );

                // Dossier
                $dossier = $this->Apre->Personne->Foyer->Dossier->findById( $foyer['Foyer']['dossier_rsa_id'], null, null, -1 );
                $comiteapre['Apre'][$key] = Set::merge( $comiteapre['Apre'][$key], $dossier );

                // Adresse
                $adresse = $this->Apre->Personne->Foyer->Adressefoyer->Adresse->findById( $foyer['Foyer']['id'], null, null, -1 );
                $comiteapre['Apre'][$key] = Set::merge( $comiteapre['Apre'][$key], $adresse );
            }

            $this->set( 'comiteapre', $comiteapre );
            $this->_setOptions();
            $participants = $this->Participantcomite->find( 'list' );
            $this->set( 'participants', $participants );

        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }


        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function _add_edit( $id = null ) {
            $this->Comiteapre->begin();

            $isRapport = Set::classicExtract( $this->params, 'named.rapport' );

            /// Récupération des id afférents
            if( $this->action == 'add' ) {
                $this->assert( empty( $id ), 'invalidParameter' );
            }
            else if( $this->action == 'edit' ) {
                $comiteapre_id = $id;
                $comiteapre = $this->Comiteapre->findById( $comiteapre_id, null, null, 1 );
                $this->assert( !empty( $comiteapre ), 'invalidParameter' );

            }

            if( !empty( $this->data ) ){

                if( $this->Comiteapre->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    $saved = $this->Comiteapre->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );

                    if( $saved ) {
                        $this->Comiteapre->commit(); // FIXME
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );

                        if( !$isRapport ){
                            $this->redirect( array(  'controller' => 'comitesapres', 'action' => 'view', $this->Comiteapre->id ) );
                        }
                        else if( $isRapport ){
                            $this->redirect( array(  'controller' => 'comitesapres', 'action' => 'rapport', $this->Comiteapre->id ) );
                        }
                    }
                    else {
                        $this->Comiteapre->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {
                    $this->data = $comiteapre;
                }
            }
            $this->Comiteapre->commit();
            $this->_setOptions();
            $this->render( $this->action, null, 'add_edit' );
        }

        function exportcsv() {
            $querydata = $this->Comiteapre->search( 'Comiteapre::index', array_multisize( $this->params['named'] ) );
            unset( $querydata['limit'] );
            $comitesapres = $this->Comiteapre->find( 'all', $querydata );

            $this->_setOptions();
            $this->layout = '';
            $this->set( compact( 'comitesapres' ) );
        }

    }
?>