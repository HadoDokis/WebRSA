<?php
	/**
	 * Code source de la classe Libactdomi66MetierDsp.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Libactdomi66MetierDsp ...
	 *
	 * @package app.Model
	 */
	class Libactdomi66MetierDsp extends AppModel
	{
		public $name = 'Libactdomi66MetierDsp';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $useTable = 'codesromemetiersdsps66';
	}
?>