<?php	
	/**
	 * Code source de la classe Nameapre66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Nameapre66 ...
	 *
	 * @package app.Model
	 */
	class Nameapre66 extends AppModel
	{
		public $name = 'Nameapre66';

		public $belongsTo = array(
			'Nameapre66Typeaideapre66' => array(
				'className' => 'Nameapre66Typeaideapre66',
				'foreignKey' => 'nameapre66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasAndBelongsToMany = array(
			'Typeaideapre66' => array(
				'className' => 'Typeaideapre66',
				'joinTable' => 'namesapres66_typesaidesapres66',
				'foreignKey' => 'nameapre66_id',
				'associationForeignKey' => 'typeaideapre66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Nameapre66Typeaideapre66'
			)
		);
	}
?>