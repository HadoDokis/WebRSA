<?php	
	/**
	 * Code source de la classe Nameapre66Typeaideapre66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Nameapre66Typeaideapre66 ...
	 *
	 * @package app.Model
	 */
	class Nameapre66Typeaideapre66 extends AppModel
	{
		public $name = 'Nameapre66Typeaideapre66';

		public $belongsTo = array(
			'Nameapre66' => array(
				'className' => 'Nameapre66',
				'foreignKey' => 'nameapre66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typeaideapre66' => array(
				'className' => 'Typeaideapre66',
				'foreignKey' => 'typeaideapre66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>