<?php
	class Aidedirecte extends AppModel
	{
		public $name = 'Aidedirecte';

		public $validate = array(
			'actioninsertion_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			/*
			'lib_aide' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'date_aide' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'typo_aide' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)*/
		);

		public $belongsTo = array(
			'Actioninsertion' => array(
				'className' => 'Actioninsertion',
				'foreignKey' => 'actioninsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>