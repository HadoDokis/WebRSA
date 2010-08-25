<?php
    class EntretiensController extends AppController
    {

        var $name = 'Entretiens';
        var $uses = array( 'Entretien', 'Option', 'Personne', 'Dsp', 'Typerdv', 'Rendezvous', 'Structurereferente', 'Referent' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform' );

		var $commeDroit = array(
			'view' => 'Entretiens:index',
			'add' => 'Entretiens:edit'
		);

        /** ********************************************************************
        *
        *** *******************************************************************/
        protected function _setOptions() {
            $options = array();
            $optionsdsps = array();

            $options = $this->Entretien->enums();
            $optionsdsps = $this->Dsp->enums();
            $options = Set::merge( $options,  $optionsdsps );

            $options[$this->modelClass]['typerdv_id'] = $this->{$this->modelClass}->Typerdv->find( 'list' );
            $options[$this->modelClass]['structurereferente_id'] = $this->{$this->modelClass}->Structurereferente->listOptions();

            $this->set( compact( 'options' ) );
// debug($options);
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ){
            // On s'assure que la personne existe
            $nbrPersonnes = $this->Entretien->Personne->find(
                'count',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    ),
                    'recursive' => -1
                )
            );
            $this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

            /*$dsps = $this->Entretien->Personne->Dsp->find(
                'first',
                array(
                    'conditions' => array(
                        'Dsp.personne_id' => $personne_id
                    ),
                    'order' => 'Dsp.id ASC'
                )
            );*/

            // On n'a besoin que de Structurereferente et Referent en modèles liés
            $belongsTo = array(
                'Structurereferente' => $this->Entretien->belongsTo['Structurereferente'],
                'Referent' => $this->Entretien->belongsTo['Referent'],
            );
            $this->Entretien->unbindModelAll();
            $this->Entretien->bindModel( array( 'belongsTo' => $belongsTo ) );
			$this->Entretien->forceVirtualFields = true;
            $entretiens = $this->Entretien->find(
                'all',
                array(
                    'fields' => array(
                        'Entretien.id',
                        'Entretien.personne_id',
                        'Entretien.dateentretien',
                        'Structurereferente.lib_struc',
                        'Referent.nom_complet'
                    ),
                    'conditions' => array(
                        'Entretien.personne_id' => $personne_id
                    )
                )
            );
			$this->Entretien->forceVirtualFields = false;

//             $this->_setOptions();
            $this->set( compact( /*'dsps', */'entretiens' ) );
            $this->set( 'personne_id', $personne_id );
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

            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                if( $this->action == 'edit' ) {
                    $id = $this->Entretien->field( 'personne_id', array( 'id' => $id ) );
                }
                $this->redirect( array( 'action' => 'index', $id ) );
            }

            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $personne_id = $id;
            }
            else if( $this->action == 'edit' ) {
                $entretien_id = $id;
                $entretien = $this->Entretien->findById( $entretien_id, null, null, -1 );
                $this->assert( !empty( $entretien ), 'invalidParameter' );

                $personne_id = $entretien['Entretien']['personne_id'];
            }

            $this->Entretien->begin();

            $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_rsa_id ), 'invalidParameter' );

            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Entretien->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );

            ///Récupération de la liste des structures référentes
            $structs = $this->Structurereferente->listOptions( );
            $this->set( 'structs', $structs );

            ///Récupération de la liste des référents
            $referents = $this->Referent->listOptions();
            $this->set( 'referents', $referents );

            if( !empty( $this->data ) ){

                if( $this->Entretien->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Entretien->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Entretien->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'entretiens','action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {

                    $entretien['Entretien']['referent_id'] = $entretien['Entretien']['structurereferente_id'].'_'.$entretien['Entretien']['referent_id'];

                    $rdv_id = Set::classicExtract( $entretien, 'Entretien.rendezvous_id' );
                    $rdv = $this->Rendezvous->findById( $rdv_id, null, null, -1 );
                    if( !empty( $rdv ) ) {
                        $entretien = Set::merge( $entretien, $rdv );
                        $entretien['Rendezvous']['referent_id'] = $entretien['Rendezvous']['structurereferente_id'].'_'.$entretien['Rendezvous']['referent_id'];
                    }
                    $this->data = $entretien;
                }
            }
            $this->Entretien->commit();

            $this->_setOptions();
            $this->set( 'personne_id', $personne_id );

            $this->render( $this->action, null, 'add_edit' );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function view( $id = null ){
            $entretien = $this->Entretien->findById( $id, null, null, -1 );
            $personne_id = Set::classicExtract( $entretien, 'Entretien.personne_id' );

            // Retour à l'entretien en cas de retour
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'entretiens', 'action' => 'index', $personne_id ) );
            }

            $this->_setOptions();
            $this->set( compact( 'entretien' ) );
            $this->set( 'personne_id', $personne_id );
        }

        /**
        *
        */

        public function delete( $id ) {
            $this->Default->delete( $id );
        }

    }
?>
