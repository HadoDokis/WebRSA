<?php

	class SituationspdosController extends AppController
	{
		public $name = 'Situationspdos';
		public $uses = array( 'Situationpdo', 'Propopdo', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Situationspdos:index',
			'add' => 'Situationspdos:edit'
		);

// 		protected function _setOptions() {
// 			$modelelist = $this->Situationpdo->Modeletypecourrierpcg66->find(
// 				'list',
// 				array(
// 					'fields' => array(
// 						'Modeletypecourrierpcg66.id',
// 						'Modeletypecourrierpcg66.name'
// 					)
// 				)
// 			);
// 			$this->set( 'modelelist', $modelelist );
// 		}
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
// 			$this->_setOptions();
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
// 			$this->_setOptions();
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