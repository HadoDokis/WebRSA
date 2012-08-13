<?php
	class PersonnesController extends AppController
	{

		public $name = 'Personnes';
		public $uses = array( 'Personne', 'Option', 'Grossesse', 'Foyer' );
		public $helpers = array( 'Default2', 'Fileuploader' );
		public $components = array( 'Fileuploader' );
		public $commeDroit = array(
			'view' => 'Personnes:index',
			'add' => 'Personnes:edit'
		);
		public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		/**
		 *
		 */
		protected function _setOptions() {
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'nationalite', $this->Option->nationalite() );
			$this->set( 'typedtnai', $this->Option->typedtnai() );
			$this->set( 'pieecpres', $this->Option->pieecpres() );
			$this->set( 'sexe', $this->Option->sexe() );
			$this->set( 'sitfam', $this->Option->sitfam() );
			$this->set( 'natfingro', $this->Option->natfingro() );
			$this->set( 'options', $this->Personne->allEnumLists() );
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
			$personne = $this->Personne->find(
					'first', array(
				'conditions' => array(
					'Personne.id' => $id
				),
				'contain' => array(
					'Fichiermodule' => array(
						'fields' => array( 'name', 'id', 'created', 'modified' )
					)
				)
					)
			);

			$dossier_id = $this->Personne->dossierId( $id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			$foyer_id = Set::classicExtract( $personne, 'Personne.foyer_id' );

			$this->Personne->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Personne->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $foyer_id ) );
			}

			if( !empty( $this->data ) ) {
				$saved = $this->Personne->updateAll(
						array( 'Personne.haspiecejointe' => '\''.$this->data['Personne']['haspiecejointe'].'\'' ), array(
					'"Personne"."id"' => $id
						)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->data, "Personne.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->Personne->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
// 					$this->redirect( array(  'controller' => 'personnes','action' => 'index', $foyer_id ) );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Personne->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/personnes/view/'.$id );
			$this->set( compact( 'dossier_id', 'id', 'fichiers', 'personne' ) );
		}

		/**
		 *   Voir les personnes d'un foyer
		 */
		public function index( $foyer_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $foyer_id ), 'invalidParameter' );

			$personnes = $this->Personne->find(
					'all', array(
				'fields' => array(
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.dtnai',
					'Prestation.rolepers',
					'Calculdroitrsa.toppersdrodevorsa',
					'( SELECT COUNT(fichiersmodules.id) FROM fichiersmodules WHERE fichiersmodules.modele = \'Personne\' AND fichiersmodules.fk_value = "Personne"."id" ) AS "Fichiermodule__nbFichiersLies"'
				),
				'conditions' => array( 'Personne.foyer_id' => $foyer_id ),
				'contain' => array(
					'Prestation',
					'Calculdroitrsa'
				)
					)
			);

			// Assignations à la vue
			$this->_setOptions();
			$this->set( 'foyer_id', $foyer_id );
			$this->set( 'personnes', $personnes );
		}

		/**
		 *
		 *   Voir une personne en particulier
		 *
		 */
		public function view( $id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$queryData = array(
				'fields' => array(
					'Personne.id',
					'Personne.foyer_id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nomnai',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Personne.rgnai',
					'Personne.typedtnai',
					'Personne.nir',
					'Personne.topvalec',
					'Personne.sexe',
					'Personne.nati',
					'Personne.dtnati',
					'Personne.pieecpres',
					'Personne.idassedic',
					'Personne.numagenpoleemploi',
					'Personne.dtinscpoleemploi',
					'Personne.numfixe',
					'Personne.numport',
					'Personne.email',
					'Prestation.rolepers',
				),
				'conditions' => array( 'Personne.id' => $id ),
				'contain' => array(
					'Prestation',
					'Grossesse' => array(
						'order' => array( 'Grossesse.ddgro DESC' ),
						'limit' => 1
					)
				)
			);

			if( Configure::read( 'nom_form_ci_cg' ) == 'cg58' ) {
				$queryData['fields'][] = 'Foyer.sitfam';
				$queryData['contain'][] = 'Foyer';
			}

			$personne = $this->Personne->find( 'first', $queryData );

			// Mauvais paramètre ?
			$this->assert( !empty( $personne ), 'invalidParameter' );

			// Assignation à la vue
			$this->_setOptions();
			$this->set( 'personne', $personne );
		}

		/**
		 *   Ajout d'une personne au foyer
		 */
		public function add( $foyer_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $foyer_id ), 'invalidParameter' );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'personnes', 'action' => 'index', $foyer_id ) );
			}

			$dossier_id = $this->Foyer->dossierId( $foyer_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$personne = $this->Personne->Foyer->find(
					'first', array(
				'fields' => array(
					'Foyer.sitfam'
				),
				'conditions' => array(
					'Foyer.id' => $foyer_id
				),
				'recursive' => -1
					)
			);

			$this->Personne->begin();

			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Personne->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			if( !empty( $this->data ) ) {
				if( ( $this->data['Prestation']['rolepers'] == 'DEM' ) || ( $this->data['Prestation']['rolepers'] == 'CJT' ) ) {
					$this->data['Calculdroitrsa']['toppersdrodevorsa'] = true;
				}

				if( $this->Personne->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					if( $this->Personne->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

						// FIXME: mettre dans un afterSave (mais ça pose des problèmes)
						// FIXME: valeur de retour
						$qd_thisPersonne = array(
							'conditions' => array(
								'Personne.id' => $this->Personne->id
							),
							'fields' => null,
							'order' => null,
							'recursive' => -1
						);
						$thisPersonne = $this->Personne->find( 'first', $qd_thisPersonne );


						$this->Personne->Foyer->refreshSoumisADroitsEtDevoirs( $thisPersonne['Personne']['foyer_id'] );

						$this->Jetons->release( $dossier_id );
						$this->Personne->commit();
						$this->Session->setFlash( 'Enregistrement réussi', 'flash/success' );
						$this->redirect( array( 'controller' => 'personnes', 'action' => 'index', $foyer_id ) );
					}
					else {
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else {
				$roles = $this->Personne->find(
						'all', array(
					'fields' => array(
						'Personne.id',
						'Prestation.rolepers',
					),
					'conditions' => array(
						'Personne.foyer_id' => $foyer_id,
						'Prestation.rolepers' => array( 'DEM', 'CJT' )
					),
					'recursive' => 0
						)
				);
				$roles = Set::extract( '/Prestation/rolepers', $roles );

				// On ne fait apparaître les roles de demandeur et de conjoint que
				// si ceux-ci n'existent pas encore dans le foyer
				$rolepersPermis = $this->Option->rolepers();
				foreach( $rolepersPermis as $key => $rPP ) {
					if( in_array( $key, $roles ) ) {
						unset( $rolepersPermis[$key] );
					}
				}
				$this->set( 'rolepers', $rolepersPermis );
			}

			$this->set( 'foyer_id', $foyer_id );
			$this->data['Personne']['foyer_id'] = $foyer_id;
			$this->set( 'personne', $personne );
			$this->_setOptions();
			$this->Personne->commit();
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		 *   Éditer une personne spécifique d'un foyer
		 */
		public function edit( $id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$foyer_id = Set::classicExtract( $this->data, 'Personne.foyer_id' );

			$dossier_id = $this->Personne->dossierId( $id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->Personne->begin();
				if( $this->Jetons->release( $dossier_id ) ) {
					$this->Personne->commit();
					$this->redirect( array( 'controller' => 'personnes', 'action' => 'index', $foyer_id ) );
				}
				else {
					$this->Personne->rollback();
					$this->Session->setFlash( 'Erreur lors de la restitution du jeton', 'flash/error' );
				}
			}

			$personne = $this->Personne->find(
					'first', array(
				'conditions' => array( 'Personne.id' => $id ),
				'recursive' => 0
					)
			);

			$this->Personne->begin();

			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Personne->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Essai de sauvegarde
			if( !empty( $this->data ) ) {
				if( $this->Personne->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					if( $this->Personne->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

						// FIXME: mettre dans un afterSave (mais ça pose des problèmes)
						// FIXME: valeur de retour
						$qd_thisPersonne = array(
							'conditions' => array(
								'Personne.id' => $this->Personne->id
							),
							'fields' => null,
							'order' => null,
							'recursive' => -1
						);
						$thisPersonne = $this->Personne->find( 'first', $qd_thisPersonne );

						$this->Personne->Foyer->refreshSoumisADroitsEtDevoirs( $thisPersonne['Personne']['foyer_id'] );

						$this->Jetons->release( $dossier_id );
						$this->Personne->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'personnes', 'action' => 'index', $this->data['Personne']['foyer_id'] ) );
					}
					else {
						$this->Personne->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$this->Personne->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			// Afficage des données
			else {
				// On n'a besoin que de la prestation RSA et du Foyer
				$prestationRsa = $this->Personne->hasOne['Prestation'];
				$this->Personne->unbindModelAll();
				$this->Personne->bindModel(
						array(
							'belongsTo' => array( 'Foyer' ),
							'hasOne' => array( 'Prestation' => $prestationRsa )
						)
				);

				$personne = $this->Personne->find(
						'first', array(
					'conditions' => array( 'Personne.id' => $id ),
					'recursive' => 0
						)
				);

				$this->assert( !empty( $personne ), 'invalidParameter' );

				$sitfam = $this->Option->sitfam();
				$situationfamiliale = Set::enum( Set::classicExtract( $personne, 'Foyer.sitfam' ), $sitfam );
				$this->set( 'situationfamiliale', $situationfamiliale );

				// Assignation au formulaire
				$this->data = $personne;

				$this->Personne->commit();
			}
			$this->set( 'personne', $personne );
			$this->_setOptions();
			$this->Personne->commit();
			$this->render( $this->action, null, 'add_edit' );
		}

	}
?>