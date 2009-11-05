<?php
    class ApresController extends AppController
    {

        var $name = 'Apres';
        var $uses = array( 'Apre', 'Option', 'Personne', 'Referentapre', 'Prestation', 'Dsp', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
        var $aucunDroit = array( 'ajaxrefapre' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $options = $this->Apre->allEnumLists();
            $this->set( 'options', $options );
            $optionsacts = $this->Actprof->allEnumLists();
            $this->set( 'optionsacts', $optionsacts );
            $optionsdsps = $this->Dsp->allEnumLists();
            $this->set( 'optionsdsps', $optionsdsps );
            $optionslogts = $this->Amenaglogt->allEnumLists();
            $this->set( 'optionslogts', $optionslogts );
            $optionscrea = $this->Acccreaentr->allEnumLists();
            $this->set( 'optionscrea', $optionscrea );
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

            $refsapre = $this->Referentapre->_referentsApre( Set::classicExtract( $apres, 'Apre.id' ) );
            $this->set( 'refsapre', $refsapre );
            $this->set( 'personne_id', $personne_id );
        }



        /** ********************************************************************
        *   Ajax pour les coordonnées du référent APRE
        *** *******************************************************************/

        function ajaxrefapre( $referentapre_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
            $dataReferentapre_id = Set::extract( $this->data, 'Apre.referentapre_id' );
            $referentapre_id = ( empty( $referentapre_id ) && !empty( $dataReferentapre_id ) ? $dataReferentapre_id : $referentapre_id );

            $refapre = $this->Apre->Referentapre->findbyId( $referentapre_id, null, null, -1 );
            $this->set( 'refapre', $refapre );
            $this->render( 'ajaxrefapre', 'ajax' );
        }


        /** ********************************************************************
        *
        *** *******************************************************************/

        function view( $apre_id = null ){
            $apre = $this->Apre->findById( $apre_id );
            $this->assert( !empty( $apre ), 'invalidParameter' );

            $refsapre = $this->Referentapre->_referentsApre( Set::classicExtract( $apre, 'Apre.id' ) );
            $this->set( 'refsapre', $refsapre );

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

            $this->Apre->begin();

            /// Pièces liées à l'APRE
            $piecesapre = $this->Apre->Pieceapre->find( 'list' );
            $this->set( 'piecesapre', $piecesapre );

            /// Pièces liées à la Formqualif
            $piecesformqualif = $this->Apre->Formqualif->Pieceformqualif->find( 'list' );
            $this->set( 'piecesformqualif', $piecesformqualif );

            /// Pièces liées à la Actprof
            $piecesactprof = $this->Apre->Actprof->Pieceactprof->find( 'list' );
            $this->set( 'piecesactprof', $piecesactprof );

            /// Pièces liées au Permisb
            $piecespermisb = $this->Apre->Permisb->Piecepermisb->find( 'list' );
            $this->set( 'piecespermisb', $piecespermisb );

            /// Pièces liées à Amenaglogt
            $piecesamenaglogt = $this->Apre->Amenaglogt->Pieceamenaglogt->find( 'list' );
            $this->set( 'piecesamenaglogt', $piecesamenaglogt );

            /// Pièces liées à Acccreaentr
            $piecesacccreaentr = $this->Apre->Acccreaentr->Pieceacccreaentr->find( 'list' );
            $this->set( 'piecesacccreaentr', $piecesacccreaentr );

            /// Pièces liées à Acqmatprof
            $piecesacqmatprof = $this->Apre->Acqmatprof->Pieceacqmatprof->find( 'list' );
            $this->set( 'piecesacqmatprof', $piecesacqmatprof );

            /// Pièces liées à Locvehicinsert
            $pieceslocvehicinsert = $this->Apre->Locvehicinsert->Piecelocvehicinsert->find( 'list' );
            $this->set( 'pieceslocvehicinsert', $pieceslocvehicinsert );

            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $personne_id = $id;
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );

                ///Création automatique du N° APRE de la forme : Année / Mois / N°
                $numapre = date('Ym').sprintf( "%010s", $id /*$this->Apre->find( 'count' ) + 1*/ );
                $this->set( 'numapre', $numapre);
            }
            else if( $this->action == 'edit' ) {
                $apre_id = $id;
                $apre = $this->Apre->findById( $apre_id, null, null, 0 );
                $this->assert( !empty( $apre ), 'invalidParameter' );

                $personne_id = $apre['Apre']['personne_id'];
                $dossier_rsa_id = $this->Apre->dossierId( $apre_id );

                $this->set( 'numapre', Set::extract( $apre, 'Apre.numeroapre' ) );
            }

            $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_rsa_id ), 'invalidParameter' );
            $this->set( 'dossier_id', $dossier_rsa_id );

            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Apre->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );


            $personne = $this->Apre->Personne->detailsApre( $personne_id );
            $this->set( 'personne', $personne );

            ///Nombre d'enfants par foyer
            $nbEnfants = $this->Apre->nbEnfants( Set::classicExtract( $personne, 'Foyer.id' ) );
            $this->set( 'nbEnfants', $nbEnfants );

            ///Récupération de la liste des référents liés à l'APRE
            $refsapre = $this->Referentapre->_referentsApre( Set::classicExtract( $personne, 'Apre.id' ) );
            $this->set( 'refsapre', $refsapre );

            if( !empty( $this->data ) ){
                // FIXME: pourquoi doit-on faire ceci ?
                $this->Apre->bindModel( array( 'hasOne' => array( 'Formqualif', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert' ) ), false );

                if( $this->Apre->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    $saved = $this->Apre->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );
                    $tablesLiees = array(
                        'Formqualif' => 'Pieceformqualif',
                        'Actprof' => 'Pieceactprof',
                        'Permisb' => 'Piecepermisb',
                        'Amenaglogt' => 'Pieceamenaglogt',
                        'Acccreaentr' => 'Pieceacccreaentr',
                        'Acqmatprof' => 'Pieceacqmatprof',
                        'Locvehicinsert' => 'Piecelocvehicinsert'
                    );
                    foreach( $tablesLiees as $model => $piecesLiees ) {
                        if( !empty( $this->data[$piecesLiees] ) ) {
                            $linkedData = array(
                                $model => array(
                                    'id' => $this->Apre->{$model}->id
                                ),
                                $piecesLiees => $this->data[$piecesLiees]
                            );
                            $saved = $this->Apre->{$model}->save( $linkedData ) && $saved;
                        }
                    }
                    if( $saved ) {
                        $this->Jetons->release( $dossier_rsa_id );
//                         $this->Apre->rollback(); // FIXME
                        $this->Apre->commit(); // FIXME
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
//             $this->Apre->rollback(); // FIXME
            $this->Apre->commit(); // FIXME


            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }

    }
?>