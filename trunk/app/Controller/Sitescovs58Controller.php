<?php	
	/**
	 * Code source de la classe Sitescovs58Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Sitescovs58Controller ...
	 *
	 * @package app.Controller
	 */
	class Sitescovs58Controller extends AppController
	{

		public $name = 'Sitescovs58';
		public $uses = array( 'Sitecov58' );
		public $helpers = array( 'Xform', 'Default2', 'Theme' );
		public $components = array( 'Default' );

		public $commeDroit = array(
			'view' => 'Sitescovs58:index',
			'add' => 'Sitescovs58:edit'
		);

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
			$args = func_get_args();
			$zg = $this->Sitecov58->Zonegeographique->find(
				'list',
				array(
					'fields' => array(
						'Zonegeographique.id',
						'Zonegeographique.libelle'
					)
				)
			);
			$this->set( 'zglist', $zg );
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