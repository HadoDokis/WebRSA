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
			'Seanceep',
			'Motifdemreorient',
			// FIXME: ça casse souvent à cause de ces alias
// 			'VxTypeorient' => array(
// 				'className' => 'Typeorient',
// 				'foreignKey' => 'vx_typeorient_id'
// 			),
// 			'VxStructurereferente' => array(
// 				'className' => 'Structurereferente',
// 				'foreignKey' => 'vx_structurereferente_id'
// 			),
// 			'VxReferent' => array(
// 				'className' => 'Referent',
// 				'foreignKey' => 'vx_referent_id'
// 			),
// 			'NvTypeorient' => array(
// 				'className' => 'Typeorient',
// 				'foreignKey' => 'nv_typeorient_id'
// 			),
// 			'NvStructurereferente' => array(
// 				'className' => 'Structurereferente',
// 				'foreignKey' => 'nv_structurereferente_id'
// 			),
// 			'NvReferent' => array(
// 				'className' => 'Referent',
// 				'foreignKey' => 'nv_referent_id'
// 			),
// 			'NvOrientstruct' => array(
// 				'className' => 'Orientstruct',
// 				'foreignKey' => 'nv_orientstruct_id'
// 			),
		);

		var $hasOne = array(
			'Decisionreorientequipe' => array(
				'className' => 'Decisionreorientequipe',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'Decisionreorientequipe.etape' => 'ep'
				)
			),
			'Decisionreorientconseil' => array(
				'className' => 'Decisionreorientconseil',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'Decisionreorientconseil.etape' => 'cg'
				)
			),
		);

		/*public $virtualFields = array(
			'statut' => array(
				'type'		=> 'integer',
				'postgres'	=> '( SELECT COUNT( "demandesreorient_seanceseps"."id" ) FROM "demandesreorient_seanceseps" WHERE "demandesreorient_seanceseps"."demandereorient_id" = "%s"."id" )'
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

		/**
		*
		*/

		public function countAtraiterParZonegeographique( $seanceep_id = null ) {
			$this->unbindModelAll();
			return $this->find(
				'all',
				array(
					'fields' => array(
						"COUNT(\"{$this->alias}\".\"id\") AS \"{$this->alias}__limit\"",
						'Adresse.numcomptt',
						'Adresse.locaadr',
					),
					'conditions' => array(
						/*'( SELECT COUNT( "demandesreorient_seanceseps"."id" )
							FROM "demandesreorient_seanceseps"
							WHERE "demandesreorient_seanceseps"."demandereorient_id" = "'.$this->alias.'"."id"
								AND (
									"demandesreorient_seanceseps"."seanceep_id" = \''.$seanceep_id.'\'
									OR "demandesreorient_seanceseps"."seanceep_id" IS NULL
								)
						) = \'0\''*/
						'or' => array(
							"{$this->alias}.seanceep_id" => $seanceep_id,
							"{$this->alias}.seanceep_id IS NULL",
						)
					),
					'joins' => array(
						array(
							'table'      => Inflector::tableize( 'Personne' ),
							'alias'      => 'Personne',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Personne.id = {$this->alias}.personne_id" )
						),
						array(
							'table'      => Inflector::tableize( 'Foyer' ),
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Personne.foyer_id = Foyer.id" )
						),
						array(
							'table'      => 'adresses_foyers',
							'alias'      => 'Adressefoyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								"Adressefoyer.foyer_id = Foyer.id",
								"Adressefoyer.rgadr = '01'"
							)
						),
						array(
							'table'      => 'adresses',
							'alias'      => 'Adresse',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Adresse.id = Adressefoyer.adresse_id" )
						),
					),
					'group' => array( 'Adresse.numcomptt', 'Adresse.locaadr' ),
					'order' => array( 'Adresse.locaadr' ),
					'recursive' => -1
				)
			);
		}

		/**
		*
		*/

		public function marquerAtraiterParZonegeographique( $seanceep_id, $numcomptt, $limit ) {
			if( $limit == 0 ) {
				return true;
			}

			$this->unbindModelAll();
			$ids = $this->find(
				'all',
				array(
					'fields' => array(
						"{$this->alias}.id"
					),
					'conditions' => array( /// FIXME
						"Adresse.numcomptt" => $numcomptt,
						"{$this->alias}.seanceep_id" => NULL,
// 						"{$this->alias}.passageep" => '1',
					),
					'joins' => array(
						array(
							'table'      => Inflector::tableize( 'Personne' ),
							'alias'      => 'Personne',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Personne.id = {$this->alias}.personne_id" )
						),
						array(
							'table'      => Inflector::tableize( 'Foyer' ),
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Personne.foyer_id = Foyer.id" )
						),
						array(
							'table'      => 'adresses_foyers',
							'alias'      => 'Adressefoyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								"Adressefoyer.foyer_id = Foyer.id",
								"Adressefoyer.rgadr = '01'"
							)
						),
						array(
							'table'      => 'adresses',
							'alias'      => 'Adresse',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Adresse.id = Adressefoyer.adresse_id" )
						),
					),
					'order' => array(
						"{$this->alias}.urgent DESC", // INFO: d'abord les urgents
						"{$this->alias}.created ASC"
					),
					'recursive' => -1,
					'limit' => $limit
				)
			);

			$ids = Set::extract( $ids, "/{$this->alias}/id" );
			return $this->updateAll(
				array( "{$this->alias}.seanceep_id" => $seanceep_id ),
				array( "{$this->alias}.id" => $ids )
			);
		}
    }
?>