<?php
	/**
	 * Fichier source du modèle Expprocer93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe Expprocer93.
	 *
	 * @package app.Model
	 */
	class Expprocer93 extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = 'Expprocer93';

		/**
		 * Récursivité.
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
			'Validation.Autovalidate',
			'Formattable',
		);

		/**
		 * Liaisons "belongsTo" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Cer93' => array(
				'className' => 'Cer93',
				'foreignKey' => 'cer93_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Metierexerce' => array(
				'className' => 'Metierexerce',
				'foreignKey' => 'metierexerce_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Secteuracti' => array(
				'className' => 'Secteuracti',
				'foreignKey' => 'secteuracti_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			)
		);
		
	}
?>