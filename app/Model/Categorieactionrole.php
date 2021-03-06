<?php
	/**
	 * Code source de la classe Categorieactionrole.
	 *
	 * @package app.Model
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Model.php.
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Categorieactionrole ...
	 *
	 * @package app.Model
	 */
	class Categorieactionrole extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Categorieactionrole';

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Formattable',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);

		/**
		 * @var array
		 */
		public $hasMany = array(
			'Actionrole' => array(
				'className' => 'Actionrole',
				'foreignKey' => 'categorieactionrole_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);
	}