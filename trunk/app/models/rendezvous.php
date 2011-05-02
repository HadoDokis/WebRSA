<?php
	class Rendezvous extends AppModel
	{
		public $name = 'Rendezvous';

		public $displayField = 'libelle';

		public $actsAs = array(
			'Formattable' => array(
				'suffix' => array( 'referent_id', 'permanence_id' )
			),
			'Enumerable' => array(
                'fields' => array(
                    'haspiecejointe'
                )
            ),
			'Gedooo'
		);

		public $validate = array(
			'structurereferente_id' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'typerdv_id' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'daterdv' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				)
			),
			'heurerdv' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'statutrdv_id' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
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
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typerdv' => array(
				'className' => 'Typerdv',
				'foreignKey' => 'typerdv_id',
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
			'Permanence' => array(
				'className' => 'Permanence',
				'foreignKey' => 'permanence_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Statutrdv' => array(
				'className' => 'Statutrdv',
				'foreignKey' => 'statutrdv_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'ActioncandidatPersonne' => array(
				'className' => 'ActioncandidatPersonne',
				'foreignKey' => 'rendezvous_id',
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
			'Bilanparcours' => array(
				'className' => 'Bilanparcours',
				'foreignKey' => 'rendezvous_id',
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
			'Entretien' => array(
				'className' => 'Entretien',
				'foreignKey' => 'rendezvous_id',
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
            'Fichiermodule' => array(
                'className' => 'Fichiermodule',
                'foreignKey' => false,
                'dependent' => false,
                'conditions' => array(
                    'Fichiermodule.modele = \'Rendezvous\'',
                    'Fichiermodule.fk_value = {$__cakeID__$}'
                ),
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
            )
		);

		/**
		* Retourne la clé primaire d'un dossier RSA à partir de la clé primaire
		* d'un rendez-vous.
		*/

		public function dossierId( $rdv_id ){
			$this->unbindModelAll();
			$this->bindModel(
				array(
					'hasOne' => array(
						'Personne' => array(
							'foreignKey' => false,
							'conditions' => array( 'Personne.id = Rendezvous.personne_id' )
						),
						'Foyer' => array(
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
						)
					)
				)
			);

			$rdv = $this->find(
				'first',
				array(
					'fields' => array( 'Foyer.dossier_id' ),
					'conditions' => array( "{$this->alias}.{$this->primaryKey}" => $rdv_id ),
					'recursive' => 0
				)
			);

			if( !empty( $rdv ) ) {
				return $rdv['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}
		
		/**
		 * Retourne un booléen selon si un dossier d'EP doit ou non
		 * être créé pour la personne dont l'id est passé en paramètre
		 */
		public function passageEp( $personne_id, $newTyperdv_id ) {
			$rdvs = $this->find(
				'all',
				array(
					'conditions' => array(
						'Rendezvous.typerdv_id' => $newTyperdv_id
					),
					'contain' => false,
					'order' => array( 'Rendezvous.daterdv DESC' ),
					'limit' => 2
				)
			);
			
			$typerdv = $this->Typerdv->find(
				'first',
				array(
					'conditions' => array(
						'Typerdv.id' => $newTyperdv_id
					),
					'contain' => false
				)
			);
			
			return ( count( $rdvs ) == $typerdv['Typerdv']['nbabsencesavpassageep'] );
		}
	}
?>
