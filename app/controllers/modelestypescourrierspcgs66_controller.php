<?php
    class Modelestypescourrierspcgs66Controller extends AppController
    {
        public $name = 'Modelestypescourrierspcgs66';
        
        public $helpers = array( 'Default2' );
        
		public $commeDroit = array(
			'view' => 'Modelestypescourrierspcgs66:index',
			'add' => 'Modelestypescourrierspcgs66:edit'
		);

		protected function _setOptions() {
			$options = array();
			$options[$this->modelClass]['typecourrierpcg66_id'] = $this->Modeletypecourrierpcg66->Typecourrierpcg66->find( 'list', array( 'fields' => array( 'id', 'name' ) ) );

			$this->set( compact( 'options' ) );

		}

        public function index() {
			$queryData = array(
				'Modeletypecourrierpcg66' => array(
					'fields' => array(
						'Modeletypecourrierpcg66.id',
						'Modeletypecourrierpcg66.name',
						'Modeletypecourrierpcg66.modeleodt',
						'Typecourrierpcg66.name'
					),
					'contain' => array(
						'Typecourrierpcg66'
					),
					'group' => array(  'Modeletypecourrierpcg66.id', 'Modeletypecourrierpcg66.name', 'Modeletypecourrierpcg66.modeleodt', 'Modeletypecourrierpcg66.typecourrierpcg66_id', 'Typecourrierpcg66.id', 'Typecourrierpcg66.name' ),
					'order' => array( 'Typecourrierpcg66.name ASC' ),
					'limit' => 10
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
