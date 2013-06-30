<?php
	/**
	 * Code source de la classe GestionsrdvsController.
	 *
	 * TODO: en faire une action/méthode du contrôleur Paramétrages, de même pour les copines.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe GestionsrdvsController ...
	 *
	 * @package app.Controller
	 */
	class GestionsrdvsController extends AppController
	{
		public $name = 'Gestionsrdvs';

		public $uses = array( 'Rendezvous' );

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
		);

		/**
		 * On ne fait que présenter la liste des paramétrage du module.
		 */
		public function index() {
		}
	}
?>