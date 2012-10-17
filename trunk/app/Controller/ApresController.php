<?php
	/**
	 * Code source de la classe ApresController.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe ApresController permet de lister, voir, ajouter, supprimer, ...  des APREs (CG 93).
	 *
	 * @package app.controllers
	 */
	class ApresController extends AppController
	{

		public $name = 'Apres';
		public $uses = array( 'Apre', 'Histoaprecomplementaire', 'Dossier', 'Option', 'Personne', 'ApreComiteapre', 'Prestation', 'Dsp', 'Formpermfimo', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert', 'Contratinsertion', 'Relanceapre', 'Tiersprestataireapre', 'Structurereferente', 'Referent', 'Foyer' );
		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml', 'Fileuploader', 'Default2' );
		public $components = array( 'Fileuploader', 'Jetons2' );
		public $commeDroit = array(
			'view' => 'Apres:index',
			'add' => 'Apres:edit'
		);
		public $aucunDroit = array( 'ajaxstruct', 'ajaxref', 'ajaxtierspresta', 'ajaxtiersprestaformqualif', 'ajaxtiersprestaformpermfimo', 'ajaxtiersprestaactprof', 'ajaxtiersprestapermisb', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		/**
		 * Méthode commune d'envoi des options dans les vues.
		 *
		 * @return void
		 */
		protected function _setOptions() {
			$options = $this->Apre->allEnumLists();
			$this->set( 'options', $options );
			$optionsacts = $this->Actprof->allEnumLists();
			$this->set( 'optionsacts', $optionsacts );
			$optionsdsps = $this->Dsp->allEnumLists();
			$this->set( 'optionsdsps', $optionsdsps );
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
			$this->set( 'typeservice', ClassRegistry::init( 'Serviceinstructeur' )->find( 'first' ) );

			$optionsaprecomite = $this->ApreComiteapre->allEnumLists();
			$this->set( 'optionsaprecomite', $optionsaprecomite );

			/// Pièces liées à l'APRE
			$piecesapre = $this->Apre->Pieceapre->find( 'list' );
			$this->set( 'piecesapre', $piecesapre );

			///Tiers prestataire présent dans la table paramétrage
			$tiersPrestataire = $this->Tiersprestataireapre->find( 'list' );
			$this->set( 'tiersPrestataire', $tiersPrestataire );
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
		 * Liste des fichiers liés pour une APRE donnée.
		 *
		 * @param integer $id L'id de l'APRE à laquelle sont liés les fichiers.
		 * @return void
		 */
		public function filelink( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$fichiers = array( );
			$apre = $this->{$this->modelClass}->find(
				'first',
				array(
					'conditions' => array(
						"{$this->modelClass}.id" => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);

			$personne_id = $apre[$this->modelClass]['personne_id'];
			$dossier_id = $this->{$this->modelClass}->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->{$this->modelClass}->begin();

				$saved = $this->{$this->modelClass}->updateAll(
					array( "{$this->modelClass}.haspiecejointe" => '\''.$this->request->data[$this->modelClass]['haspiecejointe'].'\'' ),
					array(
						"{$this->modelClass}.personne_id" => $personne_id,
						"{$this->modelClass}.id" => $id
					)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "{$this->modelClass}.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->{$this->modelClass}->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'index', $personne_id ) );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->{$this->modelClass}->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'apre' ) );
		}

		/**
		 * Permet de regrouper l'ensemble des paramétrages pour l'APRE
		 *
		 * @return void
		 */
		public function indexparams() {
			$this->render( 'indexparams_'.Configure::read( 'nom_form_apre_cg' ) );
		}


		/**
		 * Liste des APREs liées à un allocataire.
		 *
		 * @param integer $personne_id L'id technique de l'allocataire pour lequel on veut la liste des APREs
		 * @return void
		 */
		public function index( $personne_id = null ) {
			$qd_personne = array(
				'conditions' => array(
					'Personne.id' => $personne_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$personne = $this->Apre->Personne->find( 'first', $qd_personne );
			$this->assert( !empty( $personne ), 'invalidParameter' );
			$this->set( 'personne', $personne );

			$apres = $this->Apre->find( 'all', array( 'conditions' => array( 'Apre.personne_id' => $personne_id ), 'order' => 'Apre.datedemandeapre DESC' ) );
			$this->set( 'apres', $apres );

			//CG93 - Harry
			if( Configure::read( 'Apre.complementaire.query' ) === true ) {
				$foypers = $personne ['Personne']['foyer_id'];
				$qd_foyerid = array(
					'conditions' => array(
						'Foyer.id' => $foypers
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$foyid = $this->Foyer->find( 'first', $qd_foyerid );
				$dosfoy = $foyid ['Foyer']['dossier_id'];
				$qd_matid = array(
					'conditions' => array(
						'Dossier.id' => $dosfoy
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$matid = $this->Dossier->find( 'first', $qd_matid );
				$matricule = $matid ['Dossier']['matricule'];

				$nomaprecomp = $personne['Personne']['nom'];
				$prenomaprecomp = $personne['Personne']['prenom'];

				$conditions = array( "OR" => array( 'Histoaprecomplementaire.caf' => $matricule, array( 'Histoaprecomplementaire.nom' => $nomaprecomp, 'Histoaprecomplementaire.prenom' => $prenomaprecomp ) ) );
				$aprescomp = $this->Histoaprecomplementaire->find( 'count', array( 'conditions' => $conditions ) );
				$this->set( 'aprescomp', $aprescomp );
			}
			//END CG93 - Harry


			$referents = $this->Referent->find( 'list' );
			$this->set( 'referents', $referents );

			$this->set( 'personne_id', $personne_id );

			///Afin de connaître s'il y avait une APRE forfaitaire pour pouvoir créer une APRE complémentaire

			$apre_forfait = $this->Apre->find(
					'count', array(
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
						'contain' => array(
							'Apre'
						),
						'order' => 'Relanceapre.id DESC'
					)
				);

				if( isset( $relancesapres['0']['Relanceapre']['id'] ) && !empty( $relancesapres['0']['Relanceapre']['id'] ) ) {
					$lastrelance_id = $relancesapres['0']['Relanceapre']['id'];
				}
				else {
					$lastrelance_id = 0;
				}
				$this->set( 'lastrelance_id', $lastrelance_id );
			}
			else {
				$relancesapres = array( );
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
					'all', array(
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
			$this->_setOptions();
			$this->set( 'alerteMontantAides', $alerteMontantAides );
		}

		/**
		 * Ajax pour les coordonnées de la structure référente liée
		 *
		 * @param integer $structurereferente_id
		 * @return void
		 */
		public function ajaxstruct( $structurereferente_id = null ) {
			Configure::write( 'debug', 0 );
			$dataStructurereferente_id = Set::extract( $this->request->data, 'Apre.structurereferente_id' );
			$structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );
			$qd_struct = array(
				'conditions' => array(
					'Structurereferente.id' => $structurereferente_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$struct = $this->Apre->Structurereferente->find( 'first', $qd_struct );


			$this->set( 'struct', $struct );
			$this->render( 'ajaxstruct', 'ajax' );
		}

		/**
		 * Ajax pour les coordonnées du référent APRE
		 *
		 * @param integer $referent_id
		 * @return void
		 */
		public function ajaxref( $referent_id = null ) {
			Configure::write( 'debug', 0 );
			if( !empty( $referent_id ) ) {
				$referent_id = suffix( $referent_id );
			}
			else {
				$referent_id = suffix( Set::extract( $this->request->data, 'Apre.referent_id' ) );
			}

			// INFO: éviter les requêtes erronées du style ... WHERE "Referent"."id" = ''
			$referent = array( );
			if( is_int( $referent_id ) ) {
				$qd_referent = array(
					'conditions' => array(
						'Referent.id' => $referent_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$referent = $this->Apre->Referent->find( 'first', $qd_referent );
			}

			$this->set( 'referent', $referent );
			$this->render( 'ajaxref', 'ajax' );
		}

		/**
		 * Ajax pour les coordonnées du tiers prestataire APRE pour Formqualif
		 *
		 * @param integer $tiersprestataireapre_id
		 * @return void
		 */
		public function ajaxtiersprestaformqualif( $tiersprestataireapre_id = null ) {
			Configure::write( 'debug', 0 );
			$dataTiersprestataireapre_id = Set::extract( $this->request->data, 'Formqualif.tiersprestataireapre_id' );
			$tiersprestataireapre_id = ( empty( $tiersprestataireapre_id ) && !empty( $dataTiersprestataireapre_id ) ? $dataTiersprestataireapre_id : $tiersprestataireapre_id );
			$qd_tiersprestatireapre = array(
				'conditions' => array(
					'Tiersprestataireapre.id' => $tiersprestataireapre_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$tiersprestataireapre = $this->Tiersprestataireapre->find( 'first', $qd_tiersprestatireapre );


			$this->set( 'tiersprestataireapre', $tiersprestataireapre );
			$this->render( 'ajaxtierspresta', 'ajax' );
		}

		/**
		 * Ajax pour les coordonnées du tiers prestataire APRE pour Formpermfimo
		 *
		 * @param integer $tiersprestataireapre_id
		 * @return void
		 */
		public function ajaxtiersprestaformpermfimo( $tiersprestataireapre_id = null ) {
			Configure::write( 'debug', 0 );
			$dataTiersprestataireapre_id = Set::extract( $this->request->data, 'Formpermfimo.tiersprestataireapre_id' );
			$tiersprestataireapre_id = ( empty( $tiersprestataireapre_id ) && !empty( $dataTiersprestataireapre_id ) ? $dataTiersprestataireapre_id : $tiersprestataireapre_id );

			$qd_tiersprestatireapre = array(
				'conditions' => array(
					'Tiersprestataireapre.id' => $tiersprestataireapre_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$tiersprestataireapre = $this->Tiersprestataireapre->find( 'first', $qd_tiersprestatireapre );

			$this->set( 'tiersprestataireapre', $tiersprestataireapre );
			$this->render( 'ajaxtierspresta', 'ajax' );
		}

		/**
		 * Ajax pour les coordonnées du tiers prestataire APRE pour Actprof
		 *
		 * @param integer $tiersprestataireapre_id
		 * @return void
		 */
		public function ajaxtiersprestaactprof( $tiersprestataireapre_id = null ) {
			Configure::write( 'debug', 0 );
			$dataTiersprestataireapre_id = Set::extract( $this->request->data, 'Actprof.tiersprestataireapre_id' );
			$tiersprestataireapre_id = ( empty( $tiersprestataireapre_id ) && !empty( $dataTiersprestataireapre_id ) ? $dataTiersprestataireapre_id : $tiersprestataireapre_id );

			$qd_tiersprestatireapre = array(
				'conditions' => array(
					'Tiersprestataireapre.id' => $tiersprestataireapre_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$tiersprestataireapre = $this->Tiersprestataireapre->find( 'first', $qd_tiersprestatireapre );

			$this->set( 'tiersprestataireapre', $tiersprestataireapre );
			$this->render( 'ajaxtierspresta', 'ajax' );
		}

		/**
		 * Ajax pour les coordonnées du tiers prestataire APRE pour PermisB
		 *
		 * @param integer $tiersprestataireapre_id
		 * @return void
		 */
		public function ajaxtiersprestapermisb( $tiersprestataireapre_id = null ) {
			Configure::write( 'debug', 0 );
			$dataTiersprestataireapre_id = Set::extract( $this->request->data, 'Permisb.tiersprestataireapre_id' );
			$tiersprestataireapre_id = ( empty( $tiersprestataireapre_id ) && !empty( $dataTiersprestataireapre_id ) ? $dataTiersprestataireapre_id : $tiersprestataireapre_id );

			$qd_tiersprestatireapre = array(
				'conditions' => array(
					'Tiersprestataireapre.id' => $tiersprestataireapre_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$tiersprestataireapre = $this->Tiersprestataireapre->find( 'first', $qd_tiersprestatireapre );

			$this->set( 'tiersprestataireapre', $tiersprestataireapre );
			$this->render( 'ajaxtierspresta', 'ajax' );
		}

		/**
		 * Visualisation d'une APRE
		 *
		 * @param integer $apre_id L'id technique de l'APRE
		 * @return void
		 */
		public function view( $apre_id = null ) {

			$this->Apre->forceVirtualFields = true;

			$apre = $this->Apre->find(
				'first',
				array(
					'conditions' => array(
						'Apre.id' => $apre_id
					),
					'contain' => array(
						'Personne',
						'Comiteapre' => array(
							'order' => array(
								'Comiteapre.datecomite DESC',
								'Comiteapre.heurecomite DESC'
							)
						),
						'Referent',
						'Structurereferente'
					)
				)
			);

			$this->assert( !empty( $apre ), 'invalidParameter' );
			$this->Apre->forceVirtualFields = false;

			$this->set( 'apre', $apre );
			$this->_setOptions();
			$this->set( 'personne_id', $apre['Apre']['personne_id'] );
		}

		/**
		 * Formulaire d'ajout d'une demande d'APRE.
		 *
		 * @param  integer $id L'id technique de la Personne à laquelle ajouter une APRE
		 * @return void
		 */
		public function add( $id = null ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Formulaire de modification d'une demande d'APRE.
		 *
		 * @param  integer $id L'id technique de la demande d'APRE
		 * @return void
		 */
		public function edit( $id = null ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Formaulaire d'ajout/de modification d'une demande d'APRE.
		 *
		 * @param integer $id L'id technique de la personne en cas de add, de l'APRE en cas de edit.
		 * @return void
		 */
		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

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
				$dossier_id = $this->Personne->dossierId( $personne_id );

				///Création automatique du N° APRE de la forme : Année / Mois / N°
				$numapre = date( 'Ym' ).sprintf( "%010s", $this->Apre->find( 'count' ) + 1 );
				$this->set( 'numapre', $numapre );
			}
			else if( $this->action == 'edit' ) {
				$apre_id = $id;

				$contain = array( 'Pieceapre' );
				foreach( $this->Apre->aidesApre as $modelAideAlias ) {
					$modelPieceAlias = 'Piece'.Inflector::underscore( $modelAideAlias );
					$contain[$modelAideAlias] = array( $modelPieceAlias );
				}


				$apre = $this->Apre->find(
					'first',
					array(
						'conditions' => array(
							'Apre.id' => $apre_id
						),
						'contain' => $contain
					)
				);
				$this->assert( !empty( $apre ), 'invalidParameter' );

				$personne_id = $apre['Apre']['personne_id'];
				$dossier_id = $this->Apre->dossierId( $apre_id );

				$this->set( 'numapre', Set::extract( $apre, 'Apre.numeroapre' ) );
			}

			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			$this->set( 'dossier_id', $dossier_id );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			///Récupération de la liste des structures référentes liés uniquement à l'APRE
			$structs = $this->Structurereferente->listeParType( array( 'apre' => true ) );
			$this->set( 'structs', $structs );
			///Récupération de la liste des référents liés à l'APRE
			$referents = $this->Referent->listOptions();
			$this->set( 'referents', $referents );

			///On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur

			$personne = $this->{$this->modelClass}->Personne->detailsApre( $personne_id, $this->Session->read( 'Auth.User.id' ) );
			$this->set( 'personne', $personne );

			/// Recherche du type d'orientation
			$orientstruct = $this->Apre->Structurereferente->Orientstruct->find(
				'first',
				array(
					'conditions' => array(
						'Orientstruct.personne_id' => $personne_id,
						'Orientstruct.typeorient_id IS NOT NULL',
						'Orientstruct.statut_orient' => 'Orienté'
					),
					'order' => 'Orientstruct.date_valid DESC',
					'recursive' => -1
				)
			);
			$this->set( 'orientstruct', $orientstruct );

			///Personne liée au parcours
			$personne_referent = $this->Apre->Personne->PersonneReferent->find(
				'first',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id' => $personne_id,
						'PersonneReferent.dfdesignation IS NULL'
					),
					'recursive' => -1
				)
			);

			///Nombre d'enfants par foyer
			$nbEnfants = $this->Foyer->nbEnfants( Set::classicExtract( $personne, 'Foyer.id' ) );
			$this->set( 'nbEnfants', $nbEnfants );

			if( !empty( $this->request->data ) ) {
				$this->Apre->begin();

				// INFO: pourquoi doit-on faire ceci ?
				$this->Apre->bindModel( array( 'hasOne' => array( 'Formqualif', 'Formpermfimo', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert' ) ), false );

				///Mise en place lors de la sauvegarde du statut de l'APRE à Complémentaire
				$this->request->data['Apre']['statutapre'] = 'C';

				$saveApre = array( );
				$saveApre['Apre'] = $this->request->data['Apre'];
				$saveApre['Pieceapre'] = $this->request->data['Pieceapre'];

				if( $this->Apre->saveAll( $saveApre, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Apre->saveAll( $saveApre, array( 'validate' => 'first', 'atomic' => false ) );

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
							if( !empty( $this->request->data[$piecesLiees] ) ) {
								$linkedData[$model] = $this->request->data[$model];
								$linkedData[$model]['apre_id'] = $this->Apre->id;
								$linkedData[$piecesLiees] = $this->request->data[$piecesLiees];

								$saved = $this->Apre->{$model}->save( $linkedData ) && $saved;
							}
						}
					}

					if( $saved ) {
						$this->Apre->supprimeAidesObsoletes( $this->request->data );
						$this->Apre->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'apres', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Apre->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$this->Apre->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				if( $this->action == 'edit' ) {
					$this->request->data = $apre;
					$this->request->data = Set::insert(
						$this->request->data,
						'Apre.referent_id',
						Set::extract( $this->request->data, 'Apre.structurereferente_id' ).'_'.Set::extract( $this->request->data, 'Apre.referent_id' )
					);
				}
			}

			// Doit-on setter les valeurs par défault ?
			$dataStructurereferente_id = Set::classicExtract( $this->request->data, "{$this->modelClass}.structurereferente_id" );
			$dataReferent_id = Set::classicExtract( $this->request->data, "{$this->modelClass}.referent_id" );

			// Si le formulaire ne possède pas de valeur pour ces champs, on met celles par défaut
			if( empty( $dataStructurereferente_id ) && empty( $dataReferent_id ) ) {
				$structurereferente_id = $referent_id = null;

				// Valeur par défaut préférée: à partir de personnes_referents
				if( !empty( $personne_referent ) ) {
					$structurereferente_id = Set::classicExtract( $personne_referent, 'PersonneReferent.structurereferente_id' );
					$referent_id = Set::classicExtract( $personne_referent, 'PersonneReferent.referent_id' );
				}
				// Valeur par défaut de substitution: à partir de orientsstructs
				else if( !empty( $orientstruct ) ) {
					$structurereferente_id = Set::classicExtract( $orientstruct, 'Orientstruct.structurereferente_id' );
					$referent_id = Set::classicExtract( $orientstruct, 'Orientstruct.referent_id' );
				}

				if( !empty( $structurereferente_id ) ) {
					$this->request->data = Set::insert( $this->request->data, "{$this->modelClass}.structurereferente_id", $structurereferente_id );
				}
				if( !empty( $structurereferente_id ) && !empty( $referent_id ) ) {
					$this->request->data = Set::insert( $this->request->data, "{$this->modelClass}.referent_id", preg_replace( '/^_$/', '', "{$structurereferente_id}_{$referent_id}" ) );
				}
			}

			$struct_id = Set::classicExtract( $this->request->data, "{$this->modelClass}.structurereferente_id" );
			$this->set( 'struct_id', $struct_id );

			$referent_id = Set::classicExtract( $this->request->data, "{$this->modelClass}.referent_id" );
			$referent_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $referent_id );
			$this->set( 'referent_id', $referent_id );

			$this->set( 'personne_id', $personne_id );
			$this->_setOptions();
			$this->render( 'add_edit_'.Configure::read( 'nom_form_apre_cg' ) );
		}
	}
?>
