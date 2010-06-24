<?php
    class RendezvousController extends AppController
    {

        var $name = 'Rendezvous';
        var $uses = array( 'Rendezvous', 'Option', 'Personne', 'Structurereferente', 'Typerdv', 'Referent', 'Statutrdv', 'Permanence', 'Statutrdv' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform' );
        var $aucunDroit = array( 'ajaxreferent', 'ajaxreffonct', 'ajaxperm' );

        /** ********************************************************************
        *
        *** *******************************************************************/


        protected function _setOptions() {
            $this->set( 'struct', $this->Structurereferente->listOptions() );
            $this->set( 'permanences', $this->Permanence->find( 'list' ) );
            $this->set( 'statutrdv', $this->Statutrdv->find( 'list' ) );
        }




        /*function beforeFilter() {
            parent::beforeFilter();
//             $this->Rendezvous->query( 'SELECT 1;' );

//             $this->set( 'struct', $this->Structurereferente->find( 'list', array( 'recursive' => 1 ) ) );
//             $this->set( 'struct', $this->Structurereferente->listOptions() );
//             $this->set( 'sr', $this->Structurereferente->find( 'list', array( 'recursive' => 1 ) ) );
//             $this->set( 'referent', $this->Referent->find( 'list', array( 'recursive' => 1 ) ) );
//             $this->set( 'permanences', $this->Permanence->find( 'list' ) );
//             $this->set( 'statutrdv', $this->Statutrdv->find( 'list' ) );
        }*/

        /** ********************************************************************
        * Ajax pour lien référent - structure référente
        *** *******************************************************************/

        function _selectReferents( $structurereferente_id ) {
            $refrdv = $this->Referent->find(
                'all',
                array(
                    'conditions' => array(
                        'Referent.structurereferente_id' => $structurereferente_id
                    ),
                    'recursive' => -1
                )
            );
            return $refrdv;

        }

        function ajaxreferent() { // FIXME
            Configure::write( 'debug', 0 );
            $refrdv = $this->_selectReferents( Set::classicExtract( $this->data, 'Rendezvous.structurereferente_id' ) );
            $options = array( '<option value=""></option>' );
            foreach( $refrdv as $referent ) {
                $options[] = '<option value="'.$referent['Referent']['id'].'">'.$referent['Referent']['nom'].' '.$referent['Referent']['prenom'].'</option>';
            } ///FIXME: à mettre dans la vue
            echo implode( '', $options );
            $this->render( null, 'ajax' );
        }


        /** ********************************************************************
        *   Ajax pour la fonction du référent
        *** *******************************************************************/

        function ajaxreffonct() { // FIXME
            Configure::write( 'debug', 0 );
            $referent = $this->Rendezvous->Structurereferente->Referent->findbyId( Set::extract( $this->data, 'Rendezvous.referent_id' ), null, null, -1 );
            echo $referent['Referent']['fonction'];
            $this->render( null, 'ajax' );
        }


        /** ********************************************************************
        *   Ajax pour la permanence liée à la structure référente
        *** *******************************************************************/
        function _selectPermanences( $structurereferente_id ) {
            $permanences = $this->Rendezvous->Structurereferente->Permanence->find(
                'all',
                array(
                    'conditions' => array(
                        'Permanence.structurereferente_id' => $structurereferente_id
                    ),
                    'recursive' => -1
                )
            );

            return $permanences;

        }

        function ajaxperm() { // FIXME
            Configure::write( 'debug', 0 );
            $permanences = $this->_selectPermanences( Set::classicExtract( $this->data, 'Rendezvous.structurereferente_id' ) );

            $options = array( '<option value=""></option>' );
            foreach( $permanences as $permanence ) {
                $options[] = '<option value="'.$permanence['Permanence']['id'].'">'.$permanence['Permanence']['libpermanence'].'</option>';
            }
            echo implode( '', $options );
            $this->render( null, 'ajax' );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ){

            $nbrPersonnes = $this->Rendezvous->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );

            $this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

            $rdvs = $this->Rendezvous->find( 'all', array( 'conditions' => array( 'Rendezvous.personne_id' => $personne_id ) ) );

            $this->_setOptions();
            $this->set( compact( 'rdvs' ) );
            $this->set( 'personne_id', $personne_id );
        }


        /** ********************************************************************
        *
        *** *******************************************************************/

        function view( $rendezvous_id = null ){
            $rendezvous = $this->Rendezvous->findById( $rendezvous_id );
            $this->assert( !empty( $rendezvous ), 'invalidParameter' );

            /*$typerdv = $this->Typerdv->find( 'list', array( 'fields' => array( 'id', 'libelle' ) ) );
            $this->set( 'typerdv', $typerdv );*/

            /*$referent = $this->Referent->find( 'list', array( 'fields' => array( 'id', 'nom' ) ) );
            $this->set( 'referent', $referent );*/

            /*$referentFonction = $this->Referent->find( 'list', array( 'fields' => array( 'id', 'fonction' ) ) );
            $this->set( 'referentFonction', $referentFonction );*/

            $this->set( 'rendezvous', $rendezvous );
            $this->set( 'personne_id', $rendezvous['Rendezvous']['personne_id'] );
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

            $typerdv = $this->Typerdv->find( 'list', array( 'fields' => array( 'id', 'libelle' ) ) );
            $this->set( 'typerdv', $typerdv );


            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $personne_id = $id;
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            }
            else if( $this->action == 'edit' ) {
                $rdv_id = $id;
                $rdv = $this->Rendezvous->findById( $rdv_id, null, null, -1 );
                $this->assert( !empty( $rdv ), 'invalidParameter' );

                $personne_id = $rdv['Rendezvous']['personne_id'];
                $dossier_rsa_id = $this->Rendezvous->dossierId( $rdv_id );
            }

            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index', $personne_id ) );
            }

            $this->Rendezvous->begin();

            $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_rsa_id ), 'invalidParameter' );

            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->Rendezvous->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );

            if( !empty( $this->data ) ){
                if( $this->Rendezvous->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    if( $this->Rendezvous->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Rendezvous->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'rendezvous','action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {
                    $this->data = $rdv;
                }
            }
            $this->Rendezvous->commit();

            ///Récupération des référents liés aux structures référentes
            $refrdv = $this->Rendezvous->Structurereferente->Referent->find( 'all', array( 'order' => 'Referent.nom ASC', 'recursive' => -1, 'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom', 'Referent.fonction' ) ) );
            $ids = Set::extract( $refrdv, '/Referent/id' );
            $values = Set::format( $refrdv, '{0} {1}', array( '{n}.Referent.nom', '{n}.Referent.prenom' ) );
            $refrdv = array_combine( $ids, $values );
            $this->set( 'referents', $refrdv );

            $referent_id = Set::classicExtract( $this->data, 'Rendezvous.referent_id' );
            if( !empty( $referent_id ) ) {
                $referent = $this->Rendezvous->Structurereferente->Referent->findbyId( Set::extract( $this->data, 'Rendezvous.referent_id' ), null, null, -1 );
                $this->set( 'ReferentFonction', $referent['Referent']['fonction'] );
            }

            $this->_setOptions();
            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }

    }
?>