<?php
    class PersonnesReferentsController extends AppController
    {

        var $name = 'PersonnesReferents';
        var $uses = array( 'PersonneReferent', 'Option', 'Personne', 'Orientstruct', 'Structurereferente', 'Typerdv', 'Statutrdv', 'Referent' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform' );
        var $aucunDroit = array( 'ajaxreferent', 'ajaxreffonct', 'ajaxperm' );
        
		var $commeDroit = array(
			'add' => 'PersonnesReferents:edit'
		);

        /** ********************************************************************
        *
        *** *******************************************************************/

        protected function _setOptions() {
            $this->set( 'struct', $this->Structurereferente->listOptions() );
        }
/*
        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'struct', $this->Structurereferente->find( 'list', array( 'recursive' => 1 ) ) );
        }*/


        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ){

            $nbrPersonnes = $this->PersonneReferent->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );
            $this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );


            $personnes_referents = $this->PersonneReferent->find( 'all', array( 'conditions' => array( 'PersonneReferent.personne_id' => $personne_id ) ) );
            $this->set( 'personnes_referents', $personnes_referents );

//             debug($personnes_referents);
            foreach( $personnes_referents as $index => $date ) {
                $pers = $this->PersonneReferent->find( 'first', array( 'conditions' => array( 'PersonneReferent.personne_id' => $personne_id ), 'order' => 'PersonneReferent.dfdesignation DESC' ) );
                $pers['PersonneReferent']['dernier'] = $pers['PersonneReferent'];

                $dfdesignation = Set::extract( $pers, '/PersonneReferent/dernier/dfdesignation' );
                $this->set( 'pers', $pers );
                $this->set( 'dfdesignation', $dfdesignation );
//     debug($pers);
            }

            $this->_setOptions();
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

            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $personne_id = $id;
                $dossier_id = $this->Personne->dossierId( $personne_id );
            }
            else if( $this->action == 'edit' ) {
                $personne_referent_id = $id;
                $personne_referent = $this->PersonneReferent->findById( $personne_referent_id, null, null, -1 );
                $this->assert( !empty( $personne_referent ), 'invalidParameter' );

                $referent = $this->Referent->findById( $personne_referent['PersonneReferent']['referent_id'], null, null, -1 );
                $this->assert( !empty( $referent ), 'invalidParameter' );
                $this->set( 'referent', $referent );

// debug($referent);
                $personne_id = $personne_referent['PersonneReferent']['personne_id'];
                $dossier_id = $this->PersonneReferent->dossierId( $personne_referent_id );
            }

            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index', $personne_id ) );
            }

            $this->PersonneReferent->begin();

            $dossier_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->set( 'referents', $this->Referent->listOptions() );


            $orientstruct = $this->Orientstruct->findByPersonneId( $personne_id, null, null, -1 );
            $this->set( 'orientstruct', $orientstruct);

            if( !empty( $orientstruct ) ) {
                $sr = Set::classicExtract( $orientstruct, 'Orientstruct.structurereferente_id' );
                $this->set( 'sr', $sr );
            }



            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->PersonneReferent->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            if( !empty( $this->data ) ){

                if( $this->PersonneReferent->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->PersonneReferent->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
// debug( $this->data );
                        $this->Jetons->release( $dossier_id );
                        $this->PersonneReferent->commit(); /// FIXE
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'personnes_referents','action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {

                    $this->data = $personne_referent;
//     debug($this->data);
                }
            }
            $this->PersonneReferent->commit();
            $this->_setOptions();
            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }

    }
?>
