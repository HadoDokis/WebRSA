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
			'Saisineepbilanparcours66' => array(
				'className' => 'Saisineepbilanparcours66',
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
		);

		/**
		* Sauvegarde du bilan de parcours d'un allocataire.
		*
		* Le bilan de parcours entraîne:
		*	- pour le thème réorientation/saisinesepsbilansparcours66
		*		* soit un maintien de l'orientation, sans passage en EP
		*		* soit une saisine de l'EP locale, commission parcours
		*
		* @param array $data Les données du bilan à sauvegarder.
		* @return boolean True en cas de succès, false sinon.
		* @access public
		*/

		public function sauvegardeBilan( $data ) {
			$data[$this->alias]['saisineepparcours'] = !$data[$this->alias]['maintienorientation'];
			// Recondution du contrat
			if( !empty( $data[$this->alias]['maintienorientation'] ) ) {
				$cleanedData = $data;
				unset( $cleanedData['Saisineepbilanparcours66'] );
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
						)
					)
				);

				if( empty( $vxOrientstruct ) ) {
					debug( 'Vieille orientation répondant aux critères non trouvé.' );
					return false;
				}

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
					debug( 'Vieux contrat d\'insertion répondant aux critères non trouvé.' );
					return false;
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
					)
				);
				$this->Orientstruct->create( $orientstruct );
				$success = $this->Orientstruct->save() && $success;

				if( !empty( $this->validationErrors ) ) {
					debug( $this->validationErrors );
				}

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
		* FIXME: modification du bilan
		* TODO: comment finaliser l'orientation précédente ?
		* TODO: pouvoir envoyer la cause d'échec (ex.: $vxContratinsertion non trouvé avec ces critères)
		*/

		public function saisine( $data ) {
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

				if( empty( $vxOrientstruct ) ) {
					debug( 'Vieille orientation répondant aux critères non trouvée.' );
					return false;
				}

				$vxContratinsertion = $this->Contratinsertion->find(
					'first',
					array(
						'conditions' => array(
							'Contratinsertion.personne_id' => $vxOrientstruct['Orientstruct']['personne_id'],
							'Contratinsertion.structurereferente_id' => $vxOrientstruct['Orientstruct']['structurereferente_id'],
							'Contratinsertion.df_ci >=' => date( 'Y-m-d' )
						),
						'contain' => false
					)
				);

				if( empty( $vxContratinsertion ) ) {
					debug( 'Vieux contrat d\'insertion répondant aux critères non trouvé.' );
					return false;
				}

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
						'themeep' => 'saisinesepsbilansparcours66'
					)
				);
				$this->Saisineepbilanparcours66->Dossierep->create( $dataDossierEp );
				$success = $this->Saisineepbilanparcours66->Dossierep->save() && $success;

				// Sauvegarde de la saisine
				$data['Saisineepbilanparcours66']['bilanparcours66_id'] = $this->id;
				$data['Saisineepbilanparcours66']['dossierep_id'] = $this->Saisineepbilanparcours66->Dossierep->id;
				$this->Saisineepbilanparcours66->create( $data );
				$success = $this->Saisineepbilanparcours66->save() && $success;
			}

			return $success;
		}
	}
?>
