<?php
    class RendezvousController extends AppController
    {

        var $name = 'Rendezvous';
        var $uses = array( 'Rendezvous', 'Option', 'Personne', 'Structurereferente', 'Typerdv', 'Referent', 'Statutrdv', 'Permanence', 'Statutrdv' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform' );
        var $aucunDroit = array( 'ajaxreferent', 'ajaxreffonct', 'ajaxperm' );

		var $commeDroit = array(
			'view' => 'Rendezvous:index',
			'add' => 'Rendezvous:edit'
		);

        /** ********************************************************************
        *
        *** *******************************************************************/


        protected function _setOptions() {
            $this->set( 'struct', $this->Structurereferente->listOptions() );
            $this->set( 'permanences', $this->Permanence->listOptions() );
            $this->set( 'statutrdv', $this->Statutrdv->find( 'list' ) );

        }


        /** ********************************************************************
        *   Ajax pour les coordonnées du référent APRE
        *** *******************************************************************/

        function ajaxreffonct( $referent_id = null ) { // FIXME
            Configure::write( 'debug', 0 );

            if( !empty( $referent_id ) ) {
                $referent_id = suffix( $referent_id );
            }
            else {
                $referent_id = suffix( Set::extract( $this->data, 'Rendezvous.referent_id' ) );
            }

            $referent = array();
            if( !empty( $referent_id ) ) {
                $referent = $this->Referent->findbyId( $referent_id, null, null, -1 );
            }

            $this->set( 'referent', $referent );
            $this->render( 'ajaxreffonct', 'ajax' );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ){
            $nbrPersonnes = $this->Rendezvous->Personne->find(
                'count',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    ),
                    'recursive' => -1
                )
            );
            $this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );


            $belongsTo = array(
                'Personne' => $this->Rendezvous->belongsTo['Personne'],
                'Structurereferente' => $this->Rendezvous->belongsTo['Structurereferente'],
                'Referent' => $this->Rendezvous->belongsTo['Referent'],
                'Statutrdv' => $this->Rendezvous->belongsTo['Statutrdv'],
                'Permanence' => $this->Rendezvous->belongsTo['Permanence'],
            );
            $hasOne = array(
                'Typerdv' => $this->Rendezvous->hasOne['Typerdv'],
            );
            $this->Rendezvous->unbindModelAll();
            $this->Rendezvous->bindModel( array( 'belongsTo' => $belongsTo, 'hasOne' => $hasOne ) );
            $this->Rendezvous->forceVirtualFields = true;
            $rdvs = $this->Rendezvous->find(
                'all',
                array(
                    'fields' => array(
                        'Rendezvous.id',
                        'Rendezvous.personne_id',
                        'Personne.nom_complet',
                        'Structurereferente.lib_struc',
                        'Referent.nom_complet',
                        'Permanence.libpermanence',
                        'Typerdv.libelle',
                        'Statutrdv.libelle',
                        'Rendezvous.daterdv',
                        'Rendezvous.heurerdv',
                        'Rendezvous.objetrdv',
                        'Rendezvous.commentairerdv'
                    ),
                    'conditions' => array(
                        'Rendezvous.personne_id' => $personne_id
                    )
                )
            );
            $this->Rendezvous->forceVirtualFields = false;

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

            $referents = $this->Referent->listOptions();
            $this->set( 'referents', $referents );


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

            $struct_id = Set::classicExtract( $this->data, "{$this->modelClass}.structurereferente_id" );
            $this->set( 'struct_id', $struct_id );

            $referent_id = Set::classicExtract( $this->data, "{$this->modelClass}.referent_id" );
            $referent_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $referent_id );
            $this->set( 'referent_id', $referent_id );


            $permanence_id = Set::classicExtract( $this->data, "{$this->modelClass}.permanence_id" );
            $permanence_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $permanence_id );
            $this->set( 'permanence_id', $permanence_id );
//             $refrdv = $this->Rendezvous->Structurereferente->Referent->find( 'all', array( 'order' => 'Referent.nom ASC', 'recursive' => -1, 'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom', 'Referent.fonction' ) ) );
//             $ids = Set::extract( $refrdv, '/Referent/id' );
//             $values = Set::format( $refrdv, '{0} {1}', array( '{n}.Referent.nom', '{n}.Referent.prenom' ) );
//             $refrdv = array_combine( $ids, $values );
//             $this->set( 'referents', $refrdv );
//
//             $referent_id = Set::classicExtract( $this->data, 'Rendezvous.referent_id' );
//             if( !empty( $referent_id ) ) {
//                 $referent = $this->Rendezvous->Structurereferente->Referent->findbyId( Set::extract( $this->data, 'Rendezvous.referent_id' ), null, null, -1 );
//                 $this->set( 'ReferentFonction', $referent['Referent']['fonction'] );
//             }

            $this->_setOptions();
            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }

    }
?>
