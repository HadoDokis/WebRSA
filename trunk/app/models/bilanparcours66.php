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
					'referent_id'
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
			'Gedooo',
			'StorablePdf',
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
			)
		);

		public $belongsTo = array(
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'dossierep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
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
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
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
			)
		);


		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			$this->data[$this->alias]['positionbilan'] = $this->_calculPositionBilan( $this->data );

			return $return;
		}


		protected function _calculPositionBilan( $data ){

			$traitement = Set::classicExtract( $data, 'Bilanparcours66.proposition' );
// debug($data);
			$positionbilan = null;
			// 'eplaudit', 'eplparc', 'attcga', 'attct', 'ajourne', 'annule'

			if ( ( $traitement == 'audition' || $traitement == 'auditionpe' ) && empty( $saisineep ) )
				$positionbilan = 'eplaudit';
			elseif ( $traitement == 'parcours' && empty( $saisineep ) )
				$positionbilan = 'eplparc';
// // Une fois le traitement = audition et avis ep émis, alorspaser en attcga
//             elseif( ( $traitement == 'audition' ) && !empty( $saisineep ) && ( $etapedossierep == 'traiterep' ) )
//                 $positionbilan = 'attcga';
//
// // Une fois le traitement = parcours et avis ep émis, alorspaser en attct
//             elseif( ( $traitement == 'parcours' ) &&  !empty( $saisineep ) && ( $etapedossierep == 'traiterep' ) )
//                 $positionbilan = 'attct';

// Si dossier incomplet -> ajourne
//             elseif( ( $traitement == 'parcours' ) && ( !empty( $saisineep ) && ( $etapedossierep == 'traiterep' ) )
//                 $positionbilan = 'ajourne';

// Si bilan annulé -> annule
//                 $positionbilan = 'annule';
			return $positionbilan;

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
			if ( isset( $data['Pe']['Bilanparcours66'] ) && !empty( $data['Pe']['Bilanparcours66'] ) ) {
				$data = $data['Pe'];
				if ( isset( $id ) ) {
					$data['Bilanparcours66']['id'] = $id;
				}
			}

			$data[$this->alias]['saisineepparcours'] = ( @$data[$this->alias]['proposition'] == 'parcours' );
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
								'Contratinsertion.structurereferente_id' => $vxOrientstruct['Orientstruct']['structurereferente_id'],
								//'Contratinsertion.df_ci >=' => date( 'Y-m-d' )
							),
							'contain' => false
						)
					);

					if( empty( $vxContratinsertion ) ) {
						$this->invalidate( 'changementrefsansep', 'Cette personne ne possède aucune contrat d\'insertion validé dans une structure référente liée à celle de sa dernière orientation validée.' );
						return false;
					}
				}

				// Sauvegarde de la nouvelle orientation
				$orientstruct = array(
					'Orientstruct' => array(
						'personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
						'typeorient_id' => $vxOrientstruct['Orientstruct']['typeorient_id'],
						'structurereferente_id' => $vxOrientstruct['Orientstruct']['structurereferente_id'],
						'referent_id' => $vxOrientstruct['Orientstruct']['referent_id'],
						'date_propo' => date( 'Y-m-d' ),
						'date_valid' => date( 'Y-m-d' ),
						'statut_orient' => 'Orienté',
						'user_id' => $vxOrientstruct['Orientstruct']['user_id']
					)
				);
				$this->Orientstruct->create( $orientstruct );
				$success = $this->Orientstruct->save() && $success;

				if( !empty( $this->validationErrors ) ) {
					debug( $this->validationErrors );
				}

				if( !empty( $vxContratinsertion ) ) {
					// Suppression des règles de validation pour la copie du contrat
					$validateContratinsertion = $this->Contratinsertion->validate;
					$this->Contratinsertion->validate = array();

					// Clôture de l'ancien contrat à la date d'aujourd'hui
					$vxContratinsertion['Contratinsertion']['df_ci'] = date( 'Y-m-d' );
					$this->Contratinsertion->create( $vxContratinsertion );
					$success = $this->Contratinsertion->save() && $success;

					// Création du nouveau contrat avec les dates préconisées
					$contratinsertion = $vxContratinsertion;
					unset( $contratinsertion['Contratinsertion']['id'] );
					$contratinsertion['Contratinsertion']['dd_ci'] = $data[$this->alias]['ddreconductoncontrat'];
					$contratinsertion['Contratinsertion']['df_ci'] = $data[$this->alias]['dfreconductoncontrat'];

					/// FIXME: recherche de l'id du type de contrat "Renouvellement" (changer par un enum)
					$idRenouvellement = $this->Contratinsertion->Typocontrat->field( 'Typocontrat.id', array( 'Typocontrat.lib_typo' => 'Renouvellement' ) );

					// Incrémentation rang du contrat et du type de contrat
					$contratinsertion['Contratinsertion']['rg_ci'] = ( $contratinsertion['Contratinsertion']['rg_ci'] + 1 );
					$contratinsertion['Contratinsertion']['typocontrat_id'] = $idRenouvellement;
					$contratinsertion['Contratinsertion']['num_contrat'] = 'REN';

					// La date de validation est à null afin de pouvoir modifier le contrat
					$contratinsertion['Contratinsertion']['datevalidation_ci'] = null;
					// La date de saisie du nouveau contrat est égale à la date du jour
					$contratinsertion['Contratinsertion']['date_saisi_ci'] = date( 'Y-m-d' );

					unset($contratinsertion['Contratinsertion']['decision_ci']);
					unset($contratinsertion['Contratinsertion']['datevalidation_ci']);

					// Sauvegarde du nouveau contrat d'insertion
					$this->Contratinsertion->create( $contratinsertion );
					$success = $this->Contratinsertion->save() && $success;

					// Remise des règles de validation pour la copie du contrat
					$this->Contratinsertion->validate = $validateContratinsertion;


					/*// Recherche du référent -> FIXME: une fonction ?
					$referent_id = $vxOrientstruct['Orientstruct']['referent_id'];

					// Recherche référent lié au CER
					if( empty( $referent_id ) ) {
						$referent_id = $contratinsertion['Contratinsertion']['referent_id'];
					}

					// Recherche du référent lié au parcours
					if( empty( $referent_id ) ) {
						$personneReferent = $this->Contratinsertion->Personne->PersonneReferent->find(
							'first',
							array(
								'conditions' => array(
									'PersonneReferent.personne_id' => $vxOrientstruct['Orientstruct']['personne_id']
								)
							)
						);
						if( !empty( $personneReferent ) ) {
							$referent_id = $personneReferent['PersonneReferent']['referent_id'];
						}
					}

					// Sauvegarde du bilan
					$data[$this->alias]['referent_id'] = $referent_id;//FIXME: si changement  de référent*/

					$data[$this->alias]['contratinsertion_id'] = $vxContratinsertion['Contratinsertion']['id'];
				}

				$this->create( $data );
				$success = $this->save() && $success;
			}
			else {
				debug( $this->validationErrors );
				debug( $data );
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
			if( isset($data['Bilanparcours66']['proposition']) && $data['Bilanparcours66']['proposition'] == 'parcours' ) {
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

						if( empty( $vxContratinsertion ) && ( $data[$this->alias]['choixparcours'] != 'reorientation' ) ) {
								$this->invalidate( 'choixparcours', 'Cette personne ne possède aucun CER validé dans une structure référente liée à celle de sa dernière orientation validée.' );
							return false;
						}

						// Sauvegarde du bilan
						$data[$this->alias]['contratinsertion_id'] = @$vxContratinsertion['Contratinsertion']['id'];
					}

					if( isset( $data[$this->alias]['origine'] ) && $data[$this->alias]['origine'] == 'Defautinsertionep66' && !isset( $data[$this->alias]['structurereferente_id'] ) ) {
						$data[$this->alias]['structurereferente_id'] = $vxOrientstruct['Orientstruct']['structurereferente_id'];
					}

					$this->create( $data );
					$success = $this->save() && $success;

					if( !empty( $this->validationErrors ) ) {
						debug( $this->validationErrors );
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
					$data['Saisinebilanparcoursep66']['choixparcours'] = $data['Bilanparcours66']['choixparcours'];
					if ( isset( $data['Bilanparcours66']['maintienorientparcours'] ) ) {
						$data['Saisinebilanparcoursep66']['maintienorientparcours'] = $data['Bilanparcours66']['maintienorientparcours'];
					}
					if ( isset( $data['Bilanparcours66']['changementrefparcours'] ) ) {
						$data['Saisinebilanparcoursep66']['changementrefparcours'] = $data['Bilanparcours66']['changementrefparcours'];
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
								'Contratinsertion.structurereferente_id' => $vxOrientstruct['Orientstruct']['structurereferente_id']/*,
								'Contratinsertion.df_ci >=' => date( 'Y-m-d' )*/
							),
							'contain' => false
						)
					);

					// FIXME: erreur pas dans choixparcours
					if( $data[$this->alias]['examenaudition'] != 'DOD' && empty( $vxContratinsertion ) ) {
						$this->invalidate( 'examenaudition', 'Cette personne ne possède aucun CER validé dans une structure référente liée à celle de sa dernière orientation validée.' );
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
							$nbPosPasEplAud += floor( $nbrdvsnonvenu / $typerdv['Typerdv']['nbabsaveplaudition'] );
						}

						if ( $nbpassageeplaudition >= $nbPosPasEplAud ) {
							$this->invalidate( 'examenaudition', 'Cette personne ne possède pas assez de rendez-vous où elle ne s\'est pas présentée.' );
							return false;
						}
					}

					// Mise en commentaire car les dates de CER ne doivent pas être bloquantes au niveau du bilan de parcours
					/*if( $data[$this->alias]['examenaudition'] != 'DOD' && empty( $vxContratinsertion ) ) {
						$nbPerimes = $this->Contratinsertion->find(
							'count',
							array(
								'conditions' => array(
									'Contratinsertion.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
									'Contratinsertion.structurereferente_id' => $vxOrientstruct['Orientstruct']['structurereferente_id'],
									'Contratinsertion.df_ci <' => date( 'Y-m-d' )
								),
								'contain' => false
							)
						);
						if( $nbPerimes == 0 ){
							$this->invalidate( 'examenaudition', 'Cette personne ne possède aucun CER validé dans une structure référente liée à celle de sa dernière orientation validée.' );
						}
						else{
							$this->invalidate( 'examenaudition', 'Cette personne possède un CER validé mais dont la date de fin est dépassée.' );
						}
						return false;
					}*/

					// Sauvegarde du bilan
	// 				$data[$this->alias]['referent_id'] = $vxOrientstruct['Orientstruct']['referent_id'];//FIXME: si changement  de référent
					$data[$this->alias]['contratinsertion_id'] = $vxContratinsertion['Contratinsertion']['id'];
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
					$data['Defautinsertionep66']['origine'] = 'bilanparcours';

					$this->Defautinsertionep66->create( $data );
					$success = $this->Defautinsertionep66->save() && $success;
				}
			}
			// Saisine audition pôle emploi
			else if( isset($data['Bilanparcours66']['proposition']) && $data['Bilanparcours66']['proposition'] == 'auditionpe' ) {
				$data[$this->alias]['saisineepparcours'] = '0';
				
				$this->create( $data );
				if( $success = $this->validates() ) {
					$success = $this->save() && $success;
					
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
							'origine' => $data['Bilanparcours66']['examenauditionpe']
						)
					);
					
					if( $data['Bilanparcours66']['examenauditionpe'] == 'radiationpe' ) {
						$queryDataPersonne = $this->Defautinsertionep66->qdRadies( array(), array(), array() );
						$queryDataPersonne['fields'][] = 'Historiqueetatpe.id';
						$queryDataPersonne['conditions']['Personne.id'] = $data['Bilanparcours66']['personne_id'];
						$historiqueetatpe = $this->Defautinsertionep66->Dossierep->Personne->find( 'first', $queryDataPersonne );
						$defautinsertionep66['Defautinsertionep66']['historiqueetatpe_id'] = $historiqueetatpe['Historiqueetatpe']['id'];
					}
					
					$this->Defautinsertionep66->create( $defautinsertionep66 );
					$success = $this->Defautinsertionep66->save() && $success;
				}
			}
			else {
				$success = $this->save($data) && $success;
				debug($this->validationErrors);
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
					'conditions' => array(
						'OR' => array(
							'Personne.id = Orientstruct.personne_id',
							'Personne.id = Contratinsertion.personne_id'
						)
					)
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
	}
?>
