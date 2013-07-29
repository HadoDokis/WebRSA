<?php
	/**
	 * Code source de la classe Formationcui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Formationcui66 ...
	 *
	 * @package app.Model
	 */
	class Formationcui66 extends AppModel
	{
		public $name = 'Formationcui66';

		public $recursive = -1;

		public $actsAs = array(
			'Formattable',
            'Pgsqlcake.PgsqlAutovalidate'
		);

		public $belongsTo = array(
			'Accompagnementcui66' => array(
				'className' => 'Accompagnementcui66',
				'foreignKey' => 'accompagnementcui66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>