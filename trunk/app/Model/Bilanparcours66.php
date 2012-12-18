<?php
	/**
	 * Code source de la classe Bilanparcours66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	* Bilan de parcours pour le conseil général du département 66.
	*
	* @package app.Model
	*/
	class Bilanparcours66 extends AppModel
	{
		public $name = 'Bilanparcours66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
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
				'datePassee' => array(
					'rule' => 'datePassee',
					'message' => 'Merci de choisir une date antérieure à la date du jour'
				),
				'date' => array(
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
			'duree_engag' => array(
				array(
					'rule' => array( 'notEmptyIf', 'changementrefsansep', true, array( 'N' ) ),
					'message' => 'Champ obligatoire',
				),
//				array(
//					'rule' => array( 'notEmptyIf', 'changementrefavecep', true, array( 'N' ) ),
//					'message' => 'Champ obligatoire',
//				)
			),
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
			'Nvcontratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'nvcontratinsertion_id',
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
			'Serviceinstructeur' => array(
				'className' => 'Serviceinstructeur',
				'foreignKey' => 'serviceinstructeur_id',
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
			'NvStructurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'nvstructurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typeorientprincipale' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'typeorientprincipale_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'NvTypeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'nvtypeorient_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
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
			),
			'Manifestationbilanparcours66' => array(
				'className' => 'Manifestationbilanparcours66',
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
			if( isset( $data[$this->alias]['proposition'] ) && in_array( $data[$this->alias]['proposition'], array( 'traitement', 'aucun' ) ) ){
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
				if( $data[$this->alias]['proposition'] == 'aucun' ) {
					$this->create( $data );
					$success = $this->save() && $success;
				}
				else{
					// Recherche de l'ancienne orientation
					$vxOrientstruct = array();
					if( !empty( $data[$this->alias]['orientstruct_id'] ) ) {
						$vxOrientstruct = $this->Orientstruct->find(
							'first',
							array(
								'conditions' => array(
									'Orientstruct.id' => $data[$this->alias]['orientstruct_id']
								),
								'contain' => false
							)
						);
					}

					if( empty( $vxOrientstruct ) ) {
						$this->invalidate( 'proposition', 'Vieille orientation répondant aux critères non trouvé.');
						return false;
					} 
					
					if( $data['Bilanparcours66']['changementrefsansep'] != 'O' ) {
						// Recherche de l'ancien contrat d'insertion
						$sqDernierCer = $this->Contratinsertion->sqDernierContrat( '"Contratinsertion"."personne_id"' );
						$vxContratinsertion = $this->Contratinsertion->find(
							'first',
							array(
								'conditions' => array(
									'Contratinsertion.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
									"Contratinsertion.id IN ( {$sqDernierCer} )"
								),
								'contain' => false
							)
						);

						$sqDernierCui = $this->Cui->sqDernierContrat( '"Cui"."personne_id"' );
						$vxCui = $this->Cui->find(
							'first',
							array(
								'conditions' => array(
									'Cui.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
									"Cui.id IN ( {$sqDernierCui} )"
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
							$this->invalidate( 'changementref', 'Cette personne ne possède aucun contrat.' );
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
								'user_id' => $data['Bilanparcours66']['user_id']
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
						$contratinsertion['Contratinsertion']['num_contrat'] = 'REN';

						$contratinsertion['Contratinsertion']['rg_ci'] = ( $contratinsertion['Contratinsertion']['rg_ci'] + 1 );

						// La date de validation est à null afin de pouvoir modifier le contrat
						$contratinsertion['Contratinsertion']['datevalidation_ci'] = null;
						$contratinsertion['Contratinsertion']['datedecision'] = null;
						// La date de saisie du nouveau contrat est égale à la date du jour
						$contratinsertion['Contratinsertion']['date_saisi_ci'] = date( 'Y-m-d' );

						unset($contratinsertion['Contratinsertion']['decision_ci']);
						unset($contratinsertion['Contratinsertion']['datevalidation_ci']);
						unset($contratinsertion['Contratinsertion']['datedecision']);

						$fields = array( 'actions_prev', 'aut_expr_prof', 'emp_trouv', 'sect_acti_emp', 'emp_occupe', 'duree_hebdo_emp', 'nat_cont_trav', 'duree_cdd', 'niveausalaire' ); // FIXME: une variable du modèle
						foreach( $fields as $field ) {
							unset( $contratinsertion['Contratinsertion'][$field] );
						}

						// Calcul de la limite de cumul de durée de CER à l'enregistrement du bilan
						$nbCumulDureeCER66 = $this->Contratinsertion->limiteCumulDureeCER( $data['Bilanparcours66']['personne_id'] );
						$Option = ClassRegistry::init( 'Option' );
						$durees = $Option->duree_engag();
						$dureeEngagReconductionCER = Set::classicExtract( $durees, $contratinsertion['Contratinsertion']['duree_engag'] );
						$dureeEngagReconductionCER = str_replace( ' mois', '', $dureeEngagReconductionCER );

// 						debug(var_export($dureeEngagReconductionCER, true));

						if( ( $nbCumulDureeCER66 + $dureeEngagReconductionCER ) > 24 ){
							$this->invalidate( 'duree_engag', 'La durée du CER sélectionnée dépasse la limite des 24 mois de contractualisation autorisée pour une orientation en SOCIAL' );
							return false;
						}
						$this->Contratinsertion->create( $contratinsertion );
						$success = $this->Contratinsertion->save() && $success;

						$data[$this->alias]['contratinsertion_id'] = $vxContratinsertion['Contratinsertion']['id'];
						$data[$this->alias]['nvcontratinsertion_id'] = $this->Contratinsertion->id;
					}

					if( !empty( $this->validationErrors ) ) {
						debug( $this->validationErrors );
					}

					$data['Bilanparcours66']['typeorientprincipale_id'] = $data['Bilanparcours66']['sansep_typeorientprincipale_id'];
					$data['Bilanparcours66']['changementref'] = $data['Bilanparcours66']['changementrefsansep'];

					$this->create( $data );
					$success = $this->save() && $success;
				}
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
						$sqDernierCer = $this->Contratinsertion->sqDernierContrat( '"Contratinsertion"."personne_id"' );
						$vxContratinsertion = $this->Contratinsertion->find(
							'first',
							array(
								'conditions' => array(
									'Contratinsertion.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
									"Contratinsertion.id IN ( {$sqDernierCer} )"
								),
								'contain' => false
							)
						);

						
						// Possède-t-on un CUI (pour rappel, un CUI vaut CER)
						$sqDernierCui = $this->Cui->sqDernierContrat( '"Cui"."personne_id"' );
						$vxCui = $this->Cui->find(
							'first',
							array(
								'conditions' => array(
									'Cui.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
									"Cui.id IN ( {$sqDernierCui} )"
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


					$this->Saisinebilanparcoursep66->create( $data );
					$success = $this->Saisinebilanparcoursep66->save() && $success;

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

							$typesrdvs = $this->Contratinsertion->Personne->Rendezvous->Typerdv->find(
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
								if( ( $typerdv['Typerdv']['nbabsaveplaudition'] != 0 ) ) {
									$nbPosPasEplAud += floor( $nbrdvsnonvenu / $typerdv['Typerdv']['nbabsaveplaudition'] );
								}
							}

							if ( $nbpassageeplaudition >= $nbPosPasEplAud ) {
								$this->invalidate( 'examenaudition', 'Cette personne ne possède pas assez de rendez-vous où elle ne s\'est pas présentée.' );
								return false;
							}
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

						$sqDernierCer = $this->Contratinsertion->sqDernierContrat( '"Contratinsertion"."personne_id"' );
						$vxContratinsertion = $this->Contratinsertion->find(
							'first',
							array(
								'conditions' => array(
									'Contratinsertion.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
									"Contratinsertion.id IN ( {$sqDernierCer} )"
								),
								'contain' => false
							)
						);

						$sqDernierCui = $this->Cui->sqDernierContrat( '"Cui"."personne_id"' );
						$vxCui = $this->Cui->find(
							'first',
							array(
								'conditions' => array(
									'Cui.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
									"Cui.id IN ( {$sqDernierCui} )"
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

							//INFO: mise en commentaire du calcul du nb de RDV non honoré au CG66
							$typesrdvs = $this->Contratinsertion->Personne->Rendezvous->Typerdv->find(
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
								if( ( $typerdv['Typerdv']['nbabsaveplaudition'] != 0 ) ) {
									$nbPosPasEplAud += floor( $nbrdvsnonvenu / $typerdv['Typerdv']['nbabsaveplaudition'] );
								}
							}

							if ( $nbpassageeplaudition >= $nbPosPasEplAud ) {
								$this->invalidate( 'examenaudition', 'Cette personne ne possède pas assez de rendez-vous où elle ne s\'est pas présentée.' );
								return false;
							}
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
		 * Récupère les données pour le PDF du bilan de parcours.
		 *
		 * @param integer $id L'id technique du bilan de parcours
		 * @return array
		 */
		public function getDataForPdf( $id ) {
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$querydata = Cache::read( $cacheKey );

			if( $querydata === false ) {
				$conditions = array(
					array(
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					$this->Dossierpcg66->sqLatest( 'Decisiondossierpcg66', 'datevalidation', array( 'Decisiondossierpcg66.validationproposition' => 'O' ) )
				);

				// Jointure spéciale sur Dossierep suivant la thématique
				$joinSaisinebilanparcoursep66 = $this->Saisinebilanparcoursep66->join( 'Dossierep', array( 'type' => 'LEFT OUTER' ) );
				$joinDefautinsertionep66 = $this->Defautinsertionep66->join( 'Dossierep', array( 'type' => 'LEFT OUTER' ) );

				$joinDossierep = $joinSaisinebilanparcoursep66;
				$joinDossierep['conditions'] = array(
					'OR' => array(
						$joinSaisinebilanparcoursep66['conditions'],
						$joinDefautinsertionep66['conditions']
					)
				);

				$joins = array(
					$this->join( 'Orientstruct', array( 'type' => 'LEFT OUTER' ) ),
					$this->Orientstruct->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) ),
					$this->Orientstruct->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
					$this->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
					$this->join( 'Serviceinstructeur', array( 'type' => 'LEFT OUTER' ) ),
					$this->join( 'Saisinebilanparcoursep66', array( 'type' => 'LEFT OUTER' ) ),
					$this->join( 'Defautinsertionep66', array( 'type' => 'LEFT OUTER' ) ),
					$this->join( 'Contratinsertion', array( 'type' => 'LEFT OUTER' ) ),
					$this->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					$joinDossierep,
					$this->Saisinebilanparcoursep66->Dossierep->join( 'Passagecommissionep', array( 'type' => 'LEFT OUTER' ) ),
					$this->Saisinebilanparcoursep66->Dossierep->Passagecommissionep->join( 'Commissionep', array( 'type' => 'LEFT OUTER' ) ),
					$this->Saisinebilanparcoursep66->Dossierep->Passagecommissionep->join( 'Decisionsaisinebilanparcoursep66', array( 'type' => 'LEFT OUTER' ) ),
					
					array_words_replace(
						$this->Saisinebilanparcoursep66->Dossierep->Passagecommissionep->join(
							'Decisionsaisinebilanparcoursep66',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Decisionsaisinebilanparcoursep66.etape' => 'ep'
								)
							)
						),
						array(
							'Decisionsaisinebilanparcoursep66' => 'Decisionsaisinebilanparcoursep66ep'
						)
					),
					array_words_replace(
						$this->Saisinebilanparcoursep66->Dossierep->Passagecommissionep->join(
							'Decisionsaisinebilanparcoursep66',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Decisionsaisinebilanparcoursep66.etape' => 'cg'
								)
							)
						),
						array(
							'Decisionsaisinebilanparcoursep66' => 'Decisionsaisinebilanparcoursep66cg'
						)
					),
					
					$this->Defautinsertionep66->Dossierep->Passagecommissionep->join( 'Decisiondefautinsertionep66', array( 'type' => 'LEFT OUTER' ) ),
					$this->join( 'Dossierpcg66', array( 'type' => 'LEFT OUTER' ) ),
					$this->Dossierpcg66->join( 'Decisiondossierpcg66', array( 'type' => 'LEFT OUTER' ) ),
					$this->Dossierpcg66->Decisiondossierpcg66->join( 'Decisionpdo', array( 'type' => 'LEFT OUTER' ) )
				);

				// Liste des champs par étpae de décisions pour le passage en EPL Parcours du bilan
				$fieldsDecisionsaisinebilanparcoursep66 = $this->Saisinebilanparcoursep66->Dossierep->Passagecommissionep->Decisionsaisinebilanparcoursep66->fields();
				$fieldsDecisionsaisinebilanparcoursep66ep = array_words_replace(
					$fieldsDecisionsaisinebilanparcoursep66,
					array(
						'Decisionsaisinebilanparcoursep66' => 'Decisionsaisinebilanparcoursep66ep'
					)
				);
				$fieldsDecisionsaisinebilanparcoursep66cg = array_words_replace(
					$fieldsDecisionsaisinebilanparcoursep66,
					array(
						'Decisionsaisinebilanparcoursep66' => 'Decisionsaisinebilanparcoursep66cg'
					)
				);
				
				$querydata = array(
					'fields' => array_merge(
						$this->fields(),
						$this->Orientstruct->fields(),
						$this->Orientstruct->Typeorient->fields(),
						$this->Orientstruct->Structurereferente->fields(),
						$this->Referent->fields(),
						$this->Serviceinstructeur->fields(),
						$this->Defautinsertionep66->fields(),
						$this->Saisinebilanparcoursep66->fields(),
						$this->Saisinebilanparcoursep66->Dossierep->fields(),
						$this->Saisinebilanparcoursep66->Dossierep->Passagecommissionep->fields(),
						$this->Saisinebilanparcoursep66->Dossierep->Passagecommissionep->Commissionep->fields(),
						$fieldsDecisionsaisinebilanparcoursep66,
						$this->Defautinsertionep66->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->fields(),
						$this->Contratinsertion->fields(),
						$this->Personne->fields(),
						$this->Personne->Foyer->fields(),
						$this->Personne->Foyer->Dossier->fields(),
						$this->Personne->Foyer->Adressefoyer->Adresse->fields(),
						$this->Dossierpcg66->fields(),
						$this->Dossierpcg66->Decisiondossierpcg66->fields(),
						$this->Dossierpcg66->Decisiondossierpcg66->Decisionpdo->fields(),
						$fieldsDecisionsaisinebilanparcoursep66ep,
						$fieldsDecisionsaisinebilanparcoursep66cg
					),
					'joins' => $joins,
					'conditions' => $conditions,
					'contain' => false
				);
				
				Cache::write( $cacheKey, $querydata );
			}
			
			$querydata['conditions']['Bilanparcours66.id'] = $id;

			$data = $this->find( 'first', $querydata );

			return $data;
		}

		/**
		*
		*
		*/

		public function getDefaultPdf( $id ) {
			$data = $this->getDataForPdf( $id );
			$modeleodt = $this->modeleOdt( $data );

			$data['Bilanparcours66']['examenaudition_value'] = $data['Bilanparcours66']['examenaudition'];
			$data['Bilanparcours66']['choixparcours_value'] = $data['Bilanparcours66']['choixparcours'];
			// Pour les données de Pôle emploi
			$data['Bilanparcours66']['examenauditionpe_value'] = $data['Bilanparcours66']['examenauditionpe'];

			$Option = ClassRegistry::init( 'Option' );
			$options =  Set::merge(
				array(
					'Personne' => array(
						'qual' => $Option->qual()
					),
					'Adresse' => array(
						'typevoie' => $Option->typevoie()
					)
				),
				$this->enums(),
				$this->Defautinsertionep66->enums(),
				$this->Defautinsertionep66->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->enums(),
				$this->Saisinebilanparcoursep66->enums(),
				$this->Saisinebilanparcoursep66->Dossierep->Passagecommissionep->Decisionsaisinebilanparcoursep66->enums()

			);

			$typeformulaire = Set::classicExtract( $data, 'Bilanparcours66.typeformulaire' );
			$proposition = Set::classicExtract( $data, 'Bilanparcours66.proposition' );
			if( $typeformulaire == 'pe' ) {
				if( $proposition == 'parcourspe' ) {
					$modeleodt = 'Bilanparcours/bilanparcourspe_parcours.odt';
				}
				else {
					$modeleodt = 'Bilanparcours/bilanparcourspe_audition.odt';
				}
			}

			return $this->ged( $data, $modeleodt, false, $options );
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
						'Bilanparcours66.id' => $id
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
		
		
		
		/**
		 * Retourne l'ensemble de données liées au Bilan de parcours en cours
		 *
		 * @param integer $id Id du Bilan de parcours
		 * @return array
		 */
		public function dataView( $bilanparcours66_id ) {

			$Informationpe = ClassRegistry::init( 'Informationpe' );
			// Recherche du bilan pour l'affichage
			$data = $this->find(
				'first',
				array(
					'fields' => array_merge(
						$this->fields(),
						$this->Personne->fields(),
// 						$this->Structurereferente->fields(),
						$this->Referent->fields(),
						$this->Orientstruct->fields(),
						$this->Personne->Foyer->fields(),
						$this->Personne->Prestation->fields(),
						$this->Personne->Foyer->Dossier->fields(),
						$this->Personne->Foyer->Adressefoyer->Adresse->fields(),
						array(
							'NvTypeorient.lib_type_orient',
							'Structurereferente.lib_struc',
							'NvStructurereferente.lib_struc',
							'Serviceinstructeur.lib_service',
							$this->Referent->sqVirtualField( 'nom_complet' ),
							'Historiqueetatpe.identifiantpe',
							'Historiqueetatpe.etat'
						),
						array_words_replace(
							$this->Orientstruct->Typeorient->fields(),
							array(
								'Typeorient' => 'Typeorientorigine'
							)
						),
						array_words_replace(
							$this->Orientstruct->Structurereferente->fields(),
							array(
								'Structurereferente' => 'Structurereferenteorigine'
							)
						),
						array_words_replace(
							$this->Typeorientprincipale->fields(),
							array(
								'Typeorient' => 'Typeorientaccompagnement'
							)
						)
					),
					'conditions' => array(
						'Bilanparcours66.id' => $bilanparcours66_id,
						array(
							'OR' => array(
								'Adressefoyer.id IS NULL',
								'Adressefoyer.id IN ( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
							)
						),
						array(
							'OR' => array(
								'Historiqueetatpe.id IS NULL',
								'Historiqueetatpe.id IN( '.$Informationpe->Historiqueetatpe->sqDernier( 'Informationpe' ).' )'
							)
						)
					),
					'joins' => array(
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->join( 'Orientstruct', array( 'type' => 'INNER' ) ),
						$this->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
						$this->join( 'NvStructurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'NvTypeorient', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Serviceinstructeur', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Referent', array( 'type' => 'INNER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' ),
						$Informationpe->join( 'Historiqueetatpe', array( 'type' => 'LEFT OUTER' ) ),
						array_words_replace(
							$this->Orientstruct->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) ),
							array(
								'Typeorient' => 'Typeorientorigine'
							)
						),
						array_words_replace(
							$this->join( 'Typeorientprincipale', array( 'type' => 'LEFT OUTER' ) ),
							array(
								'Typeorient' => 'Typeorientaccompagnement'
							)
						),
						array_words_replace(
							$this->Orientstruct->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
							array(
								'Structurereferente' => 'Structurereferenteorigine'
							)
						)
					),
					'contain' => false
				)
			);
// debug($data);
			return $data;
		}
		
		/**
		 *	Liste des options envoyées à la vue pour le Bilan de parcours 66
		 * 	@return array
		 */
		public function optionsView() {
			// Options
			$options = array(
				'Prestation' => array(
					'rolepers' => ClassRegistry::init( 'Option' )->rolepers()
				),
				'Personne' => array(
					'qual' => ClassRegistry::init( 'Option' )->qual()
				),
				'Adresse' => array(
					'typevoie' => ClassRegistry::init( 'Option' )->typevoie()
				),
				'Serviceinstructeur' => array(
					'typeserins' => ClassRegistry::init( 'Option' )->typeserins()
				),
				'Foyer' => array(
					'sitfam' => ClassRegistry::init( 'Option' )->sitfam()
				),
				'Structurereferente' => array(
					'type_voie' => ClassRegistry::init( 'Option' )->typevoie()
				),
				'Bilanparcours66' => array(
					'duree_engag' => ClassRegistry::init( 'Option' )->duree_engag()
				),
			);
			$options = Set::merge(
				$this->enums(),
				$options
			);
			return $options;

		}
	}
?>