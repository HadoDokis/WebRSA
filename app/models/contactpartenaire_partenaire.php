<?php
	class ContactpartenairePartenaire extends AppModel
	{
		public $name = 'ContactpartenairePartenaire';

		public $validate = array(
			'partenaire_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'contactpartenaire_id' => array(
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
			'Partenaire' => array(
				'className' => 'Partenaire',
				'foreignKey' => 'partenaire_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Contactpartenaire' => array(
				'className' => 'Contactpartenaire',
				'foreignKey' => 'contactpartenaire_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>