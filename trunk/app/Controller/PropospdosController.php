<?php
	/**
	 * Code source de la classe PropospdosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe PropospdosController ...
	 *
	 * @package app.Controller
	 */
	class PropospdosController extends AppController
	{
		public $name = 'Propospdos';

		public $uses = array( 'Propopdo', 'Situationdossierrsa', 'Option', 'Typepdo', 'Typenotifpdo', 'Decisionpdo', 'Suiviinstruction', 'Piecepdo', 'Traitementpdo', 'Originepdo', 'Statutpdo', 'Statutdecisionpdo', 'Situationpdo', 'Referent', 'Personne', 'Dossier', 'Pdf' );

		public $components = array( 'Fileuploader', 'Gedooo.Gedooo', 'Jetons2' );

		public $helpers = array( 'Default', 'Default2', 'Cake1xLegacy.Ajax', 'Fileuploader' );

		public $commeDroit = array(
			'view' => 'Propospdos:index',
			'add' => 'Propospdos:edit'
		);

		public $aucunDroit = array( 'ajaxstruct', 'ajaxetatpdo', 'ajaxetat1', 'ajaxetat2', 'ajaxetat3', 'ajaxetat4', 'ajaxetat5', 'ajaxfichecalcul', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		protected function _setOptions() {
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'pieecpres', $this->Option->pieecpres() );
			$this->set( 'commission', $this->Option->commission() );
			$this->set( 'motidempdo', $this->Option->motidempdo() );
			$this->set( 'motifpdo', $this->Option->motifpdo() );
			$this->set( 'categoriegeneral', $this->Option->sect_acti_emp() );
			$this->set( 'categoriedetail', $this->Option->emp_occupe() );

			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'typepdo', $this->Typepdo->find( 'list' ) );
			$this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
			$this->set( 'decisionpdo', $this->Decisionpdo->find( 'list' ) );
			$this->set( 'typetraitement', $this->Propopdo->Traitementpdo->Traitementtypepdo->find( 'list' ) );
			$this->set( 'originepdo', $this->Originepdo->find( 'list' ) );
			$this->set( 'statutlist', $this->Statutpdo->find( 'list' ) );
			$this->set( 'situationlist', $this->Situationpdo->find( 'list' ) );
			$this->set( 'serviceinstructeur', $this->Propopdo->Serviceinstructeur->listOptions() );
			$this->set( 'orgpayeur', array( 'CAF' => 'CAF', 'MSA' => 'MSA' ) );
			$this->set( 'gestionnaire', $this->User->find(
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

			$options = $this->Propopdo->allEnumLists();
			$options = Set::insert( $options, 'Suiviinstruction.typeserins', $this->Option->typeserins() );
			$this->set( 'structs', $this->Propopdo->Structurereferente->listeParType( 'pdo' ) );
			$this->set( compact( 'options' ) );
		}

		public function ajaxstruct( $structurereferente_id = null ) {
			$dataStructurereferente_id = Set::extract( $this->request->data, 'Propopdo.structurereferente_id' );
			$structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );

			$qd_struct = array(
				'conditions' => array(
					'Structurereferente.id' => $structurereferente_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$struct = $this->Structurereferente->find('first', $qd_struct);

			$this->set( 'struct', $struct );

			Configure::write( 'debug', 0 );
			$this->render( 'ajaxstruct', 'ajax' );
		}

		public function ajaxetatpdo( $typepdo_id = null, $user_id = null, $complet = null, $incomplet = null ) {
			$dataTypepdo_id = Set::extract( $this->request->params, 'form.typepdo_id' );
			$dataUser_id = Set::extract( $this->request->params, 'form.user_id' );

			$dataComplet = Set::extract( $this->request->params, 'form.complet' );
			$dataIncomplet = Set::extract( $this->request->params, 'form.incomplet' );
			if( !empty( $dataComplet ) )
				$iscomplet = 'COM';
			elseif( !empty( $dataIncomplet ) )
				$iscomplet = 'INC';
			else
				$iscomplet = null;

			$dataDecisionpdo_id = null;
			$dataAvistech = null;
			$dataAvisvalid = null;

			if( isset( $this->request->data['propopdo_id'] ) && $this->request->data['propopdo_id'] != 0 ) {
				$decisionpropopdo = $this->Propopdo->Decisionpropopdo->find(
						'first', array(
					'conditions' => array(
						'Decisionpropopdo.propopdo_id' => Set::extract( $this->request->params, 'form.propopdo_id' )
					),
					'contain' => false,
					'order' => array(
						'Decisionpropopdo.datedecisionpdo DESC',
						'Decisionpropopdo.id DESC'
					)
						)
				);

				$dataDecisionpdo_id = Set::extract( $decisionpropopdo, 'Decisionpropopdo.decisionpdo_id' );
				$dataAvistech = Set::extract( $decisionpropopdo, 'Decisionpropopdo.avistechnique' );
				$dataAvisvalid = Set::extract( $decisionpropopdo, 'Decisionpropopdo.validationdecision' );

				$etatdossierpdo = $this->Propopdo->etatDossierPdo( $dataTypepdo_id, $dataUser_id, $dataDecisionpdo_id, $dataAvistech, $dataAvisvalid, $iscomplet, $this->request->data['propopdo_id'] );
			}
			else {
				$etatdossierpdo = $this->Propopdo->etatDossierPdo( $dataTypepdo_id, $dataUser_id, $dataDecisionpdo_id, $dataAvistech, $dataAvisvalid, $iscomplet );
			}

			$this->Propopdo->etatPdo( $this->request->data );
			$this->set( compact( 'etatdossierpdo' ) );
			Configure::write( 'debug', 0 );
			$this->render( 'ajaxetatpdo', 'ajax' );
		}

		public function index( $personne_id = null ) {
			$nbrPersonnes = $this->Propopdo->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );
			//$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );
			$this->assert( ( $nbrPersonnes >= 1 ), 'invalidParameter' );

			$conditions = array( 'Propopdo.personne_id' => $personne_id );

			/// Récupération des listes des PDO
			$options = $this->Propopdo->prepare( 'propopdo', array( 'conditions' => $conditions ) );
			$pdos = $this->Propopdo->find( 'all', $options );

			$this->set( 'personne_id', $personne_id );
			$this->_setOptions();
			$this->set( 'pdos', $pdos );
		}

		public function view( $pdo_id = null ) {
			$this->assert( valid_int( $pdo_id ), 'invalidParameter' );

			$conditions = array( 'Propopdo.id' => $pdo_id );

			$options = $this->Propopdo->prepare( 'propopdo', array( 'conditions' => $conditions ) );

			$pdo = $this->Propopdo->find(
				'first',
				array(
					'conditions' => array(
						'Propopdo.id' => $pdo_id
					),
					'contain' => array(
						'Fichiermodule',
						'Typepdo',
						'Decisionpropopdo'
					)
				)
			);

			// Afficahge des traitements liés à une PDO
			$traitements = $this->{$this->modelClass}->Traitementpdo->find(
					'all', array(
				'conditions' => array(
					'propopdo_id' => $pdo_id
				),
				'contain' => array(
					'Descriptionpdo',
					'Traitementtypepdo'
				)
					)
			);
			$this->set( compact( 'traitements' ) );

			// Afficahge des propositions de décisions liées à une PDO
			$propositions = $this->{$this->modelClass}->Decisionpropopdo->find(
					'all', array(
				'conditions' => array(
					'propopdo_id' => $pdo_id
				),
				'contain' => array(
					'Decisionpdo'
				)
					)
			);
			$this->set( compact( 'propositions' ) );

			// Retour à la apge d'index une fois que l'on clique sur Retour
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'propospdos', 'action' => 'index', Set::classicExtract( $pdo, 'Propopdo.personne_id' ) ) );
			}
			$this->set( 'pdo', $pdo );
			$this->_setOptions();

			$this->set( 'personne_id', $pdo['Propopdo']['personne_id'] );
			$this->set( 'urlmenu', '/propospdos/index/'.$pdo['Propopdo']['personne_id'] );

			$this->render( 'view_'.Configure::read( 'nom_form_pdo_cg' ) );
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

		/**		 * *******************************************************************
		 *
		 * ** ****************************************************************** */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**		 * *******************************************************************
		 *
		 * ** ****************************************************************** */
		protected function _add_edit( $id = null ) {
			$fichiers = array( );
			if( $this->action == 'add' ) {
				$personne_id = $id;
				$dossier_id = $this->Personne->dossierId( $personne_id );
			}
			elseif( $this->action == 'edit' ) {
				$pdo_id = $id;
				$qd_pdo = array(
					'conditions' => array(
						'Propopdo.id' => $pdo_id
					),
                    'joins' => array(
                      $this->Propopdo->join( 'Decisionpropopdo' )
                    ),
					'fields' => array_merge(
                        $this->Propopdo->fields(),
                        $this->Propopdo->Decisionpropopdo->fields()
                    ),
					'order' => null,
					'recursive' => -1
				);
				$pdo = $this->Propopdo->find( 'first', $qd_pdo );


				$this->assert( !empty( $pdo ), 'invalidParameter' );
				$personne_id = Set::classicExtract( $pdo, 'Propopdo.personne_id' );
				$dossier_id = $this->Personne->dossierId( $personne_id );

				$this->set( 'pdo_id', $pdo_id );
			}

			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				if( $this->action == 'edit' ) {
					$id = $this->Propopdo->field( 'personne_id', array( 'id' => $id ) );
				}
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $id ) );
			}

//			$this->Dossier->Suiviinstruction->order = 'Suiviinstruction.id DESC';

			$qd_dossier = array(
				'conditions' => array(
					'Dossier.id' => $personne_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$dossier = $this->Dossier->find( 'first', $qd_dossier );

			// Recherche de la dernière entrée des suivis instruction  associée au dossier
			$suiviinstruction = $this->Dossier->Suiviinstruction->find(
                'first',
                array(
                    'conditions' => array( 'Suiviinstruction.dossier_id' => $dossier_id ),
                    'order' => array( 'Suiviinstruction.date_etat_instruction DESC' ),
                    'recursive' => -1
                )
			);
			$dossier = Set::merge( $dossier, $suiviinstruction );
			$this->set( compact( 'dossier' ) );

			$this->set( 'referents', $this->Referent->find( 'list' ) );
//debug($dossier);
			/**
			 *   FIN
			 */
			//Essai de sauvegarde
			if( !empty( $this->request->data ) ) {
				$this->Propopdo->begin();

				// Nettoyage des Propopdos
				$keys = array_keys( $this->Propopdo->schema() );
				$defaults = array_combine( $keys, array_fill( 0, count( $keys ), null ) );
				unset( $defaults['id'] );

				$this->request->data['Propopdo'] = Set::merge( $defaults, $this->request->data['Propopdo'] );

                $this->request->data['Decisionpropopdo'] = array( $this->request->data['Decisionpropopdo'] );
//debug($this->request->data);
				$saved = $this->Propopdo->saveAssociated( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) );
				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers(
									$dir, !Set::classicExtract( $this->request->data, "Propopdo.haspiece" ), ( ( $this->action == 'add' ) ? $this->Propopdo->id : $id )
							) && $saved;
				}

				if( $saved ) {
					$this->Propopdo->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'propospdos', 'action' => 'index', $personne_id ) );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Propopdo->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			//Affichage des données
			elseif( $this->action == 'edit' ) {
				$this->request->data = $pdo;
				$fichiers = $this->Fileuploader->fichiers( $pdo['Propopdo']['id'] );

				$this->set( 'etatdossierpdo', $pdo['Propopdo']['etatdossierpdo'] );
			}

			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/propospdos/index/'.$personne_id );
			$this->set( 'fichiers', $fichiers );
			$this->_setOptions();
			$this->render( 'add_edit_'.Configure::read( 'nom_form_pdo_cg' ) );
		}

		/**
		 *   Téléchargement des fichiers préalablement associés à un traitement donné
		 */
		public function download( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );
			$this->Fileuploader->download( $fichiermodule_id );
		}

		/**
		 *    Génération du courrier de PDO pour le bénéficiaire
		 */
		public function printCourrier( $propopdo_id ) {

			$pdf = $this->Propopdo->getCourrierPdo( $propopdo_id, $this->Session->read( 'Auth.User.id' ) );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'CourrierPdo.pdf' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier d\'information', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

	}
?>