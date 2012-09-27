<?php
	/**
	 * Code source de la classe OrientsstructsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe OrientsstructsController permet de gérer les orientations.
	 *
	 * @package app.controllers
	 */
	class OrientsstructsController extends AppController
	{
		public $name = 'Orientsstructs';

		public $uses = array( 'Orientstruct', 'Option', 'Dossier', 'Foyer', 'Adresse', 'Adressefoyer', 'Personne', 'Typeorient', 'Structurereferente', 'Pdf', 'Referent' );

		public $helpers = array( 'Default', 'Default2', 'Fileuploader' );

		public $components = array( 'Gedooo.Gedooo', 'Fileuploader', 'Jetons2' );

		public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		protected function _setOptions() {
			$this->set( 'pays', $this->Option->pays() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );
			$this->set( 'referents', $this->Referent->listOptions() );
			$this->set( 'typesorients', $this->Typeorient->listOptions() );
			$this->set( 'structs', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );

			$options = array( );
			$options = $this->Orientstruct->allEnumLists();
			$this->set( compact( 'options' ) );

			//Ajout des structures et référents orientants
			$this->set( 'refsorientants', $this->Referent->listOptions() );
			$this->set( 'structsorientantes', $this->Structurereferente->listOptions( array( 'orientation' => 'O' ) ) );
		}

		/**
		 *
		 */
		public function beforeFilter() {
			$return = parent::beforeFilter();

			$this->set( 'options', $this->Orientstruct->enums() );

			return $return;
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
			$orientstruct = $this->Orientstruct->find(
					'first', array(
				'conditions' => array(
					'Orientstruct.id' => $id
				),
				'contain' => array(
					'Fichiermodule' => array(
						'fields' => array( 'name', 'id', 'created', 'modified' )
					)
				)
					)
			);

			$personne_id = $orientstruct['Orientstruct']['personne_id'];
			$dossier_id = $this->Orientstruct->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {
				$this->Orientstruct->begin();

				$saved = $this->Orientstruct->updateAll(
						array( 'Orientstruct.haspiecejointe' => '\''.$this->data['Orientstruct']['haspiecejointe'].'\'' ), array(
					'"Orientstruct"."personne_id"' => $personne_id,
					'"Orientstruct"."id"' => $id
						)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->data, "Orientstruct.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Orientstruct->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
// 					$this->redirect( array(  'controller' => 'orientsstructs','action' => 'index', $personne_id ) );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Orientstruct->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'orientstruct' ) );
			$this->_setOptions();
		}

		/**
		 * Liste des orientations de l'allocataire.
		 *
		 * @param integer $personne_id L'id technique de l'allocataire.
		 */
		public function index( $personne_id = null ) {
			$this->assert( valid_int( $personne_id ), 'invalidParameter' );

			$dossier_id = $this->Orientstruct->Personne->dossierId( $personne_id );

			$querydata = $this->Orientstruct->qdIndex( $personne_id );
			$orientstructs = $this->Orientstruct->find( 'all', $querydata );

			// Ajout d'informations en fonction du CG.
			$en_procedure_relance = false;
			$force_edit = false;
			$rgorient_max = $this->Orientstruct->rgorientMax( $personne_id );

			// L'entrée la plus récente est-elle non liée à d'autres tables, et donc suppressible ?
			$last_orientstruct_suppressible = false;
			if( !empty( $orientstructs ) ) {
				App::import( 'Behaviors', array( 'Occurences' ) );
				$this->Orientstruct->Behaviors->attach( 'Occurences' );

				$occurences = $this->Orientstruct->occurencesExists( array( 'Orientstruct.id' => $orientstructs[0]['Orientstruct']['id'] ), array( 'Fichiermodule', 'Nonoriente66' ) );
				$last_orientstruct_suppressible = !$occurences[$orientstructs[0]['Orientstruct']['id']];

				$last_orientstruct_suppressible = (
					$this->Orientstruct->Personne->Dossierep->find(
						'count',
						$this->Orientstruct->Personne->Dossierep->qdDossiersepsOuverts( $personne_id )
					) == 0
				) && $last_orientstruct_suppressible;
			}

			if( Configure::read( 'Cg.departement' ) == 58 ) {
				// Nouvelle orientation en cours de validation par la commission d'orientation et de validation ?
				$qdEnCours = $this->Orientstruct->Personne->Dossiercov58->Propoorientationcov58->qdEnCours( $personne_id );
				$propoorientationcov58 = $this->Orientstruct->Personne->Dossiercov58->Propoorientationcov58->find(
					'first',
					$qdEnCours
				);
				$this->set( 'propoorientationcov58', $propoorientationcov58 );
				$this->set( 'optionsdossierscovs58', $this->Orientstruct->Personne->Dossiercov58->Passagecov58->enums() );

				// Réorientation en cours de validation par la commission d'orientation et de validation ?
				$qdReorientationEnCours = $this->Orientstruct->Personne->Dossierep->Regressionorientationep58->qdReorientationEnCours( $personne_id );
				$regressionorientaionep58 = $this->Orientstruct->Personne->Dossierep->Regressionorientationep58->find(
					'first',
					$qdReorientationEnCours
				);

				if( $rgorient_max <= 1 ) {
					$ajout_possible = $this->Orientstruct->Personne->Dossiercov58->ajoutPossible( $personne_id ) && $this->Orientstruct->ajoutPossible( $personne_id );

					$qdDossiersNonFinalises = $this->Orientstruct->Personne->Dossiercov58->Propoorientationcov58->qdDossiersNonFinalises( $personne_id );
					$nbdossiersnonfinalisescovs = $this->Orientstruct->Personne->Dossiercov58->find(
						'count',
						$qdDossiersNonFinalises
					);

					$this->set( 'nbdossiersnonfinalisescovs', $nbdossiersnonfinalisescovs );
				}
				else {
					$ajout_possible = $this->Orientstruct->ajoutPossible( $personne_id );
				}

				$this->set( compact( 'regressionorientaionep58' ) );
				$this->set( 'optionsdossierseps', $this->Orientstruct->Personne->Dossierep->Passagecommissionep->enums() );
			}
			else if( Configure::read( 'Cg.departement' ) == 66 ) {
				$ajout_possible = $this->Orientstruct->ajoutPossible( $personne_id );
			}
			else if( Configure::read( 'Cg.departement' ) == 93 ) {
				$ajout_possible = $this->Orientstruct->ajoutPossible( $personne_id );
				$force_edit = ( $rgorient_max == 0 );

				$en_procedure_relance = $this->Orientstruct->Nonrespectsanctionep93->enProcedureRelance( $personne_id );

				// La dernière réorientation en cours
				$qdReorientationEnCours = $this->Orientstruct->Reorientationep93->qdReorientationEnCours( $personne_id );
				$reorientationep93 = $this->Orientstruct->Reorientationep93->find( 'first', $qdReorientationEnCours );

				$this->set( 'reorientationep93', $reorientationep93 );
				$this->set( 'optionsdossierseps', $this->Orientstruct->Reorientationep93->Dossierep->Passagecommissionep->enums() );
			}

			$this->_setOptions();
			$this->set( 'last_orientstruct_suppressible', $last_orientstruct_suppressible );
			$this->set( 'orientstructs', $orientstructs );
			$this->set( 'en_procedure_relance', $en_procedure_relance );
			$this->set( 'personne_id', $personne_id );
			$this->set( 'droitsouverts', $this->Dossier->Situationdossierrsa->droitsOuverts( $dossier_id ) );
			$this->set( 'ajout_possible', $ajout_possible );
			$this->set( 'orientstructs', $orientstructs );
			$this->set( 'force_edit', $force_edit );
			$this->set( 'rgorient_max', $rgorient_max );
			$this->set( 'last_orientstruct_id', @$orientstructs[0]['Orientstruct']['id'] );
		}

		/**
		 * Formulaire d'ajout d'une orientation à un allocataire.
		 *
		 * @param integer $personne_id
		 */
		public function add( $personne_id = null ) {
			$this->assert( valid_int( $personne_id ), 'invalidParameter' );

			// Retour à l'index s'il n'est pas possible d'ajouter une orientation
			if( !$this->Orientstruct->ajoutPossible( $personne_id ) ) {
				$this->Session->setFlash( 'Impossible d\'ajouter une orientation pour cette personne.', 'flash/error' );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			$dossier_id = $this->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			// Récupération du dossier afin de précharger la date de demande RSA
			$qd_dossier = array(
				'conditions' => array(
					'Dossier.id' => $dossier_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$dossier = $this->Orientstruct->Personne->Foyer->Dossier->find( 'first', $qd_dossier );
			$this->set( compact( 'dossier' ) );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {
				$this->data['Orientstruct']['user_id'] = $this->Session->read( 'Auth.User.id' );

				$this->Orientstruct->set( $this->data );

				$validates = $this->Orientstruct->validates();

				if( $validates ) {
					$this->Orientstruct->begin();

					$saved = true;

					if( $this->Orientstruct->isRegression( $personne_id, $this->data['Orientstruct']['typeorient_id'] ) && Configure::read( 'Cg.departement' ) == 58 ) {
						$theme = 'Regressionorientationep'.Configure::read( 'Cg.departement' );

						$dossierep = array(
							'Dossierep' => array(
								'personne_id' => $personne_id,
								'themeep' => Inflector::tableize( $theme )
							)
						);

						$saved = $this->Orientstruct->Personne->Dossierep->save( $dossierep ) && $saved;

						$regressionorientationep[$theme] = $this->data['Orientstruct'];
						$regressionorientationep[$theme]['personne_id'] = $personne_id;
						$regressionorientationep[$theme]['dossierep_id'] = $this->Orientstruct->Personne->Dossierep->id;

						if( isset( $regressionorientationep[$theme]['referent_id'] ) && !empty( $regressionorientationep[$theme]['referent_id'] ) ) {
							list( $structurereferente_id, $referent_id ) = explode( '_', $regressionorientationep[$theme]['referent_id'] );
							$regressionorientationep[$theme]['structurereferente_id'] = $structurereferente_id;
							$regressionorientationep[$theme]['referent_id'] = $referent_id;
						}
						$regressionorientationep[$theme]['datedemande'] = $regressionorientationep[$theme]['date_propo'];

						$saved = $this->Orientstruct->Personne->Dossierep->{$theme}->save( $regressionorientationep ) && $saved;
					}
					else {
						// Correction: si la personne n'a pas encore d'entrée dans calculdroitsrsa
						$this->data['Calculdroitrsa']['personne_id'] = $personne_id;
						$this->data['Orientstruct']['personne_id'] = $personne_id;
						$this->data['Orientstruct']['valid_cg'] = true;
						if( Configure::read( 'Cg.departement' ) != 66 ) {
							$this->data['Orientstruct']['date_propo'] = date( 'Y-m-d' );
							$this->data['Orientstruct']['date_valid'] = date( 'Y-m-d' );
						}
						$this->data['Orientstruct']['statut_orient'] = 'Orienté';

						$saved = $this->Orientstruct->Personne->Calculdroitrsa->save( $this->data );
						$saved = $this->Orientstruct->save( $this->data['Orientstruct'] ) && $saved;
					}

					if( /* Configure::read( 'Cg.departement' ) == 66 && */$saved && !empty( $this->data['Orientstruct']['referent_id'] ) ) {
						$saved = $this->Orientstruct->Referent->PersonneReferent->referentParOrientstruct( $this->data ) && $saved;
					}

					if( $saved ) {
						$this->Orientstruct->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Orientstruct->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$this->Orientstruct->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				$qd_personne = array(
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'fields' => null,
					'order' => null,
					'contain' => array( 'Calculdroitrsa' )
				);
				$personne = $this->Personne->find( 'first', $qd_personne );

				$this->data['Calculdroitrsa'] = $personne['Calculdroitrsa'];
			}

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		 * Formulaire de modification d'une orientation d'un allocataire.
		 *
		 * @param integer $orientstruct_id
		 */
		public function edit( $orientstruct_id = null ) {
			$this->assert( valid_int( $orientstruct_id ), 'invalidParameter' );

			$orientstruct = $this->Orientstruct->find(
					'first', array(
				'conditions' => array(
					'Orientstruct.id' => $orientstruct_id
				),
				'contain' => array(
					'Personne' => array(
						'Calculdroitrsa'
					)
				)
					)
			);
			$this->assert( !empty( $orientstruct ), 'invalidParameter' );

			// Retour à l'index si on essaie de modifier une autre orientation que la dernière
			if( !empty( $orientstruct['Orientstruct']['date_valid'] ) && $orientstruct['Orientstruct']['statut_orient'] == 'Orienté' && $orientstruct['Orientstruct']['rgorient'] != $this->Orientstruct->rgorientMax( $orientstruct['Orientstruct']['personne_id'] ) ) {
				$this->Session->setFlash( 'Impossible de modifier une autre orientation que la plus récente.', 'flash/error' );
				$this->redirect( array( 'action' => 'index', $orientstruct['Orientstruct']['personne_id'] ) );
			}

			$dossier_id = $this->Orientstruct->dossierId( $orientstruct_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			// Récupération du dossier afin de précharger la date de demande RSA
			$qd_dossier = array(
				'conditions' => array(
					'Dossier.id' => $dossier_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$dossier = $this->Orientstruct->Personne->Foyer->Dossier->find( 'first', $qd_dossier );

			$this->set( compact( 'dossier' ) );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$orientstruct_id = $this->Orientstruct->field( 'personne_id', array( 'id' => $orientstruct_id ) );
				$this->redirect( array( 'action' => 'index', $orientstruct_id ) );
			}

			// Essai de sauvegarde
			if( !empty( $this->data ) ) {
				$this->Orientstruct->begin();

				$this->data['Orientstruct']['user_id'] = $this->Session->read( 'Auth.User.id' );

				// Correction: si la personne n'a pas encore d'entrée dans calculdroitsrsa
				$this->data['Calculdroitrsa']['personne_id'] = $orientstruct['Orientstruct']['personne_id'];
				$this->data['Orientstruct']['personne_id'] = $orientstruct['Orientstruct']['personne_id'];

				$this->Orientstruct->set( $this->data );
				$this->Orientstruct->Personne->Calculdroitrsa->set( $this->data );
				$valid = $this->Orientstruct->Personne->Calculdroitrsa->validates();
				$valid = $this->Orientstruct->validates() && $valid;

				if( $valid ) {
					$saved = true;
					$saved = $this->Orientstruct->Personne->Calculdroitrsa->save( $this->data ) && $saved;
					$saved = $this->Orientstruct->save( $this->data ) && $saved;

					if( /* Configure::read( 'Cg.departement' ) == 66 && */ $saved && !empty( $this->data['Orientstruct']['referent_id'] ) ) {
						$saved = $this->Orientstruct->Referent->PersonneReferent->referentParOrientstruct( $this->data ) && $saved;
					}

					if( $saved ) {
						$this->Orientstruct->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $orientstruct['Orientstruct']['personne_id'] ) );
					}
					else {
						$this->Orientstruct->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			// Afficage des données
			else {
				// Assignation au formulaire*
				$this->data = Set::merge( array( 'Orientstruct' => $orientstruct['Orientstruct'] ), array( 'Calculdroitrsa' => $orientstruct['Personne']['Calculdroitrsa'] ) );
			}

			$this->_setOptions();
			$this->set( 'personne_id', $orientstruct['Orientstruct']['personne_id'] );
			$this->set( 'urlmenu', '/orientsstructs/index/'.$orientstruct['Orientstruct']['personne_id'] );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		 * Impression d'une orientation simple.
		 *
		 * Méthode appelée depuis les vues:
		 * 	- cohortes/orientees
		 * 	- orientsstructs/index
		 *
		 * @param integer $id L'id de l'orientstruct que l'on souhaite imprimer.
		 * @return void
		 */
		public function impression( $id = null ) {
			$pdf = $this->Orientstruct->getStoredPdf( $id, 'date_impression' );
			$pdf = ( isset( $pdf['Pdf']['document'] ) ? $pdf['Pdf']['document'] : null );

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'orientstruct_%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'impression de l\'orientation.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Suppression d'orientation.
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$success = $this->Orientstruct->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( $this->referer() );
		}

		/**
		 * Impression d'une orientation simple.
		 *
		 * Méthode appelée depuis les vues:
		 * 	- cohortes/orientees
		 * 	- orientsstructs/index
		 *
		 * @param integer $id L'id de l'orientstruct que l'on souhaite imprimer.
		 * @return void
		 */
		public function printChangementReferent( $id = null ) {
			$pdf = $this->Orientstruct->getChangementReferentOrientation( $id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'Notification_Changement_Referent_%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la notification.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>