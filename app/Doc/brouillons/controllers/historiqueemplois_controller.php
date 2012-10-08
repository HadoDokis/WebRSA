<?php
	class HistoriqueemploisController extends AppController
	{
		public $name = 'Historiqueemplois';

		public $uses = array( 'Historiqueemploi' );

		public $helpers = array( 'Xpaginator2', 'Type2' );

		/**
		*
		*/
		public function index( $personne_id ) {
			$this->paginate = array(
				'conditions' => array(
					'Historiqueemploi.personne_id' => $personne_id
				),
				'contain' => false,
				'order' => array( 'Historiqueemploi.datedebut DESC' )
			);

			$historiqueemplois = $this->paginate( $this->Historiqueemploi );

			$options = $this->Historiqueemploi->enums();
			$this->set( compact( 'historiqueemplois', 'options' ) );
			$this->set( 'personne_id', $personne_id );
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
		protected function _add_edit( $id ) {
			if( !empty( $this->data ) ) {
				$personne_id = $this->data['Historiqueemploi']['personne_id'];

				$this->Historiqueemploi->create( $this->data );
				$success = $this->Historiqueemploi->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Historiqueemploi->find(
					'first',
					array(
						'conditions' => array(
							'Historiqueemploi.id' => $id
						)
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
				$personne_id = $this->data['Historiqueemploi']['personne_id'];
			}
			else if( $this->action == 'add' ) {
				$personne_id = $id;
			}

			$options = $this->Historiqueemploi->enums();

			$this->set( compact( 'options' ) );
			$this->set( 'personne_id', $personne_id );

			$this->render( 'add_edit' );
		}

		/**
		*
		*/
		public function delete( $id ) {
			$this->assert( is_numeric( $id ), 'error404' );

			$historiqueemploi = $this->Historiqueemploi->find(
				'first',
				array(
					'conditions' => array(
						'Historiqueemploi.id' => $id
					),
					'contain' => false
				)
			);

			$this->assert( !empty( $historiqueemploi ), 'error404' );

			if( $this->Historiqueemploi->delete( $id ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
			}
			else {
				$this->Session->setFlash( 'Impossible de supprimer l\'enregistrement', 'flash/error' );
			}

			$this->redirect( array( 'action' => 'index', $historiqueemploi['Historiqueemploi']['personne_id'] ) );
		}
	}
?>