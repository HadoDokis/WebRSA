<?php
	class RolesepsController extends AppController
	{
		public $name = 'Roleseps';
        
		var $commeDroit = array(
			'view' => 'Roleseps:index',
			'add' => 'Roleseps:edit'
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
