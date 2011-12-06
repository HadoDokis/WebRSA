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
				),
				array(
					'rule' => 'futureDate',
					'on' => 'create',
					'message' => 'Veuillez saisir une date postérieure à celle du jour.'
				)
			),
			'heurerdv' => array(
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
			),
			'Sanctionrendezvousep58' => array(
				'className' => 'Sanctionrendezvousep58',
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
		/* public function passageEp( $personne_id, $newTyperdv_id ) {
			$typerdv = $this->Typerdv->find(
				'first',
				array(
					'conditions' => array(
						'Typerdv.id' => $newTyperdv_id
					),
					'contain' => false
				)
			);

			$rdvs = $this->find(
				'all',
				array(
					'conditions' => array(
						'Rendezvous.typerdv_id' => $newTyperdv_id,
						'Rendezvous.personne_id' => $personne_id,
						'Rendezvous.statutrdv_id IN ('.$this->Statutrdv->sq(
							array(
								'alias' => 'statutsrdvs',
								'fields' => array(
									'statutsrdvs.id'
								),
								'conditions' => array(
									'statutsrdvs.provoquepassageep' => 1
								)
							)
						).' )'
					),
					'contain' => false,
					'order' => array( 'Rendezvous.daterdv DESC', 'Rendezvous.heurerdv DESC', 'Rendezvous.id DESC' )
				)
			);

			$dossierep = $this->Personne->Dossierep->find(
				'first',
				array(
					'conditions' => array(
						'Dossierep.personne_id' => $personne_id,
						'Dossierep.themeep' => 'sanctionsrendezvouseps58',
						'Dossierep.id NOT IN ( '.
							$this->Personne->Dossierep->Passagecommissionep->sq(
								array(
									'fields' => array(
										'passagescommissionseps.dossierep_id'
									),
									'alias' => 'passagescommissionseps',
									'conditions' => array(
										'passagescommissionseps.etatdossierep' => array ( 'traite', 'annule' )
									)
								)
							)
						.' )'
					),
					'contain' => array(
						'Sanctionrendezvousep58' => array(
							'Rendezvous' => array(
								'conditions' => array(
									'Rendezvous.typerdv_id' => $newTyperdv_id
								)
							)
						)
					)
				)
			);

			return ( ( count( $rdvs ) % $typerdv['Typerdv']['nbabsencesavpassageep'] ) == 0 && empty( $dossierep ) );
		} */


		/**
		* Retourne un booléen selon si un dossier d'EP doit ou non
		* être créé pour la personne dont l'id est passé en paramètre
		*/
		public function passageEp( $personne_id, $newTyperdv_id, $newStatutrdv_id ) {

			$statutrdvtyperdv = $this->Typerdv->StatutrdvTyperdv->find(
				'first',
				array(
					'conditions' => array(
						'StatutrdvTyperdv.typerdv_id' => $newTyperdv_id,
						'StatutrdvTyperdv.statutrdv_id' => $newStatutrdv_id
					),
					'contain' => false
				)
			);

// debug($statutrdvtyperdv);
// die();
			$rdvs = $this->find(
				'all',
				array(
					'conditions' => array(
						'Rendezvous.typerdv_id' => $newTyperdv_id,
						'Rendezvous.personne_id' => $personne_id,
						'Rendezvous.statutrdv_id IN ('.$this->Statutrdv->sq(
							array(
								'alias' => 'statutsrdvs',
								'fields' => array(
									'statutsrdvs.id'
								),
								'conditions' => array(
									'statutsrdvs.provoquepassageep' => 1
								)
							)
						).' )'
					),
					'contain' => false,
					'order' => array( 'Rendezvous.daterdv DESC', 'Rendezvous.heurerdv DESC', 'Rendezvous.id DESC' )
				)
			);

			$dossierep = $this->Personne->Dossierep->find(
				'first',
				array(
					'conditions' => array(
						'Dossierep.personne_id' => $personne_id,
						'Dossierep.themeep' => 'sanctionsrendezvouseps58',
						'Dossierep.id NOT IN ( '.
							$this->Personne->Dossierep->Passagecommissionep->sq(
								array(
									'fields' => array(
										'passagescommissionseps.dossierep_id'
									),
									'alias' => 'passagescommissionseps',
									'conditions' => array(
										'passagescommissionseps.etatdossierep' => array ( 'traite', 'annule' )
									)
								)
							)
						.' )'
					),
					'contain' => array(
						'Sanctionrendezvousep58' => array(
							'Rendezvous' => array(
								'conditions' => array(
									'Rendezvous.typerdv_id' => $newTyperdv_id
								)
							)
						)
					)
				)
			);

			return ( ( count( $rdvs ) % $statutrdvtyperdv['StatutrdvTyperdv']['nbabsenceavantpassageep'] ) == 0 && empty( $dossierep ) );
		}


		/**
		*
		*/

		public function beforeSave( $options = array ( ) ) {
			$return = parent::beforeSave( $options );

			if ( Configure::read( 'Cg.departement' ) == 58 ) {
				if ( !isset( $this->data['Rendezvous']['id'] ) || empty( $this->data['Rendezvous']['id'] ) ) {
					$dossierep = $this->Personne->Dossierep->find(
						'first',
						array(
							'conditions' => array(
								'Dossierep.personne_id' => $this->data['Rendezvous']['personne_id'],
								'Dossierep.themeep' => 'sanctionsrendezvouseps58',
								'Dossierep.id NOT IN ( '.
									$this->Personne->Dossierep->Passagecommissionep->sq(
										array(
											'fields' => array(
												'passagescommissionseps.dossierep_id'
											),
											'alias' => 'passagescommissionseps',
											'conditions' => array(
												'passagescommissionseps.etatdossierep' => array ( 'traite', 'annule' )
											)
										)
									)
								.' )'
							),
							'contain' => array(
								'Sanctionrendezvousep58' => array(
									'Rendezvous'
								)
							)
						)
					);

					if ( isset( $dossierep['Sanctionrendezvousep58']['Rendezvous']['typerdv_id'] ) ) {
						$this->invalidate( 'typerdv_id', 'Un passage en EP est déjà en cours pour cette objet, vous ne pouvez créer un nouveau rendez-vous pour ce même objet.' );
						$return = false;
					}
				}
				else {
					if ( !$this->Statutrdv->provoquePassageEp( $this->data['Rendezvous']['statutrdv_id'] ) || !$this->passageEp( $this->data['Rendezvous']['personne_id'], $this->data['Rendezvous']['typerdv_id'], $this->data['Rendezvous']['statutrdv_id'] ) ) {
						$dossierep = $this->Sanctionrendezvousep58->find(
							'first',
							array(
								'fields' => array(
									'Sanctionrendezvousep58.id',
									'Sanctionrendezvousep58.dossierep_id'
								),
								'conditions' => array(
									'Sanctionrendezvousep58.rendezvous_id' => $this->data['Rendezvous']['id']
								),
								'contain' => false
							)
						);

						if ( !empty( $dossierep ) ) {
							$this->Sanctionrendezvousep58->delete( $dossierep['Sanctionrendezvousep58']['id'] );
							$this->Sanctionrendezvousep58->Dossierep->delete( $dossierep['Sanctionrendezvousep58']['dossierep_id'] );
						}
					}
				}
			}

			return $return;
		}

		/**
		* Règle de validation sur le statut du RDV uniquement si pas CG58
		*/

		public function beforeValidate( $options = array() ) {
			$return = parent::beforeValidate( $options );

			if( Configure::read( 'Cg.departement' ) != 58 ){
				$rule = array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire',
				);

				$this->validate['statutrdv_id'][] = $rule;
			}


			return $return;
		}

	}
?>