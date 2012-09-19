<?php
	class EntretiensController extends AppController
	{

		public $name = 'Entretiens';
		public $uses = array( 'Entretien', 'Option' );
		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Default2', 'Fileuploader' );
		public $components = array( 'Fileuploader' );
		public $commeDroit = array(
			'view' => 'Entretiens:index',
			'add' => 'Entretiens:edit'
		);
		public $aucunDroit = array( 'ajaxaction', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		/**
		 *
		 */
		protected function _setOptions() {
			$options = array( );
			$optionsdsps = array( );

			$options = $this->Entretien->enums();
			$optionsdsps = $this->Entretien->Personne->Dsp->enums();
			$options = Set::merge( $options, $optionsdsps );

			$options[$this->modelClass]['typerdv_id'] = $this->Entretien->Typerdv->find( 'list' );
			$options[$this->modelClass]['objetentretien_id'] = $this->Entretien->Objetentretien->find( 'list' );
			$options[$this->modelClass]['structurereferente_id'] = $this->Entretien->Structurereferente->listOptions();

			$typerdv = $this->Entretien->Rendezvous->Typerdv->find( 'list' );
			$this->set( compact( 'options', 'typerdv' ) );
		}

        
		/**
		 *   Ajax pour les partenaires fournissant l'action liée à l'entretien
		 */
		public function ajaxaction( $actioncandidat_id = null ) {
			Configure::write( 'debug', 0 );

			$dataActioncandidat_id = Set::extract( $this->data, 'Entretien.actioncandidat_id' );
			$actioncandidat_id = ( empty( $actioncandidat_id ) && !empty( $dataActioncandidat_id ) ? $dataActioncandidat_id : $actioncandidat_id );

			if( !empty( $actioncandidat_id ) ) {
				$this->Entretien->Actioncandidat->forceVirtualFields = true;
				$actioncandidat = $this->Entretien->Actioncandidat->find(
					'first',
                    array(
                        'conditions' => array(
                            'Actioncandidat.id' => $actioncandidat_id
                        ),
                        'contain' => array(
                            'Contactpartenaire' => array(
                                'Partenaire'
                            ),
                            'Fichiermodule'
                        )
                    )
				);

				if( ($actioncandidat['Actioncandidat']['correspondantaction'] == 1) && !empty( $actioncandidat['Actioncandidat']['referent_id'] ) ) {
					$this->ActioncandidatPersonne->Personne->Referent->recursive = -1;
					$referent = $this->ActioncandidatPersonne->Personne->Referent->read( null, $actioncandidat['Actioncandidat']['referent_id'] );
				}
				$this->set( compact( 'actioncandidat', 'referent' ) );
			}
			$this->render( 'ajaxaction', 'ajax' );
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
			$entretien = $this->Entretien->find(
					'first', array(
				'conditions' => array(
					'Entretien.id' => $id
				),
				'contain' => array(
					'Fichiermodule' => array(
						'fields' => array( 'name', 'id', 'created', 'modified' )
					)
				)
					)
			);

			$personne_id = $entretien['Entretien']['personne_id'];
			$dossier_id = $this->Entretien->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Entretien->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Entretien->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {

				$saved = $this->Entretien->updateAll(
						array( 'Entretien.haspiecejointe' => '\''.$this->data['Entretien']['haspiecejointe'].'\'' ), array(
					'"Entretien"."personne_id"' => $personne_id,
					'"Entretien"."id"' => $id
						)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->data, "Entretien.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->Entretien->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
// 					$this->redirect( array(  'controller' => 'entretiens','action' => 'index', $personne_id ) );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Entretien->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'entretien' ) );
			$this->set( 'urlmenu', '/entretiens/index/'.$personne_id );
		}

		/**
		 *
		 */
		function index( $personne_id = null ) {
			// On s'assure que la personne existe
			$this->Entretien->Personne->unbindModelAll();
			$nbrPersonnes = $this->Entretien->Personne->find(
					'count', array(
				'conditions' => array(
					'Personne.id' => $personne_id
				),
				'contain' => false
					)
			);
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			$this->Entretien->forceVirtualFields = true;
			$entretiens = $this->Entretien->find(
					'all', array(
				'fields' => array(
					'Entretien.id',
					'Entretien.personne_id',
					'Entretien.dateentretien',
					'Entretien.arevoirle',
					'Entretien.typeentretien',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					'Objetentretien.name',
				),
				'contain' => array(
					'Structurereferente',
					'Referent',
					'Objetentretien',
					'Fichiermodule'
				),
				'conditions' => array(
					'Entretien.personne_id' => $personne_id
				)
					)
			);
			$this->Entretien->forceVirtualFields = false;

			$this->_setOptions();

			$this->set( compact( 'entretiens', 'nbFichiersLies' ) );
			$this->set( 'personne_id', $personne_id );
		}

		/**
		 *
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 *
		 */
		function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				if( $this->action == 'edit' ) {
					$id = $this->Entretien->field( 'personne_id', array( 'id' => $id ) );
				}
				$this->redirect( array( 'action' => 'index', $id ) );
			}

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$personne_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$entretien_id = $id;
				$qd_entretien = array(
					'conditions' => array(
						'Entretien.id' => $entretien_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$entretien = $this->Entretien->find( 'first', $qd_entretien );

				$this->assert( !empty( $entretien ), 'invalidParameter' );

				$personne_id = $entretien['Entretien']['personne_id'];
			}

			$this->Entretien->begin();

			$dossier_id = $this->Entretien->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Entretien->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			///Récupération de la liste des structures référentes
			$structs = $this->Entretien->Structurereferente->listOptions();
			$this->set( 'structs', $structs );

			///Récupération de la liste des référents
			$referents = $this->Entretien->Referent->listOptions();
			$this->set( 'referents', $referents );
            
            //On affiche les actions inactives en édition mais pas en ajout, 
            // afin de pouvoir gérer les actions n'étant plus prises en compte mais toujours en cours
            $isactive = 'O';
            if( $this->action == 'edit' ){
                $isactive = array( 'O', 'N' );
            }
            $actionsSansFiche = $this->{$this->modelClass}->Actioncandidat->listePourFicheCandidature( null, $isactive, '0' );
            $this->set( 'actionsSansFiche', $actionsSansFiche );

			if( !empty( $this->data ) ) {
				if( isset( $this->data['Entretien']['arevoirle'] ) ) {
					$this->data['Entretien']['arevoirle']['day'] = '01';
				}

				if( $this->Entretien->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					if( $this->Entretien->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

						$this->Jetons->release( $dossier_id );
						$this->Entretien->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'entretiens', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else {
				if( $this->action == 'edit' ) {

					$entretien['Entretien']['referent_id'] = $entretien['Entretien']['structurereferente_id'].'_'.$entretien['Entretien']['referent_id'];

					$rdv_id = Set::classicExtract( $entretien, 'Entretien.rendezvous_id' );
					$qd_rdv = array(
						'conditions' => array(
							'Rendezvous.id' => $rdv_id
						),
						'fields' => null,
						'order' => null,
						'recursive' => -1
					);
					$rdv = $this->Entretien->Personne->Rendezvous->find( 'first', $qd_rdv );

					if( !empty( $rdv ) ) {
						$entretien = Set::merge( $entretien, $rdv );
						$entretien['Rendezvous']['referent_id'] = $entretien['Rendezvous']['structurereferente_id'].'_'.$entretien['Rendezvous']['referent_id'];
					}
					$this->data = $entretien;
				}
			}
			$this->Entretien->commit();

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/entretiens/index/'.$personne_id );

			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		 *
		 */
		public function view( $id = null ) {
			$this->Entretien->forceVirtualFields = true;
			$qd_entretien = array(
				'conditions' => array(
					'Entretien.id' => $id
				),
				'fields' => array(
					'Entretien.personne_id',
					'Entretien.dateentretien',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					'Entretien.typeentretien',
					'Entretien.typerdv_id',
					'Entretien.commentaireentretien'
				),
				'order' => null,
				'joins' => array(
					$this->Entretien->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->Entretien->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
					$this->Entretien->Structurereferente->join( 'Referent', array( 'type' => 'LEFT OUTER' ) )
				),
				'recursive' => -1
			);
			$entretien = $this->Entretien->find( 'first', $qd_entretien );

			$this->Entretien->forceVirtualFields = false;
			$personne_id = Set::classicExtract( $entretien, 'Entretien.personne_id' );

			// Retour à l'entretien en cas de retour
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'entretiens', 'action' => 'index', $personne_id ) );
			}

			$this->_setOptions();
			$this->set( compact( 'entretien' ) );
			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/entretiens/index/'.$personne_id );
		}

		/**
		 *
		 */
		public function delete( $id ) {
			$this->Default->delete( $id );
		}

	}
?>