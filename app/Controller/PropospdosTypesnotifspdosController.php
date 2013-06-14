<?php
	/**
	 * Code source de la classe PropospdosTypesnotifspdosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe PropospdosTypesnotifspdosController ...
	 *
	 * @package app.Controller
	 */
	class PropospdosTypesnotifspdosController extends AppController
	{

		public $name = 'PropospdosTypesnotifspdos';
		public $uses = array( 'PropopdoTypenotifpdo', 'Typenotifpdo', 'Propopdo'/* , 'Dossier' */ );
		public $commeDroit = array(
			'add' => 'PropospdosTypesnotifspdos:edit'
		);

		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
		}

		public function index( $pdo_id = null ) {
			$this->assert( valid_int( $pdo_id ), 'invalidParameter' );
			$dossier_id = $this->Propopdo->dossierId( $pdo_id );

			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'dossierspdo', 'action' => 'index', $dossier_id ) );
			}

			$notifs = $this->PropopdoTypenotifpdo->find(
					'all', array(
				'conditions' => array(
					'PropopdoTypenotifpdo.propopdo_id' => $pdo_id
				)
					)
			);
			$this->set( 'pdo_id', $pdo_id );
			$this->set( compact( 'notifs', 'dossier_id' ) );
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
			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				if( $this->action == 'edit' ) {
					$id = $this->PropopdoTypenotifpdo->field( 'propopdo_id', array( 'id' => $id ) );
				}
				$this->redirect( array( 'action' => 'index', $id ) );
			}

			if( $this->action == 'add' ) {
				$pdo_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$propotype_id = $id;
				$qd_propotype = array(
					'conditions' => array(
						'PropopdoTypenotifpdo.id' => $propotype_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$propotype = $this->PropopdoTypenotifpdo->find( 'first', $qd_propotype );

				$this->assert( !empty( $propotype ), 'invalidParameter' );
				$pdo_id = Set::classicExtract( $propotype, 'PropopdoTypenotifpdo.propopdo_id' );
			}

			$dossier_id = $this->Propopdo->dossierId( $pdo_id );

			$this->set( 'dossier_id', $dossier_id );

			if( !empty( $this->request->data ) ) {

				if( $this->PropopdoTypenotifpdo->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'propospdos_typesnotifspdos', 'action' => 'index', $id ) );
				}
			}
			else {
				if( $this->action == 'edit' ) {
					$this->request->data = $propotype;
				}
				else {
					$this->request->data['PropopdoTypenotifpdo']['propopdo_id'] = $pdo_id;
				}
			}
			$this->render( 'add_edit' );
		}

	}
?>