<?php
	/**
	 * Code source de la classe CantonFixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe CantonFixture ...
	 *
	 * @package app.Test.Fixture
	 */
	class CantonFixture extends CakeTestFixture
	{
		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = array(
			'model' => 'Canton',
			'records' => false
		);

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = array(
			array(
				'typevoie' => '',
				'nomvoie' => '',
				'locaadr' => 'AUBERVILLIERS',
				'codepos' => '93300',
				'numcomptt' => '93001',
				'canton' => 'Canton 1',
				'zonegeographique_id' => 1,
			),
			array(
				'typevoie' => '',
				'nomvoie' => '',
				'locaadr' => 'BOBIGNY',
				'codepos' => '93000',
				'numcomptt' => '93008',
				'canton' => 'Canton 1',
				'zonegeographique_id' => 2,
			),
			array(
				'typevoie' => '',
				'nomvoie' => '',
				'locaadr' => 'BONDY',
				'codepos' => '93140',
				'numcomptt' => '93010',
				'canton' => 'Canton 2',
				'zonegeographique_id' => 3,
			),
		);

	}
?>