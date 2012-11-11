<?php
	/**
	 * Code source de la classe Piecescomptables66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Piecescomptables66Controller ...
	 *
	 * @package app.Controller
	 */
	class Piecescomptables66Controller extends AppController
	{
		public $name = 'Piecescomptables66';

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Piecescomptables66:index',
			'add' => 'Piecescomptables66:edit'
		);

		/**
		*
		*/

		public function index() {
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