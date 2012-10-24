<?php
	/**
	 * Code source de la classe DossierFixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe DossierFixture.
	 *
	 * @package app.Test.Fixture
	 */
	class DossierFixture extends CakeTestFixture
	{
		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = array(
			'model' => 'Dossier',
			'records' => false
		);

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = array(
			array(
				'numdemrsa' => '66666666693',
				'dtdemrsa' => '2009-09-01',
				'dtdemrmi' => null,
				'numdepinsrmi' => null,
				'typeinsrmi' => null,
				'numcominsrmi' => null,
				'numagrinsrmi' => null,
				'numdosinsrmi' => null,
				'numcli' => null,
				'numorg' => '931',
				'fonorg' => 'CAF',
				'matricule' => '123456700000000',
				'statudemrsa' => null,
				'typeparte' => 'CG',
				'ideparte' => '093',
				'fonorgcedmut' => null,
				'numorgcedmut' => null,
				'matriculeorgcedmut' => null,
				'ddarrmut' => null,
				'codeposanchab' => null,
				'fonorgprenmut' => null,
				'numorgprenmut' => null,
				'dddepamut' => null,
				'detaildroitrsa_id' => null,
				'avispcgdroitrsa_id' => null,
				'organisme_id' => null,
			)
		);

	}
?>