<?php	
	/**
	 * Code source de la classe Prestform.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Prestform ...
	 *
	 * @package app.Model
	 */
	class Prestform extends AppModel
	{
		public $name = 'Prestform';

		public $validate = array(
			'actioninsertion_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'refpresta_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
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
			),
			'Refpresta' => array(
				'className' => 'Refpresta',
				'foreignKey' => 'refpresta_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>