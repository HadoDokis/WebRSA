<?php
	/**
	 * Code source de la classe OriginespdosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe OriginespdosController ...
	 *
	 * @package app.Controller
	 */
	class OriginespdosController extends AppController
	{
		public $name = 'Originespdos';
		public $uses = array( 'Originepdo', 'Propopdo', 'Option' );
		public $helpers = array( 'Xform', 'Default', 'Default2', 'Theme' );
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Originespdos:index',
			'add' => 'Originespdos:edit'
		);

		protected function _setOptions() {
			$options = $this->Originepdo->enums();
			$this->set( compact ( 'options' ) );
		}

		/**
		*   Ajout à la suite de l'utilisation des nouveaux helpers
		*   - default.php
		*   - theme.php
		*/

		public function index() {
			$this->paginate['recursive'] = -1;
			$queryData = $this->paginate( $this->modelClass );
            $queryData = array_merge(
                $queryData,
                $this->Originepdo->qdOccurences()
            );
            $originespdos = $this->Originepdo->find( 'all', $queryData );
            
            $this->set( compact( 'originespdos' ) );
			$this->_setOptions();
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
