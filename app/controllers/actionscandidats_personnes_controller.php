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

		public $helpers = array( 'Default', 'Locale', 'Csv', 'Ajax', 'Xform', 'Default2', 'Fileuploader' );

		public $components = array( 'Default', 'Gedooo', 'Fileuploader' );

		public $aucunDroit = array( 'ajaxpart', 'ajaxstruct', 'ajaxreferent', 'ajaxreffonct', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		public $commeDroit = array(
			'view' => 'ActionscandidatsPersonnes:index',
			'add' => 'ActionscandidatsPersonnes:edit'
		);

		/**
		*
		*/

		protected function _setOptions() {
			$options = array();
			foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
			}

			$options = Set::insert( $options, 'Adresse.typevoie', $this->Option->typevoie() );
			$options = Set::insert( $options, 'Personne.qual', $this->Option->qual() );
			$options = Set::insert( $options, 'Contratinsertion.decision_ci', $this->Option->decision_ci() );
			$options = Set::insert( $options, 'Dsp', $this->ActioncandidatPersonne->Personne->Dsp->allEnumLists() );

			foreach( array( 'Referent', 'Motifsortie' ) as $linkedModel ) {
				$field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list', array( 'recursive' => -1 ) ) );
			}
			$field = Inflector::singularize( Inflector::tableize( 'Actioncandidat' ) ).'_id';
			$options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{'Actioncandidat'}->find( 'list', array( 'recursive' => -1, 'order' => 'name' ) ) );
			App::import( 'Helper', 'Locale' );
			$this->Locale = new LocaleHelper();

// 			$options = Set::insert( $options, 'ActioncandidatPersonne.naturemobile', $this->ActioncandidatPersonne->Personne->Dsp->Detailnatmob->enumList( 'natmob' ) );

			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
			$this->set( 'sitfam', $this->Option->sitfam() );
			$this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'typeserins', $this->Option->typeserins() );
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
			$this->render( $this->action, null, 'indexparams_'.Configure::read( 'ActioncandidatPersonne.suffixe' ) );
		}


		/**
		* http://valums.com/ajax-upload/
		* http://doc.ubuntu-fr.org/modules_php
		* increase post_max_size and upload_max_filesize to 10M
		* debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		*/

		public function ajaxfileupload() {
			$this->Fileuploader->ajaxfileupload();
		}

		/**
		* http://valums.com/ajax-upload/
		* http://doc.ubuntu-fr.org/modules_php
		* increase post_max_size and upload_max_filesize to 10M
		* debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		* FIXME: traiter les valeurs de retour
		*/

		public function ajaxfiledelete() {
			$this->Fileuploader->ajaxfiledelete();
		}

		/**
		*   Fonction permettant de visualiser les fichiers chargés dans la vue avant leur envoi sur le serveur
		*/

		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		*   Téléchargement des fichiers préalablement associés à un traitement donné
		*/

		public function download( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );
			$this->Fileuploader->download( $fichiermodule_id );
		}

		/**
		*   Fonction permettant d'accéder à la page pour lier les fichiers à l'Orientation
		*/

		public function filelink( $id ){
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$fichiers = array();
			$actioncandidat_personne = $this->ActioncandidatPersonne->find(
				'first',
				array(
					'conditions' => array(
						'ActioncandidatPersonne.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);


			$dossier_id = $this->ActioncandidatPersonne->Personne->dossierId( $actioncandidat_personne['ActioncandidatPersonne']['personne_id'] );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			$personne_id = Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.personne_id' );

			$this->ActioncandidatPersonne->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->ActioncandidatPersonne->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {

				$saved = $this->ActioncandidatPersonne->updateAll(
					array( 'ActioncandidatPersonne.haspiecejointe' => '\''.$this->data['ActioncandidatPersonne']['haspiecejointe'].'\'' ),
					array(
						'"ActioncandidatPersonne"."id"' => $id
					)
				);

				if( $saved ){
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->data, "ActioncandidatPersonne.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->ActioncandidatPersonne->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array(  'controller' => 'actionscandidats_personnes','action' => 'index', $personne_id ) );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->ActioncandidatPersonne->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( compact( 'dossier_id', 'id', 'fichiers', 'actioncandidat_personne' ) );
			$this->set( 'personne_id', $personne_id );

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

			//Vérification de la présence d'une orientation ou d'un référent pour cet allocataire
			$referentLie = $this->ActioncandidatPersonne->Personne->PersonneReferent->find(
				'count',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id' => $personne_id
					),
					'contain' => false
				)
			);
			$this->set( compact( 'referentLie' ) );

			$orientationLiee = $this->ActioncandidatPersonne->Personne->Orientstruct->find(
				'count',
				array(
					'conditions' => array(
						'Orientstruct.personne_id' => $personne_id
					),
					'contain' => false
				)
			);
			$this->set( compact( 'orientationLiee' ) );




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
						'Referent',
						'Motifsortie'
					)
				)
			);
			$this->paginate = array(
				$this->modelClass => array(
					'limit' => 10/*,
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
// debug($items);
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
			$this->set( 'typevoie', $this->Option->typevoie() );
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

				$referentId = null;
				$this->set( compact( 'referentId', 'personne' ) );

				$dossierId = $this->ActioncandidatPersonne->Personne->dossierId( $personne_id );
				$this->assert( !empty( $dossierId ), 'invalidParameter' );
				$this->set( compact( 'dossierId', 'personne_id' ) );
			}

			$this->set( 'personne_id', $personne_id );

			///Données récupérées propre à la personne
// 			$personne = $this->{$this->modelClass}->Personne->newDetailsCi( $personne_id, $this->Session->read( 'Auth.User.id' ) );


			$personne = $this->{$this->modelClass}->Personne->newDetailsCi( $personne_id, $this->Session->read( 'Auth.User.id' ) );
			$this->set( 'personne', $personne );

			///Données Contrat engagement
// 			$contrat = $this->{$this->modelClass}->Personne->Contratinsertion->find(
// 				'first',
// 				array(
// 					'conditions' => array(
// 						'Contratinsertion.personne_id' => $personne_id
// 					),
// 					'recursive' => -1,
// 					'order' => 'Contratinsertion.date_saisi_ci DESC'
// 				)
// 			);
// 			if( !empty( $contrat ) ) {
// 				$personne = Set::merge( $personne, $contrat );
// 			}

// 			$this->set( 'personne', $personne );
// debug($personne);
			///Nombre d'enfants par foyer
			$nbEnfants = $this->ActioncandidatPersonne->Personne->Foyer->nbEnfants( Set::classicExtract( $personne, 'Personne.foyer_id' ) );
			$this->set( 'nbEnfants', $nbEnfants );

			//Numéro Pôle Emploi :
			$identifiantpe = ClassRegistry::init('Informationpe')->dernierIdentifiantpe( $personne_id);
			$this->set( 'identifiantpe', $identifiantpe );

			///Récupération de la liste des structures référentes liés uniquement à l'APRE
			$structs = $this->ActioncandidatPersonne->Personne->Orientstruct->Structurereferente->listOptions( );
			$this->set( 'structs', $structs );

			///Récupération de la liste des référents liés à l'APRE
			$referents = $this->ActioncandidatPersonne->Personne->Referent->listOptions();
			$this->set( 'referents', $referents );

			///Données Dsp
			$dsp = $this->ActioncandidatPersonne->Personne->Dsp->findByPersonneId( $personne_id, null, null, -1  );
			$this->set( compact( 'dsp' ) );

			///Récupération de la liste des actions avec une fiche de candidature
			$user = $this->User->findById( $this->Session->read( 'Auth.User.id' ), null, null, 0 );
			$codeinseeUser = Set::classicExtract( $user, 'Serviceinstructeur.code_insee' );
			$actionsfiche = $this->{$this->modelClass}->Actioncandidat->listePourFicheCandidature( $codeinseeUser );
			$this->set( 'actionsfiche', $actionsfiche );


// 			if(  !empty( $this->data['Modecontact'] ) ){
// 				$Modecontact = Xset::bump( Set::filter( Set::flatten( $this->data['Modecontact'] ) ) );
// // debug($Modecontact);
// // die();
// 				if( !empty( $Modecontact ) ){
// 					$success = $this->{$this->modelClass}->Personne->Foyer->Modecontact->saveAll( $Modecontact, array( 'validate' => 'first', 'atomic' => false ) );
// 				}
// 			}

			$this->ActioncandidatPersonne->begin();

			if( !empty( $this->data ) ){
				///Récupération des Dsps et sauvegarde
				$this->ActioncandidatPersonne->Personne->Dsp->saveAll( $this->data, array( 'validate' => 'only' ) );

				if( $this->ActioncandidatPersonne->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {

					///Récupération des Dsps et sauvegarde
					$this->ActioncandidatPersonne->Personne->Dsp->create();
					$this->ActioncandidatPersonne->Personne->Dsp->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );

					// SAuvegarde des numéros ed téléphone si ceux-ci ne sont pas présents en amont
					$isDataPersonne = Set::filter( $this->data['Personne'] );
					if( !empty( $isDataPersonne ) ){
						$success = $this->{$this->modelClass}->Personne->save( array( 'Personne' => $this->data['Personne'] ) );
					}


					if( $this->ActioncandidatPersonne->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

						$this->Jetons->release( $dossierId );
						$this->ActioncandidatPersonne->commit();
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

		
		public function delete( $id )
		{
			$this->Default->delete( $id );
		}


		/**
		*   Fonction pour annuler la fiche de candidature pour le CG66
		*/

		public function cancel( $id = null ) {
			$actioncandidat = $this->{$this->modelClass}->findById( $id, null, null, -1 );
			$personne_id = Set::classicExtract( $actioncandidat, 'ActioncandidatPersonne.personne_id' );

			$this->set( 'personne_id', $personne_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {
				if( $this->ActioncandidatPersonne->save( $this->data ) ) {

					$this->{$this->modelClass}->updateAll(
						array( 'ActioncandidatPersonne.positionfiche' => '\'annule\'' ),
						array(
							'"ActioncandidatPersonne"."id"' => $id
						)
					);

					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
			}
			else {
				$this->data = $actioncandidat;
			}
			$this->set( 'urlmenu', '/actionscandidats_personnes/index/'.$personne_id );

		}

		public function view( $id )
		{
			$this->ActioncandidatPersonne->forceVirtualFields = true;
			$personne_id = $this->ActioncandidatPersonne->field('personne_id', array('id' => $id));
			$dossierId = $this->ActioncandidatPersonne->Personne->dossierId( $personne_id );
			$this->ActioncandidatPersonne->forceVirtualFields = false;
			$this->assert( !empty( $dossierId ), 'invalidParameter' );
			$this->set( compact( 'dossierId', 'personne_id' ) );


			$actionscandidatspersonne = $this->ActioncandidatPersonne->find(
				'first',
				array(
					'conditions' => array(
						'ActioncandidatPersonne.id' => $id
					),
					'contain' => array(
						'Personne',
						'Referent',
						'Actioncandidat',
						'Motifsortie',
						'Fichiermodule'
					)
				)
			);

			if( ($actionscandidatspersonne['Actioncandidat']['correspondantaction'] == 1) && !empty($actionscandidatspersonne['Actioncandidat']['referent_id']))
			{
				$this->ActioncandidatPersonne->Personne->Referent->recursive = -1;
				$referent = $this->ActioncandidatPersonne->Personne->Referent->read(null, $actionscandidatspersonne['Actioncandidat']['referent_id'] );
			}
			if( !empty( $referent ) ){
				$actionscandidatspersonne['Actioncandidat']['referent_id'] = $referent['Referent']['nom_complet'];
			}
			else{
				$actionscandidatspersonne['Actioncandidat']['referent_id'] = '';
			}
			$this->set( compact( 'actionscandidatspersonne' ) );
			$this->_setOptions();
			// Retour à la liste en cas d'annulation
			if(  isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}
		}	
		
		
	}
?>
