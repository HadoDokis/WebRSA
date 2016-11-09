<?php
	/**
	 * Code source de la classe Libemploirech66MetierDspRev.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Libemploirech66MetierDspRev ...
	 *
	 * @package app.Model
	 */
	class Libemploirech66MetierDspRev extends AppModel
	{
		public $name = 'Libemploirech66MetierDspRev';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $useTable = 'codesromemetiersdsps66';
	}
?>