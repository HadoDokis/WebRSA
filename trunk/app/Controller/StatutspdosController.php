<?php
	/**
	 * Code source de la classe StatutspdosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe StatutspdosController ...
	 *
	 * @package app.Controller
	 */
	class StatutspdosController extends AppController
	{
		public $name = 'Statutspdos';
		public $uses = array( 'Statutpdo', 'Propopdo', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Statutspdos:index',
			'add' => 'Statutspdos:edit'
		);

        protected function _setOptions() {
			$options = $this->Statutpdo->enums();
			$this->set( compact( 'options' ) );
		}

		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
			$queryData = $this->Statutpdo->qdOccurences();
            $this->paginate = $queryData;
			$statutspdos = $this->paginate( $this->modelClass );
            $this->_setOptions();
            $this->set( compact( 'statutspdos' ) );
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
            $this->_setOptions();
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