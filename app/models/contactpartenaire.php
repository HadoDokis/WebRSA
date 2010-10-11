<?php
	class Contactpartenaire extends AppModel
	{
		public $name = 'Contactpartenaire';

		public $actsAs = array(
			'ValidateTranslate',
		);

		public $validate = array(
			'qual' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'nom' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'prenom' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'partenaire_id' => array(
				array(
					'rule' => array('notEmpty'),
				),
				'numeric' => array(
					'rule' => array('numeric'),
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
			)
		);

		public $hasAndBelongsToMany = array(
			'Partenaire' => array(
				'className' => 'Partenaire',
				'joinTable' => 'contactspartenaires_partenaires',
				'foreignKey' => 'contactpartenaire_id',
				'associationForeignKey' => 'partenaire_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ContactpartenairePartenaire'
			)
		);
	}
?>
