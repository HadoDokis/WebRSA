<?php
	class Seanceep extends AppModel
	{
		public $name = 'Seanceep';

        public $displayField = 'dateseance';

		public $actsAs = array(
			'Autovalidate',
            'Enumerable' => array(
                'fields' => array(
                    'finaliseeep',
                    'finaliseecg',
                    'demandesreorient',
                )
            ),
            'Formattable'
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
                'with' => 'PartepSeanceep'
			),
// 			'Demandereorient' => array(
// 				'className' => 'Demandereorient',
// 				'joinTable' => 'demandesreorient_seanceseps',
// 				'foreignKey' => 'seanceep_id',
// 				'associationForeignKey' => 'demandereorient_id',
// 				'unique' => true,
// 			)
		);
	}
?>