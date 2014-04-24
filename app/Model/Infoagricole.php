<?php
	/**
	 * Code source de la classe Infoagricole.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Infoagricole ...
	 *
	 * @package app.Model
	 */
	class Infoagricole extends AppModel
	{
		public $name = 'Infoagricole';

		protected $_modules = array( 'caf' );

		public $actsAs = array(
			'Allocatairelie',
		);

		public $validate = array(
			'personne_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
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

		public $hasMany = array(
			'Aideagricole' => array(
				'className' => 'Aideagricole',
				'foreignKey' => 'infoagricole_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);
	}
?>