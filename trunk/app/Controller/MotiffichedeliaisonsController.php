<?php
	/**
	 * Code source de la classe MotiffichedeliaisonsController.
	 *
	 * @package app.Controller
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses('AbstractParametragesController', 'Controller');
	App::import( 'Behaviors', 'Occurences' );

	/**
	 * La classe MotiffichedeliaisonsController ...
	 *
	 * @package app.Controller
	 */
	class MotiffichedeliaisonsController extends AbstractParametragesController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Motiffichedeliaisons';

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Motiffichedeliaison'
		);
	}