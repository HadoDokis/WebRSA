<?php
	/**
	 * Code source de la classe Recours.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Recours ...
	 *
	 * @package app.Model
	 */
	class Recours extends AppModel
	{
		public $name = 'Recours';

		public $useTable = 'infosfinancieres';
	}
?>