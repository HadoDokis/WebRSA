<?php
	/**
	 * Code source de la classe PropositionprimosController.
	 *
	 * @package app.Controller
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses('AbstractParametragesController', 'Controller');
	App::import( 'Behaviors', 'Occurences' );

	/**
	 * La classe PropositionprimosController ...
	 *
	 * @package app.Controller
	 */
	class PropositionprimosController extends AbstractParametragesController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Propositionprimos';

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Propositionprimo'
		);
	}