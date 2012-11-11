<?php	
	/**
	 * Code source de la classe FonctionsmembresepsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe FonctionsmembresepsController ...
	 *
	 * @package app.Controller
	 */
	class FonctionsmembresepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public $commeDroit = array(
			'add' => 'Fonctionsmembreseps:edit'
		);

		protected function _setOptions() {
			$options = array();
			$this->set( compact( 'options' ) );
		}

		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Fonctionmembreep.id',
					'Fonctionmembreep.name'
				),
				'limit' => 10
			);
			$this->_setOptions();
			$this->set( 'fonctionmembreeps', $this->paginate( $this->Fonctionmembreep ) );
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
				$this->Fonctionmembreep->create( $this->request->data );
				$success = $this->Fonctionmembreep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Fonctionmembreep->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Fonctionmembreep.id' => $id )
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
			$success = $this->Fonctionmembreep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>