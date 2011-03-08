<?php
    class DecisionspropospdosController extends AppController
    {
        public $name = 'Decisionspropospdos';
        /**
        * @access public
        */

        public $components = array( 'Default', 'Gedooo' );

        public $helpers = array( 'Default2', 'Ajax' );
        public $uses = array( 'Decisionpropopdo', 'Option', 'Pdf'  );

        
		public $commeDroit = array(
			'add' => 'Traitementspdos:edit'
		);

        /**
        *
        */

        protected function _options() {
            $options = $this->Decisionpropopdo->allEnumLists();
            
            $this->set( 'decisionpdo', $this->Decisionpropopdo->Decisionpdo->find( 'list' ) );
            
            return $options;
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

            $this->Decisionpropopdo->begin();
            $this->set( 'options', $this->_options() );

            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $propopdo_id = $id;

                $propopdo = $this->Decisionpropopdo->Propopdo->findById( $id, null, null, -1 );
                $this->set( 'propopdo', $propopdo );
                $personne_id = Set::classicExtract( $propopdo, 'Propopdo.personne_id' );
                $dossier_id = $this->Decisionpropopdo->Propopdo->Personne->dossierId( $personne_id );
            }
            else if( $this->action == 'edit' ) {
                $decisionpropopdo_id = $id;
                $decisionpropopdo = $this->Decisionpropopdo->findById( $decisionpropopdo_id, null, null, 1 );
                $this->assert( !empty( $decisionpropopdo ), 'invalidParameter' );
// debug($traitement);
                $propopdo_id = Set::classicExtract( $decisionpropopdo, 'Decisionpropopdo.propopdo_id' );
                $personne_id = Set::classicExtract( $decisionpropopdo, 'Propopdo.personne_id' );
                $dossier_id = $this->Decisionpropopdo->Propopdo->Personne->dossierId( $personne_id );
            }
            
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );
            $this->set( 'dossier_id', $dossier_id );
            $this->set( 'propopdo_id', $propopdo_id );
            $this->set( 'personne_id', $personne_id );


            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Decisionpropopdo->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );


            if( !empty( $this->data ) ){
                if( $this->Decisionpropopdo->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    $saved = true;
                    
                    $saved = $this->Decisionpropopdo->save( $this->data );

                    if( $saved ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Decisionpropopdo->commit(); // FIXME
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'propospdos', 'action' => 'edit', $propopdo_id ) );
                    }
                    else {
                        $this->Decisionpropopdo->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
                else {
                    $this->Decisionpropopdo->rollback();
                    $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                }
            }
            elseif( $this->action == 'edit' )
                $this->data = $decisionpropopdo;

            $this->Decisionpropopdo->commit();

            $this->render( $this->action, null, 'add_edit' );
        }


        /**
        *   Enregistrement du courrier de proposition lors de l'enregistrement de la proposition
        */

        public function decisionproposition( $id ) {
            $this->assert( is_numeric( $id ), 'invalidParameter' );

            $this->Decisionpropopdo->begin();

            $content = $this->Pdf->find(
                'first',
                array(
                    'fields' => array(
                        'Pdf.document'
                    ),
                    'conditions' => array(
                        'Pdf.modele' => 'Decisionpropopdo',
                        'Pdf.fk_value' => $id
                    ),
                    'recursive' => -1
                )
            );

            if( $content['Pdf']['document'] !== false ) {
                $this->Decisionpropopdo->commit();
                $this->layout = '';
                $this->Gedooo->sendPdfContentToClient( $content['Pdf']['document'], sprintf( "proposition_decision-%s.pdf", date( "Ymd-H\hi" ) ) );
            }
            else {
                $this->Decisionpropopdo->rollback();
                $this->cakeError( 'error500' );
            }
        }



        /**
        * Suppression de la proposition de décision
        */

        public function delete( $id ) {
            $decisionpropopdo = $this->Decisionpropopdo->findById( $id, null, null, -1 );
            $pdo_id = Set::classicExtract( $decisionpropopdo, 'Decisionpropopdo.propopdo_id' );

            $success = $this->Decisionpropopdo->delete( $id );
            $this->_setFlashResult( 'Delete', $success );
            $this->redirect( array( 'controller' => 'propospdos', 'action' => 'edit', $pdo_id ) );
        }
    }
?>
