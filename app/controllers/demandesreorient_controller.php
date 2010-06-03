<?php
	class DemandesreorientController extends AppController
	{
		public $name = 'Demandesreorient';

		/**
		* @access public
		*/

		public $components = array( 'Default' );

		/**
		*
		*/

		protected function _options() {
			$options = $this->{$this->modelClass}->enums();
// 			debug( $options );
			return $options;
		}

		/**
		*
		*/

		public function index() {
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

			if( !empty( $this->data ) ) {
				debug( $this->data );
			}
			else {
				if( $this->action == 'add' ) {
					$orientstruct = $this->{$this->modelClass}->Orientstruct->findById( $id, null, null, -1 );
					$this->assert( !empty( $orientstruct ), 'invalidParameter' );

					$referent_id = $orientstruct['Orientstruct']['referent_id'];
					if( empty( $referent_id ) ) {
						$referent = $this->{$this->modelClass}->VxReferent->readByPersonneId( $orientstruct['Orientstruct']['personne_id'] );
						$referent_id = $referent['Referent']['id'];
					}

					$this->set( 'personne_id', $orientstruct['Orientstruct']['personne_id'] );

					$this->data[$this->modelClass] = array(
						'orientstruct_id' => $orientstruct['Orientstruct']['id'],
						'personne_id' => $orientstruct['Orientstruct']['personne_id'],
						'vx_typeorient_id' => $orientstruct['Orientstruct']['typeorient_id'],
						'vx_structure_id' => $orientstruct['Orientstruct']['structurereferente_id'],
						'vx_referent_id' => $referent_id
					);
				}
			}

			/*$this->{$this->modelClass}->recursive = -1;
            $this->Default->_add_edit( $id, null, null, array( 'action' => 'index' ) );*/
            $this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id = null ) {
			$this->Default->delete( $id, array( 'action' => 'index' ) );
		}
	}
?>