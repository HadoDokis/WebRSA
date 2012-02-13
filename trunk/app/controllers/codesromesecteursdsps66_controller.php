<?php
	class Codesromesecteursdsps66Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public $commeDroit = array(
			'add' => 'Codesromesecteursdsps66:edit'
		);

		protected function _setOptions() {
			$options = array();
			$this->set( compact( 'options' ) );
		}

		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Coderomesecteurdsp66.id',
					'Coderomesecteurdsp66.code',
					'Coderomesecteurdsp66.name'
				),
				'contain' => false,
				'limit' => 10
			);
			$this->_setOptions();
			$this->set( 'codesromesecteursdsps66', $this->paginate( $this->Coderomesecteurdsp66 ) );
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
				$this->Coderomesecteurdsp66->create( $this->data );
				$success = $this->Coderomesecteurdsp66->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Coderomesecteurdsp66->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Coderomesecteurdsp66.id' => $id )
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
			$success = $this->Coderomesecteurdsp66->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>