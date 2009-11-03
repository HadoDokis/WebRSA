<?php
    class ApresController extends AppController
    {

        var $name = 'Apres';
        var $uses = array( 'Apre', 'Option', 'Personne', 'Referentapre', 'Prestation', 'Dsp' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
//         var $aucunDroit = array( );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $options = $this->Apre->allEnumLists();
            $this->set( 'options', $options );
            $optionsdsps = $this->Dsp->allEnumLists();
            $this->set( 'optionsdsps', $optionsdsps );
            $this->set( 'typevoie', $this->Option->typevoie() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'sitfam', $this->Option->sitfam() );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ){
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

            $apres = $this->Apre->find( 'all', array( 'conditions' => array( 'Apre.personne_id' => $personne_id ) ) );
            $this->set( 'apres', $apres );
// debug($apres);
            $this->set( 'personne_id', $personne_id );
        }


        /** ********************************************************************
        *
        *** *******************************************************************/

        function view( $apre_id = null ){
            $apre = $this->Apre->findById( $apre_id );
            $this->assert( !empty( $apre ), 'invalidParameter' );

            $referentapre = $this->Referentapre->find( 'list', array( 'fields' => array( 'id', 'nom' ) ) );
            $this->set( 'referentapre', $referentapre );

            $this->set( 'apre', $apre );
            $this->set( 'personne_id', $apre['Apre']['personne_id'] );
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
            $this->assert( valid_int( $id ), 'invalidParameter' );

            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $personne_id = $id;
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            }
            else if( $this->action == 'edit' ) {
                $apre_id = $id;
                $apre = $this->Apre->findById( $apre_id, null, null, -1 );
                $this->assert( !empty( $apre ), 'invalidParameter' );

                $personne_id = $apre['Apre']['personne_id'];
                $dossier_rsa_id = $this->Apre->dossierId( $apre_id );
            }

            $this->Apre->begin();

            $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_rsa_id ), 'invalidParameter' );
            $this->set( 'dossier_id', $dossier_rsa_id );

            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Apre->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );


            $personne = $this->Apre->Personne->detailsApre( $personne_id );
            $this->set( 'personne', $personne );

// debug($personne);
            $nbEnfants = $this->Apre->nbEnfants( Set::classicExtract( $personne, 'Foyer.id' ) );
            $this->set( 'nbEnfants', $nbEnfants );

            ///Création automatique du N° APRE de la forme : Année / Mois / N°
            $numapre = date('Ym').sprintf("%05s\n",   $id);
            $this->set( 'numapre', $numapre);

            if( !empty( $this->data ) ){
                if( $this->Apre->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Apre->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
                        $numapre++;
                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Apre->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'apres','action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {
                    $this->data = $apre;
                }
            }
            $this->Apre->commit();


            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }

    }
?>