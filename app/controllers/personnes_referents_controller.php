<?php
	class PersonnesReferentsController extends AppController
	{

		public $name = 'PersonnesReferents';
		public $uses = array( 'PersonneReferent', 'Option', 'Personne', 'Orientstruct', 'Structurereferente', 'Typerdv', 'Statutrdv', 'Referent' );
		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Fileuploader', 'Default2' );
		public $components = array( 'Fileuploader' );
		public $commeDroit = array(
			'add' => 'PersonnesReferents:edit'
		);
		public $aucunDroit = array( 'ajaxreferent', 'ajaxreffonct', 'ajaxperm', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		/**		 * *******************************************************************
		 *
		 * ** ****************************************************************** */
		protected function _setOptions() {
			$this->set( 'struct', $this->Structurereferente->listOptions() );
			$this->set( 'options', $this->PersonneReferent->allEnumLists() );
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
		public function filelink( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$fichiers = array( );
			$personne_referent = $this->PersonneReferent->find(
					'first', array(
				'conditions' => array(
					'PersonneReferent.id' => $id
				),
				'contain' => array(
					'Fichiermodule' => array(
						'fields' => array( 'name', 'id', 'created', 'modified' )
					)
				)
					)
			);

			$personne_id = $personne_referent['PersonneReferent']['personne_id'];
			$dossier_id = $this->PersonneReferent->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->PersonneReferent->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->PersonneReferent->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {

				$saved = $this->PersonneReferent->updateAll(
						array( 'PersonneReferent.haspiecejointe' => '\''.$this->data['PersonneReferent']['haspiecejointe'].'\'' ), array(
					'"PersonneReferent"."personne_id"' => $personne_id,
					'"PersonneReferent"."id"' => $id
						)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->data, "PersonneReferent.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->PersonneReferent->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
// 					$this->redirect( array(  'controller' => 'personnes_referents','action' => 'index', $personne_id ) );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->PersonneReferent->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/personnes_referents/index/'.$personne_id );
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'personne_referent' ) );
		}

		/**		 * *******************************************************************
		 *
		 * ** ****************************************************************** */
		public function index( $personne_id = null ) {
			$nbrPersonnes = $this->PersonneReferent->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'contain' => false ) );
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			$personnes_referents = $this->PersonneReferent->find(
					'all', array(
				'conditions' => array(
					'PersonneReferent.personne_id' => $personne_id
				),
				'contain' => array(
					'Fichiermodule',
					'Referent',
					'Structurereferente'
				),
				'order' => array(
					'PersonneReferent.dddesignation DESC'
				)
					)
			);
// debug( $personnes_referents);
			$this->set( 'personnes_referents', $personnes_referents );

			foreach( $personnes_referents as $index => $date ) {
				$pers = $this->PersonneReferent->find( 'first', array( 'conditions' => array( 'PersonneReferent.personne_id' => $personne_id ), 'order' => 'PersonneReferent.dfdesignation DESC' ) );
				$pers['PersonneReferent']['dernier'] = $pers['PersonneReferent'];

				$dfdesignation = Set::extract( $pers, '/PersonneReferent/dernier/dfdesignation' );
				$this->set( 'pers', $pers );
				$this->set( 'dfdesignation', $dfdesignation );
			}
			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
		}

		/**		 * *******************************************************************
		 *
		 * ** ****************************************************************** */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**		 * *******************************************************************
		 *
		 * ** ****************************************************************** */
		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$personne_id = $id;
				$dossier_id = $this->Personne->dossierId( $personne_id );
			}
			else if( $this->action == 'edit' ) {
				$personne_referent_id = $id;
				$qd_personne_referent = array(
					'conditions' => array(
						'PersonneReferent.id' => $personne_referent_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$personne_referent = $this->PersonneReferent->find( 'first', $qd_personne_referent );

				$this->assert( !empty( $personne_referent ), 'invalidParameter' );

				$qd_referent = array(
					'conditions' => array(
						'Referent.id' => $personne_referent['PersonneReferent']['referent_id']
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$referent = $this->Referent->find( 'first', $qd_referent );

				$this->assert( !empty( $referent ), 'invalidParameter' );
				$this->set( 'referent', $referent );

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

			$qd_orientstruct = array(
					'conditions' => array(
						'Orientstruct.personne_id' =>  $personne_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$orientstruct = $this->Orientstruct->find( 'first', $qd_orientstruct );


			$this->set( 'orientstruct', $orientstruct );

			if( !empty( $orientstruct ) ) {
				$sr = Set::classicExtract( $orientstruct, 'Orientstruct.structurereferente_id' );
				$this->set( 'sr', $sr );
			}

			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->PersonneReferent->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			if( !empty( $this->data ) ) {
				if( $this->PersonneReferent->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					if( $this->PersonneReferent->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
						$this->Jetons->release( $dossier_id );
						$this->PersonneReferent->commit(); /// FIXE
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'personnes_referents', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else {
				if( $this->action == 'edit' ) {
					$this->data = $personne_referent;
				}
			}
			$this->PersonneReferent->commit();
			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/personnes_referents/index/'.$personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}

		public function cloturer( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$personne_referent = $this->PersonneReferent->find(
					'first', array(
				'fields' => array(
					'PersonneReferent.id',
					'PersonneReferent.personne_id',
					'PersonneReferent.referent_id',
					'PersonneReferent.structurereferente_id',
					'PersonneReferent.dddesignation',
					'PersonneReferent.dfdesignation',
				),
				'conditions' => array(
					'PersonneReferent.id' => $id
				),
				'contain' => false
					)
			);
			$this->assert( !empty( $personne_referent ), 'invalidParameter' );
			$this->set( 'personne_referent', $personne_referent );

			$this->set( 'personne_id', $personne_referent['PersonneReferent']['personne_id'] );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_referent['PersonneReferent']['personne_id'] ) );
			}

			if( !empty( $this->data ) ) {

				if( $this->PersonneReferent->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					if( $this->PersonneReferent->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
						$this->PersonneReferent->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'personnes_referents', 'action' => 'index', $personne_referent['PersonneReferent']['personne_id'] ) );
					}
					else {
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
						$this->PersonneReferent->rollback();
					}
				}
			}
			else {
				$this->data = $personne_referent;
			}

			$this->_setOptions();
			$this->viewVars['options']['PersonneReferent']['referent_id'] = $this->PersonneReferent->Referent->find( 'list' );
			$this->viewVars['options']['PersonneReferent']['structurereferente_id'] = $this->PersonneReferent->Structurereferente->find( 'list' );
			$this->set( 'urlmenu', '/personnes_referents/index/'.$personne_referent['PersonneReferent']['personne_id'] );
		}

	}
?>