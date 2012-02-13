<?php

	class OriginespdosController extends AppController 
	{
		public $name = 'Originespdos';
		public $uses = array( 'Originepdo', 'Propopdo', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array( 'Default' );
		
		public $commeDroit = array(
			'view' => 'Originespdos:index',
			'add' => 'Originespdos:edit'
		);

		protected function _setOptions() {
			$options = $this->Originepdo->enums();
			$this->set( compact ( 'options' ) );
		}

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
			$this->_setOptions();
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

		function _add_edit(){
			$args = func_get_args();
			$this->_setOptions();
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
