<?php	
	/**
	 * Code source de la classe RegroupementsepsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe RegroupementsepsController ...
	 *
	 * @package app.Controller
	 */
	class RegroupementsepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public $commeDroit = array(
			'add' => 'Regroupementseps:edit'
		);

		protected function _setOptions() {
			$options = $this->Regroupementep->enums();
			$this->set( compact( 'options' ) );
		}

		public function index() {
			$this->paginate = array(
				'limit' => 10
			);
			$this->_setOptions();
			$this->set( 'regroupementeps', $this->paginate( $this->Regroupementep ) );
			$this->set( 'themes', $this->Regroupementep->themes() );
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
			if( !empty( $this->request->data ) ) {
				$this->Regroupementep->create( $this->request->data );
				$success = $this->Regroupementep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Regroupementep->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Regroupementep.id' => $id )
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
			}

			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Regroupementep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>