<?php
	class CompositionsregroupementsepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		protected function _setOptions() {
			$options = array();
			$this->set( compact( 'options' ) );
		}


		public function index() {
			$this->paginate = array(
				'limit' => 10
			);

			$this->_setOptions();
			$this->set( 'regroupementeps', $this->paginate( $this->Compositionregroupementep->Regroupementep ) );
			$compteurs = array(
				'Regroupementep' => $this->Compositionregroupementep->Regroupementep->find( 'count' ),
				'Fonctionmembreep' => $this->Compositionregroupementep->Fonctionmembreep->find( 'count' )
			);
			$this->set( compact( 'compteurs' ) );
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