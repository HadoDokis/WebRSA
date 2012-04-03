<?php
	class Traitementspcgs66Controller extends AppController
	{
		public $name = 'Traitementspcgs66';
		public $uses = array( 'Traitementpcg66', 'Option', 'Dossierpcg66' );
		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Default2', 'Fileuploader' );

		public $components = array( 'Default', 'Gedooo.Gedooo', 'Fileuploader' );

		public $commeDroit = array(
			'view' => 'Traitementspcgs66:index',
			'add' => 'Traitementspcgs66:edit'
		);

		public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		/**
		*
		*/

		protected function _setOptions() {
			$options = array();
			foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
			}
			$this->set(
				'listcourrier',
				$this->Traitementpcg66->Courrierpdo->find(
					'all',
					array(
						'contain' => array(
							'Textareacourrierpdo' => array(
								'order' => 'Textareacourrierpdo.ordre ASC'
							)
						)
					)
				)
			);
			$options[$this->modelClass]['descriptionpdo_id'] = $this->Traitementpcg66->Descriptionpdo->find( 'list' );
			$options[$this->modelClass]['situationpdo_id'] = $this->Traitementpcg66->Personnepcg66Situationpdo->Situationpdo->find( 'list' );
// 			$options[$this->modelClass]['traitementtypepdo_id'] = $this->Traitementpcg66->Traitementtypepdo->find( 'list' );
			$options[$this->modelClass]['listeDescription'] = $this->Traitementpcg66->Descriptionpdo->find( 'all', array( 'contain' => false ) );
			$options[$this->modelClass]['compofoyerpcg66_id'] = $this->Traitementpcg66->Compofoyerpcg66->find( 'list' );

// 			$options[$this->modelClass]['personnepcg66_situationpdo_id'] = $this->Traitementpcg66->Personnepcg66Situationpdo->find( 'list' );

			$this->set( compact( 'options' ) );

			$descriptionspdos = $this->Traitementpcg66->Descriptionpdo->find(
				'list',
				array(
					'fields' => array(
						'Descriptionpdo.id',
						'Descriptionpdo.nbmoisecheance'
					),
					'contain' => false
				)
			);
			$this->set( compact( 'descriptionspdos' ) );

			$this->set( 'gestionnaire', $this->User->find(
					'list',
					array(
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
					'list',
					array(
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

			$traitementpcg66_id = Set::extract( $this->data, 'Traitementpcg66.id' );
			$typecourrierpcg66_id = Set::extract( $this->data, 'Traitementpcg66.typecourrierpcg66_id' );

			// Liste des modèles de courrier lié au type de courrier
			if( !empty( $typecourrierpcg66_id ) ) {                            
				$modeletypecourrierpcg66 = $this->Traitementpcg66->Typecourrierpcg66->Modeletypecourrierpcg66->find(
					'list',
					array(
						'conditions' => array(
							'Modeletypecourrierpcg66.typecourrierpcg66_id' => $typecourrierpcg66_id
						),
						'fields' => array( 'Modeletypecourrierpcg66.id', 'Modeletypecourrierpcg66.name' ),
						'contain' => false
					)
				);
			}

			// Liste des pièces liées aux modèles de courrier
			$listepieces = array();
			if( !empty( $modeletypecourrierpcg66 ) ) {
				foreach( $modeletypecourrierpcg66 as $i => $value ) {
					$listepieces[$i] = $this->Traitementpcg66->Typecourrierpcg66->Modeletypecourrierpcg66->Piecemodeletypecourrierpcg66->find(
						'list',
						array(
							'conditions' => array(
								'Piecemodeletypecourrierpcg66.modeletypecourrierpcg66_id' => $i
							)
						)
					);
				}
			}
			$this->set( compact( 'listepieces') );

			
			if( !empty( $traitementpcg66_id ) ) {
				$data = $this->Traitementpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Traitementpcg66.id' => $traitementpcg66_id
						),
						'contain' => array(
							'Modeletraitementpcg66' => array(
								'Piecemodeletypecourrierpcg66'
							)
						)
					)
				);

				
				$this->data = array();
				foreach( $data['Modeletraitementpcg66'] as $modeletraitementpcg66 ) {

					$this->data['Modeletraitementpcg66'][$modeletraitementpcg66['modeletypecourrierpcg66_id']] = array(
						'id' => $modeletraitementpcg66['id'],
						'checked' => 1,
						'modeletypecourrierpcg66_id' => $modeletraitementpcg66['modeletypecourrierpcg66_id'],
						'commentaire' => $modeletraitementpcg66['commentaire']
					);
					
					foreach( $modeletraitementpcg66['Piecemodeletypecourrierpcg66'] as $piecemodele ) {
						$piecemodele['Mtpcg66Pmtcpcg66']['checked'] = true;
						$this->data['Mtpcg66Pmtcpcg66'][$modeletraitementpcg66['modeletypecourrierpcg66_id']][$piecemodele['Mtpcg66Pmtcpcg66']['piecemodeletypecourrierpcg66_id']][] = $piecemodele['Mtpcg66Pmtcpcg66'];
					}
				}
			}

			$this->set( compact( 'modeletypecourrierpcg66') );
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

			$dossierpcgId = $this->data['Search']['Personnepcg66']['dossierpcg66_id'];
			if( !empty( $dossierpcgId ) ) {
				$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $personne_id, $dossierpcgId ) );
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

			if( !empty( $dossierpcg66_id ) ){
				$this->paginate = array( 'Traitementpcg66' => $queryData );
				$listeTraitements = $this->paginate( $this->Traitementpcg66 );

				$this->set( compact( 'listeTraitements' ) );
// debug( $listeTraitements );
				//Liste des liens entre un dossier et un allocataire
				$personnespcgs66 = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->find(
					'all',
					array(
						'fields' => array( 'id', 'dossierpcg66_id' ),
						'conditions' => array(
							'Personnepcg66.personne_id' => $personne_id
						),
						'contain' => false
					)
				);

				foreach( $personnespcgs66 as $personnepcg66 ){
					$personnepcg66_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.id' );

					$dossierpcg66_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.dossierpcg66_id'  );
					$this->set( 'dossierpcg66_id', $dossierpcg66_id );

					//Recherche des personnes liées au dossier
					$personnepcg66 = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->findById( $personnepcg66_id, null, null, -1 );
					$this->set( 'personnepcg66', $personnepcg66 );
					$this->set( 'personnepcg66_id', $personnepcg66_id );
				}
			}

			$personnespcgs66 = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->find(
				'all',
				array(
					'fields' => array(
						'Personnepcg66.dossierpcg66_id'
					),
					'conditions' => array(
						'Personnepcg66.personne_id' => $personne_id
					),
					'contain' => false
				)
			);

			$listDossierspcgs66 = array();
			foreach( $personnespcgs66 as $personnepcg66 ) {
				$listDossierspcgs66[] = $personnepcg66['Personnepcg66']['dossierpcg66_id'];
			}

			if( !empty( $listDossierspcgs66 ) ) {
				$dossierspcgs66 = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->Dossierpcg66->find(
					'all',
					array(
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
				$dossierspcgs66 = array();
			}


			$this->_setOptions();

			$searchOptions['Personnepcg66']['dossierpcg66_id'] = array();
			foreach( $dossierspcgs66 as $dossierpcg66 ) {
				$searchOptions['Personnepcg66']['dossierpcg66_id'][$dossierpcg66['Dossierpcg66']['id']] = $dossierpcg66['Typepdo']['libelle'].' ('.$dossierpcg66['Dossierpcg66']['datereceptionpdo'].')'.' géré par '.Set::classicExtract( $this->viewVars['gestionnaire'], $dossierpcg66['Dossierpcg66']['user_id'] );
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

			$fichiers = array();

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$personnepcg66_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$traitementpcg66_id = $id;
				$traitementpcg66 = $this->Traitementpcg66->find(
                                    'first',
                                    array(
                                        'conditions' => array(
                                            'Traitementpcg66.id' => $traitementpcg66_id
                                        ),
                                        'contain' => false
                                    )
//						'joins' => array(
//							$this->Traitementpcg66->join( 'Personnepcg66Situationpdo' ),
//							$this->Traitementpcg66->Personnepcg66Situationpdo->join( 'Personnepcg66' )
//						)
				);
				$this->assert( !empty( $traitementpcg66 ), 'invalidParameter' );
				$personnepcg66_id = Set::classicExtract( $traitementpcg66, 'Traitementpcg66.personnepcg66_id' );
			}

			//Récupération des informations de la personne conernée par les traitements + du dossier
			$personnepcg66 = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->find(
				'first',
				array(
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
				'list',
				array(
					'fields' => array( 'Personnepcg66Situationpdo.id', 'Situationpdo.libelle' ),
					'joins' => array(
						$this->Traitementpcg66->Personnepcg66Situationpdo->Situationpdo->join( 'Personnepcg66Situationpdo' )
					),
					'conditions' => array(
						'Personnepcg66Situationpdo.personnepcg66_id' => $personnepcg66_id/*,
						'Personnepcg66Situationpdo.id NOT IN ('
							.$this->Traitementpcg66->sq(
								array(
									'alias' => 'traitementspcgs66',
									'fields' => array( 'traitementspcgs66.personnepcg66_situationpdo_id' ),
									'conditions' => array(
										'traitementspcgs66.personnepcg66_situationpdo_id = Personnepcg66Situationpdo.id'
									)
								)
							)
						.')'*/
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
				'first',
				array(
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
				'first',
				array(
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
			$this->set( 'dtdemrsa', $dtdemrsa);

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id, $dossierpcg66_id ) );
			}

			$personne = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->Personne->findById( $personne_id, null, null, -1 );
			$nompersonne = Set::classicExtract( $personne, 'Personne.nom_complet' );
			$this->set( compact( 'nompersonne' ) );

			//Gestion des jetons
			$this->Traitementpcg66->begin();
			$dossier_id = $this->Traitementpcg66->Personnepcg66Situationpdo->Personnepcg66->Dossierpcg66->Foyer->dossierId( $foyer_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Traitementpcg66->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			if( !empty( $this->data ) ){
         
				if( $this->Traitementpcg66->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Traitementpcg66->sauvegardeTraitement( $this->data );
					if( $saved ) {
						// Début sauvegarde des fichiers attachés, en utilisant le Component Fileuploader
						$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
						$saved = $this->Fileuploader->saveFichiers(
							$dir,
							!Set::classicExtract( $this->data, "Traitementpcg66.haspiecejointe" ),
							( ( $this->action == 'add' ) ? $this->Traitementpcg66->id : $id )
						) && $saved;
						
						if( $saved ) {
							$this->Jetons->release( $dossier_id );
							$this->Traitementpcg66->commit();//FIXME -> arnaud
							$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
								$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $personne_id, $dossierpcg66_id ) );
						}
						else {
							$this->Traitementpcg66->rollback();
							$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
						}
					}
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );

					$this->Traitementpcg66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else{
				if( $this->action == 'edit' ) {
					$this->data = $traitementpcg66;

					$fichiers = $this->Fileuploader->fichiers( $id );
				}
			}

			$traitementspcgsouverts = $this->Traitementpcg66->find(
				'all',
				array(
					'conditions' => array(
						'Traitementpcg66.personnepcg66_id' => $personnepcg66_id,
						'Traitementpcg66.clos' => 'N',
						'Traitementpcg66.annule' => 'N'
					),
					'contain' => array(
						'Descriptionpdo'
					)
				)
			);

			$this->set( compact( 'traitementspcgsouverts', 'fichiers' ) );
			$this->Traitementpcg66->commit();

			$this->_setOptions();

			$this->set( compact( 'personne_id', 'dossier_id', 'dossierpcg66_id', 'personnepcg66_id' ) );
			$this->set( 'urlmenu', '/traitementspcgs66/index/'.$personne_id );

			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		public function view( $id = null ) {
			$traitementpcg66 = $this->Traitementpcg66->find(
				'first',
				array(
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
			if( isset( $this->params['form']['Cancel'] ) ) {
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
				'first',
				array(
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

			if ( $success ) {
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
				'first',
				array(
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

			if ( $success ) {
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
				'first',
				array(
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
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $traitementpcg66['Personnepcg66']['personne_id'], $traitementpcg66['Personnepcg66']['dossierpcg66_id'] ) );
			}

			if ( !empty( $this->data ) ) {
				$this->Traitementpcg66->begin();
				$success = $this->Traitementpcg66->save( $this->data['Traitementpcg66'] );
				$this->_setFlashResult( 'Save', $success );
				if ( $success ) {
					$this->Traitementpcg66->commit();
					$this->redirect( array( 'controller' => 'traitementspcgs66', 'action' => 'index', $traitementpcg66['Personnepcg66']['personne_id'], $traitementpcg66['Personnepcg66']['dossierpcg66_id'] ) );
				}
				else {
					$this->Traitementpcg66->rollback();
				}
			}
			else {
				$this->data = $traitementpcg66;
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
				'first',
				array(
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

			if ( $success ) {
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
				'first',
				array(
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

			if ( $success ) {
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
		public function printFicheCalcul( $id ){

			$this->assert( !empty( $id ), 'error404' );

			$pdf = $this->Traitementpcg66->getPdfDecision( $id );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'Décision' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la fiche de calcul', 'default', array( 'class' => 'error' ) );
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