<?php
	/**
	 * Fichier source du modèle Typesujetcer93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe Typesujetcer93.
	 *
	 * @package app.Model
	 */
	class Typesujetcer93 extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = 'Typesujetcer93';

		/**
		 * Tri par défaut
		 *
		 * @var array
		 */
		public $order = array( 'Typesujetcer93.name ASC' );

		
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
			'Sujetcer93' => array(
				'className' => 'Sujetcer93',
				'foreignKey' => 'sujetcer93_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>