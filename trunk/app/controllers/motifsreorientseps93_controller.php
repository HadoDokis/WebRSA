<?php
	class Motifsreorientseps93Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Motifreorientep93.id',
					'Motifreorientep93.name'
				),
				'limit' => 10
			);

			$this->set( 'motifsreorientseps93', $this->paginate( $this->Motifreorientep93 ) );
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
				$this->Motifreorientep93->create( $this->data );
				$success = $this->Motifreorientep93->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Motifreorientep93->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Motifreorientep93.id' => $id )
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
			$success = $this->Motifreorientep93->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>