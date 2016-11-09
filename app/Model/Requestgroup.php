<?php
	/**
	 * Code source de la classe Requestgroup.
	 *
	 * @package app.Model
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Model.php.
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Requestgroup ...
	 *
	 * @package app.Model
	 */
	class Requestgroup extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Requestgroup';

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
			'Requestmanager' => array(
				'className' => 'Requestmanager',
				'foreignKey' => 'requestgroup_id',
			),
		);
	}
?>