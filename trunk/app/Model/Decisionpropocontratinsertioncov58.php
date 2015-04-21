<?php
	/**
	 * Code source de la classe Decisionpropocontratinsertioncov58.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Decisionpropocontratinsertioncov58 ...
	 *
	 * @package app.Model
	 */
	class Decisionpropocontratinsertioncov58 extends AppModel
	{
		public $name = 'Decisionpropocontratinsertioncov58';

		public $recursive = -1;

		public $actsAs = array(
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id',
					'referent_id'
				)
			),
			'Enumerable'
		);

		public $belongsTo = array(
			'Passagecov58' => array(
				'className' => 'Passagecov58',
				'foreignKey' => 'passagecov58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>