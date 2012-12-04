<?php
	/**
	 * Code source de la classe Traitementspcgs66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Traitementspcgs66Controller (CG 66).
	 *
	 * @package app.Controller
	 */
	class Traitementspcgs66Controller extends AppController
	{
		public $name = 'Traitementspcgs66';

		public $uses = array( 'Traitementpcg66', 'Option', 'Dossierpcg66' );

		public $helpers = array( 'Locale', 'Csv', 'Cake1xLegacy.Ajax', 'Xform', 'Default2', 'Fileuploader', 'Autrepiecetraitementpcg66' );

		public $components = array( 'Default', 'Gedooo.Gedooo', 'Fileuploader', 'Jetons2' );

		public $commeDroit = array(
			'view' => 'Traitementspcgs66:index',
			'add' => 'Traitementspcgs66:edit'
		);
		public $aucunDroit = array( 'ajaxpiece', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		/**
		 *
		 */
		protected function _setOptions() {
			$options = array( );
			foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
			}
			$options[$this->modelClass]['descriptionpdo_id'] = $this->Traitementpcg66->Descriptionpdo->find( 'list' );
			$options[$this->modelClass]['situationpdo_id'] = $this->Traitementpcg66->Personnepcg66Situationpdo->Situationpdo->find( 'list' );
// 			$options[$this->modelClass]['traitementtypepdo_id'] = $this->Traitementpcg66->Traitementtypepdo->find( 'list' );
			$options[$this->modelClass]['listeDescription'] = $this->Traitementpcg66->Descriptionpdo->find( 'all', array( 'contain' => false ) );
			$options[$this->modelClass]['compofoyerpcg66_id'] = $this->Traitementpcg66->Compofoyerpcg66->find( 'list' );

// 			$options[$this->modelClass]['personnepcg66_situationpdo_id'] = $this->Traitementpcg66->Personnepcg66Situationpdo->find( 'list' );

			$this->set( compact( 'options' ) );

			$descriptionspdos = $this->Traitementpcg66->Descriptionpdo->find(
					'list', array(
				'fields' => array(
					'Descriptionpdo.id',
					'Descriptionpdo.nbmoisecheance'
				),
				'contain' => false
					)
			);
			$this->set( compact( 'descriptionspdos' ) );

			$this->set(
                'gestionnaire',
                $this->User->find(
                    'list', array(
                        'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						)
                    )
                )
			);
			$this->set( 'typescourrierspcgs66', $this->Traitementpcg66->Typecourrierpcg66->find(
							'list', array(
						'fields' => array(
							'Typecourrierpcg66.name'
						)
							)
					)
			);
		}

		/**
		 *   Ajax pour les pièces liées à un type de courrier
		 */
		public function ajaxpiece() { // FIXME
			Configure::write( 'debug', 0 );

			$datas = array( );
			foreach( array( 'Modeletraitementpcg66', 'Piecemodeletypecourrierpcg66' ) as $M ) {
				if( isset( $this->request->data[$M] ) ) {
					$datas[$M] = $this->request->data[$M];
				}
			}

			$traitementpcg66_id = Set::extract( $this->request->data, 'Traitementpcg66.id' );
			$typecourrierpcg66_id = Set::extract( $this->request->data, 'Traitementpcg66.typecourrierpcg66_id' );

			// Liste des modèles de courrier lié au type de courrier
			if( !empty( $typecourrierpcg66_id ) ) {
				$modeletypecourrierpcg66 = $this->Traitementpcg66->Typecourrierpcg66->Modeletypecourrierpcg66->find(
						'list', array(
					'conditions' => array(
						'Modeletypecourrierpcg66.typecourrierpcg66_id' => $typecourrierpcg66_id
					),
					'fields' => array( 'Modeletypecourrierpcg66.id', 'Modeletypecourrierpcg66.name' ),
					'contain' => false
						)
				);
			
				$modeletypecourrierpcg66avecmontant = $this->Traitementpcg66->Typecourrierpcg66->Modeletypecourrierpcg66->find(
					'list',
					array(
						'conditions' => array(
							'Modeletypecourrierpcg66.typecourrierpcg66_id' => $typecourrierpcg66_id,
							'Modeletypecourrierpcg66.ismontant' => '1'
						),
						'fields' => array( 'Modeletypecourrierpcg66.id'),
						'contain' => false
					)
				);
			}

			// Liste des pièces liées aux modèles de courrier
			$listepieces = array( );
			if( !empty( $modeletypecourrierpcg66 ) ) {
				foreach( $modeletypecourrierpcg66 as $i => $value ) {
					$listepieces[$i] = $this->Traitementpcg66->Typecourrierpcg66->Modeletypecourrierpcg66->Piecemodeletypecourrierpcg66->find(
							'list', array(
						'conditions' => array(
							'Piecemodeletypecourrierpcg66.modeletypecourrierpcg66_id' => $i
						),
// 							'fields' => array( 'Piecemodeletypecourrierpcg66.id', 'Piecemodeletypecourrierpcg66.isautrepiece' ),
						'contain' => false
							)
					);

					$listePiecesWithAutre[$i] = $this->Traitementpcg66->Typecourrierpcg66->Modeletypecourrierpcg66->Piecemodeletypecourrierpcg66->find(
							'list', array(
						'conditions' => array(
							'Piecemodeletypecourrierpcg66.modeletypecourrierpcg66_id' => $i
						),
						'fields' => array( 'Piecemodeletypecourrierpcg66.id', 'Piecemodeletypecourrierpcg66.isautrepiece' ),
						'contain' => false
							)
					);

// 					debug( $listePiecesWithAutre[$i] );
				}
			}
			$this->set( compact( 'listepieces', 'listePiecesWithAutre' ) );

			if( !empty( $traitementpcg66_id ) && !isset( $this->request->data['Piecemodeletypecourrierpcg66'] ) ) {
				$datas = $this->Traitementpcg66->Modeletraitementpcg66->find(
						'first', array(
					'conditions' => array(
						'Modeletraitementpcg66.traitementpcg66_id' => $traitementpcg66_id
					),
					'contain' => array(
						'Piecemodeletypecourrierpcg66'
					)
						)
				);

				$this->request->data = Set::merge( $this->request->data, $datas );
			}

			$this->set( compact( 'modeletypecourrierpcg66', 'modeletypecourrierpcg66avecmontant' ) );
			$this->render( 'ajaxpiece', 'ajax' );
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
		 *
		 */
		public function index( $personne_id = null, $dossierpcg66_id = null ) {
			$this->assert( valid_int( $personne_id ), 'error404' );

			// Récupération du nom de l'allocataire
			$personne = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->Personne->find(
                'first',
                array(
                    'fields' => array( 'nom_complet' ),
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    ),
                    'contain' => false
                )
			);
			$nompersonne = Set::classicExtract( $personne, 'Personne.nom_complet' );
			$this->set( compact( 'nompersonne' ) );
			if( !empty( $this->request->data ) ) {
				$dossierpcgId = $this->request->data['Search']['Personnepcg66']['dossierpcg66_id'];
				if( !empty( $dossierpcgId ) ) {
					$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $personne_id, $dossierpcgId ) );
				}
			}
			$this->set( 'dossierpcgId', $dossierpcg66_id );
			
			//Formulaire de recherche pour trouver l'historique de tous les dossiers PCG d'une personne
			$queryData = array(
				'conditions' => array(
					'Personnepcg66.personne_id' => $personne_id,
					'Personnepcg66.dossierpcg66_id' => $dossierpcg66_id
				),
				'fields' => array(
					'Situationpdo.libelle',
					'Traitementpcg66.id',
					'Traitementpcg66.descriptionpdo_id',
					'Traitementpcg66.datedepart',
					'Traitementpcg66.datereception',
					'Traitementpcg66.daterevision',
					'Traitementpcg66.dateecheance',
					'Traitementpcg66.typetraitement',
					'Traitementpcg66.reversedo'
				),
				'joins' => array(
					$this->Traitementpcg66->join( 'Personnepcg66', array( 'type' => 'INNER' ) ),
					$this->Traitementpcg66->join( 'Personnepcg66Situationpdo', array( 'type' => 'LEFT OUTER' ) ),
					$this->Traitementpcg66->Personnepcg66Situationpdo->join( 'Situationpdo', array( 'type' => 'LEFT OUTER' ) )
				),
				'contain' => false
			);
			/**
			  SELECT
			  traitementspcgs66.*,
			 *
			  FROM personnespcgs66
			  LEFT OUTER JOIN traitementspcgs66 ON ( traitementspcgs66.personnepcg66_id = personnespcgs66.id )
			  LEFT OUTER JOIN personnespcgs66_situationspdos ON ( traitementspcgs66.personnepcg66_situationpdo_id = personnespcgs66_situationspdos.id )
			  LEFT OUTER JOIN situationspdos ON ( personnespcgs66_situationspdos.situationpdo_id = situationspdos.id )
			  WHERE personnespcgs66.id = 6;
			 */
			if( !empty( $dossierpcg66_id ) ) {
				$this->paginate = array( 'Traitementpcg66' => $queryData );
				$listeTraitements = $this->paginate( $this->Traitementpcg66 );

				$this->set( compact( 'listeTraitements' ) );
// debug( $listeTraitements );
				//Liste des liens entre un dossier et un allocataire
				$personnespcgs66 = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->find(
						'all', array(
					'fields' => array( 'id', 'dossierpcg66_id' ),
					'conditions' => array(
						'Personnepcg66.personne_id' => $personne_id
					),
					'contain' => false
						)
				);

				foreach( $personnespcgs66 as $personnepcg66 ) {
					$personnepcg66_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.id' );

					$dossierpcg66_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.dossierpcg66_id' );
					$this->set( 'dossierpcg66_id', $dossierpcg66_id );

					//Recherche des personnes liées au dossier
					$qd_personnepcg66 = array(
						'conditions' => array(
							'Personnepcg66.id' => $personnepcg66_id
						),
						'fields' => null,
						'order' => null,
						'recursive' => -1
					);
					$personnepcg66 = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->find( 'first', $qd_personnepcg66 );

					$this->set( 'personnepcg66', $personnepcg66 );
					$this->set( 'personnepcg66_id', $personnepcg66_id );
				}
			}

			$personnespcgs66 = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->find(
					'all', array(
				'fields' => array(
					'Personnepcg66.dossierpcg66_id'
				),
				'conditions' => array(
					'Personnepcg66.personne_id' => $personne_id
				),
				'contain' => false
					)
			);

			$listDossierspcgs66 = array( );
			foreach( $personnespcgs66 as $personnepcg66 ) {
				$listDossierspcgs66[] = $personnepcg66['Personnepcg66']['dossierpcg66_id'];
			}

			if( !empty( $listDossierspcgs66 ) ) {
				$dossierspcgs66 = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->Dossierpcg66->find(
						'all', array(
					'fields' => array( 'Dossierpcg66.id', 'Dossierpcg66.datereceptionpdo', 'Dossierpcg66.user_id', 'Typepdo.libelle' ),
					'conditions' => array(
						'Dossierpcg66.id' => $listDossierspcgs66
					),
					'joins' => array(
						array(
							'table' => 'typespdos',
							'alias' => 'Typepdo',
							'type' => 'INNER',
							'conditions' => array( 'Dossierpcg66.typepdo_id = Typepdo.id' )
						)
					),
					'contain' => false
						)
				);
			}
			else {
				$dossierspcgs66 = array( );
			}


			$this->_setOptions();

			$searchOptions['Personnepcg66']['dossierpcg66_id'] = array( );
			foreach( $dossierspcgs66 as $dossierpcg66 ) {
				$searchOptions['Personnepcg66']['dossierpcg66_id'][$dossierpcg66['Dossierpcg66']['id']] = $dossierpcg66['Typepdo']['libelle'].' ('.$dossierpcg66['Dossierpcg66']['datereceptionpdo'].')'.' géré par '.Set::enum( $dossierpcg66['Dossierpcg66']['user_id'], $this->viewVars['gestionnaire'] );
			}
			$this->set( 'searchOptions', $searchOptions );

			$this->set( 'personne_id', $personne_id );

			$this->set( 'urlmenu', '/traitementspcgs66/index/'.$personne_id );
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

			$fichiers = array( );

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$personnepcg66_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$traitementpcg66_id = $id;
				$traitementpcg66 = $this->Traitementpcg66->find(
						'first', array(
					'conditions' => array(
						'Traitementpcg66.id' => $traitementpcg66_id
					),
					'contain' => array(
						'Modeletraitementpcg66'
					)
						)
				);
				$this->assert( !empty( $traitementpcg66 ), 'invalidParameter' );
				$personnepcg66_id = Set::classicExtract( $traitementpcg66, 'Traitementpcg66.personnepcg66_id' );
			}

			//Récupération des informations de la personne conernée par les traitements + du dossier
			$personnepcg66 = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->find(
					'first', array(
				'conditions' => array(
					'Personnepcg66.id' => $personnepcg66_id
				),
				'contain' => array(
					'Statutpdo',
					'Situationpdo'
				)
					)
			);
			$this->set( 'personnepcg66', $personnepcg66 );

// 			debug($personnepcg66);
			$listeMotifs = $this->Traitementpcg66->Personnepcg66Situationpdo->Situationpdo->find(
					'list', array(
				'fields' => array( 'Personnepcg66Situationpdo.id', 'Situationpdo.libelle' ),
				'joins' => array(
					$this->Traitementpcg66->Personnepcg66Situationpdo->Situationpdo->join( 'Personnepcg66Situationpdo' )
				),
				'conditions' => array(
					'Personnepcg66Situationpdo.personnepcg66_id' => $personnepcg66_id
				),
					)
			);
// debug( $listeMotifs );
			$this->set( compact( 'listeMotifs' ) );

			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );


			$dossierpcg66_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.dossierpcg66_id' );
			$personne_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.personne_id' );

			$dossierpcg66 = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->Dossierpcg66->find(
					'first', array(
				'conditions' => array(
					'Dossierpcg66.id' => $dossierpcg66_id
				),
				'contain' => false
					)
			);
			$this->set( compact( 'dossierpcg66' ) );
			$foyer_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.foyer_id' );
			$dossier_id = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->Dossierpcg66->Foyer->dossierId( $foyer_id );

			$dossier = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->Dossierpcg66->Foyer->Dossier->find(
					'first', array(
				'fields' => array(
					'dtdemrsa'
				),
				'conditions' => array(
					'Dossier.id' => $dossier_id
				),
				'contain' => false
					)
			);
			$dtdemrsa = Set::classicExtract( $dossier, 'Dossier.dtdemrsa' );
			$this->set( 'dtdemrsa', $dtdemrsa );

			$qd_personne = array(
				'conditions' => array(
					'Personne.id' => $personne_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$personne = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->Personne->find( 'first', $qd_personne );

			$nompersonne = Set::classicExtract( $personne, 'Personne.nom_complet' );
			$this->set( compact( 'nompersonne' ) );

			//Gestion des jetons
			$dossier_id = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->Dossierpcg66->Foyer->dossierId( $foyer_id );
			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id, $dossierpcg66_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Traitementpcg66->begin();

				$dataToSave = $this->request->data;
				// INFO: attention, on peut se le permettre car il n'y a pas de règle de validation sur le commentaire
				if( !empty( $dataToSave['Modeletraitementpcg66']['modeletypecourrierpcg66_id'] ) ) {
					$dataToSave['Modeletraitementpcg66']['commentaire'] = $dataToSave['Modeletraitementpcg66'][$dataToSave['Modeletraitementpcg66']['modeletypecourrierpcg66_id']]['commentaire'];

					if( !empty( $dataToSave['Modeletraitementpcg66'][$dataToSave['Modeletraitementpcg66']['modeletypecourrierpcg66_id']]['montantsaisi'] ) ) {
						$dataToSave['Modeletraitementpcg66']['montantsaisi'] = $dataToSave['Modeletraitementpcg66'][$dataToSave['Modeletraitementpcg66']['modeletypecourrierpcg66_id']]['montantsaisi'];
						
						$dataToSave['Modeletraitementpcg66']['montantdatedebut'] = $dataToSave['Modeletraitementpcg66'][$dataToSave['Modeletraitementpcg66']['modeletypecourrierpcg66_id']]['montantdatedebut'];
						
						$dataToSave['Modeletraitementpcg66']['montantdatefin'] = $dataToSave['Modeletraitementpcg66'][$dataToSave['Modeletraitementpcg66']['modeletypecourrierpcg66_id']]['montantdatefin'];
					}

					unset( $dataToSave['Modeletraitementpcg66'][$dataToSave['Modeletraitementpcg66']['modeletypecourrierpcg66_id']] );
				}

				$saved = $this->Traitementpcg66->sauvegardeTraitement( $dataToSave );

				if( $saved ) {
					// Début sauvegarde des fichiers attachés, en utilisant le Component Fileuploader
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers(
									$dir, !Set::classicExtract( $dataToSave, "Traitementpcg66.haspiecejointe" ), ( ( $this->action == 'add' ) ? $this->Traitementpcg66->id : $id )
							) && $saved;

					if( $saved ) {
						$this->Traitementpcg66->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $personne_id, $dossierpcg66_id ) );
					}
					else {
						$this->Traitementpcg66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );

					$this->Traitementpcg66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $traitementpcg66;

				$fichiers = $this->Fileuploader->fichiers( $id );
			}

			if( $this->action == 'edit' ) {
				$conditions = array(
					'Traitementpcg66.personnepcg66_id' => $personnepcg66_id,
					'Traitementpcg66.clos' => 'N',
					'Traitementpcg66.annule' => 'N',
					'Traitementpcg66.id NOT' => $id
				);
			}
			else {
				$conditions = array(
					'Traitementpcg66.personnepcg66_id' => $personnepcg66_id,
					'Traitementpcg66.clos' => 'N',
					'Traitementpcg66.annule' => 'N'
				);
			}

			$traitementspcgsouverts = $this->Traitementpcg66->find(
					'all', array(
				'conditions' => $conditions,
				'contain' => array(
					'Descriptionpdo'
				),
				'order' => array( 'Traitementpcg66.dateecheance DESC' )
					)
			);

			$this->set( compact( 'traitementspcgsouverts', 'fichiers' ) );

			$this->_setOptions();

			$this->set( compact( 'personne_id', 'dossier_id', 'dossierpcg66_id', 'personnepcg66_id' ) );
			$this->set( 'urlmenu', '/traitementspcgs66/index/'.$personne_id );

			$this->render( 'add_edit' );
		}

		/**
		 *
		 */
		public function view( $id = null ) {
			$traitementpcg66 = $this->Traitementpcg66->find(
					'first', array(
				'conditions' => array(
					'Traitementpcg66.id' => $id
				),
				'contain' => array(
					'Personnepcg66' => array(
						'Personne'
					),
					'Fichiermodule',
					'Descriptionpdo'
				)
					)
			);
			$this->assert( !empty( $traitementpcg66 ), 'invalidParameter' );

			$personnepcg66_id = Set::classicExtract( $traitementpcg66, 'Traitementpcg66.personnepcg66_id' );

			$dossierpcg66_id = Set::classicExtract( $traitementpcg66, 'Personnepcg66.dossierpcg66_id' );
			$personne_id = Set::classicExtract( $traitementpcg66, 'Personnepcg66.personne_id' );

			// Retour à l'entretien en cas de retour
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $personne_id, $dossierpcg66_id ) );
			}

			$this->_setOptions();
			$this->set( compact( 'traitementpcg66', 'personne_id' ) );

			$this->set( 'urlmenu', '/traitementspcgs66/index/'.$personne_id );
		}

		/**
		 *
		 */
		public function cancel( $id ) {
			$this->Traitementpcg66->begin();

			$traitementpcg66 = $this->Traitementpcg66->find(
					'first', array(
				'fields' => array(
					'Traitementpcg66.id',
					'Traitementpcg66.personnepcg66_id',
					'Personnepcg66.personne_id',
					'Personnepcg66.dossierpcg66_id'
				),
				'conditions' => array(
					'Traitementpcg66.id' => $id
				),
				'contain' => array(
					'Personnepcg66'
				)
					)
			);

			$traitementpcg66['Traitementpcg66']['clos'] = 'O';
			$traitementpcg66['Traitementpcg66']['annule'] = 'O';
			$this->Traitementpcg66->create( $traitementpcg66['Traitementpcg66'] );
			$success = $this->Traitementpcg66->save();

			$success = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->Dossierpcg66->updateEtatViaDecisionTraitement( $traitementpcg66['Personnepcg66']['dossierpcg66_id'] ) && $success;

			if( $success ) {
				$this->Traitementpcg66->commit();
				$this->Session->setFlash( 'Le traitement est annulé', 'flash/success' );
			}
			else {
				$this->Traitementpcg66->rollback();
				$this->Session->setFlash( 'Erreur lors de l\'annulation du traitement', 'flash/error' );
			}
			$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $traitementpcg66['Personnepcg66']['personne_id'], $traitementpcg66['Personnepcg66']['dossierpcg66_id'] ) );
		}

		/**
		 *
		 */
		public function clore( $id ) {
			$this->Traitementpcg66->begin();

			$traitementpcg66 = $this->Traitementpcg66->find(
					'first', array(
				'conditions' => array(
					'Traitementpcg66.id' => $id
				),
				'contain' => array(
					'Personnepcg66'
				)
					)
			);
			$this->Traitementpcg66->id = $id;
			$success = $this->Traitementpcg66->saveField( 'clos', 'O' );

			if( $success ) {
				$this->Traitementpcg66->commit();
				$this->Session->setFlash( 'Le traitement est clôturé', 'flash/success' );
			}
			else {
				$this->Traitementpcg66->rollback();
				$this->Session->setFlash( 'Erreur lors de la clôture du traitement', 'flash/error' );
			}
			$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $traitementpcg66['Personnepcg66']['personne_id'], $traitementpcg66['Personnepcg66']['dossierpcg66_id'] ) );
		}

		/**
		 *
		 */
		public function decision( $id ) {
			$traitementpcg66 = $this->Traitementpcg66->find(
					'first', array(
				'conditions' => array(
					'Traitementpcg66.id' => $id
				),
				'contain' => array(
					'Personnepcg66',
					'Descriptionpdo'
				)
					)
			);

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $traitementpcg66['Personnepcg66']['personne_id'], $traitementpcg66['Personnepcg66']['dossierpcg66_id'] ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Traitementpcg66->begin();
				$success = $this->Traitementpcg66->save( $this->request->data['Traitementpcg66'] );
				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Traitementpcg66->commit();
					$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $traitementpcg66['Personnepcg66']['personne_id'], $traitementpcg66['Personnepcg66']['dossierpcg66_id'] ) );
				}
				else {
					$this->Traitementpcg66->rollback();
				}
			}
			else {
				$this->request->data = $traitementpcg66;
			}
			$this->set( 'personne_id', $traitementpcg66['Personnepcg66']['personne_id'] );
			$this->set( 'descriptionpdo', $traitementpcg66['Descriptionpdo']['name'] );
			$this->_setOptions();
		}

		/**
		 *
		 */
		public function reverseDO( $id ) {
			$this->Traitementpcg66->begin();

			$traitementpcg66 = $this->Traitementpcg66->find(
					'first', array(
				'conditions' => array(
					'Traitementpcg66.id' => $id
				),
				'contain' => array(
					'Personnepcg66'
				)
					)
			);
			$this->Traitementpcg66->id = $id;
			$success = $this->Traitementpcg66->saveField( 'reversedo', '1' );

			if( $success ) {
				$this->Traitementpcg66->commit();
				$this->Session->setFlash( 'La fiche de calcul sera repercutée dans la décision', 'flash/success' );
			}
			else {
				$this->Traitementpcg66->rollback();
				$this->Session->setFlash( 'Erreur lors de la répercussion de la fiche de calcul', 'flash/error' );
			}
			$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $traitementpcg66['Personnepcg66']['personne_id'], $traitementpcg66['Personnepcg66']['dossierpcg66_id'] ) );
		}

		/**
		 *
		 */
		public function deverseDO( $id ) {
			$this->Traitementpcg66->begin();

			$traitementpcg66 = $this->Traitementpcg66->find(
					'first', array(
				'conditions' => array(
					'Traitementpcg66.id' => $id
				),
				'contain' => array(
					'Personnepcg66'
				)
					)
			);
			$this->Traitementpcg66->id = $id;
			$success = $this->Traitementpcg66->saveField( 'reversedo', '0' );

			if( $success ) {
				$this->Traitementpcg66->commit();
				$this->Session->setFlash( 'La fiche de calcul ne sera plus repercutée dans la décision', 'flash/success' );
			}
			else {
				$this->Traitementpcg66->rollback();
				$this->Session->setFlash( 'Erreur lors de la non répercussion de la fiche de calcul', 'flash/error' );
			}
			$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $traitementpcg66['Personnepcg66']['personne_id'], $traitementpcg66['Personnepcg66']['dossierpcg66_id'] ) );
		}

		/**
		 *   Enregistrement du document pour la fiche de calcul lors de l'enregistrement du traitement
		 */
		public function printFicheCalcul( $id ) {

			$this->assert( !empty( $id ), 'error404' );

			$pdf = $this->Traitementpcg66->getPdfFichecalcul( $id );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'Décision.pdf' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la fiche de calcul', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 *   Enregistrement du modèle de document lié au type de courrier lors de l'enregistrement du traitement
		 */
		public function printModeleCourrier( $id ) {

			$this->assert( !empty( $id ), 'error404' );

			$pdf = $this->Traitementpcg66->getPdfModeleCourrier( $id, $this->Session->read( 'Auth.User.id' ) );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'ModeleCourrier.pdf' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le modèle de courrier', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 *
		 */
		public function delete( $id ) {
			$this->Default->delete( $id );
		}

	}
?>