<?php
	/**
	 * Code source de la classe Cer93.
	 *
	 * PHP 5.3
	 *
	 * @package app.models
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe Cer93.
	 *
	 * @package app.models
	 */
	class Cer93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Cer93';

		/**
		 * Récursivité.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Liaison "belongsTo" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);

		public $hasOne = array(
			'Etatcivilcer93' => array(
				'className' => 'Etatcivilcer93',
				'foreignKey' => 'cer93_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
		);
	}
?>