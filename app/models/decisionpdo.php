<?php
	class Decisionpdo extends AppModel
	{
		public $name = 'Decisionpdo';

		public $displayField = 'libelle';

		public $order = 'Decisionpdo.id ASC';

		public $hasMany = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'decisionpdo_id',
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

		public $validate = array(
			'libelle' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			)
		);
	}
?>