<?php
	/**
	 * Code source de la classe Visionneuse.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Visionneuse ...
	 *
	 * @package app.Model
	 */
	class Visionneuse extends AppModel
	{
		public $name = 'Visionneuse';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $useDbConfig = 'log';
	}
?>