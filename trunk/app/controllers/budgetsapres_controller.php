<?php
	class BudgetsapresController extends AppController
	{
		var $name = 'Budgetsapres';
        var $helpers = array( 'Xform' );
        
		var $commeDroit = array(
			'add' => 'Actionscandidats:edit'
		);
		/**
		*
		*/

		public function index() {
			$this->paginate[$this->modelClass] = array(
				'limit' => 5,
				'order' => array( "{$this->modelClass}.exercicebudgetai DESC" ),
			);

			$budgetsapres = $this->paginate( $this->modelClass );
			$this->set( compact( 'budgetsapres' ) );
		}

        /**
        *
        */

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }


        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /**
        *
        */

        function _add_edit( $id = null ) {
			if( $this->action == 'edit' ) {
				$budgetapre = $this->{$this->modelClass}->findById( $id, null, null, -1 );
				$this->assert( !empty( $budgetapre ), 'invalidParameter' );
			}

			if( !empty( $this->data ) ) {
				$this->{$this->modelClass}->create( $this->data );
				if( $this->{$this->modelClass}->save() ) {
					$this->Session->setFlash( __( 'Enregistrement effectué', true ), 'flash/success' );
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $budgetapre;
			}

            $this->render( $this->action, null, 'add_edit' );
		}
	}
?>
