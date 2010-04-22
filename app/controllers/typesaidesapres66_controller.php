<?php
    class Typesaidesapres66Controller extends AppController
    {
        public $name = 'Typesaidesapres66';

        var $uses = array( 'Typeaideapre66', 'Themeapre66', 'Pieceaide66' );

        /**
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();

            $options = array();

            foreach( array( 'Themeapre66' ) as $linkedModel ) {
                $field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
                $options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
            }

            $this->set( compact( 'options' ) );

            $pieceliste = $this->Pieceaide66->find(
                'list',
                array(
                    'fields' => array(
                        'Pieceaide66.id',
                        'Pieceaide66.name'
                    )
                )
            );
            $this->set( 'pieceliste', $pieceliste );

            return $return;
        }


        public function index() {
			$queryData = array(
				'Typeaideapre66' => array(
					'order' => array( 'Themeapre66.name ASC', 'Typeaideapre66.name ASC' )
				)
			);
            $this->Default->index( $queryData );
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