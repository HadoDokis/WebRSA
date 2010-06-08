<?php
	class Decisionreorient extends AppModel
	{
		public $name = 'Decisionreorient';

		public $actsAs = array(
			'Autovalidate',
			'Enumerable' => array(
				'fields' => array(
					'decision'
				)
			),
		);

// 		public $belongsTo = array(
// 			'Personne',
// 			'Orientstruct',
// 			'Motifdemreorient',
// 			'VxTypeorient' => array(
// 				'className' => 'Typeorient',
// 				'foreignKey' => 'vx_typeorient_id'
// 			)
// 		);

		/*public $hasMany = array(
			'Decisionreorient'
		);*/
	}
?>