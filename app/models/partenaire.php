<?php
	class Partenaire extends AppModel
	{
		public $name = 'Partenaire';

		public $displayField = 'libstruc';

		public $actsAs = array(
			'ValidateTranslate',
		);

		public $validate = array(
			'libstruc' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'numvoie' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'typevoie' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'nomvoie' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'compladr' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'codepostal' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'ville' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
		);

		public $hasMany = array(
			'Contactpartenaire' => array(
				'className' => 'Contactpartenaire',
				'foreignKey' => 'partenaire_id',
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

		public $hasAndBelongsToMany = array(
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'joinTable' => 'actionscandidats_partenaires',
				'foreignKey' => 'partenaire_id',
				'associationForeignKey' => 'actioncandidat_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ActioncandidatPartenaire'
			),
			'Contactpartenaire' => array(
				'className' => 'Contactpartenaire',
				'joinTable' => 'contactspartenaires_partenaires',
				'foreignKey' => 'partenaire_id',
				'associationForeignKey' => 'contactpartenaire_id',
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