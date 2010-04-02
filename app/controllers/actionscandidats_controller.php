<?php

    class ActionscandidatsController extends AppController 
    {
        var $name = 'Actionscandidats';
        var $uses = array( 'Actioncandidat', 'ActioncandidatPersonne', 'ActioncandidatPartenaire', 'Option', 'Personne' );
        var $helpers = array( 'Xform', 'Default', 'Theme' );
        var $components = array( 'Default' );

        /**
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();

            $options = array();

            $this->set( 'typevoie', $this->Option->typevoie() );

            $this->set( compact( 'options', 'typevoie' ) );

            return $return;
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