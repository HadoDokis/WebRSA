<?php
	/**
	 * Code source de la classe Dossierspcgs66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Dossierspcgs66Controller ... (CG 66).
	 *
	 * @package app.controllers
	 */
	class Dossierspcgs66Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Ajax', 'Fileuploader' );
		public $uses = array( 'Dossierpcg66', 'Option', 'Typenotifpdo', 'Decisionpdo' );
		public $components = array( 'Fileuploader', 'Gedooo.Gedooo', 'Jetons2' );

		public $commeDroit = array(
			'add' => 'Dossierspcgs66:edit',
			'view' => 'Dossierspcgs66:index'
		);

		public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download', 'ajaxetatpdo' );

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

			$options = Set::insert( $options, 'Suiviinstruction.typeserins', $this->Option->typeserins() );
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
		*	Affichage de l'état du dossier PCG
		*/

		public function ajaxetatpdo( $typepdo_id = null, $user_id = null, $decisionpdo_id = null, $retouravistechnique = null, $vuavistechnique = null, $complet = null, $incomplet = null ) {
			$dataTypepdo_id = Set::extract( $this->params, 'form.typepdo_id' );
			$dataUser_id = Set::extract( $this->params, 'form.user_id' );

			$dataComplet = Set::extract( $this->params, 'form.complet' );
			$dataDecisionpdo_id = Set::extract( $this->params, 'form.decisionpdo_id' );

			$dataAvistech = null;
			$dataAvisvalid = null;
			$etatdossierpcg = null;

			if ( isset($this->params['form']['dossierpcg66_id'] ) && $this->params['form']['dossierpcg66_id'] != 0 ) {
				$dossierpcg66 = $this->Dossierpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Dossierpcg66.id' => Set::extract( $this->params, 'form.dossierpcg66_id' )
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
					}
				}
			}
			$etatdossierpcg = $this->Dossierpcg66->etatDossierPcg66( $dataTypepdo_id, $dataUser_id, $dataDecisionpdo_id, $dataAvistech, $dataAvisvalid, $retouravistechnique, $vuavistechnique, /*$iscomplet,*/ $etatdossierpcg );

			$this->Dossierpcg66->etatPcg66( $this->data );
			$this->set( compact( 'etatdossierpcg' ) );
			Configure::write( 'debug', 0 );
			$this->render( 'ajaxetatpdo', 'ajax' );
		}

		/**
		*
		*/

		public function index( $foyer_id = null ) {

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
						'Decisiondossierpcg66'
					)
				)
			);
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
		*/

		/** ********************************************************************
		*
		*** *******************************************************************/

		protected function _add_edit( $id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $id ), 'invalidParameter' );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				if( $this->action == 'edit' ) {
					$foyer_id = $this->Dossierpcg66->field( 'foyer_id', array( 'id' => $id ) );
				}
				else {
					$foyer_id = $id;
				}
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
			if( !empty( $this->data ) ) {
				$this->Dossierpcg66->begin();

				$saved = $this->Dossierpcg66->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );
				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers(
						$dir,
						!Set::classicExtract( $this->data, "Dossierpcg66.haspiecejointe" ),
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
				$this->data = $dossierpcg66;
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
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		function view( $id = null ) {
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
                            'order' => array( 'Decisiondossierpcg66.created DESC' )
                        )
					)
				)
			);
			$this->assert( !empty( $dossierpcg66 ), 'invalidParameter' );

			$foyer_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.foyer_id' );

			// Retour à l'entretien en cas de retour
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'dossierspcgs66', 'action' => 'index', $foyer_id ) );
			}

			$this->_setOptions();
			$this->set( compact( 'dossierpcg66' ) );
			$this->set( 'foyer_id', $foyer_id );

			$this->set( 'urlmenu', '/dossierspcgs66/index/'.$foyer_id );
		}

		/**
		*
		*/

		public function delete( $id ) {
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