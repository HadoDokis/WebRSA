<?php
	/**
	 * Code source de la classe FoyerFixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe FoyerFixture.
	 *
	 * @package app.Test.Fixture
	 */
	class FoyerFixture extends CakeTestFixture
	{
		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = array(
			'model' => 'Foyer',
			'records' => false
		);

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = array(
			array(
				'dossier_id' => 1,
				'sitfam' => 'CEL',
				'ddsitfam' => '1979-01-24',
				'typeocclog' => null,
				'mtvallocterr' => null,
				'mtvalloclog' => null,
				'contefichliairsa' => null,
				'mtestrsa' => null,
				'raisoctieelectdom' => null,
				'regagrifam' => null,
			),
			array(
				'dossier_id' => 2,
				'sitfam' => 'MAR',
				'ddsitfam' => '2001-08-24',
				'typeocclog' => null,
				'mtvallocterr' => null,
				'mtvalloclog' => null,
				'contefichliairsa' => null,
				'mtestrsa' => null,
				'raisoctieelectdom' => null,
				'regagrifam' => null,
			),
		);
	}
?>