<?php
	class MotifsdemsreorientsController extends AppController
	{
		public $name = 'Motifsdemsreorients';

		/**
		* @access public
		*/

		public $components = array( 'Default' );
        
		var $commeDroit = array(
			'view' => 'Motifsdemsreorients:index',
			'add' => 'Motifsdemsreorients:edit'
		);

        /**
        *
        */

        protected function _options() {
            $options = $this->{$this->modelClass}->enums();
//             $options[$this->modelClass]['fonctionpartep_id'] = $this->{$this->modelClass}->Fonctionpartep->find( 'list' );
//             $options[$this->modelClass]['ep_id'] = $this->{$this->modelClass}->Ep->find( 'list' );

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
