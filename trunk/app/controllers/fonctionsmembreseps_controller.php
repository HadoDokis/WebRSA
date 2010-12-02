<?php
	class FonctionsmembresepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public function beforeFilter() {
		}

		
		protected function _setOptions() {
			$options = array();
			$this->set( compact( 'options' ) );
		}


		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Fonctionmembreep.id',
					'Fonctionmembreep.name'
				),
				'limit' => 10
			);

			$this->_setOptions();
			$this->set( 'fonctionmembreeps', $this->paginate( $this->Fonctionmembreep ) );
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
			if( !empty( $this->data ) ) {
				$this->Fonctionmembreep->create( $this->data );
				$success = $this->Fonctionmembreep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Fonctionmembreep->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Fonctionmembreep.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
			}

			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Fonctionmembreep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}




	}
?>