<?php
	/**
	 * Code source de la classe Conditionactiviteprealable.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Conditionactiviteprealable ...
	 *
	 * @package app.Model
	 */
	class Conditionactiviteprealable extends AppModel
	{
		public $name = 'Conditionactiviteprealable';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		protected $_modules = array( 'caf' );

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>