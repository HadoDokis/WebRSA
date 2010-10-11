<?php
	class Typerdv extends AppModel
	{
		public $name = 'Typerdv';

		public $displayField = 'libelle';

		public $order = 'Typerdv.id ASC';

		public $validate = array(
			'libelle' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'modelenotifrdv' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			)
		);

		public $hasMany = array(
			'Entretien' => array(
				'className' => 'Entretien',
				'foreignKey' => 'typerdv_id',
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
			'Rendezvous' => array(
				'className' => 'Rendezvous',
				'foreignKey' => 'typerdv_id',
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