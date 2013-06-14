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
			),
			array(
				'numvoie' => 120,
				'typevoie' => 'R',
				'nomvoie' => 'DU MARECHAL BROUILLON',
				'complideadr' => null,
				'compladr' => null,
				'lieudist' => null,
				'numcomrat' => '93063',
				'numcomptt' => '93063',
				'codepos' => '93230',
				'locaadr' => 'ROMAINVILLE',
				'pays' => 'FRA',
			),
			array(
				'numvoie' => 10,
				'typevoie' => 'R',
				'nomvoie' => 'HECTOR BERLIOZ',
				'complideadr' => null,
				'compladr' => null,
				'lieudist' => null,
				'numcomrat' => '93008',
				'numcomptt' => '93008',
				'codepos' => '93000',
				'locaadr' => 'BOBIGNY',
				'pays' => 'FRA',
			),
		);
	}
?>