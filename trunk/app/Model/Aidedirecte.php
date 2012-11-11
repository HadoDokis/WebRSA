<?php	
	/**
	 * Code source de la classe Aidedirecte.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Aidedirecte ...
	 *
	 * @package app.Model
	 */
	class Aidedirecte extends AppModel
	{
		public $name = 'Aidedirecte';

		public $validate = array(
			'actioninsertion_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Actioninsertion' => array(
				'className' => 'Actioninsertion',
				'foreignKey' => 'actioninsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>