<?php
	/**
	* Bilan de parcours pour le conseil général du département 66.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.model
	*/

	class Bilanparcours66 extends AppModel
	{
		public $name = 'Bilanparcours66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id',
					'referent_id',
					'nvstructurereferente_id'
				)
			),
			'Enumerable' => array(
				'fields' => array(
					'presenceallocataire',
					'saisineepparcours',
					'maintienorientation',
					'changereferent',
					'accordprojet',
					'maintienorientsansep',
					'choixparcours',
					'changementrefsansep',
					'maintienorientparcours',
					'changementrefparcours',
					'reorientation',
					'examenaudition',
					'examenauditionpe',
					'maintienorientavisep',
					'changementrefeplocale',
					'reorientationeplocale',
					'typeeplocale',
					'accompagnement',
					'typeformulaire',
					'saisineepl',
					'sitfam',
					'proposition',
					'positionbilan',
					'haspiecejointe'
				)
			),
			'Gedooo.Gedooo',
			'StorablePdf' => array(
				'active' => array( 66 )
			),
			'ModelesodtConditionnables' => array(
				66 => array(
					'Bilanparcours/bilanparcours.odt',
					'Bilanparcours/courrierinformationavantep.odt',
				)
			)
		);

		public $validate = array(
			'proposition' => array(
				array(
					'rule' => 'alphanumeric',
					'message' => 'La proposition du référent est obligatoire',
					'allowEmpty' => false,
					'required' => true,
					'on' => 'create'
				)
			),
			'datebilan' => array(
				array(
					'rule' => array('datePassee'),
					'message' => 'Merci de choisir une date antérieure à la date du jour',
					'on' => 'create'
				),
				array(
					'rule' => 'date',
					'message' => 'Merci de rentrer une date valide',
					'allowEmpty' => false,
					'required' => true,
					'on' => 'create'
				)
			),
			'bilanparcoursinsertion' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'motifep', true, array( '0' ) ),
					'message' => 'Veuillez saisir une information',
				)
			),
			'motifep' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'bilanparcoursinsertion', true, array( '0' ) ),
					'message' => 'Veuillez saisir une information',
				)
			),
			'sansep_typeorientprincipale_id' => array(
				array(
					'rule' => array( 'notEmptyIf', 'proposition', true, array( 'traitement' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'avecep_typeorientprincipale_id' => array(
//				array(
//					'rule' => array( 'notEmptyIf', 'typeformulaire', true, array( 'pe' ) ),
//					'message' => 'Champ obligatoire',
//				),
				array(
					'rule' => array( 'notEmptyIf', 'proposition', true, array( 'parcours', 'parcourspe' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'nvtypeorient_id' => array(
//				array(
//					'rule' => array( 'notEmptyIf', 'typeformulaire', true, array( 'pe' ) ),
//					'message' => 'Champ obligatoire',
//				),
				array(
					'rule' => array( 'notEmptyIf', 'proposition', true, array( 'traitement', 'parcours', 'parcourspe' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'nvstructurereferente_id' => array(
//				array(
//					'rule' => array( 'notEmptyIf', 'typeformulaire', true, array( 'pe' ) ),
//					'message' => 'Champ obligatoire',
//				),
				array(
					'rule' => array( 'notEmptyIf', 'proposition', true, array( 'traitement', 'parcours', 'parcourspe' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'choixparcours' => array(
//				array(
//					'rule' => array( 'notEmptyIf', 'typeformulaire', true, array( 'pe' ) ),
//					'message' => 'Champ obligatoire',
//				),
				array(
					'rule' => array( 'notEmptyIf', 'proposition', true, array( 'parcours', 'parcourspe' ) ),
					'message' => 'Champ obligatoire',
				)
			),
//			'duree_engag' => array(
//				array(
//					'rule' => array( 'notEmptyIf', 'changementrefsansep', true, array( 'N' ) ),
//					'message' => 'Champ obligatoire',
//				),
//				array(
//					'rule' => array( 'notEmptyIf', 'changementrefavecep', true, array( 'N' ) ),
//					'message' => 'Champ obligatoire',
//				)
//			),
//			'ddreconductoncontrat' => array(
//				array(
//					'rule' => array( 'notEmptyIf', 'changementrefsansep', true, array( 'N' ) ),
//					'message' => 'Champ obligatoire',
//				),
//				array(
//					'rule' => array( 'notEmptyIf', 'changementrefavecep', true, array( 'N' ) ),
//					'message' => 'Champ obligatoire',
//				)
//			),
//			'dfreconductoncontrat' => array(
//				array(
//					'rule' => array( 'notEmptyIf', 'changementrefsansep', true, array( 'N' ) ),
//					'message' => 'Champ obligatoire',
//				),
//				array(
//					'rule' => array( 'notEmptyIf', 'changementrefavecep', true, array( 'N' ) ),
//					'message' => 'Champ obligatoire',
//				)
//			)
		);

		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'orientstruct_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
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
			),
			'Typeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'typeorient_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasOne = array(
			'Saisinebilanparcoursep66' => array(
				'className' => 'Saisinebilanparcoursep66',
				'foreignKey' => 'bilanparcours66_id',
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
			'Defautinsertionep66' => array(
				'className' => 'Defautinsertionep66',
				'foreignKey' => 'bilanparcours66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Bilanparcours66\'',
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
			'Dossierpcg66' => array(
				'className' => 'Dossierpcg66',
				'foreignKey' => 'bilanparcours66_id',
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

		/**
		*
		*/

		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			$this->data[$this->alias]['positionbilan'] = $this->_calculPositionBilan( $this->data );

			return $return;
		}

		/**
		*
		*/

		protected function _calculPositionBilan( $data ){
			// Si on nous donne la position du bilan, on ne la recalcule pas
			if( isset( $data[$this->alias]['positionbilan'] ) && !empty( $data[$this->alias]['positionbilan'] ) ) {
				return $data[$this->alias]['positionbilan'];
			}

			$traitement = Set::classicExtract( $data, 'Bilanparcours66.proposition' );
			$positionbilan = null;
			// 'eplaudit', 'eplparc', 'attcga', 'attct', 'ajourne', 'annule'

			if ( ( $traitement == 'audition' || $traitement == 'auditionpe' ) && empty( $saisineep ) )
				$positionbilan = 'eplaudit';
			elseif ( ( $traitement == 'parcours' || $traitement == 'parcourspe' ) && empty( $saisineep ) )
				$positionbilan = 'eplparc';
			return $positionbilan;
		}

		/**
		* Récupère une liste des ids des bilans de parcours à partir d'une liste d'ids
		* d'entrées de passages en commissions EP.
		*/

		protected function _bilansparcours66IdsDepuisPassagescommissionsepsIds( $modeleThematique, $passagescommissionseps_ids ) {
			return $this->{$modeleThematique}->find(
				'list',
				array(
					'fields' => array( "{$modeleThematique}.id", "{$modeleThematique}.bilanparcours66_id" ),
					'conditions' => array(
						"{$modeleThematique}.dossierep_id IN ("
							.$this->{$modeleThematique}->Dossierep->sq(
								array(
									'alias' => 'dossierseps',
									'fields' => array( 'dossierseps.id' ),
									'conditions' => array(
										'dossierseps.id IN ('
											.$this->{$modeleThematique}->Dossierep->Passagecommissionep->sq(
												array(
													'alias' => 'passagescommissionseps',
													'fields' => array( 'passagescommissionseps.dossierep_id' ),
													'conditions' => array(
														'passagescommissionseps.id' => $passagescommissionseps_ids
													),
													'contain' => false
												)
											)
										.')'
									),
									'contain' => false
								)
							)
						.')'
					),
					'contain' => false
				)
			);
		}

		/**
		* Mise à jour de la position du bilan de parcours à partir de la cohorte de
		* décisions (niveau EP ou niveau CG) des EPs.
		*/

		public function updatePositionBilanDecisionsEp( $modeleThematique, $datas, $niveauDecision, $passagescommissionseps_ids ) {
			$success = true;

			// Niveau EP
			if( $niveauDecision == 'ep' ) {
				$bilansparcours66_ids = $this->_bilansparcours66IdsDepuisPassagescommissionsepsIds( $modeleThematique, $passagescommissionseps_ids );

				$position = ( ( $modeleThematique == 'Defautinsertionep66' ) ? 'attcga' : 'attct' );
				$success = $this->updateAll(
					array( 'Bilanparcours66.positionbilan' => "'{$position}'" ),
					array( '"Bilanparcours66"."id"' => array_values( $bilansparcours66_ids ) )
				) && $success;
			}
			// Niveau CG
			else {
				$passagescommissionseps_ids_annule = array();
				$passagescommissionseps_ids_reporte = array();
				$passagescommissionseps_ids_autre = array();

				$modeleDecisionName = 'Decision'.Inflector::underscore( $modeleThematique );

				foreach( $datas as $themeTmpData ) {
					switch( $themeTmpData[$modeleDecisionName]['decision'] ) {
						case 'annule':
							$passagescommissionseps_ids_annule[] = $themeTmpData[$modeleDecisionName]['passagecommissionep_id'];
							break;
						case 'reporte':
							$passagescommissionseps_ids_reporte[] = $themeTmpData[$modeleDecisionName]['passagecommissionep_id'];
							break;
						default:
							$passagescommissionseps_ids_autre[] = $themeTmpData[$modeleDecisionName]['passagecommissionep_id'];
					}
				}

				if( !empty( $passagescommissionseps_ids_annule ) ) {
					$bilansparcours66_ids = $this->_bilansparcours66IdsDepuisPassagescommissionsepsIds( $modeleThematique, $passagescommissionseps_ids_annule );
					$success = $this->updateAll(
						array( 'Bilanparcours66.positionbilan' => '\'annule\'' ),
						array( '"Bilanparcours66"."id"' => array_values( $bilansparcours66_ids ) )
					) && $success;
				}

				if( !empty( $passagescommissionseps_ids_reporte ) ) {
					$bilansparcours66_ids = $this->_bilansparcours66IdsDepuisPassagescommissionsepsIds( $modeleThematique, $passagescommissionseps_ids_reporte );
					$success = $this->updateAll(
						array( 'Bilanparcours66.positionbilan' => '\'ajourne\'' ),
						array( '"Bilanparcours66"."id"' => array_values( $bilansparcours66_ids ) )
					) && $success;
				}

				if( !empty( $passagescommissionseps_ids_autre ) ) {
					$bilansparcours66_ids = $this->_bilansparcours66IdsDepuisPassagescommissionsepsIds( $modeleThematique, $passagescommissionseps_ids_autre );
					$success = $this->updateAll(
						array( 'Bilanparcours66.positionbilan' => '\'traite\'' ),
						array( '"Bilanparcours66"."id"' => array_values( $bilansparcours66_ids ) )
					) && $success;
				}
			}

			return $success;
		}

		/**
		* Sauvegarde du bilan de parcours d'un allocataire.
		*
		* Le bilan de parcours entraîne:
		*	- pour le thème réorientation/saisinesbilansparcourseps66
		*		* soit un maintien de l'orientation, sans passage en EP
		*		* soit une saisine de l'EP locale, commission parcours
		*
		* @param array $data Les données du bilan à sauvegarder.
		* @return boolean True en cas de succès, false sinon.
		* @access public
		*/

		public function sauvegardeBilan( $data ) {

			if ( isset( $data['Pe']['Bilanparcours66']['id'] ) ) {
				$id = $data['Pe']['Bilanparcours66']['id'];
				unset( $data['Pe']['Bilanparcours66']['id'] );
			}
			if ( isset( $data['Pe']['Bilanparcours66'] ) && !empty( $data['Pe']['Bilanparcours66']['datebilan'] ) ) {
				$datape = $data['Pe'];
				unset($data['Pe']);
				$data = Set::merge( $data, $datape );

				if ( isset( $id ) ) {
					$data['Bilanparcours66']['id'] = $id;
				}
			}

			$data[$this->alias]['saisineepparcours'] = ( @$data[$this->alias]['proposition'] == 'parcours' );
// debug($data);
			// Recondution du contrat
			if( isset( $data[$this->alias]['proposition'] ) && $data[$this->alias]['proposition'] == 'traitement' ) {
				$cleanedData = $data;
				unset( $cleanedData['Saisinebilanparcoursep66'] );
				return $this->maintien( $cleanedData );
			}
			// Saisine de l'EP
			else {
				return $this->saisine( $data );
			}
		}

		/**
		* Sauvegarde d'un maintien de l'orientation d'un allocataire suite au bilan de parcours.
		*
		* Un maintien de l'orientation entraîne la création d'une nouvelle orientation,
		* la création d'un nouveau CER. Ces nouvelles entrées sont des copies des
		* anciennes (les dates changent).
		*
		* @param array $data Les données du bilan à sauvegarder.
		* @return boolean True en cas de succès, false sinon.
		* @access public
		*
		* FIXME: modification du bilan
		*/

		public function maintien( $data ) {
			$data[$this->alias]['saisineepparcours'] = ( empty( $data[$this->alias]['maintienorientation'] ) ? '1' : '0' );
			$data[$this->alias]['positionbilan'] = 'traite';
			$this->create( $data );
			if( $success = $this->validates() ) {
				// Recherche de l'ancienne orientation
				$vxOrientstruct = $this->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'Orientstruct.id' => $data[$this->alias]['orientstruct_id'] // TODO: autre conditions ?
						),
						'contain' => false
					)
				);

				if( empty( $vxOrientstruct ) ) {
					debug( 'Vieille orientation répondant aux critères non trouvé.' );
					return false;
				}

				if( $data['Bilanparcours66']['changementrefsansep'] != 'O' ) {
					// Recherche de l'ancien contrat d'insertion
					$vxContratinsertion = $this->Contratinsertion->find(
						'first',
						array(
							'conditions' => array(
								'Contratinsertion.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
								'Contratinsertion.structurereferente_id' => $vxOrientstruct['Orientstruct']['structurereferente_id']
							),
							'contain' => false
						)
					);
					
					$vxCui = $this->Cui->find(
						'first',
						array(
							'conditions' => array(
								'Cui.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
								'Cui.datefinprisecharge >=' => date( 'Y-m-d' )
							),
							'contain' => false,
							'recursive' => -1
						)
					);

// 					if( empty( $vxContratinsertion ) ) {
// 						$this->invalidate( 'changementref', 'Cette personne ne possède aucune contrat d\'insertion validé dans une structure référente liée à celle de sa dernière orientation validée.' );
// 						return false;
// 					}
					if( empty( $vxContratinsertion ) && empty( $vxCui ) ) {
						$this->invalidate( 'changementref', 'Cette personne ne possède aucune contrat.' );
						return false;
					}
				}

				if ( $data['Bilanparcours66']['changementrefsansep'] == 'O' ) {
					list( $typeorient_id, $structurereferente_id ) = explode( '_', $data['Bilanparcours66']['nvstructurereferente_id'] );
					// Sauvegarde de la nouvelle orientation
					$orientstruct = array(
						'Orientstruct' => array(
							'personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
							'typeorient_id' => $typeorient_id,
							'structurereferente_id' => $structurereferente_id,
							'referent_id' => $vxOrientstruct['Orientstruct']['referent_id'],
							'date_propo' => date( 'Y-m-d' ),
							'date_valid' => date( 'Y-m-d' ),
							'statut_orient' => 'Orienté',
							'user_id' => $data['Bilanparcours66']['nvuser_id']
						)
					);
					$this->Orientstruct->create( $orientstruct );
					$success = $this->Orientstruct->save() && $success;
				}
				else if( $data['Bilanparcours66']['changementrefsansep'] == 'N' ) {
					$contratinsertion = $vxContratinsertion;
					unset( $contratinsertion['Contratinsertion']['id'] );
					$contratinsertion['Contratinsertion']['dd_ci'] = $data['Bilanparcours66']['ddreconductoncontrat'];
					$contratinsertion['Contratinsertion']['df_ci'] = $data['Bilanparcours66']['dfreconductoncontrat'];
					$contratinsertion['Contratinsertion']['duree_engag'] = $data['Bilanparcours66']['duree_engag'];

					$idRenouvellement = $this->Contratinsertion->Typocontrat->field( 'Typocontrat.id', array( 'Typocontrat.lib_typo' => 'Renouvellement' ) );
					$contratinsertion['Contratinsertion']['typocontrat_id'] = $idRenouvellement;
					$contratinsertion['Contratinsertion']['rg_ci'] = ( $contratinsertion['Contratinsertion']['rg_ci'] + 1 );

					// La date de validation est à null afin de pouvoir modifier le contrat
					$contratinsertion['Contratinsertion']['datevalidation_ci'] = null;
					// La date de saisie du nouveau contrat est égale à la date du jour
					$contratinsertion['Contratinsertion']['date_saisi_ci'] = date( 'Y-m-d' );

					unset($contratinsertion['Contratinsertion']['decision_ci']);
					unset($contratinsertion['Contratinsertion']['datevalidation_ci']);

					$fields = array( 'actions_prev', 'aut_expr_prof', 'emp_trouv', 'sect_acti_emp', 'emp_occupe', 'duree_hebdo_emp', 'nat_cont_trav', 'duree_cdd', 'niveausalaire' ); // FIXME: une variable du modèle
					foreach( $fields as $field ) {
						unset( $contratinsertion['Contratinsertion'][$field] );
					}

					$this->Contratinsertion->create( $contratinsertion );
					$success = $this->Contratinsertion->save() && $success;

					$data[$this->alias]['contratinsertion_id'] = $vxContratinsertion['Contratinsertion']['id'];
				}

				if( !empty( $this->validationErrors ) ) {
					debug( $this->validationErrors );
				}

				$data['Bilanparcours66']['typeorientprincipale_id'] = $data['Bilanparcours66']['sansep_typeorientprincipale_id'];
				$data['Bilanparcours66']['changementref'] = $data['Bilanparcours66']['changementrefsansep'];

				$this->create( $data );
				$success = $this->save() && $success;
			}
			else {
				// S'il manque l'information "Bilan du parcours d'insertion", on supprime
				// l'erreur concernant les "Motifs de la saisine de l'équipe pluridisciplinaire"
				// car les caractères obligatoires de ces champs cont mutuellement exclusifs
				// et que les motifs n'ont normalement aucun sens lorsqu'on maintient
				if( isset( $this->validationErrors['bilanparcoursinsertion'] ) ) {
					unset( $this->validationErrors['motifep'] );
				}
			}

			return $success;
		}

		/**
		* Sauvegarde du bilan de parcours, ainsi que d'une saisine d'EP suite
		* à ce bilan de parcours. La saisine entraîne la création d'un dossier
		* d'EP.
		*
		* @param array $data Les données du bilan à sauvegarder.
		* @return boolean True en cas de succès, false sinon.
		* @access public
		*
		* TODO: comment finaliser l'orientation précédente ?
		* TODO: pouvoir envoyer la cause d'échec (ex.: $vxContratinsertion non
		*       trouvé avec ces critères) depuis les règles de validation.
		*/

		public function saisine( $data ) {

			// Saisine parcours
			$success = true;
			if( isset($data['Bilanparcours66']['proposition']) && in_array( $data['Bilanparcours66']['proposition'], array( 'parcours', 'parcourspe' ) ) ) {
				$data[$this->alias]['saisineepparcours'] = ( empty( $data[$this->alias]['maintienorientation'] ) ? '1' : '0' );
				$this->create( $data );

				if( $success = $this->validates() ) {
					$vxOrientstruct = $this->Orientstruct->find(
						'first',
						array(
							'conditions' => array(
								'Orientstruct.id' => $data[$this->alias]['orientstruct_id'] // TODO: autre conditions ?
							),
							'contain' => false
						)
					);

					if( !isset( $data[$this->alias]['origine'] ) || $data[$this->alias]['origine'] != 'Defautinsertionep66' ) {
						if( empty( $vxOrientstruct ) ) {
							$this->invalidate( 'choixparcours', 'Cette personne ne possède aucune orientation validée.' );
							return false;
						}

						// Possède-t-on un CER 
						$vxContratinsertion = $this->Contratinsertion->find(
							'first',
							array(
								'conditions' => array(
									'Contratinsertion.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
									'Contratinsertion.structurereferente_id' => $vxOrientstruct['Orientstruct']['structurereferente_id']
								),
								'contain' => false,
								'recursive' => -1
							)
						);
						
						// Possède-t-on un CUI (pour rappel, un CUI vaut CER)
						$vxCui = $this->Cui->find(
							'first',
							array(
								'conditions' => array(
									'Cui.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
									'Cui.datefinprisecharge >=' => date( 'Y-m-d' )
								),
								'contain' => false,
								'recursive' => -1
							)
						);
	
						
						
						if( ( $data['Bilanparcours66']['changementrefavecep'] == 'N' ) ) {
							if( empty( $vxContratinsertion ) && empty( $vxCui ) ) {
								$this->invalidate( 'changementref', 'Cette personne ne possède aucun contrat.' );
								return false;
							}
						}
// 						if( empty( $vxContratinsertion ) && ( $data['Bilanparcours66']['changementrefavecep'] == 'N' ) ) {
// 							$this->invalidate( 'changementref', 'Cette personne ne possède aucun CER validé dans une structure référente liée à celle de sa dernière orientation validée.' );
// 							return false;
// 						}

						// Sauvegarde du bilan
						$data[$this->alias]['contratinsertion_id'] = @$vxContratinsertion['Contratinsertion']['id'];
						$data[$this->alias]['cui_id'] = @$vxCui['Cui']['id'];
						

					}

					if( isset( $data[$this->alias]['origine'] ) && $data[$this->alias]['origine'] == 'Defautinsertionep66' && !isset( $data[$this->alias]['structurereferente_id'] ) ) {
						$data[$this->alias]['structurereferente_id'] = $vxOrientstruct['Orientstruct']['structurereferente_id'];
					}

					if( isset( $data['Bilanparcours66']['avecep_typeorientprincipale_id'] ) ) {
						$data['Bilanparcours66']['typeorientprincipale_id'] = $data['Bilanparcours66']['avecep_typeorientprincipale_id'];
					}
					if( isset( $data['Bilanparcours66']['changementrefavecep'] ) ) {
						$data['Bilanparcours66']['changementref'] = $data['Bilanparcours66']['changementrefavecep'];
					}
// debug( $data );
					$this->create( $data );
					$success = $this->save() && $success;

					if( !empty( $this->validationErrors ) ) {
						return false;
					}

					// Sauvegarde du dossier d'EP
					$dataDossierEp = array(
						'Dossierep' => array(
							'personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
							'themeep' => 'saisinesbilansparcourseps66'
						)
					);
					$this->Saisinebilanparcoursep66->Dossierep->create( $dataDossierEp );
					$success = $this->Saisinebilanparcoursep66->Dossierep->save() && $success;

					// Sauvegarde de la saisine
					$data['Saisinebilanparcoursep66']['bilanparcours66_id'] = $this->id;
					$data['Saisinebilanparcoursep66']['dossierep_id'] = $this->Saisinebilanparcoursep66->Dossierep->id;

					if( isset( $data['Bilanparcours66']['typeorientprincipale_id'] ) ) {
						$data['Saisinebilanparcoursep66']['typeorientprincipale_id'] = $data['Bilanparcours66']['typeorientprincipale_id'];
					}
					else {
						$data['Saisinebilanparcoursep66']['typeorientprincipale_id'] = $this->Orientstruct->Typeorient->getIdLevel0( $data['Saisinebilanparcoursep66']['typeorient_id'] );
					}

					if( isset( $data['Bilanparcours66']['nvtypeorient_id'] ) ) {
						$data['Saisinebilanparcoursep66']['typeorient_id'] = $data['Bilanparcours66']['nvtypeorient_id'];
					}
					if( isset( $data['Bilanparcours66']['nvstructurereferente_id'] ) ) {
						$data['Saisinebilanparcoursep66']['structurereferente_id'] = $data['Bilanparcours66']['nvstructurereferente_id'];
					}

					if ( isset( $data['Bilanparcours66']['choixparcours'] ) ) {
						$data['Saisinebilanparcoursep66']['choixparcours'] = $data['Bilanparcours66']['choixparcours'];
					}
					if ( isset( $data['Bilanparcours66']['changementrefavecep'] ) ) {
						$data['Saisinebilanparcoursep66']['changementrefparcours'] = $data['Bilanparcours66']['changementref'];
					}
					if ( isset( $data['Bilanparcours66']['reorientation'] ) ) {
						$data['Saisinebilanparcoursep66']['reorientation'] = $data['Bilanparcours66']['reorientation'];
					}
// 					else {
// 						$data['Saisinebilanparcoursep66']['reorientation'] = 'reorientation';
// 					}

					$this->Saisinebilanparcoursep66->create( $data );
					$success = $this->Saisinebilanparcoursep66->save() && $success;

// debug($this->Saisinebilanparcoursep66->validationErrors);
// debug($success);
// die();
				}
			}
			// Saisine audition
			else if( isset($data['Bilanparcours66']['proposition']) && $data['Bilanparcours66']['proposition'] == 'audition' ) {
				$data[$this->alias]['saisineepparcours'] = '0';

				$this->create( $data );
				if( $success = $this->validates() ) {

					$nbOrientstruct = $this->Orientstruct->find(
						'count',
						array(
							'conditions' => array(
								'Orientstruct.personne_id' => $data[$this->alias]['personne_id']
							 ),
							 'contain' => false
						)
					);

					// Si pas d'orientaiton pour la personne, on peut créer un dossier EP
					if( $nbOrientstruct == 0 ) {

						// On vérifie que le choix du parcours est un EPL Audition avec passage pour Défaut de conclusion
						if( $data[$this->alias]['examenaudition'] != 'DOD'  ) {
							$this->invalidate( 'examenaudition', 'Cette personne ne possède aucune orientation, elle ne peut être signalée que pour un défaut de conclusion.' );
							return false;
						}

						if ( $data[$this->alias]['examenaudition'] == 'DOD' ) {
							$nbpassageeplaudition = $this->Defautinsertionep66->Dossierep->Passagecommissionep->find(
								'count',
								array(
									'conditions' => array(
										'Passagecommissionep.dossierep_id IN ('.$this->Defautinsertionep66->Dossierep->sq(
											array(
												'fields' => array( 'dossierseps.id' ),
												'alias' => 'dossierseps',
												'conditions' => array(
													'dossierseps.themeep' => 'defautsinsertionseps66',
													'dossierseps.personne_id' => $data['Bilanparcours66']['personne_id']
												)
											)
										).' )',
										'Passagecommissionep.etatdossierep' => 'traite'
									)
								)
							);

							/*$typesrdvs = $this->Contratinsertion->Personne->Rendezvous->Typerdv->find(
								'all',
								array(
									'conditions' => array(
										'Typerdv.nbabsaveplaudition >' => 0
									),
									'contain' => false
								)
							);

							$nbPosPasEplAud = 0;
							foreach( $typesrdvs as $typerdv ) {
								$nbrdvsnonvenu = $this->Contratinsertion->Personne->Rendezvous->find(
									'count',
									array(
										'conditions' => array(
											'Rendezvous.personne_id' => $data[$this->alias]['personne_id'],
											'Statutrdv.permetpassageepl' => 1,
											'Rendezvous.typerdv_id' => $typerdv['Typerdv']['id']
										),
										'joins' => array(
											array(
												'alias' => 'Statutrdv',
												'table' => 'statutsrdvs',
												'type' => 'INNER',
												'conditions' => array(
													'Rendezvous.statutrdv_id = Statutrdv.id'
												)
											)
										),
										'contain' => false
									)
								);
								$nbPosPasEplAud += floor( $nbrdvsnonvenu / $typerdv['Typerdv']['nbabsaveplaudition'] );
							}

							if ( $nbpassageeplaudition >= $nbPosPasEplAud ) {
								$this->invalidate( 'examenaudition', 'Cette personne ne possède pas assez de rendez-vous où elle ne s\'est pas présentée.' );
								return false;
							}*/
						}



						$this->create( $data );
						$success = $this->save() && $success;


						// Sauvegarde du dossier d'EP
						$dataDossierEp = array(
							'Dossierep' => array(
								'personne_id' => $data[$this->alias]['personne_id'],
								'themeep' => 'defautsinsertionseps66'
							)
						);
						$this->Defautinsertionep66->Dossierep->create( $dataDossierEp );
						$success = $this->Defautinsertionep66->Dossierep->save() && $success;

						// Sauvegarde de la saisine pour défaut d'insertion
						$data['Defautinsertionep66']['bilanparcours66_id'] = $this->id;
						$data['Defautinsertionep66']['dossierep_id'] = $this->Defautinsertionep66->Dossierep->id;
						$data['Defautinsertionep66']['origine'] = 'bilanparcours';

						$this->Defautinsertionep66->create( $data );
						$success = $this->Defautinsertionep66->save() && $success;
					}
					else {

						$vxOrientstruct = $this->Orientstruct->find(
							'first',
							array(
								'conditions' => array(
									'Orientstruct.id' => $data[$this->alias]['orientstruct_id'] // TODO: autre conditions ?
								),
								'contain' => false
							)
						);

						// FIXME: erreur pas dans choixparcours
						if( empty( $vxOrientstruct ) ) {
							$this->invalidate( 'examenaudition', 'Cette personne ne possède aucune orientation validée.' );
							return false;
						}

						$vxContratinsertion = $this->Contratinsertion->find(
							'first',
							array(
								'conditions' => array(
									'Contratinsertion.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
									'Contratinsertion.structurereferente_id' => $vxOrientstruct['Orientstruct']['structurereferente_id']
								),
								'contain' => false
							)
						);

						$vxCui = $this->Cui->find(
							'first',
							array(
								'conditions' => array(
									'Cui.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
									'Cui.datefinprisecharge >=' => date( 'Y-m-d' )
								),
								'contain' => false,
								'recursive' => -1
							)
						);
						// FIXME: erreur pas dans choixparcours
// 						if( $data[$this->alias]['examenaudition'] != 'DOD' && empty( $vxContratinsertion ) ) {
// 							$this->invalidate( 'examenaudition', 'Cette personne ne possède aucun CER validé dans une structure référente liée à celle de sa dernière orientation validée.' );
// 							return false;
// 						}

						//Passage en EPL Audition pour non respect
						if( $data[$this->alias]['examenaudition'] != 'DOD' ) {
							if( empty( $vxContratinsertion ) && empty( $vxCui ) ) {
								$this->invalidate( 'examenaudition', 'Cette personne ne possède aucun contrat.' );
								return false;
							}
						}

						if ( $data[$this->alias]['examenaudition'] == 'DOD' ) {
							$nbpassageeplaudition = $this->Defautinsertionep66->Dossierep->Passagecommissionep->find(
								'count',
								array(
									'conditions' => array(
										'Passagecommissionep.dossierep_id IN ('.$this->Defautinsertionep66->Dossierep->sq(
											array(
												'fields' => array( 'dossierseps.id' ),
												'alias' => 'dossierseps',
												'conditions' => array(
													'dossierseps.themeep' => 'defautsinsertionseps66',
													'dossierseps.personne_id' => $data['Bilanparcours66']['personne_id']
												)
											)
										).' )',
										'Passagecommissionep.etatdossierep' => 'traite'
									)
								)
							);

							/*INFO: mise en commentaire du calcul du nb de RDV non honoré au CG66
                             * $typesrdvs = $this->Contratinsertion->Personne->Rendezvous->Typerdv->find(
								'all',
								array(
									'conditions' => array(
										'Typerdv.nbabsaveplaudition >' => 0
									),
									'contain' => false
								)
							);

							$nbPosPasEplAud = 0;
							foreach( $typesrdvs as $typerdv ) {
								$nbrdvsnonvenu = $this->Contratinsertion->Personne->Rendezvous->find(
									'count',
									array(
										'conditions' => array(
											'Rendezvous.personne_id' => $data[$this->alias]['personne_id'],
											'Statutrdv.permetpassageepl' => 1,
											'Rendezvous.typerdv_id' => $typerdv['Typerdv']['id']
										),
										'joins' => array(
											array(
												'alias' => 'Statutrdv',
												'table' => 'statutsrdvs',
												'type' => 'INNER',
												'conditions' => array(
													'Rendezvous.statutrdv_id = Statutrdv.id'
												)
											)
										),
										'contain' => false
									)
								);
								$nbPosPasEplAud += floor( $nbrdvsnonvenu / $typerdv['Typerdv']['nbabsaveplaudition'] );
							}

							if ( $nbpassageeplaudition >= $nbPosPasEplAud ) {
								$this->invalidate( 'examenaudition', 'Cette personne ne possède pas assez de rendez-vous où elle ne s\'est pas présentée.' );
								return false;
							}*/
						}

						// Sauvegarde du bilan
						$data[$this->alias]['contratinsertion_id'] = $vxContratinsertion['Contratinsertion']['id'];
						$data[$this->alias]['cui_id'] = $vxCui['Cui']['id'];
						$this->create( $data );
						$success = $this->save() && $success;

						if( !empty( $this->validationErrors ) ) {
							debug( $this->validationErrors );
						}

						// Sauvegarde du dossier d'EP
						$dataDossierEp = array(
							'Dossierep' => array(
								'personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
								'themeep' => 'defautsinsertionseps66'
							)
						);
						$this->Defautinsertionep66->Dossierep->create( $dataDossierEp );
						$success = $this->Defautinsertionep66->Dossierep->save() && $success;

						// Sauvegarde de la saisine pour défaut d'insertion
						$data['Defautinsertionep66']['bilanparcours66_id'] = $this->id;
						$data['Defautinsertionep66']['dossierep_id'] = $this->Defautinsertionep66->Dossierep->id;
						$data['Defautinsertionep66']['orientstruct_id'] = $vxOrientstruct['Orientstruct']['id'];
						$data['Defautinsertionep66']['contratinsertion_id'] = $vxContratinsertion['Contratinsertion']['id'];
						$data['Defautinsertionep66']['cui_id'] = $vxCui['Cui']['id'];
						$data['Defautinsertionep66']['origine'] = 'bilanparcours';

						$this->Defautinsertionep66->create( $data );
						$success = $this->Defautinsertionep66->save() && $success;
					}
				}
			}
			// Saisine audition pôle emploi
			else if( isset($data['Bilanparcours66']['proposition']) && $data['Bilanparcours66']['proposition'] == 'auditionpe' ) {
				$data[$this->alias]['saisineepparcours'] = '0';

				$this->create( $data );
				if( $success = $this->validates() ) {
                    
					$success = $this->save() && $success;

					// Avant de sauvegarder le dossier d'EP, on va rechercher
					// la radiation PE qui nous a conduit ici (si nécessaire)
					$historiqueetatpe_id = null;
					if( $data['Bilanparcours66']['examenauditionpe'] == 'radiationpe' ) {
						$queryDataPersonne = $this->Defautinsertionep66->qdRadies( array(), array(), array() );
						$queryDataPersonne['fields'] = array( 'Historiqueetatpe.id' );
						$queryDataPersonne['conditions']['Personne.id'] = $data['Bilanparcours66']['personne_id'];
						$historiqueetatpe = $this->Defautinsertionep66->Dossierep->Personne->find( 'first', $queryDataPersonne );
						$historiqueetatpe_id = $historiqueetatpe['Historiqueetatpe']['id'];
					}

					$dossierep = array(
						'Dossierep' => array(
							'themeep' => 'defautsinsertionseps66',
							'personne_id' => $data['Bilanparcours66']['personne_id']
						)
					);
					$this->Defautinsertionep66->Dossierep->create( $dossierep );
					$success = $this->Defautinsertionep66->Dossierep->save() && $success;

					$defautinsertionep66 = array(
						'Defautinsertionep66' => array(
							'dossierep_id' => $this->Defautinsertionep66->Dossierep->id,
							'orientstruct_id' => $data['Bilanparcours66']['orientstruct_id'],
							'bilanparcours66_id' => $this->id,
							'origine' => $data['Bilanparcours66']['examenauditionpe'],
							'historiqueetatpe_id' => $historiqueetatpe_id
						)
					);

					$this->Defautinsertionep66->create( $defautinsertionep66 );
					$success = $this->Defautinsertionep66->save() && $success;
				}
			}
			else {
				$success = $this->save($data) && $success;
			}

			return $success;
		}

		/**
		* Retourne le chemin relatif du modèle de document à utiliser pour l'enregistrement du PDF.
		*/

		public function modeleOdt( $data ) {
			return "Bilanparcours/bilanparcours.odt";
		}

		/**
		* Récupère les données pour le PDf
		*/

		public function getDataForPdf( $id ) {
			// TODO: error404/error500 si on ne trouve pas les données
			$optionModel = ClassRegistry::init( 'Option' );
			$qual = $optionModel->qual();
			$typevoie = $optionModel->typevoie();
			$conditions = array( 'Bilanparcours66.id' => $id );

			$joins = array(
				array(
					'table'      => 'orientsstructs',
					'alias'      => 'Orientstruct',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Orientstruct.id = Bilanparcours66.orientstruct_id' )
				),
				array(
					'table'      => 'contratsinsertion',
					'alias'      => 'Contratinsertion',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Contratinsertion.id = Bilanparcours66.contratinsertion_id' )
				),
				array(
					'table'      => 'personnes',
					'alias'      => 'Personne',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Personne.id = Bilanparcours66.personne_id' )
				),
				array(
					'table'      => 'foyers',
					'alias'      => 'Foyer',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Foyer.id = Personne.foyer_id' )
				),
				array(
					'table'      => 'dossiers',
					'alias'      => 'Dossier',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
				),
				array(
					'table'      => 'adressesfoyers',
					'alias'      => 'Adressefoyer',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array(
						'Foyer.id = Adressefoyer.foyer_id',
						'Adressefoyer.rgadr' => '01'
					)
				),
				array(
					'table'      => 'adresses',
					'alias'      => 'Adresse',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
				),
				array(
					'table'      => 'pdfs',
					'alias'      => 'Pdf',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array(
						'Pdf.modele' => $this->alias,
						'Pdf.fk_value = Bilanparcours66.id'
					)
				),
			);

			$queryData = array(
				'fields' => array(
					'Dossier.matricule',
					'Adresse.numvoie',
					'Adresse.typevoie',
					'Adresse.nomvoie',
					'Adresse.complideadr',
					'Adresse.compladr',
					'Adresse.lieudist',
					'Adresse.numcomrat',
					'Adresse.numcomptt',
					'Adresse.codepos',
					'Adresse.locaadr',
					'Adresse.pays',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nir',
					'Orientstruct.date_impression',
					'Contratinsertion.df_ci',
					'Contratinsertion.datevalidation_ci',
				),
				'joins' => $joins,
				'conditions' => $conditions,
				'contain' => false
			);

			$data = $this->find( 'first', $queryData );

			$data['Personne']['qual'] = Set::enum( $data['Personne']['qual'], $qual );
			$data['Adresse']['typevoie'] = Set::enum( $data['Adresse']['typevoie'], $typevoie );

			return $data;
		}

		/**
		*
		*/

		public function getPdfCourrierInformation( $id ) {
			$gedooo_data = $this->getDataForPdf( $id );
			$this->updateAll(
				array( 'Bilanparcours66.datecourrierimpression' => "'".date( 'Y-m-d' )."'" ),
				array(
					'"Bilanparcours66"."datecourrierimpression" IS NULL',
					'"Bilanparcours66"."id"' => $id
				)
			);

			return $this->ged( $gedooo_data, 'Bilanparcours/courrierinformationavantep.odt'/*, true, $options*/ );
		}



		/**
		*	On cherche le nombre de dossiers d'EP pour une personne concernant une thématique donnée
		*	qui :
		*		- ne sont pas liés à un passage en EP (non lié à une commission)
		*		OU
		*		- sont liés à un passage en EP (lié à une commission) ET qui sont dans l'état traité ou annulé
		*
		*/
		public function ajoutPossibleThematique66( $themeep, $personne_id ) {
			$Dossierep = ClassRegistry::init( 'Dossierep' );
			$count = $Dossierep->find(
				'count',
				array(
					'conditions' => array(
						'Dossierep.personne_id' => $personne_id,
						'Dossierep.themeep' => $themeep,
						'OR' => array(
							'Dossierep.id NOT IN ('
								.$Dossierep->Passagecommissionep->sq(
									array(
										'alias' => 'passagescommissionseps',
										'fields' => array( 'passagescommissionseps.dossierep_id' ),
										'conditions' => array(
											'passagescommissionseps.dossierep_id = Dossierep.id'
										),
										'contain' => false
									)
								)
							.')',
							'Dossierep.id IN ('
								.$Dossierep->Passagecommissionep->sq(
									array(
										'alias' => 'passagescommissionseps',
										'fields' => array( 'passagescommissionseps.dossierep_id' ),
										'conditions' => array(
											'passagescommissionseps.dossierep_id = Dossierep.id',
											'NOT' => array(
												'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
											)
										),
										'contain' => false
									)
								)
							.')',

						)
					),
					'contain' => false
				)
			);

			return ( $count == 0 );
		}
        
        /**
		 * Retourne l'id du dossier à partir de l'id du Bilan
		 *
		 * @param integer $id
		 * @return integer
		 */
		public function dossierId( $id ) {
			$bilanparcours66 = $this->find(
				'first',
				array(
					'fields' => array(
						'Foyer.dossier_id'
					),
					'joins' => array(
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					),
					'conditions' => array(
						'$bilanparcours66.id' => $id
					),
					'contain' => false
				)
			);

			if( !empty( $bilanparcours66 ) ) {
				return $bilanparcours66['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}
	}
?>