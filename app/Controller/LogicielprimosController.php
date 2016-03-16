<?php
	/**
	 * Code source de la classe LogicielprimosController.
	 *
	 * @package app.Controller
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses('AbstractParametragesController', 'Controller');
	App::import( 'Behaviors', 'Occurences' );

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
	}
?>
