<?php
	class Traitementpdo extends AppModel
	{
		public $name = 'Traitementpdo';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'hascourrier',
					'hasrevenu',
					'haspiecejointe',
					'hasficheanalyse'
				)
			)
		);

		public $validate = array(
			'propopdo_id' => array(
				'rule' => 'numeric',
				'message' => 'Champ obligatoire',
				'allowEmpty' => false
			),
			'descriptionpdo_id' => array(
				'rule' => 'numeric',
				'message' => 'Champ obligatoire',
				'allowEmpty' => false
			),
			'traitementtypepdo_id' => array(
				'rule' => 'numeric',
				'message' => 'Champ obligatoire',
				'allowEmpty' => false
			),
			'datereception' => array(
				'rule' => 'date',
				'message' => 'Veuillez entrer une date valide.',
				'allowEmpty' => true
			),
			'datedepart' => array(
				'rule' => 'date',
				'message' => 'Veuillez entrer une date valide.',
				'allowEmpty' => true
			),
		);

		public $belongsTo = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Descriptionpdo' => array(
				'className' => 'Descriptionpdo',
				'foreignKey' => 'descriptionpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Traitementtypepdo' => array(
				'className' => 'Traitementtypepdo',
				'foreignKey' => 'traitementtypepdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>
