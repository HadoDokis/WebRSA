<?php
    class Demandereorient extends AppModel
    {
        public $name = 'Demandereorient';

        public $order = array( 'Demandereorient.created ASC' );

		public $actsAs = array(
			'Autovalidate',
			'Enumerable' => array(
				'fields' => array(
					'accordconcertation',
					'urgent' => array(
						'values' => array( 0, 1 )
					),
					'passageep' => array(
						'values' => array( 0, 1 )
					),
				)
			),
			'Formattable' => array(
				'suffix' => array( 'nv_structurereferente_id', 'nv_referent_id' ),
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
			'NvTypeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'nv_typeorient_id'
			),
			'NvStructurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'nv_structurereferente_id'
			),
			'NvReferent' => array(
				'className' => 'Referent',
				'foreignKey' => 'nv_referent_id'
			),
// 			'VxReferent' => array(
// 				'className' => 'Referent',
// 				'foreignKey' => 'vx_referent_id'
// 			),
		);

		/*public $virtualFields = array(
			'statut' => array(
				'type'		=> 'string',
				'postgres'	=> '( SELECT COUNT( "precosreorients"."id" ) FROM "precosreorients" WHERE "precosreorients"."demandereorient_id" = "%s"."id" )'
			),
		);*/

		/**
		* FIXME: on aura besoin du meme traitement ailleurs
		*/

        public function afterSave( $created ) {
            $return = parent::afterSave( $created );

			foreach( array( 'accordconcertation' ) as $field ) {
				${$field} = Set::classicExtract( $this->data, "{$this->alias}.{$field}" );
			}

			if( $created && ( $accordconcertation == 'accord' ) ) {
				debug( $this->data );
			}

            return $return;
        }
    }
?>