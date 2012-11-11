<?php	
	/**
	 * Code source de la classe Detailcalculdroitrsa.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Detailcalculdroitrsa ...
	 *
	 * @package app.Model
	 */
	class Detailcalculdroitrsa extends AppModel
	{
		public $name = 'Detailcalculdroitrsa';

		public $validate = array(
			'detaildroitrsa_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Detaildroitrsa' => array(
				'className' => 'Detaildroitrsa',
				'foreignKey' => 'detaildroitrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>