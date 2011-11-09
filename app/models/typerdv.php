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
			),
			'motifpassageep' => array(
				'rule' => array( 'notEmptyIf', 'nbabsencesavpassageep', false, array( 0 ) ),
				'message' => 'Champ obligatoire',
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



		public $hasAndBelongsToMany = array(
			'Statutrdv' => array(
				'className' => 'Statutrdv',
				'joinTable' => 'statutsrdvs_typesrdv',
				'foreignKey' => 'typerdv_id',
				'associationForeignKey' => 'statutrdv_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'StatutrdvTyperdv'
			)
		);
	}
?>