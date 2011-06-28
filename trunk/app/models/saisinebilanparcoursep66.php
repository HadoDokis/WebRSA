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

	class Saisinebilanparcoursep66 extends AppModel
	{
		public $name = 'Saisinebilanparcoursep66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id'
				)
			),
			'Gedooo',
			'Enumerable' => array(
				'fields' => array(
					'choixparcours',
					'maintienorientparcours',
					'changementrefparcours',
					'reorientation'
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

		public $hasMany = array(
			'Decisionsaisinebilanparcoursep66' => array(
				'className' => 'Decisionsaisinebilanparcoursep66',
				'foreignKey' => 'saisinebilanparcoursep66_id',
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
		public function containQueryData() {
			return array(
				'Saisinebilanparcoursep66' => array(
					'Decisionsaisinebilanparcoursep66'=>array(
						'Typeorient',
						'Structurereferente'
					),
				)
			);
		}


		/**
		* TODO: comment finaliser l'orientation précédente ?
		* FIXME: à ne faire que quand le cg valide sa décision
		*/

		public function finaliser( $commissionep_id, $etape, $user_id ) {
			$dossierseps = $this->Dossierep->find(
				'all',
				array(
					'conditions' => array(
						'Dossierep.themeep' => Inflector::tableize( $this->alias ),
						'Dossierep.id IN ( '.
							$this->Dossierep->Passagecommissionep->sq(
								array(
									'fields' => array(
										'passagescommissionseps.dossierep_id'
									),
									'alias' => 'passagescommissionseps',
									'conditions' => array(
										'passagescommissionseps.commissionep_id' => $commissionep_id
									)
								)
							)
						.' )'
					),
					'contain' => array(
						$this->alias => array(
							'Bilanparcours66' => array(
								'Orientstruct'
							)
						),
						'Passagecommissionep' => array(
							'conditions' => array(
								'Passagecommissionep.commissionep_id' => $commissionep_id
							),
							'Decisionsaisinebilanparcoursep66' => array(
								'conditions' => array(
									'Decisionsaisinebilanparcoursep66.etape' => $etape
								)
							)
						)
					)
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['decision'] == 'accepte' ) {
					$orientstruct = array(
						'Orientstruct' => array(
							'personne_id' => $dossierep[$this->alias]['Bilanparcours66']['Orientstruct']['personne_id'],
							'typeorient_id' => $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['typeorient_id'],
							'structurereferente_id' => $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['structurereferente_id'],
							'date_propo' => date( 'Y-m-d' ),
							'date_valid' => date( 'Y-m-d' ),
							'statut_orient' => 'Orienté',
							'user_id' => $user_id
						)
					);
					$this->Bilanparcours66->Orientstruct->create( $orientstruct );
					$success = $this->Bilanparcours66->Orientstruct->save() && $success;

					if( !empty( $dossierep['Bilanparcours66']['contratinsertion_id'] ) ) {
						$this->Bilanparcours66->Orientstruct->Personne->Contratinsertion->updateAll(
							array( 'Contratinsertion.df_ci' => "'".date( 'Y-m-d' )."'" ),
							array(
								'"Contratinsertion"."personne_id"' => $dossierep['Bilanparcours66']['Orientstruct']['personne_id'],
								'"Contratinsertion"."id"' => $dossierep['Bilanparcours66']['contratinsertion_id']
							)
						);
					}

					// TODO
					/*$this->Bilanparcours66->Orientstruct->Personne->Cui->updateAll(
						array( 'Cui.datefincontrat' => "'".date( 'Y-m-d' )."'" ),
						array( '"Cui"."personne_id"' => $dossierep['Bilanparcours66']['Orientstruct']['personne_id'] )
					);*/

					$this->Bilanparcours66->Orientstruct->Personne->PersonneReferent->updateAll(
						array( 'PersonneReferent.dfdesignation' => "'".date( 'Y-m-d' )."'" ),
						array(
							'"PersonneReferent"."personne_id"' => $dossierep[$this->alias]['Bilanparcours66']['Orientstruct']['personne_id'],
							'"PersonneReferent"."dfdesignation" IS NULL'
						)
					);

					// Enregistrement de la position du bilan de parcours suite au passage en EP
//                  $this->Bilanparcours66->updateAll(
//                         array( 'Bilanparcours66.positionbilan' => '\'attcga\'' ),
//                         array( '"Bilanparcours66"."id"' => $dossierep['Bilanparcours66']['id'] )
//                     );
				}
			}

			return $success;
		}

		/**
		* INFO: Fonction inutile dans cette saisine donc elle retourne simplement true
		*/

		public function verrouiller( $commissionep_id, $etape ) {
			return true;
		}

		/**
		* Querydata permettant d'obtenir les dossiers qui doivent être traités
		* par liste pour la thématique de ce modèle.
		*
		* TODO: une autre liste pour avoir un tableau permettant d'accéder à la fiche
		* TODO: que ceux avec accord, les autres en individuel
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut les dossiers à passer par liste.
		* @return array
		* @access public
		*/

		public function qdDossiersParListe( $commissionep_id, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			return array(
				'conditions' => array(
					'Dossierep.themeep' => Inflector::tableize( $this->alias ),
					'Dossierep.id IN ( '.
						$this->Dossierep->Passagecommissionep->sq(
							array(
								'fields' => array(
									'passagescommissionseps.dossierep_id'
								),
								'alias' => 'passagescommissionseps',
								'conditions' => array(
									'passagescommissionseps.commissionep_id' => $commissionep_id
								)
							)
						)
					.' )'
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
						'Typeorient',
						'Structurereferente',
						'Bilanparcours66' => array(
							'Orientstruct' => array(
								'Typeorient',
								'Structurereferente',
							),
						)
					),
					'Passagecommissionep' => array(
						'Decisionsaisinebilanparcoursep66' => array(
							'order' => array(
								'Decisionsaisinebilanparcoursep66.etape DESC'
							),
							'Typeorient',
							'Structurereferente'
						),
					)
				)
			);
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			// FIXME: filtrer les données
			$themeData = Set::extract( $data, '/Decisionsaisinebilanparcoursep66' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				$success = $this->Decisionsaisinebilanparcoursep66->saveAll( $themeData, array( 'atomic' => false ) );

				$this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => Set::extract( $data, '/Decisionsaisinebilanparcoursep66/passagecommissionep_id' ) )
				);

				return $success;
			}
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
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param array $datas Les données des dossiers
		* @param string $niveauDecision Le niveau de décision pour lequel il
		* 	faut préparer les données du formulaire
		* @return array
		* @access public
		*/

		public function prepareFormData( $commissionep_id, $datas, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			$formData = array();
			foreach( $datas as $key => $dossierep ) {
				$formData['Decisionsaisinebilanparcoursep66'][$key]['passagecommissionep_id'] = @$datas[$key]['Passagecommissionep'][0]['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['etape'] == $niveauDecision ) {
					$formData['Decisionsaisinebilanparcoursep66'][$key] = @$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0];
					$formData['Decisionsaisinebilanparcoursep66'][$key]['checkcomm'] = !empty( $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['commentaire'] );
					$formData['Decisionsaisinebilanparcoursep66'][$key]['referent_id'] = implode(
						'_',
						array(
							$formData['Decisionsaisinebilanparcoursep66'][$key]['structurereferente_id'],
							$formData['Decisionsaisinebilanparcoursep66'][$key]['referent_id']
						)
					);
					$formData['Decisionsaisinebilanparcoursep66'][$key]['structurereferente_id'] = implode(
						'_',
						array(
							$formData['Decisionsaisinebilanparcoursep66'][$key]['typeorient_id'],
							$formData['Decisionsaisinebilanparcoursep66'][$key]['structurereferente_id']
						)
					);
				}
				// On ajoute les enregistrements de cette étape
				else {
					if( $niveauDecision == 'ep' ) {
						$formData['Decisionsaisinebilanparcoursep66'][$key]['decision'] = $dossierep[$this->alias]['choixparcours'];
						$formData['Decisionsaisinebilanparcoursep66'][$key]['reorientation'] = $dossierep[$this->alias]['reorientation'];
						$formData['Decisionsaisinebilanparcoursep66'][$key]['checkcomm'] = 0;
						if ( $dossierep[$this->alias]['Bilanparcours66']['choixparcours'] == 'maintien' ) {
							$formData['Decisionsaisinebilanparcoursep66'][$key]['typeorient_id'] = $dossierep[$this->alias]['Bilanparcours66']['Orientstruct']['typeorient_id'];
							$formData['Decisionsaisinebilanparcoursep66'][$key]['structurereferente_id'] = implode(
								'_',
								array(
									$dossierep[$this->alias]['Bilanparcours66']['Orientstruct']['typeorient_id'],
									$dossierep[$this->alias]['Bilanparcours66']['Orientstruct']['structurereferente_id']
								)
							);
							if ( $dossierep[$this->alias]['Bilanparcours66']['changereferent'] == 'O' ) {
								$formData['Decisionsaisinebilanparcoursep66'][$key]['referent_id'] = implode(
									'_',
									array(
										$dossierep[$this->alias]['Bilanparcours66']['Orientstruct']['structurereferente_id'],
										$dossierep[$this->alias]['Bilanparcours66']['Orientstruct']['referent_id']
									)
								);
							}
						}
						else {
							$formData['Decisionsaisinebilanparcoursep66'][$key]['typeorient_id'] = $dossierep[$this->alias]['typeorient_id'];
							$formData['Decisionsaisinebilanparcoursep66'][$key]['structurereferente_id'] = implode(
								'_',
								array(
									$dossierep[$this->alias]['typeorient_id'],
									$dossierep[$this->alias]['structurereferente_id']
								)
							);
						}
					}
					elseif( $niveauDecision == 'cg' ) {
						$formData['Decisionsaisinebilanparcoursep66'][$key]['decision'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['decision'];
						$formData['Decisionsaisinebilanparcoursep66'][$key]['reorientation'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['reorientation'];
						$formData['Decisionsaisinebilanparcoursep66'][$key]['maintienorientparcours'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['maintienorientparcours'];
						$formData['Decisionsaisinebilanparcoursep66'][$key]['changementrefparcours'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['changementrefparcours'];
// 						$formData['Decisionsaisinebilanparcoursep66'][$key]['raisonnonpassage'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['raisonnonpassage'];
						$formData['Decisionsaisinebilanparcoursep66'][$key]['commentaire'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['commentaire'];
						$formData['Decisionsaisinebilanparcoursep66'][$key]['checkcomm'] = !empty( $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['commentaire'] );
						$formData['Decisionsaisinebilanparcoursep66'][$key]['typeorient_id'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['typeorient_id'];
						$formData['Decisionsaisinebilanparcoursep66'][$key]['referent_id'] = implode(
							'_',
							array(
								$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['structurereferente_id'],
								$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['referent_id']
							)
						);
						$formData['Decisionsaisinebilanparcoursep66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['typeorient_id'],
								$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['structurereferente_id']
							)
						);
					}
				}
			}

			return $formData;
		}

		/**
		*
		*/

		public function prepareFormDataUnique( $dossierep_id, $dossierep, $niveauDecision ) {
			$formData = array();
			return $formData;
		}

		/**
		*
		*/

		public function qdProcesVerbal() {
			return array(
				'fields' => array(
					'Saisinebilanparcoursep66.id',
					'Saisinebilanparcoursep66.bilanparcours66_id',
					'Saisinebilanparcoursep66.dossierep_id',
					'Saisinebilanparcoursep66.typeorient_id',
					'Saisinebilanparcoursep66.structurereferente_id',
					'Saisinebilanparcoursep66.created',
					'Saisinebilanparcoursep66.modified',
					//
					'Decisionsaisinebilanparcoursep66.id',
// 					'Decisionsaisinebilanparcoursep66.saisinebilanparcoursep66_id',
					'Decisionsaisinebilanparcoursep66.etape',
					'Decisionsaisinebilanparcoursep66.decision',
					'Decisionsaisinebilanparcoursep66.typeorient_id',
					'Decisionsaisinebilanparcoursep66.structurereferente_id',
					'Decisionsaisinebilanparcoursep66.commentaire',
					'Decisionsaisinebilanparcoursep66.created',
					'Decisionsaisinebilanparcoursep66.modified',
					'Decisionsaisinebilanparcoursep66.raisonnonpassage',
				),
				'joins' => array(
					array(
						'table'      => 'saisinesbilansparcourseps66',
						'alias'      => 'Saisinebilanparcoursep66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Saisinebilanparcoursep66.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'decisionssaisinesbilansparcourseps66',
						'alias'      => 'Decisionsaisinebilanparcoursep66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionsaisinebilanparcoursep66.passagecommissionep_id = Passagecommissionep.id',
							'Decisionsaisinebilanparcoursep66.etape' => 'ep'
						),
					),
				)
			);
		}

		/**
		* Récupération du courrier de convocation à l'allocataire pour un passage
		* en commission donné.
		* FIXME: spécifique par thématique
		*/

		public function getConvocationBeneficiaireEpPdf( $passagecommissionep_id ) {
			$gedooo_data = $this->Dossierep->Passagecommissionep->find(
				'first',
				array(
					'conditions' => array( 'Passagecommissionep.id' => $passagecommissionep_id ),
					'contain' => array(
						'Dossierep' => array(
							'Personne',
						),
						'Commissionep'
					)
				)
			);

			return $this->ged( $gedooo_data, "Commissionep/convocationep_beneficiaire.odt" );
		}

		/**
		* Fonction retournant un querydata qui va permettre de retrouver des dossiers d'EP
		*/
		public function qdListeDossier( $commissionep_id = null ) {
			$return = array(
				'fields' => array(
					'Dossierep.id',
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.locaadr',
					'Dossierep.created',
					'Dossierep.themeep',
					'Passagecommissionep.id',
					'Passagecommissionep.commissionep_id',
					'Passagecommissionep.etatdossierep',
				)
			);

			if( !empty( $commissionep_id ) ) {
				$join = array(
					'alias' => 'Dossierep',
					'table' => 'dossierseps',
					'type' => 'INNER',
					'conditions' => array(
						'Dossierep.id = '.$this->alias.'.dossierep_id'
					)
				);
			}
			else {
				$join = array(
					'alias' => $this->alias,
					'table' => Inflector::tableize( $this->alias ),
					'type' => 'INNER',
					'conditions' => array(
						'Dossierep.id = '.$this->alias.'.dossierep_id'
					)
				);
			}

			$return['joins'] = array(
				$join,
				array(
					'alias' => 'Personne',
					'table' => 'personnes',
					'type' => 'INNER',
					'conditions' => array(
						'Dossierep.personne_id = Personne.id'
					)
				),
				array(
					'alias' => 'Foyer',
					'table' => 'foyers',
					'type' => 'INNER',
					'conditions' => array(
						'Personne.foyer_id = Foyer.id'
					)
				),
				array(
					'alias' => 'Dossier',
					'table' => 'dossiers',
					'type' => 'INNER',
					'conditions' => array(
						'Foyer.dossier_id = Dossier.id'
					)
				),
				array(
					'alias' => 'Adressefoyer',
					'table' => 'adressesfoyers',
					'type' => 'INNER',
					'conditions' => array(
						'Adressefoyer.foyer_id = Foyer.id',
						'Adressefoyer.rgadr' => '01'
					)
				),
				array(
					'alias' => 'Adresse',
					'table' => 'adresses',
					'type' => 'INNER',
					'conditions' => array(
						'Adressefoyer.adresse_id = Adresse.id'
					)
				),
				array(
					'alias' => 'Passagecommissionep',
					'table' => 'passagescommissionseps',
					'type' => 'LEFT OUTER',
					'conditions' => Set::merge(
						array( 'Passagecommissionep.dossierep_id = Dossierep.id' ),
						empty( $commissionep_id ) ? array() : array(
							'OR' => array(
								'Passagecommissionep.commissionep_id IS NULL',
								'Passagecommissionep.commissionep_id' => $commissionep_id
							)
						)
					)
				)
			);

			return $return;
		}

		/**
		* Récupération de la décision suite au passage en commission d'un dossier
		* d'EP pour un certain niveau de décision.
		*/

		public function getDecisionPdf( $passagecommissionep_id  ) {
			$gedooo_data = $this->Dossierep->Passagecommissionep->find(
				'first',
				array(
					'conditions' => array( 'Passagecommissionep.id' => $passagecommissionep_id ),
					'contain' => array(
						'Commissionep',
						'Dossierep' => array(
							'Personne' => array(
								'Foyer' => array(
									'Adressefoyer' => array(
										'conditions' => array(
											'Adressefoyer.id IN ( '.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01( 'Adressefoyer.foyer_id' ).' )'
										),
										'Adresse'
									)
								)
							),
							$this->alias => array(
								'Typeorient',
								'Structurereferente',
								'Bilanparcours66' => array(
									'Orientstruct' => array(
										'Typeorient',
										'Structurereferente',
									),
								)
							),
						),
						'Decisionsaisinebilanparcoursep66' => array(
							'Typeorient',
							'Structurereferente',
							'Referent',
							'order' => array(
								'Decisionsaisinebilanparcoursep66.etape DESC'
							),
							'limit' => 1
						)
					)
				)
			);

			if( empty( $gedooo_data ) || !isset( $gedooo_data['Decisionsaisinebilanparcoursep66'][0] ) || empty( $gedooo_data['Decisionsaisinebilanparcoursep66'][0] ) ) {
				return false;
			}

			// Choix du modèle de document
			$decision = $gedooo_data['Decisionsaisinebilanparcoursep66'][0]['decision']; // maintien,reorientation,annule,reporte

			if( $decision == 'maintien' ) {
				if( $gedooo_data['Decisionsaisinebilanparcoursep66'][0]['changementrefparcours'] == 'O' ) {
					$modeleOdt  = "{$this->alias}/decision_maintien_avec_changement.odt";
				}
				else if( $gedooo_data['Decisionsaisinebilanparcoursep66'][0]['changementrefparcours'] == 'N' ) {
					$modeleOdt  = "{$this->alias}/decision_maintien_sans_changement.odt";
				}
			}
			else if( $decision == 'reorientation' ) {
				$modeleOdt  = "{$this->alias}/decision_reorientation.odt";
			}
			else if( $decision == 'reporte' ) {
				$modeleOdt  = "{$this->alias}/decision_reporte.odt";
			}
			else if( $decision == 'annule' ) {
				$modeleOdt  = "{$this->alias}/decision_annule.odt";
			}

			// Possède-t'on un PDF déjà stocké ?
			$pdfModel = ClassRegistry::init( 'Pdf' );
			$pdf = $pdfModel->find(
				'first',
				array(
					'conditions' => array(
						'modele' => 'Passagecommissionep',
						'modeledoc' => $modeleOdt,
						'fk_value' => $passagecommissionep_id
					)
				)
			);

			if( !empty( $pdf ) && empty( $pdf['Pdf']['document'] ) ) {
				$cmisPdf = Cmis::read( "/Passagecommissionep/{$passagecommissionep_id}.pdf", true );
				$pdf['Pdf']['document'] = $cmisPdf['content'];
			}

			if( !empty( $pdf['Pdf']['document'] ) ) {
				return $pdf['Pdf']['document'];
			}

			// Traductions
			$options = $this->Dossierep->Passagecommissionep->Decisionsaisinebilanparcoursep66->enums();
			$options['Personne']['qual'] = ClassRegistry::init( 'Option' )->qual();
			$options['Adresse']['typevoie'] = ClassRegistry::init( 'Option' )->typevoie();
			// INFO: c'est tricher, mais ça permet de traduire tous les Structurereferente.type_voie
			$options['type']['voie'] = $options['Adresse']['typevoie'];

			// Sinon, on génère le PDF
			$pdf =  $this->ged(
				$gedooo_data,
				$modeleOdt,
				false,
				$options
			);

			$oldRecord['Pdf']['modele'] = 'Passagecommissionep';
			$oldRecord['Pdf']['modeledoc'] = $modeleOdt;
			$oldRecord['Pdf']['fk_value'] = $passagecommissionep_id;
			$oldRecord['Pdf']['document'] = $pdf;

			$pdfModel->create( $oldRecord );
			$success = $pdfModel->save();

			if( !$success ) {
				return false;
			}

			return $pdf;
		}
	}
?>
