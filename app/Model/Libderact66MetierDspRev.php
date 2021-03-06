<?php
	/**
	 * Code source de la classe Libderact66MetierDspRev.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Libderact66MetierDspRev ...
	 *
	 * @package app.Model
	 */
	class Libderact66MetierDspRev extends AppModel
	{
		public $name = 'Libderact66MetierDspRev';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $useTable = 'codesromemetiersdsps66';
	}
?>