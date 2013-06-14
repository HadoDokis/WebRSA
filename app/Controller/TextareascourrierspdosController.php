<?php	
	/**
	 * Code source de la classe TextareascourrierspdosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe TextareascourrierspdosController ...
	 *
	 * @package app.Controller
	 */
	class TextareascourrierspdosController extends AppController
	{

		public $name = 'Textareascourrierspdos';
		public $uses = array( 'Textareacourrierpdo' );
		public $helpers = array( 'Xform', 'Default2', 'Theme' );
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Textareascourrierspdos:index',
			'add' => 'Textareascourrierspdos:edit'
		);

		protected function _setOptions(){
			$this->set( 'options', $this->{$this->modelClass}->Courrierpdo->find( 'list' ) );
		}

		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
			$this->set(
				Inflector::tableize( $this->modelClass ),
				$this->paginate( $this->modelClass )
			);
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
			$this->_setOptions();
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