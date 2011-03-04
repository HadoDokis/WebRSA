<?php

	class Listesanctionseps58Controller extends AppController {
	
		public $helpers = array( 'Default', 'Default2', 'Xhtml' );
		
		public function beforeFilter() {
			parent::beforeFilter();
		}
		
		public function index() {
			$sanctions = $this->Listesanctionep58->find(
				'all',
				array(
					'order' => array( 'Listesanctionep58.rang ASC' )
				)
			);
			$this->set( 'sanctionsValides', $this->Listesanctionep58->checkValideListe() );
			$this->set( compact( 'sanctions' ) );
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
			if ( !empty( $this->data ) ) {
				$this->Listesanctionep58->create( $this->data );
				$success = $this->Listesanctionep58->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			elseif ( $this->action == 'edit' ) {
				$this->data = $this->Listesanctionep58->find(
					'first',
					array(
						'conditions' => array(
							'Listesanctionep58.id' => $id
						)
					)
				);
			}
			
			$this->render( null, null, '_add_edit' );
		}
	
	}

?>