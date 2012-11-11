<?php	
	/**
	 * Code source de la classe Motifsreorientseps93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Motifsreorientseps93Controller ...
	 *
	 * @package app.Controller
	 */
	class Motifsreorientseps93Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public $commeDroit = array(
			'add' => 'Motifsreorientseps93:edit'
		);

		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Motifreorientep93.id',
					'Motifreorientep93.name'
				),
				'limit' => 10
			);

			$this->set( 'motifsreorientseps93', $this->paginate( $this->Motifreorientep93 ) );
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
				$this->Motifreorientep93->create( $this->request->data );
				$success = $this->Motifreorientep93->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Motifreorientep93->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Motifreorientep93.id' => $id )
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
			}

			$this->render( 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Motifreorientep93->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>