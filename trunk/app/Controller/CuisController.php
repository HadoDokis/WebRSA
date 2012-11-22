<?php
	/**
	 * Code source de la classe CuisController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe CuisController permet de gérer les CUIs (CG 58, 66 et 93).
	 *
	 * @package app.Controller
	 */
	class CuisController extends AppController
	{
		public $name = 'Cuis';

		public $uses = array( 'Cui', 'Option', 'Departement' );

		public $helpers = array( 'Default', 'Default2', 'Locale', 'Csv', 'Xform', 'Fileuploader' );

		public $components = array(
			'Default',
			'Fileuploader',
			'Gedooo.Gedooo',
			'Jetons2',
		);

		public $aucunDroit = array( 'ajaxfileupload', 'impression', 'fileview', 'download' );
		
		public $commeDroit = array(
			'add' => 'Cuis:edit',
			'view' => 'Cuis:index'
		);

		/**
		 * Envoi des options communes dans les vues.
		 *
		 * @return void
		 */
		protected function _setOptions() {
			$options = array( );
			$options = $this->Cui->allEnumLists();
			$optionsaccompagnement = $this->Cui->Accompagnementcui66->allEnumLists();
			$options = Set::merge( $options, $optionsaccompagnement );

			$secteursactivites = $this->Cui->Personne->Dsp->Libsecactderact66Secteur->find(
					'list', array(
				'contain' => false,
				'order' => array( 'Libsecactderact66Secteur.code' )
					)
			);
			$this->set( 'secteursactivites', $secteursactivites );

			$codesromemetiersdsps66 = $this->Cui->Personne->Dsp->Libderact66Metier->find(
					'all', array(
				'contain' => false,
				'order' => array( 'Libderact66Metier.code' )
					)
			);
			foreach( $codesromemetiersdsps66 as $coderomemetierdsp66 ) {
				$options['Coderomemetierdsp66'][$coderomemetierdsp66['Libderact66Metier']['coderomesecteurdsp66_id'].'_'.$coderomemetierdsp66['Libderact66Metier']['id']] = $coderomemetierdsp66['Libderact66Metier']['code'].'. '.$coderomemetierdsp66['Libderact66Metier']['name'];
			}

			$typevoie = $this->Option->typevoie();
			$options = Set::insert( $options, 'typevoie', $typevoie );




			$typevoie = $this->Option->typevoie();
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'nationalite', $this->Option->nationalite() );

			$dept = $this->Departement->find( 'list', array( 'fields' => array( 'numdep', 'name' ), 'contain' => false ) );
			$this->set( compact( 'dept' ) );



			if( Configure::read( 'CG.cantons' ) ) {
				$Canton = ClassRegistry::init( 'Canton' );
				$this->set( 'cantons', $Canton->selectList() );
			}


			$this->set( 'prestataires', $this->Cui->Referent->listOptions() );
			$this->set( 'referents', $this->Cui->Referent->find( 'list', array( 'recursive' => false ) ) );
			$this->set( 'structs', $this->Cui->Structurereferente->listOptions() );


			$this->set( 'rsaSocle', $this->Option->natpf() );
			$this->set( compact( 'options' ) );
		}

		/**
		 * Paramétrages des CUIs.
		 *
		 * @return void
		 */
		public function indexparams() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}
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
		 * Fonction permettant de visualiser les fichiers chargés dans la vue avant leur envoi sur le serveur
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
		 * Fonction permettant d'accéder à la page pour lier les fichiers au CER
		 */
		public function filelink( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$fichiers = array( );
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

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
                $this->Cui->begin();
				$saved = $this->Cui->updateAll(
					array( 'Cui.haspiecejointe' => '\''.$this->request->data['Cui']['haspiecejointe'].'\'' ),
					array(
						'"Cui"."personne_id"' => $personne_id,
						'"Cui"."id"' => $id
					)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "Cui.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Cui->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
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
		 * Liste des CUIs appartenant à un allocataire donné.
		 *
		 * @param integer $personne_id
		 */
		public function index( $personne_id = null ) {
			$nbrPersonnes = $this->Cui->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			// Précondition: La personne est-elle bien en Rsa Socle ?
			$alerteRsaSocle = $this->Cui->_prepare( $personne_id );
			$this->set( 'alerteRsaSocle', $alerteRsaSocle );

			$cuis = $this->Cui->find(
				'all',
				array(
					'fields' => array_merge(
							$this->Cui->fields(), array(
								$this->Cui->Fichiermodule->sqNbFichiersLies( $this->Cui, 'nb_fichiers_lies' )
							)
					),
					'conditions' => array(
						'Cui.personne_id' => $personne_id
					),
					'recursive' => -1,
					'contain' => false,
                    'order' => array( 'Cui.datecontrat DESC' )
				)
			);


			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( compact( 'cuis' ) );

			$this->render( 'index_cg'.Configure::read( 'Cg.departement' ) );
		}

		/**
		 * Formulaire d'ajout d'un CUI pour un allocataire donné.
		 *
		 * @param integer $personne_id
		 */
		public function add( $personne_id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Formulaire de modification d'un CUI donné.
		 *
		 * @param integer $id
		 */
		public function edit( $id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Traitement du formulaire d'ajout ou de modification de CUI.
		 *
		 * @param inetger $id Correspond à l'id de la Personne en cas d'ajout, à l'id du Cui en cas de modification.
		 */
		protected function _add_edit( $id = null ) {
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
				$qd_cui = array(
					'conditions' => array(
						'Cui.id' => $cui_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$cui = $this->Cui->find( 'first', $qd_cui );

				$this->assert( !empty( $cui ), 'invalidParameter' );
				$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );
				$valueAdressebis = Set::classicExtract( $cui, 'Cui.isadresse2' );
			}

			$dossier_id = $this->Cui->Personne->dossierId( $personne_id );
			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'valueAdressebis', $valueAdressebis );


			$personne = $this->{$this->modelClass}->Personne->detailsApre( $personne_id, $this->Session->read( 'Auth.User.id' ) );

			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );

			// On récupère la valeur du montant rsa perçu au moment de l'enregistrement
			$tDetaildroitrsa = $this->Cui->Personne->Foyer->Dossier->Detaildroitrsa->find(
				'first',
				array(
					'fields' => array(
						'Detaildroitrsa.id',
						'Detaildroitrsa.dossier_id',
					),
					'contain' => array(
						'Detailcalculdroitrsa' => array(
							'fields' => array(
								'Detailcalculdroitrsa.mtrsavers',
								'Detailcalculdroitrsa.dtderrsavers',
								'Detailcalculdroitrsa.natpf',
							),
							'order' => array(
								'Detailcalculdroitrsa.ddnatdro DESC',
							),
							'limit' => 1
						)
					),
					'conditions' => array(
						'Detaildroitrsa.dossier_id' => $dossier_id
					)
				)
			);

			$montantrsapercu = $tDetaildroitrsa['Detailcalculdroitrsa'][0]['mtrsavers'];
			$this->set( compact( 'montantrsapercu' ) );

			$this->set( 'personne', $personne );

			/// Calcul du numéro du contrat d'insertion
			$nbCui = $this->Cui->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );

			if( !empty( $this->request->data ) ) {
				$this->{$this->modelClass}->begin();

				if( $this->action == 'add' ) {
					$this->request->data['Cui']['rangcui'] = $nbCui + 1;
				}

				$this->{$this->modelClass}->create( $this->request->data );
				$success = $this->{$this->modelClass}->save();

				// Nettoyage des Periodes d'immersion
				$keys = array_keys( $this->Cui->Accompagnementcui66->schema() );
				$defaults = array_combine( $keys, array_fill( 0, count( $keys ), null ) );
				unset( $defaults['id'] );
				unset( $defaults['cui_id'] );

				if( !empty( $this->request->data['Accompagnementcui66'] ) ) {
					$this->request->data['Accompagnementcui66'] = Set::merge( $defaults, $this->request->data['Accompagnementcui66'] );
				}

				if( ( $this->request->data['Cui']['secteur'] == 'CAE' ) && !empty( $this->request->data['Accompagnementcui66'] ) ) {
					$Accompagnementcui66 = Set::filter( $this->request->data['Accompagnementcui66'] );
					if( !empty( $Accompagnementcui66 ) ) {
						$this->{$this->modelClass}->Accompagnementcui66->create( $this->request->data );
						if( $this->action == 'add' ) {
							$this->{$this->modelClass}->Accompagnementcui66->set( 'cui_id', $this->{$this->modelClass}->getLastInsertID() );
						}
						else if( $this->action == 'edit' ) {
							$this->{$this->modelClass}->Accompagnementcui66->set( 'cui_id', Set::classicExtract( $this->request->data, 'Cui.id' ) );
						}
						$success = $this->{$this->modelClass}->Accompagnementcui66->save() && $success;
					}
				}

				if( $success ) {
					$this->{$this->modelClass}->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->{$this->modelClass}->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				if( $this->action == 'edit' ) {
					$this->request->data = $cui;
					if( !empty( $this->request->data['Accompagnementcui66'] ) ) {
						$this->request->data['Accompagnementcui66'] = $this->request->data['Accompagnementcui66'][0];
					}
					$nbCui = $cui['Cui']['rangcui'];
				}
			}

			$this->_setOptions();
			$this->set( 'nbCui', $nbCui );
			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			$this->render( 'add_edit' );
		}

		/**
		 * Formulaire de validation d'un CUI.
		 *
		 * @param integer $cui_id
		 */
		public function valider( $cui_id = null ) {
			$qd_cui = array(
				'conditions' => array(
					'Cui.id' => $cui_id
				)
			);
			$cui = $this->Cui->find( 'first', $qd_cui );
			$this->assert( !empty( $cui ), 'invalidParameter' );

			$this->set( 'personne_id', $cui['Cui']['personne_id'] );

			$dossier_id = $this->Cui->dossierId( $cui_id );
			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $cui['Cui']['personne_id'] ) );
			}

			if( !empty( $this->request->data ) ) {
				if( $this->Cui->saveAll( $this->request->data ) ) {
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $cui['Cui']['personne_id'] ) );
				}
			}
			else {
				$this->request->data = $cui;
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

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'cui_%d_%d.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier de contrat unique d\'engagement.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Tentative de suppression d'un CUI.
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->Default->delete( $id );
		}

		/**
		 * Visualisation d'un CUI.
		 *
		 * @param integer $id
		 */
		public function view( $id ) {
			$this->_setOptions();
			$this->Default->view( $id );
		}

        /**
         * Annulation du CUI
         *
         * @param integer $id
         */
        public function cancel( $id ) {
			$qd_cui = array(
				'conditions' => array(
					$this->modelClass.'.id' => $id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$cui = $this->{$this->modelClass}->find( 'first', $qd_cui );

			$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );
			$this->set( 'personne_id', $personne_id );

			$dossier_id = $this->Cui->dossierId( $id );
			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Cui->begin();

				$saved = $this->Cui->save( $this->request->data );
				$saved = $this->{$this->modelClass}->updateAll(
					array( 'Cui.positioncui66' => '\'annule\'' ),
					array(
						'"Cui"."personne_id"' => $cui['Cui']['personne_id'],
						'"Cui"."id"' => $cui['Cui']['id']
					)
				) && $saved;

				if( $saved ) {
					$this->Cui->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->cui->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/erreur' );
				}
			}
			else {
				$this->request->data = $cui;
			}
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
		}
	}
?>