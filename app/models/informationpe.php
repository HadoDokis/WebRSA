<?php
	class Informationpe extends AppModel
	{
		public $name = 'Informationpe';

		// FIXME: validation

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Historiqueetatpe' => array(
				'className' => 'Historiqueetatpe',
				'foreignKey' => 'informationpe_id',
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
		);
	}
?>