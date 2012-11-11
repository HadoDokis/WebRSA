<?php	
	/**
	 * Code source de la classe Memo.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Memo ...
	 *
	 * @package app.Model
	 */
	class Memo extends AppModel
	{
		public $name = 'Memo';

		public $validate = array(
			'dddesignation' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				)
			)
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
