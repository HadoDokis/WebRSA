<?php
	class TraitementstypespdosController extends AppController
	{
		public $name = 'Traitementstypespdos';

		/**
		* @access public
		*/

		public $components = array( 'Default' );

		public $helpers = array( 'Default', 'Default2' );
		
		public $commeDroit = array(
			'view' => 'Traitementstypespdos:index',
			'add' => 'Traitementstypespdos:edit'
		);

		/**
		*
		*/

		public function index() {
			$this->set(
				Inflector::tableize( $this->modelClass ),
				$this->paginate( $this->modelClass )
			);
			$this->{$this->modelClass}->recursive = 0;
			$this->Default->search(
				$this->data
			);
		}

		/**
		*
		*/

		public function view( $id = null ) {
			$this->{$this->modelClass}->recursive = -1;
			$this->Default->view( $id );
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

		public function edit( $id = null ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function _add_edit( $id = null ) {
			$this->{$this->modelClass}->recursive = -1;
			$this->Default->_add_edit( $id, null, null, array( 'action' => 'index' ) );
//             $this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id = null ) {
			$this->Default->delete( $id, array( 'action' => 'index' ) );
		}
	}
?>
