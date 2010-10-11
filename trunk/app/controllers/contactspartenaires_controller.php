<?php

    class ContactspartenairesController extends AppController 
    {
        var $name = 'Contactspartenaires';
        var $uses = array( 'Contactpartenaire', 'Partenaire', 'Option' );
        var $helpers = array( 'Xform', 'Default', 'Theme' );
        var $components = array( 'Default' );
        
		var $commeDroit = array(
			'view' => 'Contactspartenaires:index',
			'add' => 'Contactspartenaires:edit'
		);
        /**
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();

            $options = array();
            foreach( array( 'Partenaire' ) as $linkedModel ) {
                $field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
                $options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
            }
            $this->set( 'qual', $this->Option->qual() );

            $this->set( compact( 'options', 'qual' ) );

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
