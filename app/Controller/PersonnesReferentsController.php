<?php
	/**
	 * Code source de la classe PersonnesReferentsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe PersonnesReferentsController permet la gestion des référents du parcours au niveau du dossier
	 * de l'allocataire.
	 *
	 * @package app.Controller
	 */
	class PersonnesReferentsController extends AppController
	{
		public $name = 'PersonnesReferents';

		public $uses = array( 'PersonneReferent', 'Option' );

		public $helpers = array( 'Locale', 'Xform', 'Fileuploader', 'Default2' );

		public $components = array( 'Fileuploader', 'Jetons2', 'DossiersMenus', 'InsertionsAllocataires' );

		public $aucunDroit = array( 'ajaxreferent', 'ajaxreffonct', 'ajaxperm', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'ajaxfiledelete' => 'delete',
			'ajaxfileupload' => 'update',
			'cloturer' => 'update',
			'download' => 'read',
			'edit' => 'update',
			'filelink' => 'read',
			'fileview' => 'read',
			'index' => 'read',
		);

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
		 * Fonction permettant d'accéder à la page pour lier les fichiers à un enregistrement.
		 *
		 * @param integer $id
		 */
		public function filelink( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$fichiers = array( );
			$personne_referent = $this->PersonneReferent->find(
				'first',
				array(
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
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$dossier_id = $this->PersonneReferent->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->PersonneReferent->begin();

				$saved = $this->PersonneReferent->updateAll(
					array( 'PersonneReferent.haspiecejointe' => '\''.$this->request->data['PersonneReferent']['haspiecejointe'].'\'' ),
					array(
						'"PersonneReferent"."personne_id"' => $personne_id,
						'"PersonneReferent"."id"' => $id
					)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "PersonneReferent.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->PersonneReferent->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->PersonneReferent->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->set( 'options', $this->PersonneReferent->allEnumLists() );
			$this->set( 'urlmenu', '/personnes_referents/index/'.$personne_id );
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'personne_referent' ) );
		}

		/**
		 * Liste des référents du parcours de l'alocataire.
		 *
		 * @param integer $personne_id L'id technique de l'allocataire.
		 */
		public function index( $personne_id = null ) {
			$this->PersonneReferent->Personne->id = $personne_id;
			if( !$this->PersonneReferent->Personne->exists() ) {
				$this->cakeError( 'invalidParameter' );
			}

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$personnes_referents = $this->PersonneReferent->find(
				'all',
				array(
					'fields' => array_merge(
						$this->PersonneReferent->fields(),
						$this->PersonneReferent->Referent->fields(),
						$this->PersonneReferent->Referent->Structurereferente->fields(),
						array(
							$this->PersonneReferent->Fichiermodule->sqNbFichiersLies( $this->PersonneReferent, 'nombre' )
						)
					),
					'conditions' => array(
						'PersonneReferent.personne_id' => $personne_id
					),
					'contain' => array(
						'Referent',
						'Structurereferente'
					),
					'order' => array(
						'PersonneReferent.dddesignation DESC',
						'PersonneReferent.id DESC',
					)
				)
			);

			// Existe-t'il un référent non clôturé ?
			$dfdesignations = Set::extract( '/PersonneReferent/dfdesignation', $personnes_referents );
			$ajoutPossible = ( count( $dfdesignations ) == count( Hash::filter( $dfdesignations ) ) );

			$this->set( 'personnes_referents', $personnes_referents );
			$this->set( 'personne_id', $personne_id );
			$this->set( 'ajoutPossible', $ajoutPossible );
		}

		/**
		 * Formulaire d'ajout d'un référent du parcours.
		 *
		 * @param integer $id L'id technique de l'allocataire
		 * @return void
		 */
		public function add( $id = null ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Formulaire de modification d'un référent du parcours.
		 *
		 * @param integer $id L'id technique du référent du parcours
		 * @return void
		 */
		public function edit( $id = null ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Formulaire d'ajout / de modification d'un référent du parcours.
		 *
		 * @param integer $id Si c'est un ajout, il s'agit de l'id technique de l'allocataire, sinon de celui de la
		 *	table personnes_referents
		 * @return void
		 */
		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$personne_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$personne_referent = $this->PersonneReferent->find(
					'first',
					array(
						'conditions' => array(
							'PersonneReferent.id' => $id
						),
						'contain' => array(
							'Referent'
						),
					)
				);

				$this->assert( !empty( $personne_referent ), 'invalidParameter' );

				$personne_id = $personne_referent['PersonneReferent']['personne_id'];
			}

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			// Tentative d'obtention d'un jeton sur le dossier
			$dossier_id = $this->PersonneReferent->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// Tentative de sauvagrde
			if( !empty( $this->request->data ) ) {
				$this->PersonneReferent->begin();

				if( $this->PersonneReferent->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
					$this->PersonneReferent->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'personnes_referents', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->PersonneReferent->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit' ) {
				$personne_referent['PersonneReferent']['structurereferente_id'] = $personne_referent['Referent']['structurereferente_id'];
				$personne_referent['PersonneReferent']['referent_id'] = $personne_referent['Referent']['structurereferente_id'].'_'.$personne_referent['PersonneReferent']['referent_id'];
				$this->request->data = $personne_referent;
			}
			// Sinon, on va pré-remplir la structure référente à partir de celle de la dernièère orientation
			else if( $this->action == 'add' ) {
				$orientstruct = $this->PersonneReferent->Personne->Orientstruct->find(
					'first',
					array(
						'fields' => array(
							'Orientstruct.structurereferente_id'
						),
						'conditions' => array(
							'Orientstruct.personne_id' =>  $personne_id,
							'Orientstruct.statut_orient' => 'Orienté',
						),
						'order' => array( 'Orientstruct.date_valid DESC' ),
						'recursive' => -1
					)
				);

				if( !empty( $orientstruct ) ) {
					$this->request->data = array(
						'PersonneReferent' => array(
							'structurereferente_id' => $orientstruct['Orientstruct']['structurereferente_id']
						)
					);
				}
			}

			// Cache géré dans les modèles
			$options = array(
				'referents' => $this->set( 'referents', $this->PersonneReferent->Referent->listOptions() ),
				'structuresreferentes' => $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) )
			);
			$this->set( 'options', $options );

			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/personnes_referents/index/'.$personne_id );
			$this->render( 'add_edit' );
		}

		/**
		 * Formulaire de clôture d'un référent du parcours.
		 *
		 * @param integer $id L'id technique de l'enregistrement dans la table personnes_referents
		 * @return void
		 */
		public function cloturer( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$personne_referent = $this->PersonneReferent->find(
				'first',
				array(
					'conditions' => array(
						'PersonneReferent.id' => $id
					),
					'contain' => array(
						'Referent'
					)
				)
			);
			$this->assert( !empty( $personne_referent ), 'invalidParameter' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_referent['PersonneReferent']['personne_id'] ) ) );

			$dossier_id = $this->PersonneReferent->Personne->dossierId( $personne_referent['PersonneReferent']['personne_id'] );

			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_referent['PersonneReferent']['personne_id'] ) );
			}

			// Tentative d'enregistrement du formulaire
			if( !empty( $this->request->data ) ) {
				$this->PersonneReferent->begin();

				// Ajout d'une règle de validation permettant de vérifier que la date de fin de
				// désignation est bien renseignée
				$this->PersonneReferent->validate['dfdesignation'] = array( 'rule' => array( 'notEmpty' ), 'message' => __( 'Validate::notEmpty' ) ) + $this->PersonneReferent->validate['dfdesignation'];

				$this->PersonneReferent->create( $this->request->data );
				if( $this->PersonneReferent->save() ) {
					$this->PersonneReferent->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'personnes_referents', 'action' => 'index', $personne_referent['PersonneReferent']['personne_id'] ) );
				}
				else {
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					$this->PersonneReferent->rollback();
				}
			}
			else {
				$this->request->data = $personne_referent;
			}

			// Cache géré dans les modèles
			$options = array(
				'PersonneReferent' => array(
					'referent_id' => $this->set( 'referents', $this->PersonneReferent->Referent->listOptions() ),
					'structurereferente_id' => $this->PersonneReferent->Referent->Structurereferente->listOptions()
				)
			);
			$this->set( 'options', $options );

			// On s'assure de toujours avoir ces valeurs, qui ne changent pas (champs de formulaire désactivés)
			$personne_referent['PersonneReferent']['structurereferente_id'] = $personne_referent['Referent']['structurereferente_id'];
			$personne_referent['PersonneReferent']['referent_id'] = $personne_referent['Referent']['structurereferente_id'].'_'.$personne_referent['PersonneReferent']['referent_id'];
			$this->set( 'personne_referent', $personne_referent );

			$this->set( 'personne_id', $personne_referent['PersonneReferent']['personne_id'] );
			$this->set( 'urlmenu', '/personnes_referents/index/'.$personne_referent['PersonneReferent']['personne_id'] );
		}
	}
?>