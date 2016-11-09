<?php
	/**
	 * Code source de la classe Detailaccosocindi.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Detailaccosocindi ...
	 *
	 * @package app.Model
	 */
	class Detailaccosocindi extends AppModel
	{
		public $name = 'Detailaccosocindi';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $belongsTo = array(
			'Dsp' => array(
				'className' => 'Dsp',
				'foreignKey' => 'dsp_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'nataccosocindi' => array(
						'type' => 'nataccosocindi', 'domain' => 'dsp'
					),
				)
			),
		);
	}
?>