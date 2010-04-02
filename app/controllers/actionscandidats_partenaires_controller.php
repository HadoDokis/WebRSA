<?php

    class ActionscandidatsPartenairesController extends AppController 
    {
        var $name = 'ActionscandidatsPartenaires';
        var $uses = array( 'ActioncandidatPartenaire', 'Actioncandidat', 'Partenaire', 'Option', 'Personne' );
        var $helpers = array( 'Xform', 'Default', 'Theme' );
        var $components = array( 'Default' );

        /**
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();

            $options = array();
//             foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
//                 $options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
//             }

            foreach( array( 'Actioncandidat', 'Partenaire' ) as $linkedModel ) {
                $field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
                $options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
            }

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