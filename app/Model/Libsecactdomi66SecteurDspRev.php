<?php
	/**
	 * Code source de la classe Libsecactdomi66SecteurDspRev.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Libsecactdomi66SecteurDspRev ...
	 *
	 * @package app.Model
	 */
	class Libsecactdomi66SecteurDspRev extends AppModel
	{
		public $name = 'Libsecactdomi66SecteurDspRev';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $useTable = 'codesromesecteursdsps66';
	}
?>