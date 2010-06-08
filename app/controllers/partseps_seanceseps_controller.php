<?php
	class PartsepsSeancesepsController extends AppController
	{
		public $name = 'PartsepsSeanceseps';

		/**
		* @access public
		*/

		public $components = array( 'Default' );

        /**
        *
        */

        public $uses = array( 'PartepSeanceep' );

        /**
        *
        */

        protected function _options() {
            $options = $this->{$this->modelClass}->enums();
            $options[$this->modelClass]['partep_id'] = $this->{$this->modelClass}->Partep->find( 'list' );
            $options[$this->modelClass]['seanceep_id'] = $this->{$this->modelClass}->Seanceep->find( 'list' );
            $this->{$this->modelClass}->Partep->find( 'list' );

            return $options;
        }

		/**
		*
		*/

		public function index() {
            $this->set(
                Inflector::tableize( $this->modelClass ),
                $this->paginate( $this->modelClass )
            );
            $options = $this->_options();
            $this->set( 'options', $options );

			$this->{$this->modelClass}->recursive = 0;
			$this->Default->search(
				$this->data
			);
		}

		/**
		*
		*/

		public function view( $id = null ) {
			$this->{$this->modelClass}->recursive = -1;
			$this->Default->view( $id );
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

		public function edit( $id = null ) {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function _add_edit( $id = null ) {
            $options = $this->_options();
            $this->set( 'options', $options );
			$this->{$this->modelClass}->recursive = -1;
            $this->Default->_add_edit( $id, null, null, array( 'action' => 'index' ) );
//             $this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id = null ) {
			$this->Default->delete( $id, array( 'action' => 'index' ) );
		}
	}
?>