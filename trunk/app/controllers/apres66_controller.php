<?php
    class Apres66Controller extends AppController
    {
        var $name = 'Apres66';
        var $uses = array( 'Apre66', 'Aideapre66', 'Pieceaide66', 'Typeaideapre66', 'Themeapre66', 'Option', 'Personne', 'Prestation', 'Typeaideapre66Pieceaide66', 'Adressefoyer', 'Fraisdeplacement66',  'Structurereferente', 'Referent' );
        var $helpers = array( 'Default', 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
        var $aucunDroit = array( 'ajaxstruct', 'ajaxref', 'ajaxtierspresta', 'ajaxtiersprestaformqualif', 'ajaxtiersprestaformpermfimo', 'ajaxtiersprestaactprof', 'ajaxtiersprestapermisb', 'ajaxpiece', 'notificationsop' );
        var $components = array( 'Default', 'Gedooo' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();

            $options = $this->{$this->modelClass}->allEnumLists();


            $this->set( 'typevoie', $this->Option->typevoie() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'sitfam', $this->Option->sitfam() );
            $this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'typeservice', $this->Serviceinstructeur->find( 'first' ) );

            $this->set( 'themes', $this->Themeapre66->find( 'list' ) );
            $this->set( 'nomsTypeaide', $this->Typeaideapre66->find( 'list' ) );

            $options = Set::merge( $options, $this->{$this->modelClass}->Aideapre66->allEnumLists() );
// debug($options);
            $this->set( 'options', $options );
            $pieceliste = $this->Pieceaide66->find(
                'list',
                array(
                    'fields' => array(
                        'Pieceaide66.id',
                        'Pieceaide66.name'
                    )
                )
            );
            $this->set( 'pieceliste', $pieceliste );

// debug($themes);


        }

        /** ********************************************************************
        *   Permet de regrouper l'ensemble des paramétrages pour l'APRE
        *** *******************************************************************/
        function indexparams(){

            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }

			$compteurs = array(
				'Pieceaide66' => ClassRegistry::init( 'Pieceaide66' )->find( 'count' ),
				'Themeapre66' => ClassRegistry::init( 'Themeapre66' )->find( 'count' )
			);
			$this->set( compact( 'compteurs' ) );

            $this->render( $this->action, null, '/apres/indexparams_'.Configure::read( 'nom_form_apre_cg' ) );
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

            $this->render( $this->action, null, '/apres/index66' );
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
        *   Ajax pour les coordonnées du référent APRE
        */

        function ajaxpiece( $typeaideapre66_id = null, $aideapre66_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
            if( !empty( $typeaideapre66_id ) ) {
                $typeaideapre66_id = suffix( $typeaideapre66_id );

            }
            else {
                $typeaideapre66_id = suffix( Set::extract( $this->data, 'Aideapre66.typeaideapre66_id' ) );
            }

            $pieces = $this->{$this->modelClass}->Aideapre66->Typeaideapre66->Pieceaide66->find(
                'list',
                array(
                    'fields' => array( 'Pieceaide66.id', 'Pieceaide66.name' ),
                    'joins' => array(
                        array(
                            'table'      => 'typesaidesapres66_piecesaides66',
                            'alias'      => 'Typeaideapre66Pieceaide66',
                            'type'       => 'INNER',
                            'foreignKey' => false,
                            'conditions' => array(
                                'Typeaideapre66Pieceaide66.pieceaide66_id = Pieceaide66.id',
                                'Typeaideapre66Pieceaide66.typeaideapre66_id' => $typeaideapre66_id,
                            )
                        )
                    ),
                    'order' => array( 'Pieceaide66.name' ),
                    'recursive' => -1
                )
            );

            // Cases déjà cochées
            $checked = array();
            $aideapre_id = Set::classicExtract( $this->params, 'named.aideapre_id' );
            if( !empty( $aideapre_id ) ) {
                $checked = $this->{$this->modelClass}->Aideapre66->Pieceaide66->find(
                    'all',
                    array(
                        'fields' => array( 'Pieceaide66.id' ),
                        'joins' => array(
                            array(
                                'table'      => 'aidesapres66_piecesaides66',
                                'alias'      => 'Aideapre66Pieceaide66',
                                'type'       => 'INNER',
                                'foreignKey' => false,
                                'conditions' => array(
                                    'Aideapre66Pieceaide66.pieceaide66_id = Pieceaide66.id',
                                    'Aideapre66Pieceaide66.aideapre66_id' => $aideapre_id
                                )
                            )
                        ),
                        'recursive' => -1
                    )
                );

                $this->data['Pieceaide66']['Pieceaide66'] = Set::extract( $checked, '/Pieceaide66/id' );
            }

            $typeaideapre = $this->{$this->modelClass}->Aideapre66->Typeaideapre66->findById( $typeaideapre66_id, null, null, -1 );

            $this->set( compact( 'pieces', 'typeaideapre' ) );
            $this->render( $this->action, 'ajax', '/apres/ajaxpiece' );
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

            $valueIsDecision = null;
            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $personne_id = $id;
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );
                $valueIsDecision = 'N';

                ///Création automatique du N° APRE de la forme : Année / Mois / N°
                $numapre = date('Ym').sprintf( "%010s",  $this->{$this->modelClass}->find( 'count' ) + 1 );
                $this->set( 'numapre', $numapre);


            }
            else if( $this->action == 'edit' ) {
                $apre_id = $id;
                $apre = $this->{$this->modelClass}->findById( $apre_id, null, null, 2 );
                $this->assert( !empty( $apre ), 'invalidParameter' );
                $valueIsDecision = Set::classicExtract( $this->data, "{$this->modelClass}.isdecision" );

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
            $this->set( 'valueIsDecision', $valueIsDecision );

            if( !$this->Jetons->check( $dossier_rsa_id ) ) {
                $this->{$this->modelClass}->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_rsa_id ), 'lockedDossier' );

            /**
            *   Liste des APREs de la personne pour l'affichage de l'historique
            *   lors de l'add/edit
            **/

			$conditionsListeApres = array( "{$this->modelClass}.personne_id" => $personne_id );
			if( $this->action == 'edit' ) {
				$conditionsListeApres["{$this->modelClass}.id <>"] = $apre_id;
			}

            $listApres = $this->{$this->modelClass}->find(
                'all',
                array(
                    'conditions' => $conditionsListeApres,
                    'recursive' => -1
                )
            );
            $this->set( compact( 'listApres' ) );
            if( !empty( $listApres ) ){
                $listesAidesSelonApre = $this->{$this->modelClass}->Aideapre66->find(
                    'all',
                    array(
                        'conditions' => array(
                            'Aideapre66.apre_id' => Set::extract( $listApres, "/{$this->modelClass}/id" )
                        ),
                        'recursive' => -1
                    )
                );
                $this->set( compact( 'listesAidesSelonApre' ) );
            }


            ///Récupération de la liste des structures référentes liés uniquement à l'APRE
            $structs = $this->Structurereferente->listeParType( array( 'apre' => true ) );
            $this->set( 'structs', $structs );
            ///Récupération de la liste des référents liés à l'APRE
            $referents = $this->Referent->listOptions();
            $this->set( 'referents', $referents );
            ///Récupération de la liste des référents liés à l'APRE
            $typesaides = $this->Typeaideapre66->listOptions();
            $this->set( 'typesaides', $typesaides );


            ///Personne liée au parcours
            $personne_referent = $this->Personne->PersonneReferent->find(
                'first',
                array(
                    'conditions' => array(
                        'PersonneReferent.personne_id' => $personne_id,
                        'PersonneReferent.dfdesignation IS NULL'
                    ),
                    'recursive' => -1
                )
            );


            ///On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
            $user = $this->User->findById( $this->Session->read( 'Auth.User.id' ), null, null, 0 );
            $user_id = Set::classicExtract( $user, 'User.id' );
            $personne = $this->{$this->modelClass}->Personne->detailsApre( $personne_id, $user_id );
            $this->set( 'personne', $personne );
// debug($personne);

            ///Nombre d'enfants par foyer
            $nbEnfants = $this->Foyer->nbEnfants( Set::classicExtract( $personne, 'Foyer.id' ) );
            $this->set( 'nbEnfants', $nbEnfants );


            if( !empty( $this->data ) ){

// debug($this->data);
                /**
                *   Pour le nombre de pièces afin de savoir si le dossier est complet ou non
                */
                $valide = true;
                $nbNormalPieces = array();

                $typeaideapre66_id = suffix( Set::classicExtract( $this->data, 'Aideapre66.typeaideapre66_id' ) );
                $typeaide = $this->{$this->modelClass}->Aideapre66->Typeaideapre66->findById( $typeaideapre66_id, null, null, 2 );

                $nbNormalPieces['Typeaideapre66'] = count( Set::extract( $typeaide, '/Pieceaide66/id' ) );

                $key = 'Pieceaide66';
                if( isset( $this->data['Aideapre66'] ) && isset( $this->data[$key] ) && isset( $this->data[$key][$key] ) ) {
                    $valide = ( count( $this->data[$key][$key] ) == $nbNormalPieces['Typeaideapre66'] ) && $valide;
                }
//     debug(count( $this->data[$key][$key] ));
//     debug($nbNormalPieces['Typeaideapre66']);
                $this->data['Apre66']['etatdossierapre'] = ( $valide ? 'COM' : 'INC' );


				// Tentative d'enregistrement de l'APRE complémentaire
				$this->{$this->modelClass}->create( $this->data );
				$this->{$this->modelClass}->set( 'statutapre', 'C' );
				$success = $this->{$this->modelClass}->save();

				// Tentative d'enregistrement de l'aide liée à l'APRE complémentaire
				$this->{$this->modelClass}->Aideapre66->create( $this->data );

                $Fraisdeplacement66 = Set::filter( $this->data['Fraisdeplacement66'] );
                if( !empty( $Fraisdeplacement66 ) ){
                    $this->{$this->modelClass}->Aideapre66->Fraisdeplacement66->create( $this->data );
                }

				if( $this->action == 'add' ) {
					$this->{$this->modelClass}->Aideapre66->set( 'apre_id', $this->{$this->modelClass}->getLastInsertID( ) );
				}
				$success = $this->{$this->modelClass}->Aideapre66->save() && $success;


                if( $this->action == 'add' ) {
                    if( !empty( $Fraisdeplacement66 ) ){
                        $this->{$this->modelClass}->Aideapre66->Fraisdeplacement66->set( 'aideapre66_id', $this->{$this->modelClass}->Aideapre66->getLastInsertID() );
                    }
                }
                if( !empty( $Fraisdeplacement66 ) ){
                    $success = $this->{$this->modelClass}->Aideapre66->Fraisdeplacement66->save() && $success;
                }


                // Tentative d'enregistrement des pièces liées à une APRE selon ne aide donnée
                if( !empty( $this->data['Pieceaide66'] ) ) {
                    $linkedData = array(
                        'Aideapre66' => array(
                            'id' => $this->{$this->modelClass}->Aideapre66->id
                        ),
                        'Pieceaide66' => $this->data['Pieceaide66']
                    );
                    $saved = $this->{$this->modelClass}->Aideapre66->save( $linkedData ) && $success;
                }
// debug($this->data);



				if( $success ) {
					$this->Jetons->release( $dossier_rsa_id );
					$this->{$this->modelClass}->commit(); // FIXME
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );

					$this->redirect( array(  'controller' => 'apres'.Configure::read( 'Apre.suffixe' ),'action' => 'index', $personne_id ) );
				}
				else {
					$this->{$this->modelClass}->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
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

					$typeaideapre66_id = Set::classicExtract( $this->data, 'Aideapre66.typeaideapre66_id' );
					$themeapre66_id = $this->{$this->modelClass}->Aideapre66->Typeaideapre66->field( 'themeapre66_id', array( 'id' => $typeaideapre66_id ) );

					$this->data = Set::insert( $this->data, 'Aideapre66.themeapre66_id', $themeapre66_id );
					$this->data = Set::insert( $this->data, 'Aideapre66.typeaideapre66_id', "{$themeapre66_id}_{$typeaideapre66_id}" );

                    ///FIXME: doit faire autrement
                    if( !empty( $this->data['Fraisdeplacement66'] ) ) {
                        $this->data['Fraisdeplacement66'] = $this->data['Aideapre66']['Fraisdeplacement66'];
                    }

                }
            }
//                 debug( $this->{$this->modelClass}->validationErrors );
            // Doit-on setter les valeurs par défault ?
            $dataStructurereferente_id = Set::classicExtract( $this->data, "{$this->modelClass}.structurereferente_id" );
            $dataReferent_id = Set::classicExtract( $this->data, "{$this->modelClass}.referent_id" );

            // Si le formulaire ne possède pas de valeur pour ces champs, on met celles par défaut
            if( empty( $dataStructurereferente_id ) && empty( $dataReferent_id ) ) {
                $structurereferente_id = $referent_id = null;


                $structPersRef = Set::classicExtract( $personne_referent, 'PersonneReferent.structurereferente_id' );
                // Valeur par défaut préférée: à partir de personnes_referents
                if( !empty( $personne_referent ) && array_key_exists( $structPersRef, $structs ) ){
                    $structurereferente_id = Set::classicExtract( $personne_referent, 'PersonneReferent.structurereferente_id' );
                    $referent_id = Set::classicExtract( $personne_referent, 'PersonneReferent.referent_id' );
                }

                if( !empty( $structurereferente_id ) ) {
                    $this->data = Set::insert( $this->data, "{$this->modelClass}.structurereferente_id", $structurereferente_id );
                }
                if( !empty( $structurereferente_id ) && !empty( $referent_id ) ) {
                    $this->data = Set::insert( $this->data, "{$this->modelClass}.referent_id", preg_replace( '/^_$/', '', "{$structurereferente_id}_{$referent_id}" ) );
                }
            }


            $struct_id = Set::classicExtract( $this->data, "{$this->modelClass}.structurereferente_id" );
            $this->set( 'struct_id', $struct_id );

            $referent_id = Set::classicExtract( $this->data, "{$this->modelClass}.referent_id" );
            $referent_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $referent_id );
            $this->set( 'referent_id', $referent_id );

            $this->{$this->modelClass}->commit();


            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, '/apres/add_edit_'.Configure::read( 'nom_form_apre_cg' ) );
//             $this->render( $this->action, null, '/apres/add_edit' );
        }


        /**
        *
        */

        function notificationsop( $id = null ) {
            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();

            $apre = $this->{$this->modelClass}->find(
                'first',
                array(
                    'conditions' => array(
                        "{$this->modelClass}.id" => $id
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
                        'Adressefoyer.foyer_id' => Set::classicExtract( $apre, 'Personne.foyer_id' ),
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $apre['Adresse'] = $adresse['Adresse'];

            $apre_id = Set::classicExtract( $apre, "{$this->modelClass}.id" );

            ///Traduction pour les données de la Personne/Contact/Partenaire/Référent
            $apre['Personne']['qual'] = Set::enum( Set::classicExtract( $apre, 'Personne.qual' ), $qual );
//             $apre['Personne']['dtnai'] = $this->Locale->date( 'Date::short', Set::classicExtract( $apre, 'Personne.dtnai' ) );
            $apre['Referent']['qual'] = Set::enum( Set::classicExtract( $apre, 'Referent.qual' ), $qual );
// debug($apre);
// die();
//             $this->Gedooo->generate( $apre, 'Apre/notificationop.odt' );
        }

    }
?>