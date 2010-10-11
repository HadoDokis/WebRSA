<?php
	class Situationpdo extends AppModel
	{
		public $name = 'Situationpdo';

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
				'joinTable' => 'propospdos_situationspdos',
				'foreignKey' => 'situationpdo_id',
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
				'with' => 'PropopdoSituationpdo'
			)
		);
	}
?>
