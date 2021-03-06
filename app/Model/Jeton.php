<?php
	/**
	 * Code source de la classe Jeton.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Jeton ...
	 *
	 * @package app.Model
	 */
	class Jeton extends AppModel
	{
		public $name = 'Jeton';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		/**
		 * On ne doit jamais cacher les reqûetes (par défaut, elles sont cachées sur une page).
		 *
		 * @var boolean
		 */
		public $cacheQueries = false;

		public $validate = array(
			'dossier_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'user_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Dossier' => array(
				'className' => 'Dossier',
				'foreignKey' => 'dossier_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>