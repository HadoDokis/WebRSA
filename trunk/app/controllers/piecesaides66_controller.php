<?php
    class Piecesaides66Controller extends AppController
    {
        public $name = 'Piecesaides66';
        
		var $commeDroit = array(
			'view' => 'Piecesaides66:index',
			'add' => 'Piecesaides66:edit'
		);

        /**
        *
        */

        public function index() {
//             $this->set(
//                 Inflector::tableize( $this->modelClass )
//             );
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
