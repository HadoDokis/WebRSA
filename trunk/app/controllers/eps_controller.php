<?php
	class EpsController extends AppController
	{
		public $name = 'Eps';

		/**
		* @access public
		*/

		public $components = array( 'Default' );


        /**
        *
        */

        protected function _options() {
//             $options = $this->{$this->modelClass}->enums();
            $options['Zonegeographique'] = $this->{$this->modelClass}->Zonegeographique->find( 'list' );
//             $options[$this->modelClass]['ep_id'] = $this->{$this->modelClass}->Ep->find( 'list' );

            return $options;
        }


        /**
        *   Index pour les paramétrages des EPs
        */

        public function indexparams() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }
        }

        /**
        *   Liste des EPs mais pour les demandes de réorientation
        */

        public function liste() {
            if( !empty( $this->data ) ) {
                $eps = $this->Default->search( array( 'Ep.name' => 'LIKE', 'Ep.localisation' => 'LIKE' ), $this->data );
            }
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
            $options = $this->_options();
            $this->set( 'options', $options );
            $zglist = $this->Zonegeographique->find( 'list' );
            $this->set( 'zglist', $zglist );
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