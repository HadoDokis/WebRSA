<?php
    class RelancesapresController extends AppController
    {

        var $name = 'Relancesapres';
        var $uses = array( 'Apre', 'Option', 'Personne', 'Referentapre', 'Prestation', 'Dsp', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert', 'Apre', 'Relanceapre' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
        var $aucunDroit = array( 'ajaxpiece' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $options = $this->Relanceapre->allEnumLists();
            $this->set( 'options', $options );
            $piecesapre = $this->Apre->Pieceapre->find( 'list' );
            $this->set( 'piecesapre', $piecesapre );
        }

        /** ********************************************************************
        *   Ajax pour les pièces liées à la bonne APRE
        *** *******************************************************************/

        function ajaxpiece( $apre_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
            $dataApre_id = Set::extract( $this->data, 'Relanceapre.apre_id' );
            $apre_id = ( empty( $apre_id ) && !empty( $dataApre_id ) ? $dataApre_id : $apre_id );

            $apre = $this->Referentapre->Apre->findbyId( $apre_id, null, null, 1 );
            $this->set( 'apre', $apre );

            $this->render( 'ajaxpiece', 'ajax' );
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

            $this->Relanceapre->begin();

            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $apre_id = $id;

                $apre = $this->Apre->find( 'first', array( 'conditions' => array( 'Apre.id' => $id ) ) );
                $this->set( 'apre', $apre );

                $dossier_rsa_id = $this->Personne->dossierId( Set::classicExtract( $apre, 'Apre.personne_id' ) );
            }
            else if( $this->action == 'edit' ) {
                $relanceapre_id = $id;
                $relanceapre = $this->Relanceapre->findById( $relanceapre_id, null, null, 1 );
                $this->assert( !empty( $relanceapre ), 'invalidParameter' );

                $personne_id = Set::classicExtract( $relanceapre, 'Apre.personne_id' );
                $apre = $this->Apre->find( 'first', array( 'conditions' => array( 'Apre.personne_id' => $personne_id ) ) );
                $this->set( 'apre', $apre );
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            }

            $this->assert( !empty( $dossier_rsa_id ), 'invalidParameter' );
            $this->set( 'dossier_id', $dossier_rsa_id );


            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Relanceapre->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );


            if( !empty( $this->data ) ){

                if( $this->Relanceapre->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    $saved = $this->Relanceapre->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );

                    if( $saved ) {
                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Relanceapre->commit(); // FIXME
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'apres','action' => 'index', Set::classicExtract( $apre, 'Apre.personne_id' ) ) );
                    }
                    else {
                        $this->Relanceapre->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {
                    $this->data = $relanceapre;
                }
            }
            $this->Relanceapre->commit();


            $this->render( $this->action, null, 'add_edit' );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function view( $relanceapre_id = null ){
            $relanceapre = $this->Relanceapre->findById( $relanceapre_id );
            $this->assert( !empty( $relanceapre ), 'invalidParameter' );

            $apre = $this->Apre->findByPersonneId( Set::classicExtract( $relanceapre, 'Relanceapre.apre_id' ) );
            $this->set( 'apre', $apre );


            $this->set( 'relanceapre', $relanceapre );
            $this->set( 'personne_id', Set::classicExtract( $relanceapre, 'Relanceapre.apre_id' ) );

        }
    }
?>