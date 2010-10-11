<?php
	class Statutpdo extends AppModel
	{
		public $name = 'Statutpdo';

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
			)
		);
	}
?>
