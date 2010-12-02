<?php
	class Typeorient extends AppModel
	{
		public $name = 'Typeorient';

		public $displayField = 'lib_type_orient';

		public $hasMany = array(
			'Decisionparcours' => array(
				'className' => 'Decisionparcours',
				'foreignKey' => 'typeorient_id',
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
			'Precosreorient' => array(
				'className' => 'Precosreorient',
				'foreignKey' => 'typeorient_id',
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
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'typeorient_id',
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
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'typeorient_id',
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

		public $validate = array(
			'lib_type_orient' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'modele_notif' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'modele_notif_cohorte' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
		);

		/**
		*
		*/

		public function listOptions() {
			$options = $this->find(
				'list',
				array (
					'fields' => array(
						'Typeorient.id',
						'Typeorient.lib_type_orient'
					),
					'conditions' => array( 'Typeorient.parentid' => NULL ),
					'order'  => array( 'Typeorient.lib_type_orient ASC' )
				)
			);

			if( $this->find( 'count', array( 'conditions' => array( 'Typeorient.parentid NOT' => NULL ) ) ) > 0 ) {
				$list = array();
				foreach( $options as $key => $option ) {
					$innerOptions = $this->find(
						'list',
						array (
							'fields' => array(
								'Typeorient.id',
								'Typeorient.lib_type_orient'/*,
								'Typeorient.parentid'*/
							),
							'conditions' => array( 'Typeorient.parentid' => $key ),
							'order'  => array( 'Typeorient.lib_type_orient ASC' )
						)
					);
					$list[$option] = $innerOptions ;
				}
				return $list;
			}
			else {
				return $options;
			}
		}

		/**
		*
		*/

		public function occurences() {
			// Orientstruct
			$queryData = array(
				'fields' => array(
					'"Typeorient"."id"',
					'COUNT("Structurereferente"."id") + COUNT("Orientstruct"."id") AS "Typeorient__occurences"',
				),
				'joins' => array(
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Structurereferente',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Structurereferente.typeorient_id = Typeorient.id' )
					),
					array(
						'table'      => 'orientsstructs',
						'alias'      => 'Orientstruct',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Orientstruct.typeorient_id = Typeorient.id' )
					),
				),
				'recursive' => -1,
				'group' => array( '"Typeorient"."id"' )
			);
			$results = $this->find( 'all', $queryData );

			return Set::combine( $results, '{n}.Typeorient.id', '{n}.Typeorient.occurences' );
		}

		/**
		*   Recherche du type d'orientation qui n'a plus de parent
		*/

		public function getIdLevel0( $typeorient_id ) {
			$tmpTypeorient = $this->find(
				'first',
				array(
					'fields' => array( 'Typeorient.id', 'Typeorient.parentid' ),
					'recursive' => -1,
					'conditions' => array(
						'Typeorient.id' => $typeorient_id
					)
				)
			);
			if( !empty( $tmpTypeorient ) ) {
				while( $parentid = Set::classicExtract( $tmpTypeorient, 'Typeorient.parentid' ) ) {
					$tmpTypeorient = $this->find(
						'first',
						array(
							'fields' => array( 'Typeorient.id', 'Typeorient.parentid' ),
							'recursive' => -1,
							'conditions' => array(
								'Typeorient.id' => $parentid
							)
						)
					);
				}
			}
			if( !empty( $tmpTypeorient ) ) {
				$typeorient_niv1_id = Set::classicExtract( $tmpTypeorient, 'Typeorient.id' );
				if( !empty( $typeorient_niv1_id ) ) {
					return $typeorient_niv1_id;
				}
			}
			return null;
		}
	}
?>