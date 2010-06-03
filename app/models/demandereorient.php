<?php
    class Demandereorient extends AppModel
    {
        public $name = 'Demandereorient';

        public $order = array( 'Demandereorient.created ASC' );

		public $actsAs = array(
			'Autovalidate',
			'Enumerable' => array(
				'fields' => array(
					'accord',
					'urgent' => array(
						'values' => array( 0, 1 )
					),
					'passageep' => array(
						'values' => array( 0, 1 )
					),
				)
			)
		);

		var $belongsTo = array(
// 			'Ep' => array(
// 				'type' => 'LEFT OUTER',
// 			),
			'Motifdemreorient',
			'Orientstruct' => array(
				'type' => 'LEFT OUTER',
			),
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id'
			),
			'NvReferent' => array(
				'className' => 'Referent',
				'foreignKey' => 'nv_referent_id'
			),
			'VxReferent' => array(
				'className' => 'Referent',
				'foreignKey' => 'vx_referent_id'
			),
		);

		/*public $virtualFields = array(
			'statut' => array(
				'type'		=> 'string',
				'postgres'	=> '( SELECT COUNT( "precosreorients"."id" ) FROM "precosreorients" WHERE "precosreorients"."demandereorient_id" = "%s"."id" )'
			),
		);*/
    }
?>