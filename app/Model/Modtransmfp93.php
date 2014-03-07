<?php
	/**
	 * Code source de la classe Modtransmfp93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Modtransmfp93 ...
	 *
	 * @package app.Model
	 */
	class Modtransmfp93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Modtransmfp93';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);

		/**
		 * Associations "Has and belongs to many".
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
			'Ficheprescription93' => array(
				'className' => 'Ficheprescription93',
				'joinTable' => 'fichesprescriptions93_modstransmsfps93',
				'foreignKey' => 'modtransmfp93_id',
				'associationForeignKey' => 'ficheprescription93_id',
				'unique' => true,
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'finderQuery' => null,
				'deleteQuery' => null,
				'insertQuery' => null,
				'with' => 'Ficheprescription93Modtransmfp93'
			),
		);
	}
?>