<?php
	class EpsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		/**
		* FIXME: evite les droits
		*/

		public function beforeFilter() {
		}

		/**
		*
		*/

		protected function _setOptions() {
			$options = $this->Ep->enums();
			if( $this->action != 'index' ) {
				$options['Ep']['regroupementep_id'] = $this->Ep->Regroupementep->find( 'list' );
				$options['Zonegeographique']['Zonegeographique'] = $this->Ep->Zonegeographique->find( 'list' );
			}
			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Ep.id',
					'Ep.name',
					'Regroupementep.name',
					'Ep.'.Configure::read( 'Ep.tablesaisine' )
				),
				'contain' => array(
					'Regroupementep'
				),
				'limit' => 10
			);

			$this->_setOptions();
			$this->set( 'eps', $this->paginate( $this->Ep ) );
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

		protected function _add_edit( $id = null ) {
			if( !empty( $this->data ) ) {
				$this->Ep->begin();
				$this->Ep->create( $this->data );
				$success = $this->Ep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Ep->commit();
					$this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Ep->rollback();
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Ep->find(
					'first',
					array(
						'contain' => array(
							'Zonegeographique' => array(
								'fields' => array( 'id', 'libelle' )
							)
						),
						'conditions' => array( 'Ep.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
			}

			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Ep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>
