<?php
	/**
	 * Code source de la classe Sujetcer93Fixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe Sujetcer93Fixture.
	 *
	 * @package app.Test.Fixture
	 */
	class Sujetcer93Fixture extends CakeTestFixture
	{
		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = array(
			'model' => 'Sujetcer93',
			'records' => false
		);

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = array(
			array(
				'name' => 'Sujet 1',
			),
			array(
				'name' => 'Sujet 2',
			)
		);

	}
?>