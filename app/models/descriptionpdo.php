<?php
	class Descriptionpdo extends AppModel
	{
		public $name = 'Descriptionpdo';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'sensibilite'
				)
			),
			'ValidateTranslate'
		);

		public $validate = array(
			'name' => array(
				array(
					'rule' => array('notEmpty'),
				),
				array(
					'rule' => array('isUnique'),
				),
			),
			'modelenotification' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'sensibilite' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
		);

		public $hasMany = array(
			'Traitementpdo' => array(
				'className' => 'Traitementpdo',
				'foreignKey' => 'descriptionpdo_id',
				'dependent' => true,
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