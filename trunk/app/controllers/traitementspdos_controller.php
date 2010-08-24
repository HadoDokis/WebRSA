<?php
    class TraitementspdosController extends AppController
    {
        public $name = 'Traitementspdos';

        public $uses = array( 'Traitementpdo', 'Propopdo', 'Personne', 'Dossier', 'Descriptionpdo', 'Traitementtypepdo' );
        /**
        * @access public
        */

        public $components = array( 'Default' );
        
		var $commeDroit = array(
			'view' => 'Traitementspdos:index',
			'add' => 'Traitementspdos:edit'
		);

        /**
        *
        */

        protected function _options() {
            $options = $this->{$this->modelClass}->enums();
            $options[$this->modelClass]['descriptionpdo_id'] = $this->Descriptionpdo->find( 'list' );
            $options[$this->modelClass]['traitementtypepdo_id'] = $this->Traitementtypepdo->find( 'list' );
            $this->set( 'gestionnaire', $this->User->find(
                    'list',
                    array(
                        'fields' => array(
                            'User.nom_complet'
                        ),
                        'conditions' => array(
                            'User.isgestionnaire' => 'O'
                        )
                    )
                )
            );


            return $options;
        }

        /**
        *
        */

        public function index( $id = null ) {
            $this->{$this->modelClass}->recursive = 0;
            $traitementspdos = $this->{$this->modelClass}->find(
                'all',
                array(
                    'conditions' => array(
                        'propopdo_id' => $id
                    )
                )
            );
            $this->set( compact( 'traitementspdos' ) );

            // Dossier
            $pdo = $this->{$this->modelClass}->Propopdo->findById( $id, null, null, -1 );
            $this->set( 'pdo', $pdo );

            $personne_id = Set::classicExtract( $pdo, 'Propopdo.personne_id' );
            $pdo_id = Set::classicExtract( $pdo, 'Propopdo.id' );

            $this->set( 'personne_id', $personne_id );
            $this->set( 'pdo_id', $pdo_id );
            $this->set( 'options', $this->_options() );
        }

        /**
        *
        */

        public function view( $id = null ) {
            $this->{$this->modelClass}->recursive = -1;
            $this->Default->view( $id );
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

            $this->Traitementpdo->begin();
            $this->set( 'options', $this->_options() );

            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $propopdo_id = $id;

                $propopdo = $this->Propopdo->findById( $id, null, null, -1 );
                $this->set( 'propopdo', $propopdo );
                $personne_id = Set::classicExtract( $propopdo, 'Propopdo.personne_id' );
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            }
            else if( $this->action == 'edit' ) {
                $traitement_id = $id;
                $traitement = $this->Traitementpdo->findById( $traitement_id, null, null, 1 );
                $this->assert( !empty( $traitement ), 'invalidParameter' );
// debug($traitement);
                $propopdo_id = Set::classicExtract( $traitement, 'Traitementpdo.propopdo_id' );
                $personne_id = Set::classicExtract( $traitement, 'Propopdo.personne_id' );
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            }

            $this->assert( !empty( $dossier_rsa_id ), 'invalidParameter' );
            $this->set( 'personne_id', $personne_id );
            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'propopdo_id', $propopdo_id );


            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Traitementpdo->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );


            if( !empty( $this->data ) ){

                if( $this->Traitementpdo->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    $saved = $this->Traitementpdo->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );

                    if( $saved ) {
                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Traitementpdo->commit(); // FIXME
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'traitementspdos','action' => 'index', $propopdo_id ) );
                    }
                    else {
                        $this->Traitementpdo->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {
                    $this->data = $traitement;
                }
            }
            $this->Traitementpdo->commit();


            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        */

        public function delete( $id = null ) {
            $this->Default->delete( $id, array( 'action' => 'index' ) );
        }
        

        /**
        *
        */

        public function gedooo( $id = null ) {

        }
    }
?>
