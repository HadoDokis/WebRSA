<?php
	class Statutrdv extends AppModel
	{
		public $name = 'Statutrdv';

		public $displayField = 'libelle';

		public $order = 'Statutrdv.id ASC';

		public $validate = array(
			'libelle' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			)
		);

		public $hasMany = array(
			'Rendezvous' => array(
				'className' => 'Rendezvous',
				'foreignKey' => 'statutrdv_id',
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
