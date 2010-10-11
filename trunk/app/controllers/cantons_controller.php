<?php
	class CantonsController extends AppController
	{
		public $name = 'Cantons';
		public $uses = array( 'Canton', 'Option' );
        public $helpers = array( 'Xform' );
		public $paginate = array(
			'limit' => 20,
			'recursive' => -1,
			'order' => array( 'canton ASC' )
		);

		public $commeDroit = array(
			'add' => 'Cantons:edit'
		);

		/**
		*	FIXME: docs
		*/

		public function beforeFilter() {
			$return = parent::beforeFilter();

			$this->set( 'typevoie', $this->Option->typevoie() );

			return $return;
		}

		/**
		*	FIXME: docs
		*/

		public function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }

			$this->paginate = array(
				'fields' => array(
					'Canton.id',
					'Canton.typevoie',
					'Canton.nomvoie',
					'Canton.locaadr',
					'Canton.codepos',
					'Canton.numcomptt',
					'Canton.canton',
					'Canton.zonegeographique_id',
					'Zonegeographique.libelle',
				)
			);
			$cantons = $this->paginate( $this->modelClass );
			$this->set( compact( 'cantons' ) );
		}

        /**
		*	FIXME: docs
        */

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /**
		*	FIXME: docs
        */

        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /**
		*	FIXME: docs
        */

        protected function _add_edit( $id = null ) {
            if( $this->action == 'edit' ) {
                $canton = $this->Canton->findById( $id, null, null, -1 );
                $this->assert( !empty( $canton ), 'invalidParameter' );
            }

			if( !empty( $this->data ) ) {
				$this->Canton->create( $this->data );
				if( $this->Canton->save() ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $canton;
			}

			$this->set( 'zonesgeographiques', $this->Canton->Zonegeographique->find( 'list' ) );
			$this->set( 'typesvoies', $this->Option->typevoie() );
            $this->render( $this->action, null, 'add_edit' );
		}

        /**
		*	FIXME: docs
        */

        public function delete( $id = null ) {
            $canton = $this->Canton->findById( $id, null, null, -1 );
			$this->assert( !empty( $canton ), 'invalidParameter' );

            if( $this->Canton->delete( Set::classicExtract( $canton, 'Canton.id' ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'action' => 'index' ) );
            }
        }
	}
?>
