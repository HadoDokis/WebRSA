<?php
	/**
	 * Code source de la classe JetonsController.
	 *
	 * @package app.Controller
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe JetonsController ...
	 *
	 * @package app.Controller
	 */
	class JetonsController extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Jetons';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array( 'Jetons2' );

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array();

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Jeton' );
		
		public $aucunDroit = array( 'ajax_count', 'ajax_delete' );
		
		public function ajax_count() {
			$json = $this->Jetons2->count();
			$this->set( compact( 'json' ) );
			$this->layout = 'ajax';
			$this->render( '/Elements/json' );
		}

		public function ajax_delete() {
			$json = $this->Jetons2->deleteJetons();
			$this->set( compact( 'json' ) );
			$this->layout = 'ajax';
			$this->render( '/Elements/json' );
		}
	}
?>
