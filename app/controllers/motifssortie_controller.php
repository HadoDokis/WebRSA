<?php

	class MotifssortieController extends AppController 
	{
		public $name = 'Motifssortie';
		public $uses = array( 'Motifsortie', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array( 'Default' );
		
		public $commeDroit = array(
			'view' => 'Motifssortie:index',
			'add' => 'Motifssortie:edit'
		);

		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
			$this->set(
				Inflector::tableize( $this->modelClass ),
				$this->paginate( $this->modelClass )
			);
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

		function _add_edit( $id = null){
//            $args = func_get_args();
//            $this->Default->{$this->action}( $args );
			if( !empty( $this->data ) ) {
				$this->Motifsortie->create( $this->data );
				$success = $this->Motifsortie->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Motifsortie->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Motifsortie.id' => $id )
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
			$this->Default->delete( $id );
		}

		/**
		*
		*/

		public function view( $id ) {

			$this->Default->view( $id );
		}

	}
?>
