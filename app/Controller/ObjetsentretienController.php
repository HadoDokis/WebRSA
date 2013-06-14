<?php	
	/**
	 * Code source de la classe ObjetsentretienController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe ObjetsentretienController ...
	 *
	 * @package app.Controller
	 */
	class ObjetsentretienController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public $commeDroit = array(
			'add' => 'Objetsentretien:edit'
		);

		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Objetentretien.id',
					'Objetentretien.name'
				),
				'limit' => 10
			);

			$this->set( 'objetsentretien', $this->paginate( $this->Objetentretien ) );
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
				$this->Objetentretien->create( $this->request->data );
				$success = $this->Objetentretien->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Objetentretien->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Objetentretien.id' => $id )
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
			$success = $this->Objetentretien->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>