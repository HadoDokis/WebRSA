<?php
	/**
	* Saisines d'EP pour les bilans de parcours pour le conseil général du
	* département 66.
	*
	* Une saisine regoupe plusieurs thèmes des EPs pour le CG 66.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Defautinsertionep66 extends AppModel
	{
		public $name = 'Defautinsertionep66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
// 			'Formattable' => array(
// 				'suffix' => array(
// 					'structurereferente_id'
// 				)
// 			),
			'Enumerable' => array(
				'fields' => array(
					'origine',
					'type'
				)
			)
		);

		public $belongsTo = array(
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
				'foreignKey' => 'bilanparcours66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
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
			'Historiqueetatpe' => array(
				'className' => 'Historiqueetatpe',
				'foreignKey' => 'historiqueetatpe_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Decisiondefautinsertionep66' => array(
				'className' => 'Decisiondefautinsertionep66',
				'foreignKey' => 'defautinsertionep66_id',
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
		*
		*/
		/*public function containQueryData() {
			return array(
				'Defautinsertionep66' => array(
					'Nvsrepreorient66'=>array(
						'Typeorient',
						'Structurereferente'
					),
				)
			);
		}*/


		/**
		* TODO: comment finaliser l'orientation précédente ?
		* FIXME: à ne faire que quand le cg valide sa décision
		*/

		/*public function finaliser( $seanceep_id, $etape ) {
			$dossierseps = $this->find(
				'all',
				array(
					'conditions' => array(
						'Dossierep.seanceep_id' => $seanceep_id
					),
					'contain' => array(
						'Nvsrepreorient66' => array(
							'conditions' => array(
								'Nvsrepreorient66.etape' => $etape
							)
						),
						'Dossierep',
						'Bilanparcours66' => array(
							'Orientstruct',
						)
					)
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $dossierep['Nvsrepreorient66'][0]['decision'] == 'accepte' ) {
					$orientstruct = array(
						'Orientstruct' => array(
							'personne_id' => $dossierep['Bilanparcours66']['Orientstruct']['personne_id'],
							'typeorient_id' => $dossierep['Nvsrepreorient66'][0]['typeorient_id'],
							'structurereferente_id' => $dossierep['Nvsrepreorient66'][0]['structurereferente_id'],
							'date_propo' => date( 'Y-m-d' ),
							'date_valid' => date( 'Y-m-d' ),
							'statut_orient' => 'Orienté',
						)
					);
					$this->Bilanparcours66->Orientstruct->create( $orientstruct );
					$success = $this->Bilanparcours66->Orientstruct->save() && $success;

					$this->Bilanparcours66->Orientstruct->Personne->Contratinsertion->updateAll(
						array( 'Contratinsertion.df_ci' => "'".date( 'Y-m-d' )."'" ),
						array(
							'"Contratinsertion"."personne_id"' => $dossierep['Bilanparcours66']['Orientstruct']['personne_id'],
							'"Contratinsertion"."id"' => $dossierep['Bilanparcours66']['contratinsertion_id']
						)
					);

					// TODO
// 					$this->Bilanparcours66->Orientstruct->Personne->Cui->updateAll(
// 						array( 'Cui.datefincontrat' => "'".date( 'Y-m-d' )."'" ),
// 						array( '"Cui"."personne_id"' => $dossierep['Bilanparcours66']['Orientstruct']['personne_id'] )
// 					);

					$this->Bilanparcours66->Orientstruct->Personne->PersonneReferent->updateAll(
						array( 'PersonneReferent.dfdesignation' => "'".date( 'Y-m-d' )."'" ),
						array(
							'"PersonneReferent"."personne_id"' => $dossierep['Bilanparcours66']['Orientstruct']['personne_id'],
							'"PersonneReferent"."dfdesignation" IS NULL'
						)
					);
				}
			}

			return $success;
		}*/

		/**
		* INFO: Fonction inutile dans cette saisine donc elle retourne simplement true
		*/

		public function verrouiller( $seanceep_id, $etape ) {
			return true;
		}

		/**
		* Querydata permettant d'obtenir les dossiers qui doivent être traités
		* par liste pour la thématique de ce modèle.
		*
		* TODO: une autre liste pour avoir un tableau permettant d'accéder à la fiche
		* TODO: que ceux avec accord, les autres en individuel
		*
		* @param integer $seanceep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut les dossiers à passer par liste.
		* @return array
		* @access public
		*/

		public function qdDossiersParListe( $seanceep_id, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Seanceep->themesTraites( $seanceep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			return array(
				'conditions' => array(
					'Dossierep.themeep' => Inflector::tableize( $this->alias ),
					'Dossierep.seanceep_id' => $seanceep_id,
				),
				'contain' => array(
					'Personne' => array(
						'Foyer' => array(
							'Adressefoyer' => array(
								'conditions' => array(
									'Adressefoyer.rgadr' => '01'
								),
								'Adresse'
							)
						)
					),
					$this->alias => array(
// 						'Typeorient',
// 						'Structurereferente',
						'Bilanparcours66' => array(
// 							'Orientstruct' => array(
// 								'Typeorient',
// 								'Structurereferente',
// 							),
						),
						'Contratinsertion',
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
						),
						'Historiqueetatpe',
						'Decisiondefautinsertionep66'
					)
				)
			);
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			$this->Decisiondefautinsertionep66->begin();

			$success = $this->Decisiondefautinsertionep66->saveAll( Set::extract( $data, '/Decisiondefautinsertionep66' ), array( 'atomic' => false ) );

			$this->Dossierep->updateAll(
				array( 'Dossierep.etapedossierep' => '\'decision'.$niveauDecision.'\'' ),
				array( '"Dossierep"."id"' => Set::extract( $data, '/Defautinsertionep66/dossierep_id' ) )
			);

			if( $success ) {
				$this->Decisiondefautinsertionep66->commit();
			}
			else {
				$this->Decisiondefautinsertionep66->rollback();
			}

			return $success;
		}

		/**
		*
		*/

		public function saveDecisionUnique( $data, $niveauDecision ) {
			return true;
		}

		/**
		* Prépare les données du formulaire d'un niveau de décision
		* en prenant en compte les données du bilan ou du niveau de décision
		* précédent.
		*
		* @param integer $seanceep_id L'id technique de la séance d'EP
		* @param array $datas Les données des dossiers
		* @param string $niveauDecision Le niveau de décision pour lequel il
		* 	faut préparer les données du formulaire
		* @return array
		* @access public
		*/

		public function prepareFormData( $seanceep_id, $datas, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Seanceep->themesTraites( $seanceep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			$formData = array();
			/*if( $niveauDecision == 'ep' ) {
				foreach( $datas as $key => $dossierep ) {
					if (isset($dossierep[$this->alias]['Nvsrepreorient66'][0]['id'])) {
						$formData['Nvsrepreorient66'][$key]['id'] = $dossierep[$this->alias]['Nvsrepreorient66'][0]['id'];
						$formData['Nvsrepreorient66'][$key]['typeorient_id'] = $dossierep[$this->alias]['Nvsrepreorient66'][0]['typeorient_id'];
						$formData['Nvsrepreorient66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Nvsrepreorient66'][0]['typeorient_id'],
								$dossierep[$this->alias]['Nvsrepreorient66'][0]['structurereferente_id']
							)
						);
					}
					else {
						$formData['Nvsrepreorient66'][$key]['typeorient_id'] = $dossierep[$this->alias]['typeorient_id'];
						$formData['Nvsrepreorient66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['typeorient_id'],
								$dossierep[$this->alias]['structurereferente_id']
							)
						);
					}
				}
			}
			else if( $niveauDecision == 'cg' ) {
				foreach( $datas as $key => $dossierep ) {
					if (isset($dossierep[$this->alias]['Nvsrepreorient66'][1]['id'])) {
						$formData['Nvsrepreorient66'][$key]['id'] = $dossierep[$this->alias]['Nvsrepreorient66'][1]['id'];
						$formData['Nvsrepreorient66'][$key]['decision'] = $dossierep[$this->alias]['Nvsrepreorient66'][1]['decision'];
						$formData['Nvsrepreorient66'][$key]['typeorient_id'] = $dossierep[$this->alias]['Nvsrepreorient66'][1]['typeorient_id'];
						$formData['Nvsrepreorient66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Nvsrepreorient66'][1]['typeorient_id'],
								$dossierep[$this->alias]['Nvsrepreorient66'][1]['structurereferente_id']
							)
						);
					}
					else {
						$formData['Nvsrepreorient66'][$key]['decision'] = $dossierep[$this->alias]['Nvsrepreorient66'][0]['decision'];
						$formData['Nvsrepreorient66'][$key]['typeorient_id'] = $dossierep[$this->alias]['Nvsrepreorient66'][0]['typeorient_id'];
						$formData['Nvsrepreorient66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Nvsrepreorient66'][0]['typeorient_id'],
								$dossierep[$this->alias]['Nvsrepreorient66'][0]['structurereferente_id']
							)
						);
					}
				}
			}*/

			return $formData;
		}

		/*public function prepareFormDataUnique( $dossierep_id, $dossierep, $niveauDecision ) {
			$formData = array();
			return $formData;
		}*/

		/**
		* TODO: docs
		*/

		public function finaliser( $seanceep_id, $etape ) {
			$seanceep = $this->Dossierep->Seanceep->find(
				'first',
				array(
					'conditions' => array( 'Seanceep.id' => $seanceep_id ),
					'contain' => array( 'Ep' )
				)
			);

			$niveauDecisionFinale = $seanceep['Ep'][Inflector::underscore( $this->alias )];

			$dossierseps = $this->find(
				'all',
				array(
					'conditions' => array(
						'Dossierep.seanceep_id' => $seanceep_id,
						'Dossierep.themeep' => Inflector::tableize( $this->alias ),//FIXME: ailleurs aussi
					),
					'contain' => array(
						'Decisiondefautinsertionep66' => array(
							'conditions' => array(
								'Decisiondefautinsertionep66.etape' => $etape
							)
						),
						'Dossierep'
					)
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $niveauDecisionFinale == $etape ) {
					$defautinsertionep66 = array( 'Defautinsertionep66' => $dossierep['Defautinsertionep66'] );
					if( !isset( $dossierep['Decisiondefautinsertionep66'][0]['decision'] ) ) {
						$success = false;
					}
					$defautinsertionep66['Defautinsertionep66']['decision'] = @$dossierep['Decisiondefautinsertionep66'][0]['decision'];

					// Si réorientation, alors passage en EP Parcours "Réorientation ou maintien d'orientation"
					if( $defautinsertionep66['Defautinsertionep66']['decision'] == 'reorientationprofverssoc' || $defautinsertionep66['Defautinsertionep66']['decision'] == 'reorientationsocversprof' ) {
						$oBilanparcours66 = ClassRegistry::init( 'Bilanparcours66' );

						$orientsstruct = $oBilanparcours66->Orientstruct->find(
							'first',
							array(
								'fields' => array(
									'Orientstruct.id',
									'Orientstruct.typeorient_id',
									'Orientstruct.structurereferente_id',
									'Orientstruct.referent_id'
								),
								'conditions' => array(
									'Orientstruct.personne_id' => $dossierep['Dossierep']['personne_id'],
									'Orientstruct.statut_orient' => 'Orienté',
									'Orientstruct.date_valid IS NOT NULL',
								),
								'order' => array( 'Orientstruct.date_valid DESC' ),
								'contain' => false
							)
						);
						// FIXME: si on ne trouve pas l'orientation ?

						// FIXME: referent_id -> champ obligatoire
						// FIXME: si la structure ne possède pas de référent ???
						if( empty( $orientsstruct['Orientstruct']['referent_id'] ) ) {
							$referent = $oBilanparcours66->Orientstruct->Referent->find(
								'first',
								array(
									'fields' => array( 'Referent.id' ),
									'conditions' => array(
										'Referent.structurereferente_id' => $orientsstruct['Orientstruct']['structurereferente_id']
									),
									'contain' => false
								)
							);

							$orientsstruct['Orientstruct']['referent_id'] = $referent['Referent']['id'];
						}

						$nvdossierep = array(
							/*'Dossierep' => array(
								'personne_id' => $dossierep['Dossierep']['personne_id'],
								// FIXME: changer le nom du thème (reorientationsmaintiens66) ? 2 sous-thèmes: venant du bilan + veant réorientation suite décision commission audition
								// FIXME: faire une nouvelle thématique
								'themeep' => 'saisinesepsbilansparcours66'
							),*/
							'Bilanparcours66' => array(
								'personne_id' => $dossierep['Dossierep']['personne_id'],
								'typeformulaire' => 'cg',
								'orientstruct_id' => $orientsstruct['Orientstruct']['id'],
								'structurereferente_id' => $orientsstruct['Orientstruct']['structurereferente_id'],// FIXME: ?
								'referent_id' => $orientsstruct['Orientstruct']['referent_id'],// FIXME: ?
								'presenceallocataire' => 1,// FIXME: vient des détails de la séance
								'motifsaisine' => 'Proposition de réorientation suite à un passage en EP pour défaut d\'insertion',
								'proposition' => 'parcours',
								'choixparcours' => 'reorientation',
								'reorientation' => ( $defautinsertionep66['Defautinsertionep66']['decision'] == 'reorientationprofverssoc' ? 'PS' : 'SP' ),
								'observbenef' => '', // TODO, vient des observations lors de la séance
								'datebilan' =>date( 'Y-m-d' ),
								'saisineepparcours' => '1',
								'maintienorientation' => 0
							),
							// FIXME: bilan de parcours arrangé ? nouvelle thématique ?
							'Saisineepbilanparcours66' => array(
								'typeorient_id' => @$dossierep['Decisiondefautinsertionep66'][0]['typeorient_id'],
								'structurereferente_id' => @$dossierep['Decisiondefautinsertionep66'][0]['structurereferente_id'],
							)
						);

						$success = $oBilanparcours66->sauvegardeBilan( $nvdossierep ) && $success;
					}
					/*// TODO Si maintien, alors, RDV référent
					else if( $defautinsertionep66['Defautinsertionep66']['decision'] == 'maintien' ) {
					}*/

					$this->create( $defautinsertionep66 );
					$success = $this->save() && $success;
				}
			}

			return $success;
		}
	}
?>