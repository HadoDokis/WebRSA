<?php	
	/**
	 * Code source de la classe Evenement.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Evenement ...
	 *
	 * @package app.Model
	 */
	class Evenement extends AppModel
	{
		public $name = 'Evenement';

		public $validate = array(
			'foyer_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Foyer' => array(
				'className' => 'Foyer',
				'foreignKey' => 'foyer_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>