<?php
    class Apres66Controller extends AppController
    {
        var $name = 'Apres66';
        var $uses = array( 'Apre66', 'Option', 'Personne', 'ApreComiteapre', 'Prestation'/*, 'Dsp'*/, 'Formpermfimo', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert', 'Contratinsertion', 'Relanceapre', 'Tiersprestataireapre', 'Structurereferente', 'Referent' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
        var $aucunDroit = array( 'ajaxstruct', 'ajaxref', 'ajaxtierspresta', 'ajaxtiersprestaformqualif', 'ajaxtiersprestaformpermfimo', 'ajaxtiersprestaactprof', 'ajaxtiersprestapermisb' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $options = $this->{$this->modelClass}->allEnumLists();
            $this->set( 'options', $options );
            $optionsacts = $this->Actprof->allEnumLists();
            $this->set( 'optionsacts', $optionsacts );
            $this->set( 'typevoie', $this->Option->typevoie() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'sitfam', $this->Option->sitfam() );
            $this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'typeservice', $this->Serviceinstructeur->find( 'first' ) );
// debug( $this->modelClass );
        }

        /** ********************************************************************
        *   Permet de regrouper l'ensemble des paramétrages pour l'APRE
        *** *******************************************************************/
        function indexparams(){

            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }

            $this->render( $this->action, null, 'indexparams_'.Configure::read( 'nom_form_apre_cg' ) );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ) {
            $personne = $this->{$this->modelClass}->Personne->findById( $personne_id, null, null, -1 );
            $this->assert( !empty( $personne ), 'invalidParameter' );
            $this->set( 'personne', $personne );

            $apres = $this->{$this->modelClass}->find( 'all', array( 'conditions' => array( "{$this->modelClass}.personne_id" => $personne_id ) ) );
            $this->set( 'apres', $apres );

            $referents = $this->Referent->find( 'list' );
            $this->set( 'referents', $referents );

            $this->set( 'personne_id', $personne_id );


            /// La personne a-t'elle bénéficié d'aides trop importantes ?
            $alerteMontantAides = false;
            $montantMaxComplementaires = Configure::read( 'Apre.montantMaxComplementaires' );
            $periodeMontantMaxComplementaires = Configure::read( 'Apre.periodeMontantMaxComplementaires' );

            $this->{$this->modelClass}->unbindModel(
                array(
                    'belongsTo' => array_keys( $this->{$this->modelClass}->belongsTo ),
                    'hasMany' => array_keys( $this->{$this->modelClass}->hasMany ),
                    'hasAndBelongsToMany' => array( 'Pieceapre' ),
                )
            );
            $apres = $this->{$this->modelClass}->find(
                'all',
                array(
                    'conditions' => array(
                        "{$this->modelClass}.personne_id" => $personne_id,
                        "{$this->modelClass}.statutapre" => 'C',
                        "{$this->modelClass}.datedemandeapre >=" => date( 'Y-m-d', strtotime( '-'.Configure::read( "{$this->modelClass}.periodeMontantMaxComplementaires" ).' months' ) )
                    )
                )
            );
            /// FIXME: {$this->modelClass} partout

            $montantComplementaires = 0;
            if( $montantComplementaires > Configure::read( "{$this->modelClass}.montantMaxComplementaires" ) ) {
                $alerteMontantAides = true;
            }
            $this->set( 'alerteMontantAides', $alerteMontantAides );

            $this->render( $this->action, null, '/apres/index' );
        }

        /** ********************************************************************
        *   Ajax pour les coordonnées de la structure référente liée
        *** *******************************************************************/

        function ajaxstruct( $structurereferente_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
            $dataStructurereferente_id = Set::extract( $this->data, "{$this->modelClass}.structurereferente_id" );
            $structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );

            $struct = $this->{$this->modelClass}->Structurereferente->findbyId( $structurereferente_id, null, null, -1 );
            $this->set( 'struct', $struct );
            $this->render( $this->action, 'ajax', '/apres/ajaxstruct' );
        }


        /**
        *   Ajax pour les coordonnées du référent APRE
        */

        function ajaxref( $referent_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
            if( !empty( $referent_id ) ) {
                $referent_id = suffix( $referent_id );

            }
            else {
                $referent_id = suffix( Set::extract( $this->data, "{$this->modelClass}.referent_id" ) );
            }

            $referent = $this->{$this->modelClass}->Referent->findbyId( $referent_id, null, null, -1 );
            $this->set( 'referent', $referent );
            $this->render( $this->action, 'ajax', '/apres/ajaxref' );
        }

        /**
        * Visualisation de l'APRE
        */

        function view( $apre_id = null ){
            $apre = $this->{$this->modelClass}->findById( $apre_id );
            $this->assert( !empty( $apre ), 'invalidParameter' );

            $referents = $this->Referent->find( 'list' );
            $this->set( 'referents', $referents );

            $this->set( 'apre', $apre );
            $this->set( 'personne_id', $apre['Apre']['personne_id'] );
            $this->render( $this->action, null, '/apres/view' );
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


            $this->{$this->modelClass}->begin();

            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $personne_id = $id;
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );

                ///Création automatique du N° APRE de la forme : Année / Mois / N°
                $numapre = date('Ym').sprintf( "%010s",  $this->{$this->modelClass}->find( 'count' ) + 1 );
                $this->set( 'numapre', $numapre);

            }
            else if( $this->action == 'edit' ) {
                $apre_id = $id;
                $apre = $this->{$this->modelClass}->findById( $apre_id, null, null, 2 );
                $this->assert( !empty( $apre ), 'invalidParameter' );

                $personne_id = $apre[$this->modelClass]['personne_id'];
                $dossier_rsa_id = $this->{$this->modelClass}->dossierId( $apre_id );

                $this->set( 'numapre', Set::extract( $apre, "{$this->modelClass}.numeroapre" ) );
            }

            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index', $personne_id ) );
            }
            $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_rsa_id ), 'invalidParameter' );
            $this->set( 'dossier_id', $dossier_rsa_id );

            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->{$this->modelClass}->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );


            $personne = $this->{$this->modelClass}->Personne->detailsApre( $personne_id );
            $this->set( 'personne', $personne );

            ///Nombre d'enfants par foyer
            $nbEnfants = $this->Foyer->nbEnfants( Set::classicExtract( $personne, 'Foyer.id' ) );
            $this->set( 'nbEnfants', $nbEnfants );

            ///Récupération de la liste des structures référentes liés uniquement à l'APRE
            $structs = $this->Structurereferente->listeParType( array( 'apre' => true ) );
            $this->set( 'structs', $structs );
            ///Récupération de la liste des référents liés à l'APRE
            $referents = $this->Referent->listOptions();
            $this->set( 'referents', $referents );


            if( !empty( $this->data ) ){

                ///Mise en place lors de la sauvegarde du statut de l'APRE à Complémentaire
                $this->data[$this->modelClass]['statutapre'] = 'C';

                if( $this->{$this->modelClass}->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    $saved = $this->{$this->modelClass}->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );
                    if( $saved ) {
//                         $this->{$this->modelClass}->supprimeFormationsObsoletes( $this->data );
                        $this->Jetons->release( $dossier_rsa_id );
                        $this->{$this->modelClass}->commit(); // FIXME
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
//                         debug( $this->data );
                        $this->redirect( array(  'controller' => 'apres'.Configure::read( 'Apre.suffixe' ),'action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->{$this->modelClass}->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {

                    /// FIXME
                    $this->data = $apre;
                    $this->data = Set::insert(
                        $this->data, "{$this->modelClass}.referent_id",
                        Set::extract( $this->data, "{$this->modelClass}.structurereferente_id" ).'_'.Set::extract( $this->data, "{$this->modelClass}.referent_id" )
                    );
                }
            }
            $this->{$this->modelClass}->commit();


            $this->set( 'personne_id', $personne_id );
//             $this->render( $this->action, null, '/apres/add_edit_'.Configure::read( 'nom_form_apre_cg' ) );
            $this->render( $this->action, null, '/apres/add_edit' );
        }

    }
?>