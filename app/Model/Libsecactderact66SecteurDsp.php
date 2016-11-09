<?php
	/**
	 * Code source de la classe Libsecactderact66SecteurDsp.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Libsecactderact66SecteurDsp ...
	 *
	 * @package app.Model
	 */
	class Libsecactderact66SecteurDsp extends AppModel
	{
		public $name = 'Libsecactderact66SecteurDsp';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $useTable = 'codesromesecteursdsps66';
	}
?>