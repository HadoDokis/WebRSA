<?php
	class Typepdo extends AppModel
	{
		public $name = 'Typepdo';

		public $displayField = 'libelle';

		public $order = 'Typepdo.id ASC';

		public $validate = array(
			'libelle' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			)
		);

		public $hasMany = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'typepdo_id',
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