<?php
	class StatutsdecisionspdosController extends AppController 
	{

		public $name = 'Statutsdecisionspdos';
		public $uses = array( 'Statutdecisionpdo', 'Propopdo', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Theme' );
		public $components = array( 'Default' );
		
		public $commeDroit = array(
			'view' => 'Statutsdecisionspdos:index',
			'add' => 'Statutsdecisionspdos:edit'
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

		protected function _add_edit(){
			$args = func_get_args();
			$this->Default->{$this->action}( $args );
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