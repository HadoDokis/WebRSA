<?php	
	/**
	 * Code source de la classe Secteurcui.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Secteurcui ...
	 *
	 * @package app.Model
	 */
	class Secteurcui extends AppModel
	{
		public $name = 'Secteurcui';

		public $recursive = -1;

		public $actsAs = array(
			'Formattable',
			'Pgsqlcake.PgsqlAutovalidate'
		);

		public $hasMany = array(
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'secteurcui_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Tauxcgcui' => array(
				'className' => 'Tauxcgcui',
				'foreignKey' => 'secteurcui_id',
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