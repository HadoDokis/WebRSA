<?php
	/**
	 * Code source de la classe Proposdecisionscuis66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Proposdecisionscuis66Controller ... (CG 66).
	 *
	 * @package app.Controller
	 */
	class Proposdecisionscuis66Controller extends AppController
	{
		public $name = 'Proposdecisionscuis66';

		public $uses = array( 'Propodecisioncui66', 'Option' );

		public $helpers = array( 'Default2', 'Default' );

		public $components = array( 'Jetons2', 'Default', 'Gedooo.Gedooo', 'DossiersMenus' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'delete' => 'delete',
			'edit' => 'udate',
			'notifelucui' => 'read',
			'propositioncui' => 'read',
		);

		/**
		 *
		 */
		protected function _setOptions() {
			$options = $this->Propodecisioncui66->enums();

			$this->set( 'qual', $this->Option->qual() );
			$options = Set::merge(
				$this->Propodecisioncui66->Cui->enums(),
				$options
			);
			$this->set( 'options', $options );
		}

		/**
		 *
		 * @param integer $cui_id
		 */
		public function propositioncui( $cui_id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Propodecisioncui66->Cui->personneId( $cui_id ) ) ) );

			$nbrCuis = $this->Propodecisioncui66->Cui->find( 'count', array( 'conditions' => array( 'Cui.id' => $cui_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrCuis == 1 ), 'invalidParameter' );

			$cui = $this->Propodecisioncui66->Cui->find(
				'first',
				array(
					'conditions' => array(
						'Cui.id' => $cui_id
					),
					'contain' => false,
					'recursive' => -1
				)
			);
			$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );

			$proposdecisionscuis66 = $this->Propodecisioncui66->find(
				'all',
				array(
					'conditions' => array(
						'Propodecisioncui66.cui_id' => $cui_id
					),
					'recursive' => -1,
					'contain' => false
				)
			);

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui_id', $cui_id );
			$this->set( compact( 'proposdecisionscuis66' ) );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );

			// Retour à la liste des CUI en cas de retour
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $personne_id ) );
			}
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
			$this->assert( valid_int( $id ), 'invalidParameter' );

			if( $this->action == 'add' ) {
				$cui_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$propodecisioncui66_id = $id;
				$propodecisioncui66 = $this->Propodecisioncui66->find(
					'first',
					array(
						'conditions' => array(
							'Propodecisioncui66.id' => $propodecisioncui66_id
						),
						'contain' => array(
							'Cui'
						),
						'recursive' => -1
					)
				);
				$this->set( 'propodecisioncui66', $propodecisioncui66 );

				$cui_id = Set::classicExtract( $propodecisioncui66, 'Propodecisioncui66.cui_id' );
			}

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Propodecisioncui66->Cui->personneId( $cui_id ) ) ) );


			// CUI en lien avec la proposition
			$cui = $this->Propodecisioncui66->Cui->find(
				'first',
				array(
					'conditions' => array(
						'Cui.id' => $cui_id
					),
					'contain' => false,
					'recursive' => -1
				)
			);

			$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );

			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui', $cui );
			$this->set( 'cui_id', $cui_id );

			$dossier_id = $this->Propodecisioncui66->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'controller' => 'proposdecisionscuis66', 'action' => 'propositioncui', $cui_id ) );
			}

			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );

			if ( !empty( $this->request->data ) ) {
				$this->Propodecisioncui66->begin();

				if( $this->Propodecisioncui66->saveAll( $this->request->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Propodecisioncui66->save( $this->request->data );

					if( $saved ) {
						$saved = $this->Propodecisioncui66->Cui->updatePositionFromPropodecisioncui66( $this->Propodecisioncui66->id ) && $saved;
					}

					if( $saved ) {
						$this->Propodecisioncui66->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'proposdecisionscuis66', 'action' => 'propositioncui', $cui_id ) );
					}
					else {
						$this->Propodecisioncui66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

				}
			}
			else{
				if( $this-> action == 'edit' ){
					$this->request->data = $propodecisioncui66;
				}
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			$this->render( 'add_edit' );
		}


		/**
		 * Imprime la notification pour récupérer l'avis de l'élu, suite à l'avis de la MNE
		 *
		 * @param integer $id L'id de la proposition du CUI que l'on veut imprimer
		 * @return void
		 */
		public function notifelucui( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Propodecisioncui66->personneId( $id ) ) );

			$pdf = $this->Propodecisioncui66->getNotifelucuiPdf( $id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'NotifElu_%d_%d.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier à destination de l\'élu.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer( null,true ) );
			}
		}

		/**
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Propodecisioncui66->personneId( $id ) ) );

			$this->Default->delete( $id );
		}
	}
?>
