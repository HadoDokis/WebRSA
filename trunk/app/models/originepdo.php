<?php
	class Originepdo extends AppModel
	{
		public $name = 'Originepdo';

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

		public $validate = array(
			'libelle' => array(
				array(
						'rule' => 'isUnique',
						'message' => 'Valeur déjà utilisée'
				),
			)
		);

		public $hasMany = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'originepdo_id',
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
				'foreignKey' => 'originepdo_id',
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