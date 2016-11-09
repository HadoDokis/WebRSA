<?php
	/**
	 * Code source de la classe Libactdomi66MetierDspRev.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Libactdomi66MetierDspRev ...
	 *
	 * @package app.Model
	 */
	class Libactdomi66MetierDspRev extends AppModel
	{
		public $name = 'Libactdomi66MetierDspRev';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $useTable = 'codesromemetiersdsps66';
	}
?>