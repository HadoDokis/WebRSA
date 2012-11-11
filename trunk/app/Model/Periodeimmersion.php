<?php	
	/**
	 * Code source de la classe Periodeimmersion.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Periodeimmersion ...
	 *
	 * @package app.Model
	 */
	class Periodeimmersion extends AppModel
	{
		public $name = 'Periodeimmersion';

		public $actsAs = array(
			'Formattable',
			'Autovalidate2'
		);

		public $belongsTo = array(
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>