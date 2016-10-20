<?php
	/**
	 * Code source de la classe LogicielprimosController.
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::import( 'Behaviors', 'Occurences' );
	App::uses( 'AppController', 'Controller' );
	App::uses( 'AbstractParametragesController', 'Controller' );

	/**
	 * La classe LogicielprimosController ...
	 *
	 * @package app.Controller
	 */
	class LogicielprimosController extends AbstractParametragesController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Logicielprimos';

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Logicielprimo'
		);

		/**
		 * Méthodes ne nécessitant aucun droit.
		 *
		 * @var array
		 */
		public $aucunDroit = array(

		);
	}
?>
