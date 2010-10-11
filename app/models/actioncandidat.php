<?php
	class Actioncandidat extends AppModel
	{
		public $name = 'Actioncandidat';

		public $displayField = 'intitule';

		public $actsAs = array(
			'ValidateTranslate',
		);

		public $validate = array(
			'intitule' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'code' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
		);

		public $hasAndBelongsToMany = array(
			'Partenaire' => array(
				'className' => 'Partenaire',
				'joinTable' => 'actionscandidats_partenaires',
				'foreignKey' => 'actioncandidat_id',
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
				'with' => 'ActioncandidatPartenaire'
			),
			'Personne' => array(
				'className' => 'Personne',
				'joinTable' => 'actionscandidats_personnes',
				'foreignKey' => 'actioncandidat_id',
				'associationForeignKey' => 'personne_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ActioncandidatPersonne'
			)
		);
	}
?>
