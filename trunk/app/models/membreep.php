<?php
	class Membreep extends AppModel
	{
		public $name = 'Membreep';

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'qual'					
				)
			)
		);		

		public $belongsTo = array(
			'Fonctionmembreep' => array(
				'className' => 'Fonctionmembreep',
				'foreignKey' => 'fonctionmembreep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Ep' => array(
				'className' => 'Ep',
				'foreignKey' => 'ep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Membreep' => array(
				'className' => 'Membreep',
				'foreignKey' => 'suppleant_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasOne = array(
			'Membreep' => array(
				'className' => 'Membreep',
				'foreignKey' => 'suppleant_id',
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
		
		public $hasAndBelongsToMany = array(
			'Seanceep' => array(
				'className' => 'Seanceep',
				'joinTable' => 'membreseps_seanceseps',
				'foreignKey' => 'membreep_id',
				'associationForeignKey' => 'seanceep_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'MembreepSeanceep'
			)
		);					
	}
?>
