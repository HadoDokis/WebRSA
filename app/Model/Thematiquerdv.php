<?php
	/**
	 * Code source de la classe Thematiquerdv.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Thematiquerdv ...
	 *
	 * @package app.Model
	 */
	class Thematiquerdv extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = 'Thematiquerdv';

		/**
		 * Récursivité
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Formattable',
			'Pgsqlcake.PgsqlAutovalidate',
		);

		/**
		 * Associations "Has and belongs to many".
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
			/*'' => array(
				'className' => '',
				'joinTable' => '',
				'foreignKey' => 'thematiquerdv_id',
				'associationForeignKey' => '_id',
				'unique' => true,
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'finderQuery' => null,
				'deleteQuery' => null,
				'insertQuery' => null,
				'with' => null
			),*/
		);
	}
?>