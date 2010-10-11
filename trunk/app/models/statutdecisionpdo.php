<?php
	class Statutdecisionpdo extends AppModel
	{
		public $name = 'Statutdecisionpdo';

		public $displayField = 'libelle';

		public $validate = array(
			'libelle' => array(
				array( 'rule' => 'notEmpty' )
			)
		);

		public $actsAs = array(
			'ValidateTranslate'
		);

		public $hasAndBelongsToMany = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'joinTable' => 'propospdos_statutsdecisionspdos',
				'foreignKey' => 'statutdecisionpdo_id',
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
				'with' => 'PropopdoStatutdecisionpdo'
			)
		);

	}
?>
