<?php
	class Seanceep extends AppModel
	{
		public $name = 'Seanceep';

		public $actsAs = array(
			'Autovalidate'
		);

		public $belongsTo = array(
			'Ep',
			'Structurereferente'
		);

		public $hasMany = array(
			'Demandereorient'
		);

		public $hasAndBelongsToMany = array(
			'Partep' => array(
				'className' => 'Partep',
				'joinTable' => 'partseps_seanceseps',
				'foreignKey' => 'seanceep_id',
				'associationForeignKey' => 'partep_id',
				'unique' => true,
			),
			'Demandereorient' => array(
				'className' => 'Demandereorient',
				'joinTable' => 'demandesreorient_seanceseps',
				'foreignKey' => 'seanceep_id',
				'associationForeignKey' => 'demandereorient_id',
				'unique' => true,
			)
		);
	}
?>