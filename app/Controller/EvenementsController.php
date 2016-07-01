<?php
	/**
	 * Code source de la classe EvenementsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe EvenementsController ...
	 *
	 * @package app.Controller
	 */
	class EvenementsController extends AppController
	{
		public $name = 'Evenements';

		public $uses = array( 'Option', 'Foyer', 'Evenement' );

		public $helpers = array( 'Locale', 'Xform' );

		public $components = array( 'Jetons2', 'DossiersMenus' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'index' => 'read',
		);

		/**
		 *
		 */
		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'fg', ClassRegistry::init('Evenement')->enum('fg') );
		}

		/**
		 *
		 * @param integer $foyer_id
		 */
		public function index( $foyer_id = null ){
			$this->assert( valid_int( $foyer_id ), 'invalidParameter' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'foyer_id' => $foyer_id ) ) );

			$evenements = $this->Evenement->find(
				'all',
				array(
					'conditions' => array(
						'Evenement.foyer_id' => $foyer_id
					)
				)
			);
			$this->set( 'evenements', $evenements );
			$this->set( 'foyer_id', $foyer_id );
		}
	}
?>