<?php
	/**
	 * Code source de la classe Dossierspcgs66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Dossierspcgs66Controller ... (CG 66).
	 *
	 * @package app.Controller
	 */
	class Dossierspcgs66Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Cake1xLegacy.Ajax', 'Fileuploader' );

		public $uses = array( 'Dossierpcg66', 'Option', 'Typenotifpdo', 'Decisionpdo' );

		public $components = array( 'Fileuploader', 'Gedooo.Gedooo', 'Jetons2', 'DossiersMenus' );

		public $commeDroit = array(
			'add' => 'Dossierspcgs66:edit',
			'view' => 'Dossierspcgs66:index'
		);

		public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download', 'ajaxetatpdo' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'ajaxetatpdo' => 'read',
			'ajaxfiledelete' => 'delete',
			'ajaxfileupload' => 'update',
			'delete' => 'delete',
			'download' => 'read',
			'edit' => 'update',
			'fileview' => 'read',
			'index' => 'read',
			'view' => 'read',
		);

		/**
		 *
		 */
		protected function _setOptions() {
			$options = $this->Dossierpcg66->enums();

			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'pieecpres', $this->Option->pieecpres() );
			$this->set( 'commission', $this->Option->commission() );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'motidempdo', $this->Option->motidempdo() );
			$this->set( 'motifpdo', $this->Option->motifpdo() );
			$this->set( 'categoriegeneral', $this->Option->sect_acti_emp() );
			$this->set( 'categoriedetail', $this->Option->emp_occupe() );

			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'typepdo', $this->Dossierpcg66->Typepdo->find( 'list' ) );
			$this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
			$this->set( 'decisionpdo', $this->Decisionpdo->find( 'list', array( 'order' => 'Decisionpdo.libelle ASC' ) ) );

			$this->set( 'originepdo', $this->Dossierpcg66->Originepdo->find( 'list' ) );

			$this->set( 'serviceinstructeur', $this->Dossierpcg66->Serviceinstructeur->listOptions() );
			$this->set( 'orgpayeur', array('CAF'=>'CAF', 'MSA'=>'MSA') );

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

			$options = Set::merge(
				$this->Dossierpcg66->Decisiondossierpcg66->enums(),
				$options
			);

			$options = Hash::insert( $options, 'Suiviinstruction.typeserins', $this->Option->typeserins() );
			$this->set( compact( 'options' ) );
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
		 *
		 * @param integer $id
		 */
		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		 * Téléchargement des fichiers préalablement associés
		 *
		 * @param integer $fichiermodule_id
		 */
		public function download( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );
			$this->Fileuploader->download( $fichiermodule_id );
		}

		/**
		 * Affichage de l'état du dossier PCG
		 *
		 * @param type $typepdo_id
		 * @param type $user_id
		 * @param type $decisionpdo_id
		 * @param type $retouravistechnique
		 * @param type $vuavistechnique
		 * @param type $complet
		 * @param type $incomplet
		 */
		public function ajaxetatpdo( $typepdo_id = null, $user_id = null, $decisionpdo_id = null, $retouravistechnique = null, $vuavistechnique = null, $complet = null, $incomplet = null ) {
			$dataTypepdo_id = Set::extract( $this->request->params, 'form.typepdo_id' );
			$dataUser_id = Set::extract( $this->request->params, 'form.user_id' );

			$dataComplet = Set::extract( $this->request->params, 'form.complet' );
			$dataDecisionpdo_id = Set::extract( $this->request->params, 'form.decisionpdo_id' );

			$dataAvistech = null;
			$dataAvisvalid = null;
			$etatdossierpcg = null;

			if ( isset($this->request->data['dossierpcg66_id'] ) && $this->request->data['dossierpcg66_id'] != 0 ) {
				$dossierpcg66 = $this->Dossierpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Dossierpcg66.id' => Set::extract( $this->request->data, 'dossierpcg66_id' )
						),
						'contain' => false
					)
				);
				$etatdossierpcg = $dossierpcg66['Dossierpcg66']['etatdossierpcg'];

				if( !empty( $dossierpcg66 ) ){
					$decisionsdossierspcgs66 = $this->Dossierpcg66->Decisiondossierpcg66->find(
						'all',
						array(
							'conditions' => array(
								'Decisiondossierpcg66.dossierpcg66_id' => $dossierpcg66['Dossierpcg66']['id']
							),
							'contain' => false
						)
					);

					$datetransmission = null;
					foreach( $decisionsdossierspcgs66 as $decisiondossierpcg66 ){
						if( $decisiondossierpcg66['Decisiondossierpcg66']['etatop'] == 'transmis' ){
							$datetransmission = $decisiondossierpcg66['Decisiondossierpcg66']['datetransmissionop'];
						}
						else{
							$datetransmission = null;
						}
						$this->set( compact( 'datetransmission' ) );
// 						$dossierspcgs66[$i]['Dossierpcg66']['datetransmissionfinale'] = $datetransmission;
					}
				}
			}
			$etatdossierpcg = $this->Dossierpcg66->etatDossierPcg66( $dataTypepdo_id, $dataUser_id, $dataDecisionpdo_id, $dataAvistech, $dataAvisvalid, $retouravistechnique, $vuavistechnique, /*$iscomplet,*/ $etatdossierpcg );

			$this->Dossierpcg66->etatPcg66( $this->request->data );
			$this->set( compact( 'etatdossierpcg' ) );
			Configure::write( 'debug', 0 );
			$this->render( 'ajaxetatpdo', 'ajax' );
		}

		/**
		 *
		 * @param integer $foyer_id
		 */
		public function index( $foyer_id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'foyer_id' => $foyer_id ) ) );

			$personneDem = $this->Dossierpcg66->Foyer->Personne->find(
				'first',
				array(
					'fields' => array(
						'Personne.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Prestation.rolepers'
					),
					'conditions' => array(
						'Personne.foyer_id' => $foyer_id,
						'Prestation.rolepers' => 'DEM'
					),
					'joins' => array(
						$this->Dossierpcg66->Foyer->Personne->join( 'Prestation' )
					),
					'contain' => false
				)
			);
			$this->set( compact( 'personneDem' ) );

			$dossierspcgs66 = $this->Dossierpcg66->find(
				'all',
				array(
					'conditions' => array(
						'Dossierpcg66.foyer_id' => $foyer_id
					),
					'contain' => array(
						'Typepdo',
						'Decisiondossierpcg66' => array(
							'order' => array( 'Decisiondossierpcg66.datetransmissionop DESC' )
						)
					),
					'order' => array( 'Dossierpcg66.datereceptionpdo DESC', 'Dossierpcg66.id DESC' )
				)
			);
			
			foreach( $dossierspcgs66 as $i => $dossierpcg66 ) {
				$listeSituationsPersonnePCG66 = $this->Dossierpcg66->Personnepcg66->find(
					'all',
					array(
						'fields' => array(
							'Situationpdo.libelle'
						),
						'conditions' => array(
							'Personnepcg66.dossierpcg66_id' => $dossierpcg66['Dossierpcg66']['id']
						),
						'joins' => array(
							$this->Dossierpcg66->Personnepcg66->join( 'Personnepcg66Situationpdo', array( 'type' => 'LEFT OUTER' ) ),
							$this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->join( 'Situationpdo', array( 'type' => 'LEFT OUTER' ) )
						),
						'contain' => false
					)
				);

				$listeStatuts = Set::extract( $listeSituationsPersonnePCG66, '/Situationpdo/libelle' );
				$listeSituationsPersonnePCG66 = $listeStatuts;
				$dossierspcgs66[$i]['Personnepcg66']['listemotifs'] = $listeSituationsPersonnePCG66;
				
				// Liste des décisions avec la date de transmission
				$decisionsdossierspcgs66 = $this->Dossierpcg66->Decisiondossierpcg66->find(
					'all',
					array(
						'conditions' => array(
							'Decisiondossierpcg66.dossierpcg66_id' => $dossierpcg66['Dossierpcg66']['id']
						),
						'contain' => false
					)
				);

				$datetransmission = null;
				foreach( $decisionsdossierspcgs66 as $decisiondossierpcg66 ){
					if( $decisiondossierpcg66['Decisiondossierpcg66']['etatop'] == 'transmis' ){
						$datetransmission = $decisiondossierpcg66['Decisiondossierpcg66']['datetransmissionop'];
					}
					else{
						$datetransmission = null;
					}
					$dossierspcgs66[$i]['Dossierpcg66']['datetransmissionfinale'] = $datetransmission;
				}
			}
			$this->set( compact( 'dossierspcgs66' ) );
			$this->_setOptions();
			$this->set( 'foyer_id', $foyer_id );
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
		 * @param integer $id
		 */
		protected function _add_edit( $id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $id ), 'invalidParameter' );

			if( $this->action == 'edit' ) {
				$foyer_id = $this->Dossierpcg66->field( 'foyer_id', array( 'id' => $id ) );
			}
			else {
				$foyer_id = $id;
			}

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'foyer_id' => $foyer_id ) ) );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$dossier_id = $this->Dossierpcg66->Foyer->dossierId( $foyer_id );
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $foyer_id ) );

			}
			$fichiers = array();
			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$foyer_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$dossierpcg66_id = $id;
				$dossierpcg66 = $this->Dossierpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Dossierpcg66.id' => $dossierpcg66_id
						),
						'contain' => array(
							'Personnepcg66' => array(
								'Statutpdo',
								'Situationpdo'
							)
						)
					)
				);
				$this->assert( !empty( $dossierpcg66 ), 'invalidParameter' );

				//Recherche des personnes liées au foyer
				$this->Dossierpcg66->forceVirtualFields = true;
				$personnespcgs66 = $this->Dossierpcg66->Personnepcg66->find(
					'all',
					array(
						'conditions' => array(
							'Personnepcg66.dossierpcg66_id' => $dossierpcg66_id
						),
						'contain' => array(
							'Statutpdo',
							'Situationpdo',
							'Personne',
							'Traitementpcg66'
						)
					)
				);
				$this->Dossierpcg66->forceVirtualFields = false;
				$this->set( 'personnespcgs66', $personnespcgs66 );
				$this->set( 'dossierpcg66_id', $dossierpcg66_id );
				$foyer_id = $dossierpcg66['Dossierpcg66']['foyer_id'];

				$this->set( 'etatdossierpcg', $dossierpcg66['Dossierpcg66']['etatdossierpcg'] );

				//Gestion des décisions pour le dossier au niveau foyer
				$joins = array(
					array(
						'table'      => 'pdfs',
						'alias'      => 'Pdf',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Pdf.modele' => 'Decisiondossierpcg66',
							'Pdf.fk_value = Decisiondossierpcg66.id'
						)
					),
					array(
						'table'      => 'decisionspdos',
						'alias'      => 'Decisionpdo',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionpdo.id = Decisiondossierpcg66.decisionpdo_id'
						)
					),
//                    array(
//						'table'      => 'fichiersmodules',
//						'alias'      => 'Fichiermodule',
//						'type'       => 'LEFT OUTER',
//						'foreignKey' => false,
//						'conditions' => array(
//							'Decisionpdo.id = Decisiondossierpcg66.decisionpdo_id'
//						)
//					)
				);

				$decisionsdossierspcgs66 = $this->{$this->modelClass}->Decisiondossierpcg66->find(
					'all',
					array(
						'fields' => array(
							'Decisiondossierpcg66.id',
							'Decisiondossierpcg66.dossierpcg66_id',
							'Decisiondossierpcg66.decisionpdo_id',
							'Decisiondossierpcg66.datepropositiontechnicien',
							'Decisiondossierpcg66.commentairetechnicien',
							'Decisiondossierpcg66.commentaire',
							'Decisiondossierpcg66.avistechnique',
							'Decisiondossierpcg66.commentaireavistechnique',
							'Decisiondossierpcg66.dateavistechnique',
							'Decisiondossierpcg66.validationproposition',
							'Decisiondossierpcg66.commentairevalidation',
							'Decisiondossierpcg66.datevalidation',
							'Decisionpdo.libelle',
							'Pdf.fk_value',
                            $this->{$this->modelClass}->Decisiondossierpcg66->Fichiermodule->sqNbFichiersLies( $this->{$this->modelClass}->Decisiondossierpcg66, 'nb_fichiers_lies' )
						),
						'conditions' => array(
							'dossierpcg66_id' => $dossierpcg66_id
						),
		                'joins' => $joins,
						'order' => array(
							'Decisiondossierpcg66.modified DESC'
						),
						'recursive' => -1
					)
				);

				$this->set( compact( 'decisionsdossierspcgs66' ) );
				if ( !empty( $decisionsdossierspcgs66 ) ) {
					$lastDecisionId = $decisionsdossierspcgs66[0]['Decisiondossierpcg66']['id'];
					( is_numeric( $decisionsdossierspcgs66[0]['Decisiondossierpcg66']['validationproposition'] ) ) ? $ajoutDecision = true : $ajoutDecision = false;
				}
				else{
					$lastDecisionId = null;
					$ajoutDecision = null;
				}
				$this->set( compact( 'ajoutDecision', 'lastDecisionId' ) );
			}


			$personneDem = $this->Dossierpcg66->Foyer->Personne->find(
				'first',
				array(
					'fields' => array(
						'Personne.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Prestation.rolepers'
					),
					'conditions' => array(
						'Personne.foyer_id' => $foyer_id,
						'Prestation.rolepers' => 'DEM'
					),
					'joins' => array(
						$this->Dossierpcg66->Foyer->Personne->join( 'Prestation' )
					),
					'contain' => false
				)
			);
			$this->set( compact( 'personneDem' ) );


			//Gestion des jetons
			$dossier_id = $this->Dossierpcg66->Foyer->dossierId( $foyer_id );
			$this->Jetons2->get( $dossier_id );

			// Essai de sauvegarde
			if( !empty( $this->request->data ) ) {
				$this->Dossierpcg66->begin();

				$saved = $this->Dossierpcg66->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) );
				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers(
						$dir,
						!Set::classicExtract( $this->request->data, "Dossierpcg66.haspiecejointe" ),
						( ( $this->action == 'add' ) ? $this->Dossierpcg66->id : $id )
					) && $saved;
				}

				if( $saved ) {
					$this->Dossierpcg66->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array(  'controller' => 'dossierspcgs66','action' => 'index', $foyer_id ) );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Dossierpcg66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			//Affichage des données
			elseif( $this->action == 'edit' ) {
				$this->request->data = $dossierpcg66;
				$fichiers = $this->Fileuploader->fichiers( $dossierpcg66['Dossierpcg66']['id'] );
			}

			// avistechniquemodifiable, validationmodifiable
			$etatdossierpcg = 'instrencours';
			if( isset( $dossierpcg66 ) ) {
				$etatdossierpcg = $dossierpcg66['Dossierpcg66']['etatdossierpcg'];
			}
			$gestionnairemodifiable = $personnedecisionmodifiable = false;
			switch( $etatdossierpcg ) {
				case 'instrencours':
					break;
				case 'attaffect':
					$gestionnairemodifiable = true;
					break;
				default:
					$gestionnairemodifiable = true;
					$personnedecisionmodifiable = true;
				break;
			}

			$this->set( compact( 'gestionnairemodifiable', 'personnedecisionmodifiable' ) );

			// Assignation à la vue
			$this->set( 'fichiers', $fichiers );
			$this->set( 'foyer_id', $foyer_id );

			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		/**
		 *
		 * @param integer $id
		 */
		public function view( $id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $this->Dossierpcg66->dossierId( $id ) ) ) );

			$dossierpcg66 = $this->Dossierpcg66->find(
				'first',
				array(
					'conditions' => array(
						'Dossierpcg66.id' => $id
					),
					'contain' => array(
						'Personnepcg66' => array(
							'Personne'
						),
						'Fichiermodule',
						'Typepdo',
						'Originepdo',
						'Serviceinstructeur',
						'User',
                        'Decisiondossierpcg66' => array(
                            'order' => array( 'Decisiondossierpcg66.created DESC' ),
                            'conditions' => array(
                                'Decisiondossierpcg66.validationproposition' => 'O',
                                'Decisiondossierpcg66.etatop' => 'transmis'
                            )
                        )
					)
				)
			);
			$this->assert( !empty( $dossierpcg66 ), 'invalidParameter' );

			$foyer_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.foyer_id' );

			// Retour à l'entretien en cas de retour
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'index', $foyer_id ) );
			}

			
			//Récupération de la liste des courriers envoyés à l'allocataire:
			$personnesIds = array();
			foreach( $dossierpcg66['Personnepcg66'] as $i => $personnepcg66 ) {
				$personnesIds[] = $personnepcg66['personne_id'];
			}
			$traitementsCourriersEnvoyes = $this->Dossierpcg66->listeCourriersEnvoyes( $personnesIds, $dossierpcg66 );
			$this->set( compact( 'traitementsCourriersEnvoyes' ) );
			
			$this->_setOptions();
			$this->set( compact( 'dossierpcg66' ) );
			$this->set( 'foyer_id', $foyer_id );

			$this->set( 'urlmenu', '/dossierspcgs66/index/'.$foyer_id );
		}

		/**
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'id' => $this->Dossierpcg66->dossierId( $id ) ) );

			$dossierpcg66 = $this->Dossierpcg66->find(
				'first',
				array(
					'conditions' => array(
						'Dossierpcg66.id' => $id
					),
					'joins' => array(
						$this->Dossierpcg66->join( 'Foyer' )
					),
					'contain' => false,
					'fields' => array(
						'Dossierpcg66.foyer_id'
					)
				)
			);

			$foyer_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.foyer_id' );

			$success = $this->Dossierpcg66->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'index', $foyer_id ) );
		}
	}
?>