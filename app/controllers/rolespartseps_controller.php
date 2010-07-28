<?php
	class RolespartsepsController extends AppController
	{
		public $name = 'Rolespartseps';
        
		var $commeDroit = array(
			'view' => 'Rolespartseps:index',
			'add' => 'Rolespartseps:edit'
		);

		/**
		*
		*/

		public function index() {
			$this->Default->index();
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
