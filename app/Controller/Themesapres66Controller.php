<?php
	/**
	 * Code source de la classe Themesapres66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Themesapres66Controller ...
	 *
	 * @package app.Controller
	 */
	class Themesapres66Controller extends AppController
	{
		public $name = 'Themesapres66';

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Themesapres66:index',
			'add' => 'Themesapres66:edit'
		);

		/**
		*
		*/

		public function index() {
		// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'apres', 'action' => 'indexparams_'.Configure::read( 'nom_form_apre_cg' ) ) );
			}
			$this->Default->index();
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

		protected function _add_edit(){
			$args = func_get_args();
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