<?php
	/**
	 * Code source de la classe Traitementtypepdo.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Traitementtypepdo ...
	 *
	 * @package app.Model
	 */
	class Traitementtypepdo extends AppModel
	{
		public $name = 'Traitementtypepdo';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $actsAs = array(
			'ValidateTranslate'
		);

		public $validate = array(
			'name' => array(
				array(
					'rule' => array('notEmpty'),
				),
				array(
					'rule' => array('isUnique'),
				),
			),
		);

		public $hasMany = array(
			'Traitementpdo' => array(
				'className' => 'Traitementpdo',
				'foreignKey' => 'traitementtypepdo_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);
	}
?>