<?php

    class Sitescovs58Controller extends AppController 
    {
        var $name = 'Sitescovs58';
        var $uses = array( 'Sitecov58' );
        var $helpers = array( 'Xform', 'Default2', 'Theme' );
        var $components = array( 'Default' );
        
        var $commeDroit = array(
            'view' => 'Sitescovs58:index',
            'add' => 'Sitescovs58:edit'
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
