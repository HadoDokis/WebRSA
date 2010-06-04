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
				'suffix' => array(
					'vx_structurereferente_id',
					'vx_referent_id',
					'nv_structurereferente_id',
					'nv_referent_id',
				),
			)
		);

		var $belongsTo = array(
			'Personne',
			'Orientstruct',
			'Motifdemreorient',
			'VxTypeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'vx_typeorient_id'
			),
			'VxStructurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'vx_structurereferente_id'
			),
			'VxReferent' => array(
				'className' => 'Referent',
				'foreignKey' => 'vx_referent_id'
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
			'NvOrientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'nv_orientstruct_id'
			),
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

        public function beforeSave( $created ) {
            $return = parent::beforeSave( $created );

			foreach( array( 'accordconcertation', 'passageep', 'nv_typeorient_id', 'nv_structurereferente_id', 'nv_referent_id', 'nv_orientstruct_id' ) as $field ) {
				${$field} = Set::classicExtract( $this->data, "{$this->alias}.{$field}" );
			}

			if( $created ) {
				$this->data[$this->alias]['dateecheance'] = date( 'Y-m-d', strtotime( '+1 month' ) );
			}

			if( empty( $nv_orientstruct_id ) && $accordconcertation == 'accord' && empty( $passageep ) && !empty( $nv_typeorient_id ) && !empty( $nv_structurereferente_id ) && !empty( $nv_referent_id ) ) {
				$orientstruct = array(
					$this->Orientstruct->alias => array(
						'personne_id' => $this->data[$this->alias]['personne_id'],
						'typeorient_id' => $this->data[$this->alias]['nv_typeorient_id'],
						'structurereferente_id' => $this->data[$this->alias]['nv_structurereferente_id'],
						'referent_id' => $this->data[$this->alias]['nv_referent_id'],
						'valid_cg' => true,
						'date_propo' => date( 'Y-m-d' ),
						'date_valid' => date( 'Y-m-d' ),
						'statut_orient' => 'Orienté',
					)
				);
				$this->Orientstruct->create( $orientstruct );
				$return = $this->Orientstruct->save() && $return;
				$this->data[$this->alias]['nv_orientstruct_id'] = $this->Orientstruct->id;
			}


            return $return;
        }
    }
?>