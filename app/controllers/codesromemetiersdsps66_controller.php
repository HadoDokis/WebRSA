<?php
	class Codesromemetiersdsps66Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public $commeDroit = array(
			'add' => 'Codesromemetiersdsps66:edit'
		);

		protected function _setOptions() {
			$options['Coderomesecteurdsp66'] = $this->Coderomemetierdsp66->Coderomesecteurdsp66->find(
				'list',
				array(
					'contain' => false,
					'order' => array( 'Coderomesecteurdsp66.code' )
				)
			);
			$this->set( compact( 'options' ) );
		}

		public function index() {
			$this->Coderomemetierdsp66->forceVirtualFields = true;
			$this->paginate = array(
				'fields' => array(
					'Coderomemetierdsp66.id',
					'Coderomemetierdsp66.code',
					'Coderomemetierdsp66.name',
					'Coderomesecteurdsp66.intitule'
				),
				'contain' => array(
					'Coderomesecteurdsp66'
				),
				'limit' => 10
			);
			$this->_setOptions();
			$this->set( 'codesromemetiersdsps66', $this->paginate( $this->Coderomemetierdsp66 ) );
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
				$this->Coderomemetierdsp66->create( $this->data );
				$success = $this->Coderomemetierdsp66->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Coderomemetierdsp66->find(
					'first',
					array(
						'contain' => array(
							'Coderomesecteurdsp66'
						),
						'conditions' => array( 'Coderomemetierdsp66.id' => $id )
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
			$success = $this->Coderomemetierdsp66->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>