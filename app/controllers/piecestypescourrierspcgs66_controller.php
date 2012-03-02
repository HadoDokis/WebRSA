<?php
    class Piecestypescourrierspcgs66Controller extends AppController
    {
        public $name = 'Piecestypescourrierspcgs66';
        
        public $helpers = array( 'Default2' );
        
		public $commeDroit = array(
			'view' => 'Piecestypescourrierspcgs66:index',
			'add' => 'Piecestypescourrierspcgs66:edit'
		);

                protected function _setOptions() {
			$options = array();
			$options[$this->modelClass]['typecourrierpcg66_id'] = $this->Piecetypecourrierpcg66->Typecourrierpcg66->find( 'list', array( 'fields' => array( 'id', 'name' ) ) );

			$this->set( compact( 'options' ) );

		}

        public function index() {
			$queryData = array(
				'Piecetypecourrierpcg66' => array(
					'fields' => array(
						'Piecetypecourrierpcg66.id',
						'Piecetypecourrierpcg66.name',
                                                'Typecourrierpcg66.name'
					),
					'contain' => array(
                                            'Typecourrierpcg66'
                                        ),
					'group' => array(  'Piecetypecourrierpcg66.id', 'Piecetypecourrierpcg66.name', 'Piecetypecourrierpcg66.typecourrierpcg66_id', 'Typecourrierpcg66.id', 'Typecourrierpcg66.name' ),
					'order' => array( 'Piecetypecourrierpcg66.name ASC' )
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

            $this->_setOptions();
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
