<?php
    class Piecesmodelestypescourrierspcgs66Controller extends AppController
    {
        public $name = 'Piecesmodelestypescourrierspcgs66';
        
        public $helpers = array( 'Default2' );
        
		public $commeDroit = array(
			'view' => 'Piecesmodelestypescourrierspcgs66:index',
			'add' => 'Piecesmodelestypescourrierspcgs66:edit'
		);

		protected function _setOptions() {
			$options = array();
			$options[$this->modelClass]['modeletypecourrierpcg66_id'] = $this->Piecemodeletypecourrierpcg66->Modeletypecourrierpcg66->find( 'list', array( 'fields' => array( 'id', 'name' ) ) );

			$this->set( compact( 'options' ) );

		}

        public function index() {
			$queryData = array(
				'Piecemodeletypecourrierpcg66' => array(
					'fields' => array(
						'Piecemodeletypecourrierpcg66.id',
						'Piecemodeletypecourrierpcg66.name',
						'Modeletypecourrierpcg66.name'
					),
					'contain' => array(
						'Modeletypecourrierpcg66'
					),
					'group' => array(  'Piecemodeletypecourrierpcg66.id', 'Piecemodeletypecourrierpcg66.name', 'Piecemodeletypecourrierpcg66.modeletypecourrierpcg66_id', 'Modeletypecourrierpcg66.id', 'Modeletypecourrierpcg66.name' ),
					'order' => array( 'Piecemodeletypecourrierpcg66.name ASC' )
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
