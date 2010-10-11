<?php
	class DetailressourcemensuelleRessourcemensuelle extends AppModel
	{
		public $name = 'DetailressourcemensuelleRessourcemensuelle';

		public $validate = array(
			'detailressourcemensuelle_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'ressourcemensuelle_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);

		public $belongsTo = array(
			'Detailressourcemensuelle' => array(
				'className' => 'Detailressourcemensuelle',
				'foreignKey' => 'detailressourcemensuelle_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Ressourcemensuelle' => array(
				'className' => 'Ressourcemensuelle',
				'foreignKey' => 'ressourcemensuelle_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>