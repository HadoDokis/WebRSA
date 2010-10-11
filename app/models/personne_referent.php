<?php
	class PersonneReferent extends AppModel
	{
		public $name = 'PersonneReferent';

		public $actsAs = array(
			'Formattable' => array(
				'suffix' => array( 'referent_id' )
			)
		);

		public $validate = array(
            'referent_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
			'dddesignation' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				)
			)
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		*
		*/

		public function dossierId( $pers_ref_id ){
			$this->unbindModelAll();
			$this->bindModel(
				array(
					'hasOne' => array(
						'Personne' => array(
							'foreignKey' => false,
							'conditions' => array( 'Personne.id = PersonneReferent.personne_id' )
						),
						'Foyer' => array(
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
						)
					)
				)
			);
			$rdv = $this->findById( $pers_ref_id, null, null, 0 );
	// debug( $rdv );
			if( !empty( $rdv ) ) {
				return $rdv['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		*
		*/

		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			$hasMany = ( array_depth( $this->data ) > 2 );

			if( !$hasMany ) { // INFO: 1 seul enregistrement
				if( array_key_exists( 'referent_id', $this->data[$this->name] ) ) {
					$this->data[$this->name]['referent_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data[$this->name]['referent_id'] );
				}
			}
			else { // INFO: plusieurs enregistrements
				foreach( $this->data[$this->name] as $key => $value ) {
					if( is_array( $value ) && array_key_exists( 'referent_id', $value ) ) {
						$this->data[$this->name][$key]['referent_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $value['referent_id'] );
					}
				}
			}

			return $return;
		}

		/**
		*
		*/

		public function sqDerniere($field) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$table = $dbo->fullTableName( $this, false );
			return "
				SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.personne_id = ".$field."
					ORDER BY {$table}.dddesignation DESC
					LIMIT 1
			";
		}
	}
?>
