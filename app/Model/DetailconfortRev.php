<?php
	/**
	 * Code source de la classe DetailconfortRev.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe DetailconfortRev ...
	 *
	 * @package app.Model
	 */
	class DetailconfortRev extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'DetailconfortRev';

		/**
		 * Behaviors utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Enumerable'
		);

		/**
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'DspRev' => array(
				'className' => 'DspRev',
				'foreignKey' => 'dsp_rev_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);
	}
?>