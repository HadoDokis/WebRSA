<?php
	/**
	 * Code source de la classe AdresseFixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe AdresseFixture.
	 *
	 * @package app.Test.Fixture
	 */
	class AdresseFixture extends CakeTestFixture
	{
		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = array(
			'model' => 'Adresse',
			'records' => false
		);

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = array(
			array(
				'numvoie' => 66,
				'typevoie' => 'AV',
				'nomvoie' => 'DE LA REPUBLIQUE',
				'complideadr' => null,
				'compladr' => null,
				'lieudist' => null,
				'numcomrat' => '93001',
				'numcomptt' => '93001',
				'codepos' => '93300',
				'locaadr' => 'AUBERVILLIERS',
				'pays' => 'FRA',
			)
		);
	}
?>