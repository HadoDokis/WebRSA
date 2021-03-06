<?php
	/**
	 * Code source de la classe PropopdoStatutdecisionpdo.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe PropopdoStatutdecisionpdo ...
	 *
	 * @package app.Model
	 */
	class PropopdoStatutdecisionpdo extends AppModel
	{
		public $name = 'PropopdoStatutdecisionpdo';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $actsAs = array (
			'Formattable',
			'ValidateTranslate'
		);

		public $validate = array(
			'propopdo_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'statutdecisionpdo_id' => array(
				array( 'rule' => 'notEmpty' )
			)
		);

		public $belongsTo = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Statutdecisionpdo' => array(
				'className' => 'Statutdecisionpdo',
				'foreignKey' => 'statutdecisionpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>