<?php
    class ActionscandidatsPersonnesController extends AppController
    {

        var $name = 'ActionscandidatsPersonnes';
        var $uses = array( 'ActioncandidatPersonne', 'Option', 'Personne', 'Actioncandidat', 'Partenaire', 'Typerdv', 'PersonneReferent', 'Referent', 'Rendezvous', 'ActioncandidatPartenaire', 'Contactpartenaire', 'Adressefoyer', 'Natmob', 'Dsp' );
        var $helpers = array( 'Default', 'Locale', 'Csv', 'Ajax', 'Xform' );
        var $aucunDroit = array( 'ajaxpart', 'ajaxstruct', 'ajaxreferent', 'ajaxreffonct' );
        var $components = array( 'Default', 'Gedooo' );


        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            $return = parent::beforeFilter();

            $options = array();
            foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
                $options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
            }

            $options = Set::insert( $options, 'Adresse.typevoie', $this->Option->typevoie() );
            $options = Set::insert( $options, 'Personne.qual', $this->Option->qual() );
            $options = Set::insert( $options, 'Contratinsertion.decision_ci', $this->Option->decision_ci() );
            $options = Set::insert( $options, 'Dsp', $this->Dsp->allEnumLists() );

            foreach( array( 'Actioncandidat', /*'Personne', */'Referent'/*, 'Partenaire'*/ ) as $linkedModel ) {
                $field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
                $options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list', array( 'recursive' => -1 ) ) );

            }
            App::import( 'Helper', 'Locale' );
            $this->Locale = new LocaleHelper();

            $options = Set::insert( $options, 'ActioncandidatPersonne.naturemobile', $this->Natmob->find( 'list' ) );


            $this->set( 'typevoie', $this->Option->typevoie() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
            $this->set( 'sitfam', $this->Option->sitfam() );
            $this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'typeservice', $this->Serviceinstructeur->find( 'first' ) );
            $this->set( compact( 'options', 'typevoie' ) );


            ///Données nécessaire spour la création du RDV si celui-ci existe
//             $this->set( 'struct', $this->Structurereferente->listOptions() );
//             $this->set( 'sr', $this->Structurereferente->find( 'list', array( 'recursive' => 1 ) ) );
//             $this->set( 'referents', $this->Referent->find( 'list', array( 'recursive' => 1 ) ) );



            return $return;
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function indexparams(){
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }
        }

        /**
        *   Ajout à la suite de l'utilisation des nouveaux helpers
        *   - default.php
        *   - theme.php
        */

        public function index( $personne_id ) {
            // Préparation du menu du dossier
            $dossierId = $this->ActioncandidatPersonne->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossierId ), 'invalidParameter' );
            $this->set( compact( 'dossierId' ) );

            $queryData = array(
                'ActioncandidatPersonne' => array(
                    'conditions' => array(
                        'ActioncandidatPersonne.personne_id' => $personne_id
                    )
                )
            );
            $this->paginate = array(
                $this->modelClass => array(
                    'limit' => 5,
                    'recursive' => 2
                )
            );

            $this->{$this->modelClass}->Personne->unbindModelAll( false );
            $this->{$this->modelClass}->Referent->unbindModelAll( false );
            $this->paginate = Set::merge( $this->paginate, $queryData );
            $items = $this->paginate( $this->modelClass );

            $varname = Inflector::tableize( $this->name );
            $this->set( $varname, $items );

        }


        /** ********************************************************************
        *   Ajax pour les partenaires fournissant les actions
        *** *******************************************************************/

        function ajaxpart( $actioncandidat_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
// debug($actioncandidat_id);
            $dataActioncandidat_id = Set::extract( $this->data, 'ActioncandidatPersonne.actioncandidat_id' );
            $actioncandidat_id = ( empty( $actioncandidat_id ) && !empty( $dataActioncandidat_id ) ? $dataActioncandidat_id : $actioncandidat_id );

            $part = $this->ActioncandidatPartenaire->findbyActioncandidatId( $actioncandidat_id, null, null, -1 );

            $parts = $this->Partenaire->find( 'list', array(  'recursive' => -1 ) );
            $this->set( compact( 'part', 'parts' ) );


            $contact = $this->Contactpartenaire->findByPartenaireId( Set::classicExtract( $part, 'ActioncandidatPartenaire.partenaire_id', null, null, -1 ) );
            $this->set( compact( 'contact' ) );

            $this->render( 'ajaxpart', 'ajax' );
        }


        /** ********************************************************************
        *   Ajax pour les partenaires fournissant les actions
        *** *******************************************************************/

        function ajaxstruct( $referent_id = null ) { // FIXME
            Configure::write( 'debug', 0 );

// debug($referent_id);
            $this->set( 'typevoie', $this->Option->typevoie() );

            $dataReferent_id = Set::extract( $this->data, 'ActioncandidatPersonne.referent_id' );
            $referent_id = ( empty( $referent_id ) && !empty( $dataReferent_id ) ? $dataReferent_id : $referent_id );

            if( !empty( $referent_id ) ) {
                $referent = $this->Referent->findbyId( $referent_id, null, null, -1 );

                $structs = $this->Structurereferente->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Structurereferente.id' => Set::classicExtract( $referent, 'Referent.structurereferente_id' )
                        ),
                        'recursive' => -1
                    )
                );
                $this->set( compact( 'referent', 'structs' ) );
            }

            $this->render( 'ajaxstruct', 'ajax' );
        }


        /** ********************************************************************
        *   Ajax pour les partenaires fournissant les actions
        *** *******************************************************************/

        function ajaxreffonct( $referent_id = null ) { // FIXME
            Configure::write( 'debug', 0 );

// debug($referent_id);
            $this->set( 'typevoie', $this->Option->typevoie() );

            $dataReferent_id = Set::extract( $this->data, 'Rendezvous.referent_id' );
            $referent_id = ( empty( $referent_id ) && !empty( $dataReferent_id ) ? $dataReferent_id : $referent_id );

            if( !empty( $referent_id ) ) {
                $referent = $this->Referent->findbyId( $referent_id, null, null, -1 );

                $structs = $this->Structurereferente->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Structurereferente.id' => Set::classicExtract( $referent, 'Referent.structurereferente_id' )
                        ),
                        'recursive' => -1
                    )
                );
                $this->set( compact( 'referent', 'structs' ) );
            }

            $this->render( 'ajaxreffonct', 'ajax' );
        }



        /**
        *
        */

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /**
        *
        */

        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }


        /**
        *
        */

        function _add_edit( $id = null ) {
            $this->assert( valid_int( $id ), 'invalidParameter' );


            // Retour à l'index en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                if( $this->action == 'edit' ) {
                    $id = $this->ActioncandidatPersonne->field( 'personne_id', array( 'id' => $id ) );
                }
                $this->redirect( array( 'action' => 'index', $id ) );
            }

            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $personne_id = $id;
                // Préparation du menu du dossier
                $dossierId = $this->ActioncandidatPersonne->Personne->dossierId( $personne_id );
                $this->assert( !empty( $dossierId ), 'invalidParameter' );
                $this->set( compact( 'dossierId', 'personne_id' ) );

                ///Afin d'obtenir les données de la personne nécessaires
                $personne= $this->Personne->detailsCi( $personne_id );
                $this->set( compact( 'personne' ) );
                unset( $personne['Apre'] );

                ///Pour récupérer le référent lié à la personne s'il existe déjà
                $personne_referent = $this->PersonneReferent->find( 'first', array( 'conditions' => array( 'PersonneReferent.personne_id' => $personne_id ) ) );

                $referentId = null;
                if( !empty( $personne_referent ) ){
                    $referentId = Set::classicExtract( $personne_referent, 'PersonneReferent.referent_id' );
                    $referents = $this->Referent->findById( $referentId, null, null, -1 );
                    $this->set( compact( 'referents' ) );
                }
                $this->set( compact( 'referentId' ) );

                ///Données propre au partenaire
                $part = $this->Actioncandidat->Partenaire->find( 'list' );
                $this->set( compact( 'part' ) );
    //             debug($part);
            }
            else if( $this->action == 'edit' ) {
                $actioncandidat_personne_id = $id;
                $actioncandidat_personne = $this->ActioncandidatPersonne->findById( $actioncandidat_personne_id, null, null, -1 );
                $this->assert( !empty( $actioncandidat_personne ), 'invalidParameter' );

                $personne_id = Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.personne_id' );
                $personne = $this->Personne->findById( $personne_id, null, null, -1 );
                $personne_referent = $this->PersonneReferent->find( 'first', array( 'conditions' => array( 'PersonneReferent.personne_id' => $personne_id ) ) );

                $referentId = null;
                if( !empty( $personne_referent ) ){
                    $referentId = Set::classicExtract( $personne_referent, 'PersonneReferent.referent_id' );
                    $referents = $this->Referent->findById( $referentId, null, null, -1 );
                    $this->set( compact( 'referents' ) );
                }
                $this->set( compact( 'referentId', 'personne' ) );


                $dossierId = $this->ActioncandidatPersonne->Personne->dossierId( $personne_id );
                $this->assert( !empty( $dossierId ), 'invalidParameter' );
                $this->set( compact( 'dossierId', 'personne_id' ) );
            }

            ///Données récupérées propre à la personne
            $personne = $this->{$this->modelClass}->Personne->detailsCi( $personne_id );
            ///Données Contrat engagement
            $contrat = $this->{$this->modelClass}->Personne->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.personne_id' => $personne_id
                    ),
                    'recursive' => -1,
                    'order' => 'Contratinsertion.date_saisi_ci DESC'
                )
            );
            if( !empty( $contrat ) ) {
                $personne = Set::merge( $personne, $contrat );
            }
            $this->set( 'personne', $personne );

            ///Nombre d'enfants par foyer
            $nbEnfants = $this->Foyer->nbEnfants( Set::classicExtract( $personne, 'Foyer.id' ) );
            $this->set( 'nbEnfants', $nbEnfants );



            ///Récupération de la liste des structures référentes liés uniquement à l'APRE
            $structs = $this->Structurereferente->listOptions( );
            $this->set( 'structs', $structs );
//             debug($structs);
            ///Récupération de la liste des référents liés à l'APRE
            $referents = $this->Referent->listOptions();
            $this->set( 'referents', $referents );


            ///Données Dsp
            $dsp = $this->Dsp->findByPersonneId( $personne_id, null, null, -1  );
            $this->set( compact( 'dsp' ) );

            $this->ActioncandidatPersonne->begin();

            if( !empty( $this->data ) ){

                ///Récupération des Dsps et sauvegarde
                $this->ActioncandidatPersonne->Personne->Dsp->saveAll( $this->data, array( 'validate' => 'only' ) );
                ///Récupération des RDVs et sauvegarde
                $this->ActioncandidatPersonne->Personne->Rendezvous->saveAll( $this->data, array( 'validate' => 'only' ) );

                if( $this->ActioncandidatPersonne->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {

                    ///Récupération des Dsps et sauvegarde
                    $this->ActioncandidatPersonne->Personne->Dsp->create();
                    $this->ActioncandidatPersonne->Personne->Dsp->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );

                    ///Récupération des RDVs et sauvegarde
                    $this->ActioncandidatPersonne->Personne->Rendezvous->create();
                    $this->ActioncandidatPersonne->Personne->Rendezvous->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );


                    if( $this->ActioncandidatPersonne->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                        $this->Jetons->release( $dossierId );
                        $this->ActioncandidatPersonne->commit(); /// FIXME
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'actionscandidats_personnes','action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                        $this->ActioncandidatPersonne->rollback();
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {
                    $this->data = $actioncandidat_personne;



                /// Récupération des données socio pro (notamment Niveau etude) lié au contrat
                    $this->ActioncandidatPersonne->Personne->Dsp->unbindModelAll();
                    $dsp = $this->ActioncandidatPersonne->Personne->Dsp->findByPersonneId( $personne_id, null, null, 1 );
                    if( empty( $dsp ) ) {
                        $dsp = array( 'Dsp' => array( 'personne_id' => $personne_id ) );
                        $this->ActioncandidatPersonne->Personne->Dsp->set( $dsp );
                        if( $this->ActioncandidatPersonne->Personne->Dsp->save( $dsp ) ) {
                            $dsp = $this->ActioncandidatPersonne->Personne->Dsp->findByPersonneId( $personne_id, null, null, 1 );
                        }
                        else {
                            $this->cakeError( 'error500' );
                        }
                        $this->assert( !empty( $dsp ), 'error500' );
                    }
                    $this->data['Dsp'] = array( 'id' => $dsp['Dsp']['id'], 'personne_id' => $dsp['Dsp']['personne_id'] );
                    $this->data['Dsp']['nivetu'] = ( ( isset( $dsp['Dsp']['nivetu'] ) ) ? $dsp['Dsp']['nivetu'] : null );
                ///Fin des Dsps

                /// Récupération des données socio pro (notamment Niveau etude) lié à la fiche
                    $this->ActioncandidatPersonne->Personne->Rendezvous->unbindModelAll();
                    $rdv = $this->ActioncandidatPersonne->Personne->Rendezvous->findByPersonneId( $personne_id, null, null, 2 );
                    if( empty( $rdv ) ) {
                        $rdv = array( 'Rendezvous' => array( 'personne_id' => $personne_id ) );
                        $this->ActioncandidatPersonne->Personne->Rendezvous->set( $rdv );
                        if( $this->ActioncandidatPersonne->Personne->Rendezvous->save( $rdv ) ) {
                            $dsp = $this->ActioncandidatPersonne->Personne->Rendezvous->findByPersonneId( $personne_id, null, null, 2 );
                        }
                        else {
                            $this->cakeError( 'error500' );
                        }
                        $this->assert( !empty( $rdv ), 'error500' );
                    }
                    $this->data['Rendezvous'] = array(
                        'id' => $rdv['Rendezvous']['id'],
                        'personne_id' => $rdv['Rendezvous']['personne_id'],
                        'referent_id' => $rdv['Rendezvous']['structurereferente_id'].'_'.$rdv['Rendezvous']['referent_id'],
                        'structurereferente_id' => $rdv['Rendezvous']['structurereferente_id'],
                        'daterdv' => $rdv['Rendezvous']['daterdv'],
                        'heurerdv' => $rdv['Rendezvous']['heurerdv'],
                    );
                ///Fin des RDVs
// debug($this->data);

                }
            }
            $this->ActioncandidatPersonne->commit();

            $this->render( $this->action, null, 'add_edit_'.Configure::read( 'ActioncandidatPersonne.suffixe' ) );
        }

        /**
        *
        */

        function gedooo( $id = null ) {
            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();
            $mobilites = $this->Natmob->find( 'list' );

            $actioncandidat_personne = $this->ActioncandidatPersonne->find(
                'first',
                array(
                    'conditions' => array(
                        'ActioncandidatPersonne.id' => $id
                    ),
                    'recursive' => 0
                )
            );

            $this->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );

            $adresse = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => Set::classicExtract( $actioncandidat_personne, 'Personne.foyer_id' ),
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $actioncandidat_personne['Adresse'] = $adresse['Adresse'];

            $actioncandidat_id = Set::classicExtract( $actioncandidat_personne, 'Actioncandidat.id' );
            $actioncandidatpartenaire = $this->ActioncandidatPartenaire->findByActioncandidatId( $actioncandidat_id, null, null, -1 );
            $partenaire_id = Set::classicExtract( $actioncandidatpartenaire, 'ActioncandidatPartenaire.partenaire_id' );
            $partenaire = $this->Partenaire->findById( $partenaire_id, null, null, -1 );

            $actioncandidat_personne = Set::merge( $actioncandidat_personne, $partenaire );

            $contactpartenaire = $this->Contactpartenaire->findByPartenaireId( $partenaire_id, null, null, -1 );
            $actioncandidat_personne = Set::merge( $actioncandidat_personne, $contactpartenaire );

            ///Traduction pour les données de la Personne/Contact/Partenaire/Référent
            $actioncandidat_personne['Personne']['qual'] = Set::enum( Set::classicExtract( $actioncandidat_personne, 'Personne.qual' ), $qual );
            $actioncandidat_personne['Personne']['dtnai'] = $this->Locale->date( 'Date::short', Set::classicExtract( $actioncandidat_personne, 'Personne.dtnai' ) );
            $actioncandidat_personne['Referent']['qual'] = Set::enum( Set::classicExtract( $actioncandidat_personne, 'Referent.qual' ), $qual );
            $actioncandidat_personne['Contactpartenaire']['qual'] = Set::enum( Set::classicExtract( $actioncandidat_personne, 'Contactpartenaire.qual' ), $qual );
            $actioncandidat_personne['Partenaire']['typevoie'] = Set::enum( Set::classicExtract( $actioncandidat_personne, 'Partenaire.typevoie' ), $typevoie );
            $actioncandidat_personne['ActioncandidatPersonne']['naturemobile'] = Set::enum( Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.naturemobile' ), $mobilites );

// debug($partenaire);
// debug($actioncandidat_personne);
// die();
            $this->Gedooo->generate( $actioncandidat_personne, 'Candidature/fichecandidature.odt' );
        }

    }
?>