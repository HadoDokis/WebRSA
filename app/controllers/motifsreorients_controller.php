<?php
	class MotifsreorientsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Motifreorient.id',
					'Motifreorient.name'
				),
				'limit' => 10
			);

			$this->set( 'motifsreorients', $this->paginate( $this->Motifreorient ) );
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
				$this->Motifreorient->create( $this->data );
				$success = $this->Motifreorient->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Motifreorient->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Motifreorient.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
			}

			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Motifreorient->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>