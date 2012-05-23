<?php
	class Typepdo extends AppModel
	{
		public $name = 'Typepdo';

		public $displayField = 'libelle';

		public $actsAs = array(
			'ValidateTranslate',
			'Autovalidate',
			'Enumerable' => array(
				'fields' => array(
					'originepcg',
					'cerparticulier'
				)
			)
		);

		public $order = 'Typepdo.id ASC';

		public $validate = array(
			'libelle' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'isUnique',
					'message' => 'Valeur déjà utilisée'
				),
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
			),
			'Dossierpcg66' => array(
				'className' => 'Dossierpcg66',
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