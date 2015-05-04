<?php
	/**
	 * Code source de la classe Rendezvous.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'DefaultUtility', 'Default.Utility' );

	/**
	 * La classe Rendezvous ...
	 *
	 * @package app.Model
	 */
	class Rendezvous extends AppModel
	{
		public $name = 'Rendezvous';

		public $displayField = 'libelle';

		public $actsAs = array(
			'Allocatairelie',
			'Formattable' => array(
				'suffix' => array( 'referent_id', 'permanence_id' )
			),
			'Enumerable' => array(
				'fields' => array(
					'haspiecejointe',
					'isadomicile'
				)
			),
			'Gedooo.Gedooo'
		);

		public $validate = array(
			'structurereferente_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				'checkThematiqueAnnuelleParStructurereferente' => array(
					'rule' => 'checkThematiqueAnnuelleParStructurereferente',
				),
			),
			'typerdv_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
			),
			'daterdv' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				'date' => array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
			),
			'heurerdv' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
			),
			'rang' => array(
				'integer' => array(
					'rule' => 'integer',
					'message' => 'Veuillez entrer un nombre entier',
				),
			),
		);

		/**
		 * Règle de validation: on vérifie qu'il n'existe pas d'autre RDV pour
		 * un allocataire donné dans une structure référente donnée dans l'année
		 * pour une des thématiques annuelles.
		 *
		 * @see Configure::write Rendezvous.useThematique
		 * @see Configure::write Rendezvous.thematiqueAnnuelleParStructurereferente
		 *
		 * @param array $check
		 * @return boolean
		 */
		public function checkThematiqueAnnuelleParStructurereferente( $check ) {
			if( !is_array( $check ) ) {
				return false;
			}

			if( !Configure::read( 'Rendezvous.useThematique' ) ) {
				return true;
			}

			$thematiquesAnnuelles = (array)Configure::read( 'Rendezvous.thematiqueAnnuelleParStructurereferente' );
			if( empty( $thematiquesAnnuelles ) ) {
				return true;
			}

			$check = array_values( $check );
			$structurereferente_id = ( isset( $check[0] ) ? $check[0] : null );

			$id = Hash::get( $this->data, "{$this->alias}.{$this->primaryKey}" );
			$personne_id = Hash::get( $this->data, "{$this->alias}.personne_id" );
			$daterdv = Hash::get( $this->data, "{$this->alias}.daterdv" );
			$values = (array)Hash::extract( $this->data, 'Thematiquerdv.Thematiquerdv' );

			if( empty( $values ) || empty( $daterdv ) || empty( $personne_id ) || empty( $structurereferente_id ) ) {
				return true;
			}

			$intersect = array_intersect( $thematiquesAnnuelles, $values );
			if( empty( $intersect ) ) {
				return true;
			}

			$year = date( 'Y', strtotime( $daterdv ) );
			$sqThematiqueRdv = $this->RendezvousThematiquerdv->sq(
				array(
					'alias' => 'rendezvous_thematiquesrdvs',
					'fields' => array( 'rendezvous_thematiquesrdvs.rendezvous_id' ),
					'contain' => false,
					'conditions' => array(
						'rendezvous_thematiquesrdvs.rendezvous_id = Rendezvous.id',
						'rendezvous_thematiquesrdvs.thematiquerdv_id' => $intersect,
					),
				)
			);

			$querydata = array(
				'fields' => array( 'RendezvousThematiquerdv.thematiquerdv_id' ),
				'conditions' => array(
					"{$this->alias}.personne_id" => $personne_id,
					"{$this->alias}.structurereferente_id" => $structurereferente_id,
					"{$this->alias}.daterdv BETWEEN '{$year}-01-01' AND '{$year}-12-31'",
					"{$this->alias}.{$this->primaryKey} IN ( {$sqThematiqueRdv} )",
					// Qui est dans un état permettant ou ayant permis d'ajouter un QD1
					"{$this->alias}.statutrdv_id" => (array)Configure::read( 'Rendezvous.checkThematiqueAnnuelleParStructurereferente.statutrdv_id' ),
				),
				'contain' => false,
				'joins' => array(
					$this->join( 'RendezvousThematiquerdv', array( 'type' => 'INNER' ) )
				)
			);

			if( !empty( $id ) ) {
				$querydata['conditions']["{$this->alias}.{$this->primaryKey} <>"] = $id;
			}

			$found = $this->find( 'all', $querydata );

			if( $found ) {
				$thematiques = $this->Thematiquerdv->find(
					'list',
					array(
						'conditions' => array(
							'Thematiquerdv.id' => (array)Hash::extract( $found, '{n}.RendezvousThematiquerdv.thematiquerdv_id' )
						),
					'contain' => false
					)
				);

				if( count( $thematiques ) == 1 ) {
					$message = 'Il existe déjà un rendez-vous pour la thématique « %s » dans cette structure référente pour l\'année %d.';
				}
				else {
					$message = 'Il existe déjà au moins un rendez-vous pour les thématiques « %s » dans cette structure référente pour l\'année %d.';
				}

				return sprintf( $message, implode( ' », « ', $thematiques ), $year );
			}

			return true;
		}

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

		public $hasOne = array(
			'Propoorientsocialecov58' => array(
				'className' => 'Propoorientsocialecov58',
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

		public $hasMany = array(
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
		 * Associations "Has and belongs to many".
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
			'Thematiquerdv' => array(
				'className' => 'Thematiquerdv',
				'joinTable' => 'rendezvous_thematiquesrdvs',
				'foreignKey' => 'rendezvous_id',
				'associationForeignKey' => 'thematiquerdv_id',
				'unique' => true,
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'finderQuery' => null,
				'deleteQuery' => null,
				'insertQuery' => null,
				'with' => 'RendezvousThematiquerdv'
			),
		);

		/**
		 * Retourne un booléen selon si un dossier d'EP doit ou non
		 * être créé pour la personne dont l'id est passé en paramètre
		 *
		 * @param array $data
		 * @return boolean
		 */
		public function passageEp( $data ) {
			// 1. Pour le type et le statut du RDV que l'on enregistre, doit-on créer un passage en commission ?
			$statutrdvtyperdv = $this->Typerdv->StatutrdvTyperdv->find(
				'first',
				array(
					'conditions' => array(
						'StatutrdvTyperdv.typerdv_id' => $data['Rendezvous']['typerdv_id'],
						'StatutrdvTyperdv.statutrdv_id' => $data['Rendezvous']['statutrdv_id']
					),
					'contain' => false
				)
			);

			if( empty( $statutrdvtyperdv ) ) {
				return false;
			}

			// 2. Existe-t'il suffisamment de rendez-vous précédents des mêmes types et statuts ?
			$nbRdvPcd = ( $statutrdvtyperdv['StatutrdvTyperdv']['nbabsenceavantpassagecommission'] - 1 );

			if( $nbRdvPcd > 0 ) {
				$daterdv = $data['Rendezvous']['daterdv'];
				if( is_array( $daterdv ) ) {
					$daterdv = date_cakephp_to_sql( $daterdv );
				}

				$heurerdv = $data['Rendezvous']['heurerdv'];
				if( is_array( $heurerdv ) ) {
					$heurerdv = time_cakephp_to_sql( $heurerdv );
				}

				$query = array(
					'fields' => array(
						'Rendezvous.typerdv_id',
						'Rendezvous.statutrdv_id'
					),
					'contain' => false,
					'conditions' => array(
						'Rendezvous.personne_id' => Hash::get( $data, 'Rendezvous.personne_id' )
					),
					'order' => array(
						'Rendezvous.daterdv' => 'DESC',
						'Rendezvous.heurerdv' => 'DESC',
						'Rendezvous.id' => 'DESC'
					),
					'limit' => $nbRdvPcd
				);

				// Ici, le compteur à revoir...
				$id = Hash::get( $data, "{$this->alias}.{$this->primaryKey}" );
				$action = ( empty( $id ) ? 'add' : 'edit' );

				if( $action === 'add' ) {
					$query['conditions']["( Rendezvous.daterdv || ' ' || Rendezvous.heurerdv )::TIMESTAMP <"] = "{$daterdv} {$heurerdv}";
				}
				else {
					$query['conditions'][] = array(
						'OR' => array(
							"( Rendezvous.daterdv || ' ' || Rendezvous.heurerdv )::TIMESTAMP <" => "{$daterdv} {$heurerdv}",
							array(
								"( Rendezvous.daterdv || ' ' || Rendezvous.heurerdv )::TIMESTAMP" => "{$daterdv} {$heurerdv}",
								'Rendezvous.id <' => $id
							)
						)
					);
				}

				$rdvs = $this->find( 'all', $query );

				$creation = ( count($rdvs) == $nbRdvPcd );
				foreach( $rdvs as $rdv ) {
					if(
						( $rdv['Rendezvous']['typerdv_id'] != $data['Rendezvous']['typerdv_id'] )
						|| ( $rdv['Rendezvous']['statutrdv_id'] != $data['Rendezvous']['statutrdv_id'] )
					) {
						$creation = false;
					}
				}

				if( !$creation ) {
					return false;
				}
			}

			// 3. Existe-t'il déjà un passage en commission en cours pour la même raison ?
			if( $statutrdvtyperdv['StatutrdvTyperdv']['typecommission'] == 'ep' ) {
				$dossiercommission = $this->Personne->Dossierep->find(
					'first',
					array(
						'conditions' => array(
							'Dossierep.personne_id' => $data['Rendezvous']['personne_id'],
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
										'Rendezvous.typerdv_id' => $data['Rendezvous']['typerdv_id']
									)
								)
							)
						)
					)
				);
			}
			else {
				$dossiercommission = $this->Personne->Dossiercov58->find(
					'first',
					array(
						'conditions' => array(
							'Dossiercov58.personne_id' => $data['Rendezvous']['personne_id'],
							'Dossiercov58.themecov58' => 'proposorientssocialescovs58',
							'Dossiercov58.id NOT IN ( '.
								$this->Personne->Dossiercov58->Passagecov58->sq(
									array(
										'alias' => 'passagescovs58',
										'fields' => array(
											'passagescovs58.dossiercov58_id'
										),
										'conditions' => array(
											'passagescovs58.etatdossiercov' => array ( 'traite', 'annule' )
										)
									)
								)
							.' )'
						)
					)
				);
			}

			return empty( $dossiercommission );
		}


		/**
		* FIXME: la même avec dossier COV
		*/
		public function beforeSave( $options = array ( ) ) {
			$return = parent::beforeSave( $options );

			if ( Configure::read( 'Cg.departement' ) == 58 ) {
				// Pour les nouveaux enregistrements,
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
					if ( !$this->Statutrdv->provoquePassageCommission( $this->data['Rendezvous']['statutrdv_id'] ) || !$this->passageEp( $this->data ) ) {
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
		 * Règle de validation sur le statut du RDV uniquement si pas CG58.
		 *
		 * @param array $options
		 * @return boolean
		 */
		public function beforeValidate( $options = array() ) {
			$return = parent::beforeValidate( $options );

			if( Configure::read( 'Cg.departement' ) != 58 ){
				$rule = array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire',
				);

				$this->validate['statutrdv_id']['notEmpty'] = $rule;
			}

			// -----------------------------------------------------------------
			// FIXME: ailleurs + la même année
			// TODO: configuration si on utilise la fonctionnalité ?
			$CheckModelName = 'Thematiquerdv';
			$masterField = 'typerdv_id';
			$slaveField = 'statutrdv_id';
			// -----------------------------------------------------------------
			$checked = Hash::get( $this->data, "{$CheckModelName}.{$CheckModelName}" );
			if( !empty( $checked ) ) {
				$querydata = array(
					'conditions' => array(
						"{$CheckModelName}.id" => $checked,
						"{$CheckModelName}.{$masterField}" => Hash::get( $this->data, "{$this->alias}.{$masterField}" ),
						"{$CheckModelName}.{$slaveField} IS NOT NULL",
						// TODO: pas la condition ci-dessous (permettra de faire égal ou différent) ?
						"{$CheckModelName}.{$slaveField}" => Hash::get( $this->data, "{$this->alias}.{$slaveField}" ),
					)
				);
				$checks = $this->{$CheckModelName}->find( 'all', $querydata );

				if( !empty( $checks ) ) {
					$messages = array();

					foreach( $checks as $check ) {
						$Model = ClassRegistry::init( $check[$CheckModelName]['linkedmodel'] );

						// TODO: les conditions en paramétrage de la règle de validation
						$conditions = array(
							"{$Model->alias}.personne_id" => "#Rendezvous.personne_id#",
							"DATE_TRUNC( 'YEAR', {$Model->alias}.date_validation ) = DATE_TRUNC( 'YEAR', TIMESTAMP '#Rendezvous.daterdv#' )",
						);
						$conditions = DefaultUtility::evaluate( $this->data, $conditions );

						$querydata = array(
							'fields' => array(
								"{$Model->alias}.{$Model->primaryKey}"
							),
							'contain' => false,
							'conditions' => $conditions,
						);
						$found = $Model->find( 'first', $querydata );

						if( empty( $found ) ) {
							$messages[] = __d( Inflector::underscore( $this->alias ), "{$Model->alias}::missing" );
						}
					}

					if( !empty( $messages ) ) {
						// TODO: un ensemble de messages dans la vue ?
						$this->invalidate( $slaveField, $messages[0] );
						$return = false;
					}
				}
			}


			return $return;
		}

		/**
		 * Retourne le PDF d'un rendez-vous.
		 *
		 * @param integer $id L'id du rendez-vous pour lequel générer l'impression
		 * @param $user_id L'id de l'utilisateur qui génère l'impression.
		 * @return string
		 */
		public function getDefaultPdf( $id, $user_id ) {
			$rdv = $this->find(
				'first',
				array(
					'fields' => array_merge(
						$this->fields(),
						$this->Permanence->fields(),
						$this->Personne->fields(),
						$this->Referent->fields(),
						$this->Statutrdv->fields(),
						$this->Structurereferente->fields(),
						$this->Typerdv->fields(),
						$this->Personne->Foyer->fields(),
						$this->Personne->Foyer->Dossier->fields(),
						$this->Personne->Foyer->Adressefoyer->Adresse->fields()
					),
					'joins' => array(
						$this->join( 'Permanence', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Statutrdv', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
						$this->join( 'Typerdv', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					),
					'conditions' => array(
						'Rendezvous.id' => $id,
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					'contain' => false
				)
			);

			$User = ClassRegistry::init( 'User' );
			$user = $User->find(
				'first',
				array(
					'fields' => array_merge(
						$User->fields(),
						$User->Serviceinstructeur->fields()
					),
					'joins' => array(
						$User->join( 'Serviceinstructeur', array( 'type' => 'INNER' ) )
					),
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => false
				)
			);
			$rdv = Set::merge( $rdv, $user );

			$rdv['Rendezvous']['heurerdv'] = date( "H:i", strtotime( $rdv['Rendezvous']['heurerdv'] ) );

			// Utilisation des thématiques de RDV ?
			$rdv = $this->containThematique( $rdv );
			$thematiquesrdvs = $rdv['Thematiquerdv'];
			unset( $rdv['Thematiquerdv'] );

			if( !empty( $thematiquesrdvs ) ) {
				foreach( $thematiquesrdvs as $key => $values ) {
					$thematiquesrdvs[$key] = array( 'Thematiquerdv' => $values );
				}
			}

			$rdv = array(
				$rdv,
				'thematiquesrdvs' => $thematiquesrdvs
			);

			$Option = ClassRegistry::init( 'Option' );
			$options = array(
				'Permanence' => array(
					'typevoie' => $Option->typevoie()
				),
				'Personne' => array(
					'qual' => $Option->qual()
				),
				'Referent' => array(
					'qual' => $Option->qual()
				),
				'Structurereferente' => array(
					'type_voie' => $Option->typevoie()
				),
				'Type' => array(
					'voie' => $Option->typevoie()
				),
				'type' => array(
					'voie' => $Option->typevoie()
				),
			);

			return $this->ged(
				$rdv,
				"RDV/{$rdv[0]['Typerdv']['modelenotifrdv']}.odt",
				true,
				$options
			);
		}

		/**
		 * FIXME: devrait remplacer la méthode passageEp ?
		 *
		 * @param type $data
		 * @return type
		 */
		public function provoquePassageCommission( $data ) {
			return (
				Configure::read( 'Cg.departement' ) == 58
				&& !empty( $data['Rendezvous']['statutrdv_id'] )
				&& $this->Statutrdv->provoquePassageCommission( $data['Rendezvous']['statutrdv_id'] )
				&& $this->passageEp( $data )
			);
		}

		/**
		 * FIXME: le nombre vient du nouveau champ
		 *
		 * @param type $data
		 * @param type $user_id
		 * @return type
		 */
		public function creePassageCommission( $data, $user_id ) {
			$statutrdv_typerdv = $this->Statutrdv->StatutrdvTyperdv->find(
				'first',
				array(
					'conditions' => array(
						'StatutrdvTyperdv.statutrdv_id' => $data['Rendezvous']['statutrdv_id'],
						'StatutrdvTyperdv.typerdv_id' => $data['Rendezvous']['typerdv_id'],
					),
					'contain' => false
				)
			);

			if( $statutrdv_typerdv['StatutrdvTyperdv']['typecommission'] == 'ep' ) {
				$dossierep = array(
					'Dossierep' => array(
						'personne_id' => $data['Rendezvous']['personne_id'],
						'themeep' => 'sanctionsrendezvouseps58'
					)
				);
				$success = $this->Personne->Dossierep->save( $dossierep );

				$sanctionrendezvousep58 = array(
					'Sanctionrendezvousep58' => array(
						'dossierep_id' => $this->Personne->Dossierep->id,
						'rendezvous_id' => $this->id
					)
				);

				$success = $this->Personne->Dossierep->Sanctionrendezvousep58->save( $sanctionrendezvousep58 ) && $success;
			}
			else {
				$themecov58_id = $this->Propoorientsocialecov58->Dossiercov58->Themecov58->field( 'id', array( 'name' => 'proposorientssocialescovs58' ) );
				$dossiercov58 = array(
					'Dossiercov58' => array(
						'personne_id' => $data['Rendezvous']['personne_id'],
						'themecov58' => 'proposorientssocialescovs58',
						'themecov58_id' => $themecov58_id,
					)
				);
				$success = $this->Personne->Dossiercov58->save( $dossiercov58 );

				$propoorientsocialecov58 = array(
					'Propoorientsocialecov58' => array(
						'dossiercov58_id' => $this->Propoorientsocialecov58->Dossiercov58->id,
						'rendezvous_id' => $this->id,
						'user_id' => $user_id
					)
				);

				$success = $this->Propoorientsocialecov58->save( $propoorientsocialecov58 ) && $success;
			}

			return $success;
		}


		/**
		 * Retourne une sous-requête permettant de connaître le dernier rendez-vous pour un
		 * allocataire donné.
		 *
		 * @param string $field Le champ Personne.id sur lequel faire la sous-requête
		 * @return string
		 */
		public function sqDernier( $field ) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$table = $dbo->fullTableName( $this, false, false );
			return "SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.personne_id = ".$field."
					ORDER BY {$table}.daterdv DESC
					LIMIT 1";
		}

		/**
		 * Retourne la liste des rendez-vous d'une personne, ordonnés par date
		 * et heure (du plus récent au plus ancien), et libellé de l'objet,
		 * formattés comme suit:
		 * <pre>
		 * array(
		 *	<Id du RDV> => "<Objet du RDV> du <Date du RDV> à <Heure du RDV>"
		 * )
		 * </pre>
		 *
		 * @param integer $personne_id L'id de la personne
		 * @return array
		 */
		public function findListPersonneId( $personne_id ) {
			$rendezvous = array();

			$results = $this->find(
				'all',
				array(
					'fields' => array(
						'Rendezvous.id',
						'Rendezvous.daterdv',
						'Rendezvous.heurerdv',
						'Typerdv.libelle',
					),
					'contain' => false,
					'conditions' => array(
						'Rendezvous.personne_id' => $personne_id
					),
					'joins' => array(
						$this->join( 'Typerdv', array( 'type' => 'INNER' ) )
					),
					'order' => array(
						'Rendezvous.daterdv DESC',
						'Rendezvous.heurerdv DESC',
						'Typerdv.libelle ASC',
					)
				)
			);

			if( !empty( $results ) ) {
				foreach( $results as $result ) {
					$rendezvous[$result['Rendezvous']['id']] = sprintf(
						'%s du %s à %s',
						$result['Typerdv']['libelle'],
						date( 'd/m/Y', strtotime( $result['Rendezvous']['daterdv'] ) ),
						date( 'H:i:s', strtotime( $result['Rendezvous']['heurerdv'] ) )
					);
				}
			}

			return $rendezvous;
		}

		/**
		 *
		 * TODO: si on utilise les thematiquesrdv seulement
		 *
		 * @param array $results
		 * @param string $thematiqueAlias -> un array plus complet
		 * @return array
		 */
		public function containThematique( array $results, $thematiqueAlias = 'Thematiquerdv' ) {
			if( !empty( $results ) ) {
				// Une liste ou un seul enregistrement ?
				$find = 'all';
				if( !is_int( key( $results ) ) ) {
					$find = 'first';
					reset( $results );
					$results = array( $results );
				}
				else {
					reset( $results );
				}

				foreach( $results as $key => $result ) {
					$thematiquesrdvs = $this->{$thematiqueAlias}->find(
						'all',
						array(
							'fields' => array(
								"{$thematiqueAlias}.id",
								"{$thematiqueAlias}.name",
							),
							'contain' => false,
							'joins' => array(
								$this->{$thematiqueAlias}->join( 'RendezvousThematiquerdv', array( 'type' => 'INNER' ) )
							),
							'conditions' => array(
								'RendezvousThematiquerdv.rendezvous_id' => $result[$this->alias]['id']
							)
						)
					);
					$results[$key][$thematiqueAlias] = (array)Hash::extract( $thematiquesrdvs, "{n}.{$thematiqueAlias}" );
				}

				// Une liste ou un seul enregistrement ?
				if( $find != 'all' ) {
					$results = $results[0];
				}
			}

			return $results;
		}

		/**
		 * Ajoute des conditions sur les thématiques de RDV.
		 *
		 * A utiliser dans les cohortes et moteur de recherche.
		 *
		 * Exemple:
		 * <pre>$this->conditionsThematique(
		 *	array(),
		 *	array(
		 *		'Rendezvous' => array(
		 *			'thematiquerdv_id' => array(
		 *				0 => 3,
		 *				1 => 5
		 *			)
		 *		)
		 *	),
		 *	'Rendezvous.thematiquerdv_id'
		 * );</pre>
		 * retournera
		 * <pre>array( Rendezvous.id IN ( SELECT "rendezvous_thematiquesrdvs"."rendezvous_id" AS rendezvous_thematiquesrdvs__rendezvous_id FROM thematiquesrdvs AS thematiquesrdvs INNER JOIN "public"."rendezvous_thematiquesrdvs" AS rendezvous_thematiquesrdvs ON ("rendezvous_thematiquesrdvs"."rendezvous_id" = "Rendezvous"."id") WHERE "rendezvous_thematiquesrdvs"."rendezvous_id" = "Rendezvous"."id" AND "rendezvous_thematiquesrdvs"."thematiquerdv_id" IN ('3', '5') )  )</pre>
		 *
		 * @param array $conditions Les conditions déjà existantes
		 * @param array $search Les critères renvoyés par le formulaire de recherche
		 * @param mixed $paths Le chemin (ou les chemins) sur lesquels on cherche à appliquer ces filtres.
		 * @return array
		 */
		public function conditionsThematique( $conditions, $search, $paths, array $replacements = array() ) {
			$paths = (array)$paths;
			$replacements += array(
				'RendezvousThematiquerdv' => 'rendezvous_thematiquesrdvs',
				'Thematiquerdv' => 'thematiquesrdvs'
			);

			foreach( $paths as $path ) {
				$thematiquerdv_id = Hash::get( $search, $path );
				if( !empty( $thematiquerdv_id ) ) {
					$qd = array(
						'alias' => 'Thematiquerdv',
						'fields' => array( 'RendezvousThematiquerdv.rendezvous_id' ),
						'contain' => false,
						'joins' => array(
							$this->join( 'RendezvousThematiquerdv', array( 'type' => 'INNER' ) )
						),
						'conditions' => array(
							'RendezvousThematiquerdv.rendezvous_id = Rendezvous.id',
							'RendezvousThematiquerdv.thematiquerdv_id' => $thematiquerdv_id,
						)

					);
					$qd = array_words_replace( $qd, $replacements );

					$sq = $this->Thematiquerdv->sq( $qd );
					$conditions[] = "Rendezvous.id IN ( {$sq} )";
				}
			}

			return $conditions;
		}
	}
?>