<?php	
	/**
	 * Code source de la classe Tauxcgcui.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Tauxcgcui ...
	 *
	 * @package app.Model
	 */
	class Tauxcgcui extends AppModel
	{
		public $name = 'Tauxcgcui';

		public $recursive = -1;

		public $actsAs = array(
			'Formattable',
			'Pgsqlcake.PgsqlAutovalidate'
		);

		public $belongsTo = array(
			'Secteurcui' => array(
				'className' => 'Secteurcui',
				'foreignKey' => 'secteurcui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>