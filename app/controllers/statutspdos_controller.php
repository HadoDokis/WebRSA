<?php

    class StatutspdosController extends AppController 
    {
        var $name = 'Statutspdos';
        var $uses = array( 'Statutpdo', 'Propopdo', 'Option' );
        var $helpers = array( 'Xform', 'Default', 'Theme' );
        var $components = array( 'Default' );
        
		var $commeDroit = array(
			'view' => 'Statutspdos:index',
			'add' => 'Statutspdos:edit'
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
