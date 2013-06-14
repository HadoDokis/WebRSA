<?php
	/**
	 * Code source de la classe ParametragesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe ParametragesController ...
	 *
	 * @package app.Controller
	 */
	class ParametragesController extends AppController
	{

		public $name = 'Parametrages';
		public $uses = array( 'Dossier', 'Structurereferente', 'Zonegeographique' );

		public $commeDroit = array(
			'view' => 'Parametrages:index'
		);

		public function index() {

		}

		public function view( $param = null ) {
			$zone = $this->Zonegeographique->find(
				'first',
				array(
					'conditions' => array(
					)
				)
			);
			$this->set('zone', $zone);
		}
	}

?>