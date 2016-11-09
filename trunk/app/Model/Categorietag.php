<?php
	/**
	 * Code source de la classe Categorietag.
	 *
	 * @package app.Model
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Model.php.
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Categorietag ...
	 *
	 * @package app.Model
	 */
	class Categorietag extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Categorietag';

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array();

		/**
		 * Associations "Has many".
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Valeurtag' => array(
				'className' => 'Valeurtag',
				'foreignKey' => 'categorietag_id',
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