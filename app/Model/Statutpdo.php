<?php	
	/**
	 * Code source de la classe Statutpdo.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Statutpdo ...
	 *
	 * @package app.Model
	 */
	class Statutpdo extends AppModel
	{
		public $name = 'Statutpdo';

		public $displayField = 'libelle';

		public $validate = array(
			'libelle' => array(
				array( 'rule' => 'notEmpty' ),
				array(
						'rule' => 'isUnique',
						'message' => 'Valeur déjà utilisée'
				)
			)
		);

		public $actsAs = array(
			'ValidateTranslate'
		);

		public $hasAndBelongsToMany = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'joinTable' => 'propospdos_statutspdos',
				'foreignKey' => 'statutpdo_id',
				'associationForeignKey' => 'propopdo_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'PropopdoStatutpdo'
			),
			'Personnepcg66' => array(
				'className' => 'Personnepcg66',
				'joinTable' => 'personnespcgs66_statutspdos',
				'foreignKey' => 'statutpdo_id',
				'associationForeignKey' => 'personnepcg66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Personnepcg66Statutpdo'
			)
		);
	}
?>
