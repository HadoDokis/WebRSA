<?php
	App::import( 'Helper', 'Locale' );

	class CuisController extends AppController
	{
		public $name = 'Cuis';
		public $uses = array( 'Cui', 'Option', 'Departement' );

		public $helpers = array( 'Default', 'Default2', 'Locale', 'Csv', 'Ajax', 'Xform', 'Fileuploader' );
		public $components = array( 'RequestHandler', 'Gedooo.Gedooo', 'Fileuploader' );

		public $commeDroit = array(
			'add' => 'Cuis:edit',
			'view' => 'Cuis:index'
		);

		public $aucunDroit = array( 'impression' );

		/**
		*
		*/

		protected function _setOptions() {
			$options = array();
			$options = $this->Cui->allEnumLists();
			$optionsperiode = $this->Cui->Periodeimmersion->allEnumLists();
			$options = Set::merge( $options, $optionsperiode );

			$typevoie = $this->Option->typevoie();
			$options = Set::insert( $options, 'typevoie', $typevoie );

			$this->set( compact( 'options' ) );
			
			
			$typevoie = $this->Option->typevoie();
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'nationalite', $this->Option->nationalite() );

			$dept = $this->Departement->find( 'list', array( 'fields' => array( 'numdep', 'name' ), 'contain' => false ) );
			$this->set( compact( 'dept' ) );
			
			
			
			if( Configure::read( 'CG.cantons' ) ) {
				$Canton = ClassRegistry::init( 'Canton' );
				$this->set( 'cantons', $Canton->selectList() );			
				$secteursactivites = $this->Cui->Personne->Dsp->Libsecactderact66Secteur->find(
						'list',
						array(
							'contain' => false,
							'order' => array( 'Libsecactderact66Secteur.code' )
						)
					);
				$this->set( 'secteursactivites', $secteursactivites );
			}
			

			$this->set( 'prestataires', $this->Cui->Referent->listOptions() );
			$this->set( 'referents', $this->Cui->Referent->find( 'list', array( 'recursive' => false ) ) );
			$this->set( 'structs', $this->Cui->Structurereferente->listOptions() );


			$this->set( 'rsaSocle', $this->Option->natpf() );
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
		*   Fonction permettant d'accéder à la page pour lier les fichiers au CER
		*/

		public function filelink( $id ){
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$fichiers = array();
			$cui = $this->Cui->find(
				'first',
				array(
					'conditions' => array(
						'Cui.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);

			$personne_id = $cui['Cui']['personne_id'];
			$dossier_id = $this->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Cui->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Cui->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {
				$saved = $this->Cui->updateAll(
					array( 'Cui.haspiecejointe' => '\''.$this->data['Cui']['haspiecejointe'].'\'' ),
					array(
						'"Cui"."personne_id"' => $personne_id,
						'"Cui"."id"' => $id
					)
				);

				if( $saved ){
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->data, "Cui.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->Cui->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
// 					$this->redirect( array(  'controller' => 'cuis','action' => 'index', $personne_id ) );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Cui->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'cui' ) );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
		}

		/**
		*
		*/

		public function index( $personne_id = null ) {
			$nbrPersonnes = $this->Cui->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			/**
			*   Précondition: La personne est-elle bien en Rsa Socle ?
			*/
			$alerteRsaSocle = $this->Cui->_prepare( $personne_id );
			$this->set( 'alerteRsaSocle', $alerteRsaSocle );

			$cuis = $this->Cui->find(
				'all',
				array(
					'fields' => array_merge(
						$this->Cui->fields(),
						array(
							'( SELECT COUNT(fichiersmodules.id) FROM fichiersmodules WHERE fichiersmodules.modele = \'Cui\' AND fichiersmodules.fk_value = "Cui"."id" ) AS "Fichiermodule__nbFichiersLies"'
						)
					),
					'conditions' => array(
						'Cui.personne_id' => $personne_id
					),
					'recursive' => -1,
					'contain' => false
				)
			);

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( compact( 'cuis' ) );
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
			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				if( $this->action == 'edit' ) {
					$id = $this->Cui->field( 'personne_id', array( 'id' => $id ) );
				}
				$this->redirect( array( 'action' => 'index', $id ) );
			}

			$valueAdressebis = null;
			if( $this->action == 'add' ) {
				$cui_id = null;
				$personne_id = $id;
				$nbrPersonnes = $this->Cui->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
				$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );
				$valueAdressebis = 'N';

			}
			else if( $this->action == 'edit' ) {
				$cui_id = $id;
				$cui = $this->Cui->findById( $cui_id, null, null, 1 );
				$this->assert( !empty( $cui ), 'invalidParameter' );
				$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );
				$valueAdressebis = Set::classicExtract( $cui, 'Cui.isadresse2' );
			}

			/// Peut-on prendre le jeton ?
			$this->Cui->begin();
			$dossier_id = $this->Cui->Personne->dossierId( $personne_id );
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Cui->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'valueAdressebis', $valueAdressebis );


			$personne = $this->{$this->modelClass}->Personne->detailsApre( $personne_id, $this->Session->read( 'Auth.User.id' ) );

			$this->set( 'personne', $personne );

			if( !empty( $this->data ) ){
debug($this->data);
				$this->{$this->modelClass}->create( $this->data );
				$success = $this->{$this->modelClass}->save();

				// Nettoyage des Periodes d'immersion
				$keys = array_keys( $this->Cui->Periodeimmersion->schema() );
				$defaults = array_combine( $keys, array_fill( 0, count( $keys ), null ) );
				unset( $defaults['id'] );
				unset( $defaults['cui_id'] );
				if( !empty( $this->data['Periodeimmersion'] ) ) {
					$this->data['Periodeimmersion'] = Set::merge( $defaults, $this->data['Periodeimmersion'] );
				}

				if( ( $this->data['Cui']['secteur'] == 'CAE' ) && !empty( $this->data['Periodeimmersion'] ) ) {
					$Periodeimmersion = Set::filter( $this->data['Periodeimmersion'] );
					if( !empty( $Periodeimmersion ) ){
						$this->{$this->modelClass}->Periodeimmersion->create( $this->data );
						if( $this->action == 'add'  ) {
							$this->{$this->modelClass}->Periodeimmersion->set( 'cui_id', $this->{$this->modelClass}->getLastInsertID( ) );
						}
						else if( $this->action == 'edit' ) {
							$this->{$this->modelClass}->Periodeimmersion->set( 'cui_id', Set::classicExtract( $this->data, 'Cui.id' ) );
						}
						$success = $this->{$this->modelClass}->Periodeimmersion->save() && $success;
					}
				}

				if( $success  ) {
						$this->Jetons->release( $dossier_id );
						$this->{$this->modelClass}->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->{$this->modelClass}->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

			}
			else {
				if( $this-> action == 'edit' ){
					$this->data = $cui;
					if( !empty( $this->data['Periodeimmersion'] ) ) {
						$this->data['Periodeimmersion'] = $this->data['Periodeimmersion'][0];
					}

				}
			}

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		public function valider( $cui_id = null ) {
			$cui = $this->Cui->findById( $cui_id );
			$this->assert( !empty( $cui ), 'invalidParameter' );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $cui['Cui']['personne_id'] ) );
			}

			$this->set( 'personne_id', $cui['Cui']['personne_id'] );

			if( !empty( $this->data ) ) {
				if( $this->Cui->saveAll( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
				$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $cui['Cui']['personne_id']) );
				}
			}
			else {
				$this->data = $cui;
			}
			$this->_setOptions();
			$this->set( 'urlmenu', '/cuis/index/'.$cui['Cui']['personne_id'] );
		}

		/**
		 * Imprime un CUI.
		 *
		 * @param integer $id L'id du CUI que l'on veut imprimer
		 * @return void
		 */
		public function impression( $id ) {
			$pdf = $this->Cui->getDefaultPdf( $id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'cui_%d_%d.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier de contrat unique d\'engagement.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		
		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}

		/**
		*
		*/

		public function view( $id ) {
			$this->_setOptions();
			$this->Default->view( $id );
		}
	}
?>