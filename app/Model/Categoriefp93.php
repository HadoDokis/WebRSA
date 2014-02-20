<?php
	/**
	 * Code source de la classe Categoriefp93.
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Categoriefp93 ...
	 *
	 * @package app.Model
	 */
	class Categoriefp93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Categoriefp93';

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
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Thematiquefp93' => array(
				'className' => 'Thematiquefp93',
				'foreignKey' => 'thematiquefp93_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);

		/**
		 * Associations "Has many".
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Filierefp93' => array(
				'className' => 'Filierefp93',
				'foreignKey' => 'categoriefp93_id',
				'conditions' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'dependent' => true,
				'exclusive' => null,
				'finderQuery' => null
			),
		);
	}
?>