<?php	
	/**
	 * Code source de la classe Prestation.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Prestation ...
	 *
	 * @package app.Model
	 */
	class Prestation extends AppModel
	{
		public $name = 'Prestation';

		protected $_modules = array( 'caf' );

		public $validate = array(
			// Role personne
			'rolepers' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>