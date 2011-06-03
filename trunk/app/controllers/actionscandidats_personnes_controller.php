<?php
	class ActionscandidatsPersonnesController extends AppController
	{
		public $name = 'ActionscandidatsPersonnes';
//6,08 secondes. 25.48 MB / 25.75 MB. 132 modèles
//5,97 secondes. 25.36 MB / 25.75 MB. 130 modèles

//2,99 secondes. 20.16 MB / 20.50 MB. 75 modèles
		public $uses = array(
			'ActioncandidatPersonne',
			'Option',
// 			'Personne',
// 			'Actioncandidat',
// 			'Partenaire',
// 			'Typerdv',
// 			'PersonneReferent',
// 			'Referent',
			//'Rendezvous',
// 			'ActioncandidatPartenaire',
// 			'Contactpartenaire',
// 			'Adressefoyer',
// 			'Detailnatmob',
// 			'Dsp',
// 			'Serviceinstructeur',
// 			'Foyer',
//             'Structurereferente',
// 			'Motifsortie'
		);

		public $helpers = array( 'Default', 'Locale', 'Csv', 'Ajax', 'Xform', 'Default2' );

		public $aucunDroit = array( 'ajaxpart', 'ajaxstruct', 'ajaxreferent', 'ajaxreffonct' );

		public $components = array( 'Default', 'Gedooo' );

		public $commeDroit = array(
			'add' => 'ActionscandidatsPersonnes:edit'
		);


		/**
		*
		*/

		protected function _setOptions() {
			$options = array();
			foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
//                 debug($options);
			}

			$options = Set::insert( $options, 'Adresse.typevoie', $this->Option->typevoie() );
			$options = Set::insert( $options, 'Personne.qual', $this->Option->qual() );
			$options = Set::insert( $options, 'Contratinsertion.decision_ci', $this->Option->decision_ci() );
			$options = Set::insert( $options, 'Dsp', $this->ActioncandidatPersonne->Personne->Dsp->allEnumLists() );

			foreach( array( 'Actioncandidat', /*'Personne', */'Referent'/*, 'Partenaire'*/, 'Motifsortie' ) as $linkedModel ) {
				$field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list', array( 'recursive' => -1 ) ) );
			}
			App::import( 'Helper', 'Locale' );
			$this->Locale = new LocaleHelper();

			$options = Set::insert( $options, 'ActioncandidatPersonne.naturemobile', $this->ActioncandidatPersonne->Personne->Dsp->Detailnatmob->enumList( 'natmob' ) );


			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
			$this->set( 'sitfam', $this->Option->sitfam() );
			$this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'typeservice', $this->ActioncandidatPersonne->Personne->Orientstruct->Serviceinstructeur->find( 'first' ) );
			$this->set( compact( 'options', 'typevoie' ) );

		}

  /**
		*
  */

		public function indexparams(){
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}
			$compteurs = array(
                'Partenaire' => ClassRegistry::init( 'Partenaire' )->find( 'count' ),
                'Contactpartenaire' => ClassRegistry::init( 'Contactpartenaire' )->find( 'count' )
            );
            $this->set( compact( 'compteurs' ) );
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

            $this->ActioncandidatPersonne->forceVirtualFields = true;
			$queryData = array(
				'ActioncandidatPersonne' => array(
					'conditions' => array(
						'ActioncandidatPersonne.personne_id' => $personne_id
					),
					'contain' => array(
                        'Actioncandidat' => array(
                            'Contactpartenaire' => array(
                                'Partenaire'
                            )
                        ),
                        'Referent'
					)
				)
			);
			$this->paginate = array(
				$this->modelClass => array(
					'limit' => 5/*,
					'recursive' => 2*/
				)
			);

// 			$this->{$this->modelClass}->Personne->unbindModelAll( false );
// 			$this->{$this->modelClass}->Referent->unbindModelAll( false );
			$this->paginate = Set::merge( $this->paginate, $queryData );
			$items = $this->paginate( $this->modelClass );
			$varname = Inflector::tableize( $this->name );
			$this->set( $varname, $items );
			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
// 			$this->render( $this->action, null, '/actionscandidats_personnes/index_'.Configure::read( 'nom_form_ci_cg' ) );

		}


  /**
		*   Ajax pour les partenaires fournissant les actions
  */

		public function ajaxpart( $actioncandidat_id = null ) { // FIXME
			Configure::write( 'debug', 0 );

			$dataActioncandidat_id = Set::extract( $this->data, 'ActioncandidatPersonne.actioncandidat_id' );
			$actioncandidat_id = ( empty( $actioncandidat_id ) && !empty( $dataActioncandidat_id ) ? $dataActioncandidat_id : $actioncandidat_id );

            if( !empty( $actioncandidat_id ) ) {
                $this->ActioncandidatPersonne->Actioncandidat->forceVirtualFields = true;
                $actioncandidat = $this->ActioncandidatPersonne->Actioncandidat->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Actioncandidat.id' => $actioncandidat_id
                        ),
                        'contain' => array(
                            'Contactpartenaire' => array(
                                'Partenaire'
                            )
                        )
                    )
                );

                if( ($actioncandidat['Actioncandidat']['correspondantaction'] == 1) && !empty($actioncandidat['Actioncandidat']['referent_id']))
                {
                    $this->ActioncandidatPersonne->Personne->Referent->recursive = -1;
                    $referent = $this->ActioncandidatPersonne->Personne->Referent->read(null, $actioncandidat['Actioncandidat']['referent_id']);
                }
                $this->set( compact( 'actioncandidat', 'referent' ) );
			}
			$this->render( 'ajaxpart', 'ajax' );
		}


		public function ajaxreferent( $referent_id = null )
		{  // FIXME
			Configure::write( 'debug', 0 );
			$dataReferent_id = Set::extract( $this->data, 'ActioncandidatPersonne.referent_id' );
			$referent_id = ( empty( $referent_id ) && !empty( $dataReferent_id ) ? $dataReferent_id : $referent_id );
			$this->ActioncandidatPersonne->Personne->Referent->recursive = 0;
			$prescripteur = $this->ActioncandidatPersonne->Personne->Referent->read(null, $referent_id);
			$this->set( compact( 'prescripteur' ) );
			$this->render( 'ajaxreferent', 'ajax' );
		}
		

  /**
		*   Ajax pour les partenaires fournissant les actions
  */

		public function ajaxstruct( $referent_id = null ) { // FIXME
			Configure::write( 'debug', 0 );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$dataReferent_id = Set::extract( $this->data, 'ActioncandidatPersonne.referent_id' );
			$referent_id = ( empty( $referent_id ) && !empty( $dataReferent_id ) ? $dataReferent_id : $referent_id );
			if( is_int( $referent_id ) ) {
				$referent = $this->ActioncandidatPersonne->Personne->Referent->findbyId( $referent_id, null, null, -1 );
				$structs = $this->ActioncandidatPersonne->Personne->Orientstruct->Structurereferente->find(
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


  /**
		*   Ajax pour les partenaires fournissant les actions
  */

		public function ajaxreffonct( $referent_id = null ) { // FIXME
			Configure::write( 'debug', 0 );
// debug($referent_id);
			if( !empty( $referent_id ) ) {
				$referent_id = suffix( $referent_id );
			}
			else {
				$referent_id = suffix( Set::extract( $this->data, 'Rendezvous.referent_id' ) );
			}

			$this->set( 'typevoie', $this->Option->typevoie() );

			$dataReferent_id = Set::extract( $this->data, 'Rendezvous.referent_id' );
			$referent_id = ( empty( $referent_id ) && !empty( $dataReferent_id ) ? $dataReferent_id : $referent_id );

			if( is_int( $referent_id ) ) {
				$referent = $this->ActioncandidatPersonne->Personne->Referent->findbyId( $referent_id, null, null, -1 );

				$structs = $this->ActioncandidatPersonne->Personne->Orientstruct->Structurereferente->find(
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

		protected function _add_edit( $id = null ) {
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

// debug( class_registry_models_count() ); // 14

				///Pour récupérer le référent lié à la personne s'il existe déjà
				$personne_referent = $this->ActioncandidatPersonne->Personne->PersonneReferent->find( 'first', array( 'conditions' => array( 'PersonneReferent.personne_id' => $personne_id, 'PersonneReferent.dfdesignation IS NULL' ), 'contain' => false ) );

				$referentId = null;
				if( !empty( $personne_referent ) ){
					$referentId = Set::classicExtract( $personne_referent, 'PersonneReferent.referent_id' );
					$referents = $this->ActioncandidatPersonne->Personne->Referent->findById( $referentId, null, null, -1 );
					$this->set( compact( 'referents' ) );
				}
				$this->set( compact( 'referentId' ) );

				///Données propre au partenaire
				$part = $this->ActioncandidatPersonne->Actioncandidat->Partenaire->find( 'list', array( 'contain' => false ) );
				$this->set( compact( 'part' ) );

			}
			else if( $this->action == 'edit' ) {
				$actioncandidat_personne_id = $id;
				$actioncandidat_personne = $this->ActioncandidatPersonne->findById( $actioncandidat_personne_id, null, null, -1 );
				$this->assert( !empty( $actioncandidat_personne ), 'invalidParameter' );

				$personne_id = Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.personne_id' );
				$personne = $this->ActioncandidatPersonne->Personne->findById( $personne_id, null, null, -1 );
				$personne_referent = $this->ActioncandidatPersonne->Personne->PersonneReferent->find( 'first', array( 'conditions' => array( 'PersonneReferent.personne_id' => $personne_id ) ) );

				$referentId = null;
				if( !empty( $personne_referent ) ){
					$referentId = Set::classicExtract( $personne_referent, 'PersonneReferent.referent_id' );
					$referents = $this->ActioncandidatPersonne->Personne->Referent->findById( $referentId, null, null, -1 );
					$this->set( compact( 'referents' ) );
				}
				$this->set( compact( 'referentId', 'personne' ) );

				$dossierId = $this->ActioncandidatPersonne->Personne->dossierId( $personne_id );
				$this->assert( !empty( $dossierId ), 'invalidParameter' );
				$this->set( compact( 'dossierId', 'personne_id' ) );
			}


			$this->set( 'personne_id', $personne_id );

			///Données récupérées propre à la personne
			$personne = $this->{$this->modelClass}->Personne->newDetailsCi( $personne_id );

// debug($personne);
// debug( class_registry_models() );
// debug( class_registry_models_count() ); // 39
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


// debug( class_registry_models() );
// debug( class_registry_models_count() ); //54
			///Nombre d'enfants par foyer
			$nbEnfants = $this->ActioncandidatPersonne->Personne->Foyer->nbEnfants( Set::classicExtract( $personne, 'Personne.foyer_id' ) );
			$this->set( 'nbEnfants', $nbEnfants );

			//Numéro Pôle Emploi :
			$identifiantpe = ClassRegistry::init('Informationpe')->dernierIdentifiantpe( $personne_id);
			$this->set( 'identifiantpe', $identifiantpe );
// debug( class_registry_models() );
// debug( class_registry_models_count() ); //55
			///Récupération de la liste des structures référentes liés uniquement à l'APRE
			$structs = $this->ActioncandidatPersonne->Personne->Orientstruct->Structurereferente->listOptions( );
			$this->set( 'structs', $structs );
// debug( class_registry_models() );
// debug( class_registry_models_count() ); //57
			///Récupération de la liste des référents liés à l'APRE
			$referents = $this->ActioncandidatPersonne->Personne->Referent->listOptions();
			$this->set( 'referents', $referents );

// debug( class_registry_models() );
// debug( class_registry_models_count() ); //57
			///Données Dsp
			$dsp = $this->ActioncandidatPersonne->Personne->Dsp->findByPersonneId( $personne_id, null, null, -1  );
			$this->set( compact( 'dsp' ) );

// debug( class_registry_models() );
// debug( class_registry_models_count() ); //57
            ///Récupération de la liste des actions avec une fiche de candidature
            $actionsfiche = $this->{$this->modelClass}->Actioncandidat->listePourFicheCandidature();
            $this->set( 'actionsfiche', $actionsfiche );
// debug( class_registry_models() );
// debug( class_registry_models_count() ); //57
			$this->ActioncandidatPersonne->begin();

			if( !empty( $this->data ) ){

				///Récupération des Dsps et sauvegarde
				$this->ActioncandidatPersonne->Personne->Dsp->saveAll( $this->data, array( 'validate' => 'only' ) );

				if( $this->ActioncandidatPersonne->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {

					///Récupération des Dsps et sauvegarde
					$this->ActioncandidatPersonne->Personne->Dsp->create();
					$this->ActioncandidatPersonne->Personne->Dsp->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );

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
				}
			}

			$this->_setOptions();
// debug( class_registry_models() );
// debug( class_registry_models_count() ); //74
			$this->ActioncandidatPersonne->commit();

			$this->render( $this->action, null, 'add_edit_'.Configure::read( 'ActioncandidatPersonne.suffixe' ) );
		}


        /**
        * Impression de la fiche de candidature
        */

        public function printFiche( $actioncandidat_personne_id ) {
            $pdf = $this->ActioncandidatPersonne->getPdfFiche( $actioncandidat_personne_id );

            if( $pdf ) {
                $this->Gedooo->sendPdfContentToClient( $pdf, 'FicheCandidature' );
            }
            else {
                $this->Session->setFlash( 'Impossible de générer la fiche de candidature', 'default', array( 'class' => 'error' ) );
                $this->redirect( $this->referer() );
            }
        }







		/**
		*
		*/

		/*public function gedooo( $id = null ) {
			$qual = $this->Option->qual();
			$typevoie = $this->Option->typevoie();
			$mobilites = $this->ActioncandidatPersonne->Personne->Dsp->Detailnatmob->enumList( 'natmob' );

			$actioncandidat_personne = $this->ActioncandidatPersonne->find(
				'first',
				array(
					'conditions' => array(
						'ActioncandidatPersonne.id' => $id
					),
					'recursive' => 0
				)
			);

			$this->ActioncandidatPersonne->Personne->Foyer->Adressefoyer->bindModel(
				array(
					'belongsTo' => array(
						'Adresse' => array(
							'className'     => 'Adresse',
							'foreignKey'    => 'adresse_id'
						)
					)
				)
			);

			$adresse = $this->ActioncandidatPersonne->Personne->Foyer->Adressefoyer->find(
				'first',
				array(
					'conditions' => array(
						'Adressefoyer.foyer_id' => Set::classicExtract( $actioncandidat_personne, 'Personne.foyer_id' ),
						'Adressefoyer.rgadr' => '01',
					)
				)
			);
			$actioncandidat_personne['Adresse'] = $adresse['Adresse'];

			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$actioncandidat_id = Set::classicExtract( $actioncandidat_personne, 'Actioncandidat.id' );
				$actioncandidatpartenaire = $this->ActioncandidatPersonne->Actioncandidat->ActioncandidatPartenaire->findByActioncandidatId( $actioncandidat_id, null, null, -1 );
				$partenaire_id = Set::classicExtract( $actioncandidatpartenaire, 'ActioncandidatPartenaire.partenaire_id' );
				$partenaire = $this->ActioncandidatPersonne->Actioncandidat->ActioncandidatPartenaire->Partenaire->findById( $partenaire_id, null, null, -1 );

				$actioncandidat_personne = Set::merge( $actioncandidat_personne, $partenaire );

				$contactpartenaire = $this->ActioncandidatPersonne->Actioncandidat->ActioncandidatPartenaire->Partenaire->Contactpartenaire->findByPartenaireId( $partenaire_id, null, null, -1 );
				$actioncandidat_personne = Set::merge( $actioncandidat_personne, $contactpartenaire );
			}
debug($actioncandidat_personne);
die();
			///Traduction pour les données de la Personne/Contact/Partenaire/Référent
			$actioncandidat_personne['Personne']['qual'] = Set::enum( Set::classicExtract( $actioncandidat_personne, 'Personne.qual' ), $qual );
			$actioncandidat_personne['Referent']['qual'] = Set::enum( Set::classicExtract( $actioncandidat_personne, 'Referent.qual' ), $qual );
			$actioncandidat_personne['Contactpartenaire']['qual'] = Set::enum( Set::classicExtract( $actioncandidat_personne, 'Contactpartenaire.qual' ), $qual );
			$actioncandidat_personne['Partenaire']['typevoie'] = Set::enum( Set::classicExtract( $actioncandidat_personne, 'Partenaire.typevoie' ), $typevoie );
			$actioncandidat_personne['ActioncandidatPersonne']['naturemobile'] = Set::enum( Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.naturemobile' ), $mobilites );

// correspondantaction_nom_complet

			$pdf = $this->ActioncandidatPersonne->ged( $actioncandidat_personne, 'Candidature/fichecandidature.odt' );
			$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'actioncandidat_personne-%s.pdf', date( 'Y-m-d' ) ) );
		}*/

		
		public function delete( $id )
		{
			$this->Default->delete( $id );
		}


        /**
        *   Fonction pour annuler le CER pour le CG66
        */

        public function cancel( $id ) {
            $actioncandidat = $this->{$this->modelClass}->findById( $id, null, null, -1 );
            $personne_id = Set::classicExtract( $actioncandidat, 'ActioncandidatPersonne.personne_id' );

            $this->{$this->modelClass}->updateAll(
                array( 'ActioncandidatPersonne.positionfiche' => '\'annule\'' ),
                array(
                    '"ActioncandidatPersonne"."id"' => $id
                )
            );
            $this->redirect( array( 'action' => 'index', $personne_id ) );
        }



		public function view( $id )
		{
			$personne_id = $this->ActioncandidatPersonne->field('personne_id', array('id' => $id));
			$dossierId = $this->ActioncandidatPersonne->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossierId ), 'invalidParameter' );
			$this->set( compact( 'dossierId', 'personne_id' ) );
			$this->Default->view( $id );
		}	
		
		
	}
?>
