<?php
	class Decisionpdo extends AppModel
	{
		public $name = 'Decisionpdo';

		public $displayField = 'libelle';

		public $order = 'Decisionpdo.id ASC';

		public $actsAs = array(
			'Autovalidate',
			'Enumerable' => array(
				'fields' => array(
					'clos',
					'nbmoisecheance',
					'cerparticulier'
				)
			)
		);

		public $hasMany = array(
			'Decisionpropopdo' => array(
				'className' => 'Decisionpropopdo',
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
			),
			'Decisionpersonnepcg66' => array(
				'className' => 'Decisionpersonnepcg66',
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
				array(
					'rule' => 'isUnique',
					'message' => 'Cette valeur est déjà utilisée'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
			),
		);
	}
?>