<?php	
	/**
	 * Code source de la classe Condadmin.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Condadmin ...
	 *
	 * @package app.Model
	 */
	class Condadmin extends AppModel
	{
		public $name = 'Condadmin';

		public $validate = array(
			'avispcgdroitrsa_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Avispcgdroitrsa' => array(
				'className' => 'Avispcgdroitrsa',
				'foreignKey' => 'avispcgdroitrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>