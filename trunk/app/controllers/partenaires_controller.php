<?php

    class PartenairesController extends AppController 
    {
        var $name = 'Partenaires';
        var $uses = array( 'Partenaire', 'ActioncandidatPartenaire', 'Option', 'Personne' );
        var $helpers = array( 'Xform', 'Default', 'Theme' );
        var $components = array( 'Default' );
        
		var $commeDroit = array(
			'view' => 'Partenaires:index',
			'add' => 'Partenaires:edit'
		);

        /**
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();

            $options = array();
            /*foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
                $options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
            }*/
            $options = Set::insert( $options, 'Partenaire.typevoie', $this->Option->typevoie() );

//             $this->set( 'typevoie', $this->Option->typevoie() );

            $this->set( compact( 'options'/*, 'typevoie'*/ ) );
// debug($options);
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
//          debug( $this->{$this->modelClass}->getColumnType( 'count_posts', true ) );
//          debug( $this->{$this->modelClass}->getColumnTypes( true ) );
//          debug( $this->{$this->modelClass}->findById( $id, null, null, 2 ) ); // FIXME: virtual fields avec recursive => 2
            $this->Default->view( $id );
        }

    }
?>
