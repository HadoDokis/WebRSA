<?php

    class TextareascourrierspdosController extends AppController 
    {
        var $name = 'Textareascourrierspdos';
        var $uses = array( 'Textareacourrierpdo' );
        var $helpers = array( 'Xform', 'Default2', 'Theme' );
        var $components = array( 'Default' );
        
        var $commeDroit = array(
            'view' => 'Textareascourrierspdos:index',
            'add' => 'Textareascourrierspdos:edit'
        );

        public function _setOptions(){
            $this->set( 'options', $this->{$this->modelClass}->Courrierpdo->find( 'list' ) );

//             debug($options);
//             return $options;
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
            $this->_setOptions();
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
