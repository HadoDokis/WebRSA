<?php
    class ApresController extends AppController
    {

        var $name = 'Apres';
        var $uses = array( 'Apre', 'Option', 'Personne', 'ApreComiteapre', 'Prestation'/*, 'Dsp'*/, 'Formpermfimo', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert', 'Contratinsertion', 'Relanceapre', 'Tiersprestataireapre', 'Structurereferente', 'Referent' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
        var $aucunDroit = array( 'ajaxstruct', 'ajaxref', 'ajaxtierspresta', 'ajaxtiersprestaformqualif', 'ajaxtiersprestaformpermfimo', 'ajaxtiersprestaactprof', 'ajaxtiersprestapermisb' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $options = $this->Apre->allEnumLists();
            $this->set( 'options', $options );
            $optionsacts = $this->Actprof->allEnumLists();
            $this->set( 'optionsacts', $optionsacts );
//             $optionsdsps = $this->Dsp->allEnumLists();
//             $this->set( 'optionsdsps', $optionsdsps );
            $optionslogts = $this->Amenaglogt->allEnumLists();
            $this->set( 'optionslogts', $optionslogts );
            $optionscrea = $this->Acccreaentr->allEnumLists();
            $this->set( 'optionscrea', $optionscrea );
            $this->set( 'typevoie', $this->Option->typevoie() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
            $this->set( 'sitfam', $this->Option->sitfam() );
            $this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'typeservice', $this->Serviceinstructeur->find( 'first' ) );


            $optionsaprecomite = $this->ApreComiteapre->allEnumLists();
            $this->set( 'optionsaprecomite', $optionsaprecomite );

            /// Pièces liées à l'APRE
            $piecesapre = $this->Apre->Pieceapre->find( 'list' );
            $this->set( 'piecesapre', $piecesapre );

            ///Tiers prestataire présent dans la table paramétrage
            $tiersPrestataire = $this->Tiersprestataireapre->find( 'list' );
            $this->set( 'tiersPrestataire', $tiersPrestataire );
        }

        /** ********************************************************************
        *   Permet de regrouper l'ensemble des paramétrages pour l'APRE
        *** *******************************************************************/
        function indexparams(){

            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ) {
			$personne = $this->Apre->Personne->findById( $personne_id, null, null, -1 );
            $this->assert( !empty( $personne ), 'invalidParameter' );
			$this->set( 'personne', $personne );

            $apres = $this->Apre->find( 'all', array( 'conditions' => array( 'Apre.personne_id' => $personne_id ) ) );
            $this->set( 'apres', $apres );

            $referents = $this->Referent->find( 'list' );
            $this->set( 'referents', $referents );

            $this->set( 'personne_id', $personne_id );


            ///Afin de connaître s'il y avait une APRE forfaitaire pour pouvoir créer une APRE complémentaire

            $apre_forfait = $this->Apre->find(
                'count',
                array(
                    'conditions' => array(
                        'Apre.personne_id' => $personne_id,
                        'Apre.statutapre' => 'F'
                    )
                )
            );
            $this->set( 'apre_forfait', $apre_forfait );


            if( !empty( $apres ) ) {
                $relancesapres = $this->Relanceapre->find(
                    'all',
                    array(
                        'conditions' => array(
                            'Relanceapre.apre_id' => Set::extract( $apres, '/Apre/id' ),
                            'Apre.statutapre = \'C\''
                        ),
                        'recursive' => 0
                    )
                );
//                 debug($relancesapres);
            }
            else {
                $relancesapres = array();
            }
            $this->set( 'relancesapres', $relancesapres );

			/// La personne a-t'elle bénéficié d'aides trop importantes ?
			$alerteMontantAides = false;
			$montantMaxComplementaires = Configure::read( 'Apre.montantMaxComplementaires' );
			$periodeMontantMaxComplementaires = Configure::read( 'Apre.periodeMontantMaxComplementaires' );

			$this->Apre->unbindModel(
				array(
					'belongsTo' => array_keys( $this->Apre->belongsTo ),
					'hasMany' => array_keys( $this->Apre->hasMany ),
					'hasAndBelongsToMany' => array( 'Pieceapre' ),
				)
			);
			$apres = $this->Apre->find(
				'all',
				array(
					'conditions' => array(
						'Apre.personne_id' => $personne_id,
						'Apre.statutapre' => 'C',
						'Apre.datedemandeapre >=' => date( 'Y-m-d', strtotime( '-'.Configure::read( 'Apre.periodeMontantMaxComplementaires' ).' months' ) )
					)
				)
			);

			$montantComplementaires = 0;
			foreach( $apres as $apre ) {
				$decisions = Set::extract( $apre, '/Comiteapre/ApreComiteapre' );
				if( !empty( $decisions ) ) {
					foreach( $decisions as $decision ) {
						if( $decision['ApreComiteapre']['decisioncomite'] == 'ACC' ) {
							$montantComplementaires += $decision['ApreComiteapre']['montantattribue'];
						}
					}
				}
				else {
					foreach( $this->Apre->aidesApre as $aide ) {
						$montantaide = Set::classicExtract( $apre, "{$aide}.montantaide" );
						if( !empty( $montantaide ) ) {
							$montantComplementaires += $montantaide;
						}
					}
				}
			}

			if( $montantComplementaires > Configure::read( 'Apre.montantMaxComplementaires' ) ) {
				$alerteMontantAides = true;
			}
            $this->set( 'alerteMontantAides', $alerteMontantAides );
        }

        /** ********************************************************************
        *   Ajax pour les coordonnées de la structure référente liée
        *** *******************************************************************/

        function ajaxstruct( $structurereferente_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
            $dataStructurereferente_id = Set::extract( $this->data, 'Apre.structurereferente_id' );
            $structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );

            $struct = $this->Apre->Structurereferente->findbyId( $structurereferente_id, null, null, -1 );
            $this->set( 'struct', $struct );
            $this->render( 'ajaxstruct', 'ajax' );
        }


        /** ********************************************************************
        *   Ajax pour les coordonnées du référent APRE
        *** *******************************************************************/

        function ajaxref( $referent_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
            if( !empty( $referent_id ) ) {
                $referent_id = suffix( $referent_id );
            }
            else {
                $referent_id = suffix( Set::extract( $this->data, 'Apre.referent_id' ) );
            }

            $referent = $this->Apre->Referent->findbyId( $referent_id, null, null, -1 );
            $this->set( 'referent', $referent );
            $this->render( 'ajaxref', 'ajax' );
        }


        /** ********************************************************************
        *   Ajax pour les coordonnées du tiers prestataire APRE pour Formqualif
        *** *******************************************************************/
        function ajaxtiersprestaformqualif( $tiersprestataireapre_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
            $dataTiersprestataireapre_id = Set::extract( $this->data, 'Formqualif.tiersprestataireapre_id' );
            $tiersprestataireapre_id = ( empty( $tiersprestataireapre_id ) && !empty( $dataTiersprestataireapre_id ) ? $dataTiersprestataireapre_id : $tiersprestataireapre_id );

            $tiersprestataireapre = $this->Tiersprestataireapre->findbyId( $tiersprestataireapre_id, null, null, -1 );

            $this->set( 'tiersprestataireapre', $tiersprestataireapre );
            $this->render( 'ajaxtierspresta', 'ajax');
        }

        /** ********************************************************************
        *   Ajax pour les coordonnées du tiers prestataire APRE pour Formpermfimo
        *** *******************************************************************/
        function ajaxtiersprestaformpermfimo( $tiersprestataireapre_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
            $dataTiersprestataireapre_id = Set::extract( $this->data, 'Formpermfimo.tiersprestataireapre_id' );
            $tiersprestataireapre_id = ( empty( $tiersprestataireapre_id ) && !empty( $dataTiersprestataireapre_id ) ? $dataTiersprestataireapre_id : $tiersprestataireapre_id );

            $tiersprestataireapre = $this->Tiersprestataireapre->findbyId( $tiersprestataireapre_id, null, null, -1 );

            $this->set( 'tiersprestataireapre', $tiersprestataireapre );
            $this->render( 'ajaxtierspresta', 'ajax');
        }

        /** ********************************************************************
        *   Ajax pour les coordonnées du tiers prestataire APRE pour Actprof
        *** *******************************************************************/
        function ajaxtiersprestaactprof( $tiersprestataireapre_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
            $dataTiersprestataireapre_id = Set::extract( $this->data, 'Actprof.tiersprestataireapre_id' );
            $tiersprestataireapre_id = ( empty( $tiersprestataireapre_id ) && !empty( $dataTiersprestataireapre_id ) ? $dataTiersprestataireapre_id : $tiersprestataireapre_id );

            $tiersprestataireapre = $this->Tiersprestataireapre->findbyId( $tiersprestataireapre_id, null, null, -1 );

            $this->set( 'tiersprestataireapre', $tiersprestataireapre );
            $this->render( 'ajaxtierspresta', 'ajax');
        }


        /** ********************************************************************
        *   Ajax pour les coordonnées du tiers prestataire APRE pour PermisB
        *** *******************************************************************/
        function ajaxtiersprestapermisb( $tiersprestataireapre_id = null ) { // FIXME
            Configure::write( 'debug', 0 );
            $dataTiersprestataireapre_id = Set::extract( $this->data, 'Permisb.tiersprestataireapre_id' );
            $tiersprestataireapre_id = ( empty( $tiersprestataireapre_id ) && !empty( $dataTiersprestataireapre_id ) ? $dataTiersprestataireapre_id : $tiersprestataireapre_id );

            $tiersprestataireapre = $this->Tiersprestataireapre->findbyId( $tiersprestataireapre_id, null, null, -1 );

            $this->set( 'tiersprestataireapre', $tiersprestataireapre );
            $this->render(  'ajaxtierspresta', 'ajax' );
        }
        /** ********************************************************************
        *
        *** *******************************************************************/

        function view( $apre_id = null ){
            $apre = $this->Apre->findById( $apre_id );
            $this->assert( !empty( $apre ), 'invalidParameter' );


            $aprecomiteapre = $this->Apre->ApreComiteapre->findByApreId( $apre_id, null, null, -1 );
            $this->set( 'aprecomiteapre', $aprecomiteapre );


            $referents = $this->Referent->find( 'list' );
            $this->set( 'referents', $referents );

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

            // Liste de pièces pour chaque modèle lié
            foreach( $this->Apre->aidesApre as $modeleLie ) {
                $tablePieces = 'Piece'.strtolower( $modeleLie );
                $nomVar = 'pieces'.strtolower( $modeleLie );
                $list = $this->Apre->{$modeleLie}->{$tablePieces}->find( 'list' );
                $this->set( $nomVar, $list );
            }

            // Liste des tiers prestataires pour chaque formation
            foreach( $this->Apre->modelsFormation as $modelFormation ) {
                $list = $this->Tiersprestataireapre->find(
                    'list',
                    array(
                        'conditions' => array(
                            'Tiersprestataireapre.aidesliees' => $modelFormation
                        ),
                    )
                );
                $this->set( 'tiers'.$modelFormation, $list );
            }

            // Récupération des id afférents
            if( $this->action == 'add' ) {
                $personne_id = $id;
                $dossier_rsa_id = $this->Personne->dossierId( $personne_id );

                ///Création automatique du N° APRE de la forme : Année / Mois / N°
                $numapre = date('Ym').sprintf( "%010s",  $this->Apre->find( 'count' ) + 1 );
                $this->set( 'numapre', $numapre);

            }
            else if( $this->action == 'edit' ) {
                $apre_id = $id;
                $apre = $this->Apre->findById( $apre_id, null, null, 2 );
                $this->assert( !empty( $apre ), 'invalidParameter' );

                $personne_id = $apre['Apre']['personne_id'];
                $dossier_rsa_id = $this->Apre->dossierId( $apre_id );

                $this->set( 'numapre', Set::extract( $apre, 'Apre.numeroapre' ) );
            }

            // Retour à la liste en cas d'annulation
            if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'action' => 'index', $personne_id ) );
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
            $nbEnfants = $this->Foyer->nbEnfants( Set::classicExtract( $personne, 'Foyer.id' ) );
            $this->set( 'nbEnfants', $nbEnfants );

            ///Récupération de la liste des structures référentes liés uniquement à l'APRE
            $structs = $this->Structurereferente->listeParType( array( 'apre' => true ) );
            $this->set( 'structs', $structs );
            ///Récupération de la liste des référents liés à l'APRE
            $referents = $this->Referent->listOptions();
            $this->set( 'referents', $referents );


            if( !empty( $this->data ) ){
                // FIXME: pourquoi doit-on faire ceci ?
                $this->Apre->bindModel( array( 'hasOne' => array( 'Formqualif', 'Formpermfimo', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert' ) ), false );

                ///Mise en place lors de la sauvegarde du statut de l'APRE à Complémentaire
                $this->data['Apre']['statutapre'] = 'C';


                if( $this->Apre->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {

                    $saved = $this->Apre->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );
                    if( $saved ) {
                        $tablesLiees = array(
                            'Formqualif' => 'Pieceformqualif',
                            'Formpermfimo' => 'Pieceformpermfimo',
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
                    }

                    if( $saved ) {
                        $this->Apre->supprimeFormationsObsoletes( $this->data );
                        $this->Jetons->release( $dossier_rsa_id );
                        $this->Apre->commit(); // FIXME
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
//                         debug( $this->data );
                        $this->redirect( array(  'controller' => 'apres','action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Apre->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else{
                if( $this->action == 'edit' ) {
//                 debug($apre);
                ///FIXME: pour le moment on ne récupère que les pièces de la dernière aide enregistrée !!!
                    $tablesLiees = array(
                        'Formqualif' => 'Pieceformqualif',
                        'Formpermfimo' => 'Pieceformpermfimo',
                        'Actprof' => 'Pieceactprof',
                        'Permisb' => 'Piecepermisb',
                        'Amenaglogt' => 'Pieceamenaglogt',
                        'Acccreaentr' => 'Pieceacccreaentr',
                        'Acqmatprof' => 'Pieceacqmatprof',
                        'Locvehicinsert' => 'Piecelocvehicinsert'
                    );
                    foreach( $tablesLiees as $model => $piecesLiees ) {

                        if( !empty( $apre[$model][$piecesLiees] ) ) {
                            $linkedData = array(
                                $model => array(
                                    'id' => Set::classicExtract( $apre, "{$model}.id" ),
                                ),
                                $piecesLiees => $apre[$model][$piecesLiees]
                            );

                            $saved = $this->Apre->{$model}->{$piecesLiees}->save( $linkedData );
                        }
                        if( !empty( $linkedData ) ){
                            $this->data = Set::merge( $apre, $linkedData );
                        }
                        else{
                            $this->data = $apre;
                            $this->data = Set::insert(
                                $this->data, 'Apre.referent_id',
                                Set::extract( $this->data, 'Apre.structurereferente_id' ).'_'.Set::extract( $this->data, 'Apre.referent_id' )
                            );
                        }
//                         debug( $this->data );
                    }
                }
            }
            $this->Apre->commit();


            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit_'.Configure::read( 'nom_form_apre_cg' ) );
//             $this->render( $this->action, null, 'add_edit' );
        }

    }
?>