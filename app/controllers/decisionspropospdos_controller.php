<?php
	class DecisionspropospdosController extends AppController
	{
		public $name = 'Decisionspropospdos';
		/**
		* @access public
		*/

		public $components = array( 'Default', 'Gedooo' );

		public $helpers = array( 'Default2', 'Ajax' );
		public $uses = array( 'Decisionpropopdo', 'Option', 'Pdf'  );


		public $commeDroit = array(
			'view' => 'Decisionspropospdos:index',
			'add' => 'Decisionspropospdos:edit'
		);

		/**
		*
		*/

		protected function _options() {
			$options = $this->Decisionpropopdo->allEnumLists();

			$this->set( 'decisionpdo', $this->Decisionpropopdo->Decisionpdo->find( 'list' ) );

			return $options;
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}


		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->Decisionpropopdo->begin();
			$this->set( 'options', $this->_options() );

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$propopdo_id = $id;

				$propopdo = $this->Decisionpropopdo->Propopdo->findById( $id, null, null, -1 );
				$this->set( 'propopdo', $propopdo );
				$personne_id = Set::classicExtract( $propopdo, 'Propopdo.personne_id' );
				$dossier_id = $this->Decisionpropopdo->Propopdo->Personne->dossierId( $personne_id );
			}
			else if( $this->action == 'edit' ) {
				$decisionpropopdo_id = $id;
				$decisionpropopdo = $this->Decisionpropopdo->findById( $decisionpropopdo_id, null, null, 1 );
				$this->assert( !empty( $decisionpropopdo ), 'invalidParameter' );
				$propopdo_id = Set::classicExtract( $decisionpropopdo, 'Decisionpropopdo.propopdo_id' );
				$personne_id = Set::classicExtract( $decisionpropopdo, 'Propopdo.personne_id' );
				$dossier_id = $this->Decisionpropopdo->Propopdo->Personne->dossierId( $personne_id );
			}

			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'propopdo_id', $propopdo_id );
			$this->set( 'personne_id', $personne_id );

			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Decisionpropopdo->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			if( !empty( $this->data ) ){
				if( $this->Decisionpropopdo->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = true;

					$saved = $this->Decisionpropopdo->save( $this->data );

					if( $saved ) {
						$saved = $this->Decisionpropopdo->Propopdo->updateEtat( $this->Decisionpropopdo->id );
					}

					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Decisionpropopdo->commit(); // FIXME
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'propospdos', 'action' => 'edit', $propopdo_id ) );
					}
					else {
						$this->Decisionpropopdo->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$this->Decisionpropopdo->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			elseif( $this->action == 'edit' )
				$this->data = $decisionpropopdo;

			$this->Decisionpropopdo->commit();

			$this->set( 'urlmenu', '/propospdos/index/'.$personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*   Enregistrement du courrier de proposition lors de l'enregistrement de la proposition
		*/
		public function decisionproposition( $id ) {
			$this->assert( !empty( $id ), 'error404' );

			$pdf = $this->Decisionpropopdo->getStoredPdf( $id );

			$this->assert( !empty( $pdf ), 'error404' );
			$this->assert( !empty( $pdf['Pdf']['document'] ), 'error500' ); // FIXME: ou en faire l'impression ?

			$this->Gedooo->sendPdfContentToClient( $pdf['Pdf']['document'], "Proposition_decision.pdf" );
		}

		/**
		*
		*/

		public function view( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$decisionpropopdo = $this->Decisionpropopdo->find(
				'first',
				array(
					'conditions' => array(
						'Decisionpropopdo.id' => $id,
					),
					'contain' => array(
						'Propopdo' => array(
							'fields' => array( 'personne_id' )
						),
						'Decisionpdo' => array(
							'fields' => array( 'libelle' )
						)
					)
				)
			);

			$this->assert( !empty( $decisionpropopdo ), 'invalidParameter' );

			$this->set( 'dossier_id', $this->Decisionpropopdo->dossierId( $id ) );

			// Retour à la page d'édition de la PDO
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'propospdos', 'action' => 'edit', Set::classicExtract( $decisionpropopdo, 'Decisionpropopdo.propopdo_id' ) ) );
			}

			$options = $this->Decisionpropopdo->enums();
			$this->set( compact( 'decisionpropopdo', 'options' ) );
			$this->set( 'urlmenu', '/propospdos/index/'.$decisionpropopdo['Propopdo']['personne_id'] );
		}

		/**
		* Suppression de la proposition de décision
		*/

		public function delete( $id ) {
			$decisionpropopdo = $this->Decisionpropopdo->findById( $id, null, null, -1 );
			$pdo_id = Set::classicExtract( $decisionpropopdo, 'Decisionpropopdo.propopdo_id' );

			$success = $this->Decisionpropopdo->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'controller' => 'propospdos', 'action' => 'edit', $pdo_id ) );
		}
	}
?>