<?php	
	/**
	 * Code source de la classe Suspensionversement.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Suspensionversement ...
	 *
	 * @package app.Model
	 */
	class Suspensionversement extends AppModel
	{
		public $name = 'Suspensionversement';

		public $validate = array(
			'situationdossierrsa_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Situationdossierrsa' => array(
				'className' => 'Situationdossierrsa',
				'foreignKey' => 'situationdossierrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>