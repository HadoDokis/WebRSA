<?php
	/**
	 * Code source de la classe Cer93Fixture.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe Cer93Fixture.
	 *
	 * @package app.Test.Fixture
	 */
	class Cer93Fixture extends CakeTestFixture
	{
		/**
		 * On importe la définition de la table, pas les enregistrements.
		 *
		 * @var array
		 */
		public $import = array(
			'model' => 'Cer93',
			'records' => false
		);

		/**
		 * Définition des enregistrements.
		 *
		 * @var array
		 */
		public $records = array(
			array(
//				'id' => 1,
				'contratinsertion_id' => 1,
				'user_id' => 1,
				'matricule' => '66666666693',
				'dtdemrsa' => '2009-09-01',
				'qual' => 'MR',
				'nom' => 'BUFFIN',
				'prenom' => 'CHRISTIAN',
				'nomnai' => 'BUFFIN',
				'dtnai' => '1979-01-24',
				'adresse' => NULL,
				'codepos' => NULL,
				'locaadr' => NULL,
				'sitfam' => 'CEL',
				'natlog' => NULL,
				'incoherencesetatcivil' => NULL,
				'inscritpe' => NULL,
				'cmu' => NULL,
				'cmuc' => NULL,
				'nivetu' => NULL,
				'numdemrsa' => '66666666693',
				'rolepers' => NULL,
				'identifiantpe' => NULL,
				'positioncer' => '00enregistre',
				'formeci' => NULL,
				'datesignature' => NULL,
				'autresexps' => NULL,
				'isemploitrouv' => NULL,
				'metierexerce_id' => NULL,
				'secteuracti_id' => NULL,
				'naturecontrat_id' => NULL,
				'dureehebdo' => NULL,
				'dureecdd' => NULL,
				'bilancerpcd' => NULL,
				'duree' => NULL,
				'pointparcours' => NULL,
				'datepointparcours' => NULL,
				'pourlecomptede' => NULL,
				'observpro' => NULL,
				'observbenef' => NULL,
				'created' => NULL,
				'modified' => NULL,
			),
		);

	}
?>