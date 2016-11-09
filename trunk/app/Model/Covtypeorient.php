<?php
	/**
	 * Code source de la classe Covtypeorient.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Typeorient', 'Model' );

	/**
	 * La classe Covtypeorient ...
	 *
	 * @package app.Model
	 */
	class Covtypeorient extends Typeorient
	{
		public $name = 'Covtypeorient';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $useTable = 'typesorients';
	}
?>