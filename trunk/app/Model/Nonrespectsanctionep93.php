<?php
	/**
	 * ...
	 *
	 * PHP versions 5
	 *
	 * @package       app
	 * @subpackage    app.app.models
	 */
	require_once( ABSTRACTMODELS.'Thematiqueep.php' );
	class Nonrespectsanctionep93 extends Thematiqueep
	{

		public $name = 'Nonrespectsanctionep93';
		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'origine',
					'decision' => array( 'domain' => 'decisionnonrespectsanctionep93' ),
				)
			),
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Gedooo.Gedooo',
			'Conditionnable'
		);
		public $belongsTo = array(
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'dossierep_id',
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
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
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
			'Relancenonrespectsanctionep93' => array(
				'className' => 'Relancenonrespectsanctionep93',
				'foreignKey' => 'nonrespectsanctionep93_id',
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
		 * Chemin relatif pour les modèles de documents .odt utilisés lors des
		 * impressions. Utiliser %s pour remplacer par l'alias.
		 */
		public $modelesOdt = array(
			// Convocation EP
			'%s/convocationep_beneficiaire_radiepe.odt',
			'%s/convocationep_beneficiaire_1er_passage.odt',
			'%s/convocationep_beneficiaire_2eme_passage_suite_delai.odt',
			'%s/convocationep_beneficiaire_2eme_passage_suite_report.odt',
			'%s/convocationep_beneficiaire_2eme_passage_ppae_suite_reduction.odt',
			'%s/convocationep_beneficiaire_2eme_passage.odt',
			// Décision EP (décision CG)
			'%s/decision_delai.odt',
			'%s/decision_reduction_ppae.odt',
			'%s/decision_reduction_pdv.odt',
			'%s/decision_maintien.odt',
			'%s/decision_suspensiontotale.odt',
			'%s/decision_suspensionpartielle.odt',
			'%s/decision_reporte.odt',
			'%s/decision_annule.odt',
		);

		/**
		 * Querydata permettant d'obtenir les dossiers qui doivent être traités
		 * par liste pour la thématique de ce modèle.
		 *
		 * TODO: une autre liste pour avoir un tableau permettant d'accéder à la fiche
		 * TODO: que ceux avec accord, les autres en individuel
		 *
		 * @param integer $commissionep_id L'id technique de la séance d'EP
		 * @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		 * 	lequel il faut les dossiers à passer par liste.
		 * @return array
		 * @access public
		 */
		public function qdDossiersParListe( $commissionep_id, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$niveauFinal = $themes[Inflector::underscore( $this->alias )];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array( );
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
							'fields' => array(
								'id',
								'dossier_id',
								'sitfam',
								'ddsitfam',
								'typeocclog',
								'mtvallocterr',
								'mtvalloclog',
								'contefichliairsa',
								'mtestrsa',
								'raisoctieelectdom',
								"( SELECT COUNT(DISTINCT(personnes.id)) FROM personnes INNER JOIN prestations ON ( personnes.id = prestations.personne_id ) WHERE personnes.foyer_id = \"Foyer\".\"id\" AND prestations.natprest = 'RSA' AND prestations.rolepers = 'ENF' ) AS \"Foyer__nbenfants\"",
							),
							'Adressefoyer' => array(
								'conditions' => array(
									'Adressefoyer.rgadr' => '01'
								),
								'Adresse'
							)
						)
					),
					$this->alias => array(
						'fields' => array(
							'id',
							'dossierep_id',
							'propopdo_id',
							'orientstruct_id',
							'contratinsertion_id',
							'origine',
							'rgpassage',
							'active',
							'created',
							'modified'
						),
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
						)
					),
					'Passagecommissionep' => array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id
						),
						'Decisionnonrespectsanctionep93' => array(
							'order' => array( 'Decisionnonrespectsanctionep93.etape DESC' ),
						)
					)
				)
			);
		}

		/**
		 * FIXME
		 *
		 * @param integer $commissionep_id L'id technique de la séance d'EP
		 * @param array $datas Les données des dossiers
		 * @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		 * 	lequel il faut préparer les données du formulaire
		 * @return array
		 * @access public
		 */
		public function prepareFormData( $commissionep_id, $datas, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$niveauFinal = $themes[Inflector::underscore( $this->alias )];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array( );
			}

			$formData = array( );
			foreach( $datas as $key => $dossierep ) {
				$formData[$this->alias][$key]['id'] = @$datas[$key][$this->alias]['id'];
				$formData[$this->alias][$key]['dossierep_id'] = @$datas[$key][$this->alias]['dossierep_id'];
				$formData['Decisionnonrespectsanctionep93'][$key]['passagecommissionep_id'] = @$datas[$key]['Passagecommissionep'][0]['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0]['etape'] == $niveauDecision ) {
					$formData['Decisionnonrespectsanctionep93'][$key] = @$dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0];
				}
				// On ajoute les enregistrements de cette étape
				else if( $niveauDecision == 'cg' ) {
					$formData['Decisionnonrespectsanctionep93'][$key]['decision'] = $dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0]['decision'];
					$formData['Decisionnonrespectsanctionep93'][$key]['decisionpcg'] = 'valide';
					$formData['Decisionnonrespectsanctionep93'][$key]['raisonnonpassage'] = $dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0]['raisonnonpassage'];
				}
			}

			return $formData;
		}

		/**
		 * TODO: docs
		 */
		public function saveDecisions( $data, $niveauDecision ) {
			// FIXME: filtrer les données
			$themeData = Set::extract( $data, '/Decisionnonrespectsanctionep93' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				foreach( array_keys( $themeData ) as $key ) {
					// On complètre /on nettoie si ce n'est pas envoyé par le formulaire
					if( $themeData[$key]['Decisionnonrespectsanctionep93']['decision'] == '1reduction' ) {
						$themeData[$key]['Decisionnonrespectsanctionep93']['dureesursis'] = null;
						$themeData[$key]['Decisionnonrespectsanctionep93']['montantreduction'] = Configure::read( 'Nonrespectsanctionep93.montantReduction' );
					}
					else if( $themeData[$key]['Decisionnonrespectsanctionep93']['decision'] == '1delai' ) {
						$themeData[$key]['Decisionnonrespectsanctionep93']['montantreduction'] = null;
						$themeData[$key]['Decisionnonrespectsanctionep93']['dureesursis'] = Configure::read( 'Nonrespectsanctionep93.dureeSursis' );
					}
					else if( in_array( $themeData[$key]['Decisionnonrespectsanctionep93']['decision'], array( '1maintien', '1pasavis', '2pasavis', 'reporte', 'annule' ) ) ) {
						$themeData[$key]['Decisionnonrespectsanctionep93']['montantreduction'] = null;
						$themeData[$key]['Decisionnonrespectsanctionep93']['dureesursis'] = null;
					}
					// FIXME: la même chose pour l'étape 2
				}

				$success = $this->Dossierep->Passagecommissionep->Decisionnonrespectsanctionep93->saveAll( $themeData, array( 'atomic' => false ) );
				$this->Dossierep->Passagecommissionep->updateAll(
						array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ), array( '"Passagecommissionep"."id"' => Set::extract( $data, '/Decisionnonrespectsanctionep93/passagecommissionep_id' ) )
				);

				return $success;
			}
		}

		/**
		 * TODO: docs
		 */
		public function finaliser( $commissionep_id, $etape, $user_id ) {
			$commissionep = $this->Dossierep->Passagecommissionep->Commissionep->find(
					'first', array(
				'conditions' => array( 'Commissionep.id' => $commissionep_id ),
				'contain' => array(
					'Ep' => array(
						'Regroupementep'
					)
				)
					)
			);

			$niveauDecisionFinale = $commissionep['Ep']['Regroupementep'][Inflector::underscore( $this->alias )];

			$dossierseps = $this->Dossierep->Passagecommissionep->find(
					'all', array(
				'fields' => array(
					'Passagecommissionep.id',
					'Passagecommissionep.commissionep_id',
					'Passagecommissionep.dossierep_id',
					'Passagecommissionep.etatdossierep',
					'Dossierep.personne_id',
					'Decisionnonrespectsanctionep93.decision'
				),
				'conditions' => array(
					'Passagecommissionep.commissionep_id' => $commissionep_id
				),
				'joins' => array(
					array(
						'table' => 'dossierseps',
						'alias' => 'Dossierep',
						'type' => 'INNER',
						'conditions' => array(
							'Passagecommissionep.dossierep_id = Dossierep.id'
						)
					),
					array(
						'table' => 'nonrespectssanctionseps93',
						'alias' => 'Nonrespectsanctionep93',
						'type' => 'INNER',
						'conditions' => array(
							'Nonrespectsanctionep93.dossierep_id = Dossierep.id'
						)
					),
					array(
						'table' => 'decisionsnonrespectssanctionseps93',
						'alias' => 'Decisionnonrespectsanctionep93',
						'type' => 'INNER',
						'conditions' => array(
							'Decisionnonrespectsanctionep93.passagecommissionep_id = Passagecommissionep.id',
							'Decisionnonrespectsanctionep93.etape' => $etape
						)
					)
				)
					)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $niveauDecisionFinale == $etape ) {
					$nonrespectsanctionep93 = array( 'Nonrespectsanctionep93' => $dossierep['Nonrespectsanctionep93'] );
					$nonrespectsanctionep93['Nonrespectsanctionep93']['active'] = 0;
					if( !isset( $dossierep['Decisionnonrespectsanctionep93'][0]['decision'] ) ) {
						$success = false;
					}

					$this->create( $nonrespectsanctionep93 ); // TODO: un saveAll ?
					$success = $this->save() && $success;
				}
			}
			return $success;
		}

		/**
		 *
		 */
		public function containPourPv() {
			return array(
				'Nonrespectsanctionep93' => array(
					'Decisionnonrespectsanctionep93' => array(
						'conditions' => array(
							'etape' => 'ep'
						)
					)
				)
			);
		}

		/**
		 * Retourne une partie de querydata concernant la thématique pour le PV d'EP.
		 *
		 * @return array
		 */
		public function qdProcesVerbal() {
			$querydata = array(
				'fields' => array_merge(
						$this->fields(), $this->Dossierep->Passagecommissionep->Decisionnonrespectsanctionep93->fields()
				),
				'joins' => array(
					array(
						'table' => 'nonrespectssanctionseps93',
						'alias' => 'Nonrespectsanctionep93',
						'type' => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Nonrespectsanctionep93.dossierep_id = Dossierep.id' ),
					),
					array(
						'table' => 'decisionsnonrespectssanctionseps93',
						'alias' => 'Decisionnonrespectsanctionep93',
						'type' => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionnonrespectsanctionep93.passagecommissionep_id = Passagecommissionep.id',
							'Decisionnonrespectsanctionep93.etape' => 'ep'
						)
					)
				)
			);

			return $querydata;
		}

		/**
		 *
		 */
		public function qdRadies( $datas, $mesCodesInsee, $filtre_zone_geo ) {
			$queryData = array(
				'fields' => array(
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Foyer.enerreur',
					'Adresse.locaadr',
					'Orientstruct.date_valid',
					'Typeorient.lib_type_orient',
					'Contratinsertion.present',
				),
				'contain' => false,
				'joins' => array(
					array(
						'table' => 'prestations', // FIXME:
						'alias' => 'Prestation',
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest' => 'RSA',
							'Prestation.rolepers' => array( 'DEM', 'CJT' ),
						)
					),
					array(
						'table' => 'foyers',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table' => 'adressesfoyers',
						'alias' => 'Adressefoyer',
						'type' => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							// FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
							'Adressefoyer.id IN (
								'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01( 'Adressefoyer.foyer_id' ).'
							)'
						)
					),
					array(
						'table' => 'adresses',
						'alias' => 'Adresse',
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table' => 'dossiers',
						'alias' => 'Dossier',
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					),
					array(
						'table' => 'situationsdossiersrsa',
						'alias' => 'Situationdossierrsa',
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Situationdossierrsa.dossier_id = Dossier.id',
							'Situationdossierrsa.etatdosrsa' => array( 'Z', '2', '3', '4' )
						)
					),
					array(
						'table' => 'calculsdroitsrsa', // FIXME:
						'alias' => 'Calculdroitrsa',
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Calculdroitrsa.personne_id',
							'Calculdroitrsa.toppersdrodevorsa' => '1',
						)
					),
					array(
						'table' => 'orientsstructs', // FIXME:
						'alias' => 'Orientstruct',
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Orientstruct.personne_id',
							// La dernière
							'Orientstruct.id IN (
								SELECT o.id
									FROM orientsstructs AS o
									WHERE
										o.personne_id = Personne.id
										AND o.date_valid IS NOT NULL
									ORDER BY o.date_valid DESC
									LIMIT 1
							)',
						)
					),
					array(
						'table' => 'contratsinsertion', // FIXME:
						'alias' => 'Contratinsertion',
						'type' => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Contratinsertion.personne_id',
							'Contratinsertion.id IN (
								SELECT cer.id
									FROM contratsinsertion AS cer
									WHERE
										cer.personne_id = Personne.id
										AND cer.df_ci IS NOT NULL
									ORDER BY cer.df_ci DESC
									LIMIT 1
							)',
						)
					),
					array(
						'table' => 'typesorients', // FIXME:
						'alias' => 'Typeorient',
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Typeorient.id = Orientstruct.typeorient_id',
						)
					)
				),
				'conditions' => array(
					//  La dernière orientation doit être en emploi
					'Orientstruct.typeorient_id IN (
						SELECT t.id
							FROM typesorients AS t
							WHERE t.lib_type_orient LIKE \'Emploi%\'
					)',
					// La date de radiation doit être strictement supérieure à la date d'orientation
					'Historiqueetatpe.date > Orientstruct.date_valid',
					// La personne ne se trouve pas ...
					'Personne.id NOT IN ( '.
					$this->Dossierep->sq(
							array(
								'alias' => 'dossierseps',
								'fields' => array( 'dossierseps.personne_id' ),
								'conditions' => array(
									'dossierseps.personne_id = Personne.id',
									array(
										'OR' => array(
											// ... dans un dossier d'EP pas encore associé à une commission
											'dossierseps.id NOT IN ( '.
											$this->Dossierep->Passagecommissionep->sq(
													array(
														'alias' => 'passagescommissionseps',
														'fields' => array( 'passagescommissionseps.dossierep_id' ),
														'conditions' => array(
															'passagescommissionseps.dossierep_id = dossierseps.id'
														)
													)
											)
											.' )',
											// ... dans un dossier d'EP non finalisé
											'dossierseps.id IN ( '.
											$this->Dossierep->Passagecommissionep->sq(
													array(
														'alias' => 'passagescommissionseps',
														'fields' => array( 'passagescommissionseps.dossierep_id' ),
														'conditions' => array(
															'passagescommissionseps.dossierep_id = dossierseps.id',
															'NOT' => array(
																'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
															)
														)
													)
											)
											.' )',
											// ... ou dans un dossier d'EP finalisé depuis moins de temps que le délai de régularisation
											'dossierseps.id IN ( '.
											$this->Dossierep->Passagecommissionep->sq(
													array(
														'alias' => 'passagescommissionseps',
														'fields' => array( 'passagescommissionseps.dossierep_id' ),
														'conditions' => array(
															'passagescommissionseps.dossierep_id = dossierseps.id',
															'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' ),
															'( DATE( NOW() ) - CAST( dossierseps.modified AS DATE ) ) <=' => Configure::read( $this->alias.'.delaiRegularisation' )
														)
													)
											)
											.' )',
										)
									)
								)
							)
					).' )'
				)
			);

			$modeleHistoriqueetatpe = ClassRegistry::init( 'Historiqueetatpe' );


			$identifiantpe = Set::classicExtract( $datas, 'Historiqueetatpe.identifiantpe' );

			if( !empty( $identifiantpe ) ) {
				$queryData['conditions'][] = $modeleHistoriqueetatpe->conditionIdentifiantpe( $identifiantpe );
			}

			/// Filtre zone géographique
			$queryData['conditions'][] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );

			$queryData['conditions'] = $this->conditionsPersonneFoyerDossier( $queryData['conditions'], $datas );
			$queryData['conditions'] = $this->conditionsAdresse( $queryData['conditions'], $datas, $filtre_zone_geo, $mesCodesInsee );

			$qdRadies = $modeleHistoriqueetatpe->Informationpe->qdRadies();
			$queryData['fields'] = array_merge( $queryData['fields'], $qdRadies['fields'] );
			$queryData['joins'] = array_merge( $queryData['joins'], $qdRadies['joins'] );
			$queryData['conditions'] = array_merge( $queryData['conditions'], $qdRadies['conditions'] );
			$queryData['order'] = $qdRadies['order'];

			return $queryData;
		}

		/**
		 * Récupération du courrier de convocation à l'allocataire pour un passage
		 * en commission donné.
		 * FIXME: spécifique par thématique
		 */
		public function getConvocationBeneficiaireEpPdf( $passagecommissionep_id ) {
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$datas = Cache::read( $cacheKey );

			if( $datas === false ) {
				$datas = $this->_qdConvocationBeneficiaireEpPdf();

				/* // Querydata
				  $datas['querydata'] = array(
				  'fields' => array_merge(
				  $this->Dossierep->Passagecommissionep->fields(),
				  $this->Dossierep->Passagecommissionep->Commissionep->fields(),
				  $this->Dossierep->Passagecommissionep->User->fields(),
				  $this->Dossierep->Passagecommissionep->Dossierep->fields(),
				  $this->Dossierep->Passagecommissionep->Dossierep->Personne->fields(),
				  $this->Dossierep->Passagecommissionep->Dossierep->Personne->Foyer->fields(),
				  $this->Dossierep->Passagecommissionep->Dossierep->Personne->Foyer->Dossier->fields(),
				  $this->Dossierep->Passagecommissionep->Dossierep->Personne->Foyer->Adressefoyer->Adresse->fields(),
				  $this->fields()
				  ),
				  'joins' => array(
				  $this->Dossierep->Passagecommissionep->join( 'Dossierep' ),
				  $this->Dossierep->Passagecommissionep->join( 'Commissionep' ),
				  $this->Dossierep->Passagecommissionep->join( 'User' ),
				  $this->Dossierep->Passagecommissionep->Dossierep->join( $this->alias ),
				  $this->Dossierep->Passagecommissionep->Dossierep->join( 'Personne' ),
				  $this->Dossierep->Passagecommissionep->Dossierep->Personne->join( 'Foyer' ),
				  $this->Dossierep->Passagecommissionep->Dossierep->Personne->Foyer->join( 'Dossier' ),
				  $this->Dossierep->Passagecommissionep->Dossierep->Personne->Foyer->join( 'Adressefoyer' ),
				  $this->Dossierep->Passagecommissionep->Dossierep->Personne->Foyer->Adressefoyer->join( 'Adresse' )
				  ),
				  'conditions' => array(
				  'Adressefoyer.id IN ('
				  .$this->Dossierep->Passagecommissionep->Dossierep->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' )
				  .')'
				  ),
				  );

				  // Options
				  $datas['options'] = array(
				  'Adresse' => array(
				  'typevoie' => ClassRegistry::init( 'Option' )->typevoie()
				  ),
				  'Personne' => array(
				  'qual' => ClassRegistry::init( 'Option' )->qual()
				  )
				  ); */

				Cache::write( $cacheKey, $datas );
			}

			$datas['querydata']['conditions']['Passagecommissionep.id'] = $passagecommissionep_id;
			$gedooo_data = $this->Dossierep->Passagecommissionep->find( 'first', $datas['querydata'] );

			if( empty( $gedooo_data ) ) {
				return false;
			}

			// Si on est déja passés pour cette raison, on recherche la décision
			$ancienPassage = array( );
			if( $gedooo_data['Nonrespectsanctionep93']['rgpassage'] > 1 ) {
				$ancienPassage = $this->find(
						'first', array(
					'fields' => array(
						'Decisionnonrespectsanctionep93.id',
						'Decisionnonrespectsanctionep93.etape',
						'Decisionnonrespectsanctionep93.decision',
						'Decisionnonrespectsanctionep93.montantreduction',
						'Decisionnonrespectsanctionep93.dureesursis',
						'Decisionnonrespectsanctionep93.commentaire',
						'Decisionnonrespectsanctionep93.created',
						'Decisionnonrespectsanctionep93.modified',
						'Decisionnonrespectsanctionep93.passagecommissionep_id',
						'Decisionnonrespectsanctionep93.raisonnonpassage',
						'Decisionnonrespectsanctionep93.decisionpcg',
						'Passagecommissionep.impressiondecision',
					),
					'conditions' => array(
						'Nonrespectsanctionep93.sortienvcontrat' => 0,
						'Nonrespectsanctionep93.active' => 0,
						'Nonrespectsanctionep93.dossierep_id <>' => $gedooo_data['Nonrespectsanctionep93']['dossierep_id'],
						'Nonrespectsanctionep93.propopdo_id' => $gedooo_data['Nonrespectsanctionep93']['propopdo_id'],
						'Nonrespectsanctionep93.orientstruct_id' => $gedooo_data['Nonrespectsanctionep93']['orientstruct_id'],
						'Nonrespectsanctionep93.contratinsertion_id' => $gedooo_data['Nonrespectsanctionep93']['contratinsertion_id'],
						'Nonrespectsanctionep93.origine' => $gedooo_data['Nonrespectsanctionep93']['origine'],
						'Nonrespectsanctionep93.historiqueetatpe_id' => $gedooo_data['Nonrespectsanctionep93']['historiqueetatpe_id'],
					),
					'joins' => array(
						$this->join( 'Dossierep' ),
						$this->Dossierep->join( 'Passagecommissionep' ),
						$this->Dossierep->Passagecommissionep->join( 'Commissionep' ),
						$this->Dossierep->Passagecommissionep->join( 'Decisionnonrespectsanctionep93' ),
					),
					'order' => array(
						'Commissionep.dateseance DESC',
						'Decisionnonrespectsanctionep93.etape DESC'
					),
					'contain' => false
						)
				);
			}
			$ancienneDecision = ( isset( $ancienPassage['Decisionnonrespectsanctionep93']['decision'] ) ? $ancienPassage['Decisionnonrespectsanctionep93']['decision'] : null );

			// Quelle était le type de structure vers laquelle on était orienté ou dans laquelle on avait contractualisé ?
			$emploi = false;
			$origine = $gedooo_data['Nonrespectsanctionep93']['origine'];
			if( in_array( $origine, array( 'orientstruct', 'contratinsertion' ) ) ) {
				if( $origine == 'orientstruct' ) {
					$lib_type_orient = $this->Orientstruct->find(
							'first', array(
						'fields' => array(
							'Typeorient.lib_type_orient'
						),
						'joins' => array(
							$this->Orientstruct->join( 'Typeorient' )
						),
						'contain' => false,
						'conditions' => array(
							'Orientstruct.id' => $gedooo_data['Nonrespectsanctionep93']['orientstruct_id']
						)
							)
					);
					$lib_type_orient = $lib_type_orient['Typeorient']['lib_type_orient'];
				}
				else {
					$lib_type_orient = $this->Contratinsertion->find(
							'first', array(
						'fields' => array(
							'Typeorient.lib_type_orient'
						),
						'joins' => array(
							$this->Contratinsertion->join( 'Structurereferente' ),
							$this->Contratinsertion->Structurereferente->join( 'Typeorient' ),
						),
						'contain' => false,
						'conditions' => array(
							'Contratinsertion.id' => $gedooo_data['Nonrespectsanctionep93']['contratinsertion_id']
						)
							)
					);
					$lib_type_orient = $lib_type_orient['Typeorient']['lib_type_orient'];
				}
				$emploi = preg_match( '/Emploi/i', $lib_type_orient );
			}

			// Choix du courrier
			if( $gedooo_data['Nonrespectsanctionep93']['origine'] == 'radiepe' ) {
				if( $ancienneDecision == '1reduction' ) {
					$modeleOdt = "{$this->alias}/convocationep_beneficiaire_2eme_passage_ppae_suite_reduction.odt";
				}
				else {
					$modeleOdt = "{$this->alias}/convocationep_beneficiaire_radiepe.odt";
				}
			}
			else {
				// Modèle de document à utiliser:
				if( $gedooo_data['Nonrespectsanctionep93']['rgpassage'] == 1 ) {
					$modeleOdt = "{$this->alias}/convocationep_beneficiaire_1er_passage.odt";
				}
				else {
					if( $ancienneDecision == '1delai' ) {
						$modeleOdt = "{$this->alias}/convocationep_beneficiaire_2eme_passage_suite_delai.odt";
					}
					else if( in_array( $ancienneDecision, array( '1pasavis', '2pasavis', 'reporte' ) ) ) {
						$modeleOdt = "{$this->alias}/convocationep_beneficiaire_2eme_passage_suite_report.odt";
					}
					else if( $ancienneDecision == '1reduction' && $emploi ) {
						$modeleOdt = "{$this->alias}/convocationep_beneficiaire_2eme_passage_ppae_suite_reduction.odt";
					}
					else { // 1reduction,1maintien,2suspensiontotale,2suspensionpartielle,2maintien,annule
						$modeleOdt = "{$this->alias}/convocationep_beneficiaire_2eme_passage.odt";
					}

					$gedooo_data['Decisionprecedente'] = $ancienPassage['Decisionnonrespectsanctionep93'];
					$gedooo_data['Decisionprecedente']['impressiondecision'] = $ancienPassage['Passagecommissionep']['impressiondecision'];

					$datas['options'] = Set::merge( $datas['options'], ClassRegistry::init( 'Decisionnonrespectsanctionep93' )->enums() );
					$datas['options']['Decisionprecedente'] = $datas['options']['Decisionnonrespectsanctionep93'];
				}
			}

			return $this->ged(
							$gedooo_data, $modeleOdt, false, $datas['options']
			);
		}

		/**
		 * Récupération de la décision suite au passage en commission d'un dossier
		 * d'EP pour un certain niveau de décision.
		 *
		 * Ancienne méthode: 15 requêtes (que du contain)
		 * Nouvelle méthode: 6 requêtes (mélange de joins et de contain)
		 */
		public function getDecisionPdf( $passagecommissionep_id ) {
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$datas = Cache::read( $cacheKey );

			if( $datas === false ) {
				$datas['querydata'] = $this->_qdDecisionPdf();

				// Traductions
				$datas['options'] = $this->Dossierep->Passagecommissionep->Decisionnonrespectsanctionep93->enums();
				$datas['options']['Personne']['qual'] = ClassRegistry::init( 'Option' )->qual();
				$datas['options']['Adresse']['typevoie'] = ClassRegistry::init( 'Option' )->typevoie();
				$datas['options']['type']['voie'] = $datas['options']['Adresse']['typevoie'];

				Cache::write( $cacheKey, $datas );
			}

			$datas['querydata']['conditions']['Passagecommissionep.id'] = $passagecommissionep_id;
			$gedooo_data = $this->Dossierep->Passagecommissionep->find( 'first', $datas['querydata'] );

			if( empty( $gedooo_data ) || empty( $gedooo_data['Decisionnonrespectsanctionep93']['id'] ) ) {
				return false;
			}

			// Ajout d'informations propres à la thématique
			$gedooo_data_complementaires = $this->find(
					'first', array(
				'contain' => array(
					'Relancenonrespectsanctionep93',
					'Orientstruct' => array(
						'Typeorient',
						'Structurereferente'
					),
					'Contratinsertion' => array(
						'Structurereferente' => array(
							'Typeorient'
						)
					),
					'Propopdo' => array(
						'Structurereferente' => array(
							'Typeorient'
						)
					),
					'Historiqueetatpe',
				),
				'conditions' => array(
					"{$this->alias}.id" => $gedooo_data[$this->alias]['id']
				)
					)
			);

			$gedooo_data = Set::merge( $gedooo_data, $gedooo_data_complementaires );

			if( $gedooo_data['Nonrespectsanctionep93']['origine'] == 'radiepe' ) {
				// FIXME: faut-il s'assurer que la dernière orientation soit bien en emploi ?
				$orientstruct = $this->Orientstruct->find(
						'first', array(
					'conditions' => array(
						'Orientstruct.personne_id' => $gedooo_data['Personne']['id'],
						'Orientstruct.date_valid IS NOT NULL',
					),
					'contain' => array(
						'Structurereferente'
					),
					'order' => array( 'Orientstruct.date_valid DESC' )
						)
				);

				$gedooo_data['Historiqueetatpe']['Orientstruct'] = $orientstruct['Orientstruct'];
				$gedooo_data['Historiqueetatpe']['Orientstruct']['Structurereferente'] = $orientstruct['Structurereferente'];
			}

			// Choix du modèle de document
			$decision = $gedooo_data['Decisionnonrespectsanctionep93']['decision'];
			$origine = $gedooo_data['Nonrespectsanctionep93']['origine'];

			if( $decision == '1delai' ) {
				$delairelance = Configure::read( 'Nonrespectsanctionep93.decisionep.delai' );
				$gedooo_data['Nonrespectsanctionep93']['datedelaisuppl'] = date( 'Y-m-d', strtotime( "+{$delairelance} days", strtotime( $gedooo_data['Passagecommissionep']['impressiondecision'] ) ) );
				$modeleOdt = "{$this->alias}/decision_delai.odt";
			}
			else if( $decision == '1reduction' ) {
				if( in_array( $origine, array( 'orientstruct', 'contratinsertion' ) ) ) {
					if( $origine == 'orientstruct' ) {
						$emploi = preg_match( '/Emploi/i', $gedooo_data['Orientstruct']['Typeorient']['lib_type_orient'] );
					}
					else {
						$emploi = preg_match( '/Emploi/i', $gedooo_data['Contratinsertion']['Structurereferente']['Typeorient']['lib_type_orient'] );
					}
					if( $emploi ) {
						$modeleOdt = "{$this->alias}/decision_reduction_ppae.odt";
					}
					else {
						$modeleOdt = "{$this->alias}/decision_reduction_pdv.odt";
					}
				}
				else if( $origine == 'radiepe' ) {
					$modeleOdt = "{$this->alias}/decision_reduction_ppae.odt";
				}
				else if( $origine == 'pdo' ) {
					$modeleOdt = "{$this->alias}/decision_reduction_pdv.odt";
				}
			}
			else if( in_array( $decision, array( '1maintien', '2maintien' ) ) ) {
				$modeleOdt = "{$this->alias}/decision_maintien.odt";
			}
			else if( $decision == '2suspensiontotale' ) {
				$modeleOdt = "{$this->alias}/decision_suspensiontotale.odt";
			}
			else if( $decision == '2suspensionpartielle' ) {
				$modeleOdt = "{$this->alias}/decision_suspensionpartielle.odt";
			}
			else if( in_array( $decision, array( '1pasavis', '2pasavis', 'reporte' ) ) ) {
				$modeleOdt = "{$this->alias}/decision_reporte.odt";
			}
			else if( $decision == 'annule' ) {
				$modeleOdt = "{$this->alias}/decision_annule.odt";
			}

			// La structure référente, plutôt que de devoir conditionner la vue (le modèle ODT)
			if( $origine == 'orientstruct' ) {
				$gedooo_data['Structurereferente'] = $gedooo_data['Orientstruct']['Structurereferente'];
			}
			else if( $origine == 'radiepe' ) {
				$gedooo_data['Structurereferente'] = $gedooo_data['Historiqueetatpe']['Orientstruct']['Structurereferente'];
			}
			else if( $origine == 'contratinsertion' ) {
				$gedooo_data['Structurereferente'] = $gedooo_data['Contratinsertion']['Structurereferente'];
			}
			else if( $origine == 'pdo' ) {
				$gedooo_data['Structurereferente'] = $gedooo_data['Propopdo']['Structurereferente'];
			}

			// Calcul de la date de fin de sursis si besoin
			$dateDepart = strtotime( $gedooo_data['Passagecommissionep']['impressiondecision'] );
			if( empty( $dateDepart ) ) {
				$dateDepart = time();
			}

			if( $decision == '1delai' ) {
				$gedooo_data['Decisionnonrespectsanctionep93']['datefinsursis'] = date( 'Y-m-d', ( $dateDepart + ( $gedooo_data['Decisionnonrespectsanctionep93']['dureesursis'] * 24 * 60 * 60 ) ) );
			}
			else {
				// FIXME
				$gedooo_data['Decisionnonrespectsanctionep93']['datefinsursis'] = date( 'Y-m-d', ( $dateDepart + ( Configure::read( 'Dossierep.nbJoursEntreDeuxPassages' ) * 24 * 60 * 60 ) ) );
			}

			// Calcul du mois de début de sanction si besoin
			if( in_array( $decision, array( '1reduction', '2suspensiontotale', '2suspensionpartielle' ) ) ) {
				$moisCourant = preg_replace( '/\-[0-9]+$/', '-01', date( 'Y-m-d', $dateDepart ) );
				$gedooo_data['Decisionnonrespectsanctionep93']['moisdebutsanction'] = date( 'Y-m-d', strtotime( "+1 months", strtotime( $moisCourant ) ) );
			}

			return $this->_getOrCreateDecisionPdf( $passagecommissionep_id, $gedooo_data, $modeleOdt, $datas['options'] );
		}

		/**
		 * Fonction retournant un querydata qui va permettre de retrouver des dossiers d'EP
		 */
		public function qdListeDossier( $commissionep_id = null ) {
			$return = array(
				'fields' => array(
					'Dossierep.id',
					'Dossier.numdemrsa',
					'Dossier.matricule',
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.locaadr',
					"{$this->alias}.origine",
					"{$this->alias}.rgpassage",
					'Passagecommissionep.id',
					'Passagecommissionep.commissionep_id'
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
							array( 'Passagecommissionep.dossierep_id = Dossierep.id' ), empty( $commissionep_id ) ? array( ) : array(
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
		 * Vérifie le délai (intervalle) accordé pour pour la détection des DO 19
		 * pour le thème "non respect et sanctions du CG 93" grâce au shell
		 * automatisationseps.
		 */
		public function checkConfigUpdateIntervalleCerDo19Cg93() {
			return $this->_checkPostgresqlIntervals( array( 'Nonrespectsanctionep93.intervalleCerDo19' ), true );
		}

		/**
		 * Lors de la validation d'un CER, si l'allocataire est en procédure de relance pour non respect et
		 * sanctions, il est possible d'arrêter la procédure de relance si la date de validation du CER
		 * est postérieure à la date de dernière relance et qu'aucun dossier d'EP n'a  encore été créé.
		 *
		 * @param array $contratinsertion Les données du contrat qui vient d'être validé.
		 * @return boolean
		 */
		public function calculSortieProcedureRelanceParValidationCer( array $contratinsertion ) {
			$success = true;

			if( isset( $contratinsertion['Contratinsertion']['decision_ci'] ) && $contratinsertion['Contratinsertion']['decision_ci'] == 'V' ) {
				// 1°) Pas encore de dossier d'EP crée -> on sort simplement de la procédure avec un contrat
				$nonrespectssanctionseps93 = $this->find(
						'all', array(
					'fields' => array(
						'Nonrespectsanctionep93.id'
					),
					'joins' => array(
						$this->join( 'Relancenonrespectsanctionep93' )
					),
					'contain' => false,
					'conditions' => array(
						'Nonrespectsanctionep93.dossierep_id IS NULL',
						'Nonrespectsanctionep93.sortienvcontrat <>' => '1',
						'Nonrespectsanctionep93.active' => '1',
						'Relancenonrespectsanctionep93.id IN ( '.$this->Relancenonrespectsanctionep93->sqDerniere( 'Nonrespectsanctionep93.id' ).' )',
						'Relancenonrespectsanctionep93.daterelance <=' => "{$contratinsertion['Contratinsertion']['datevalidation_ci']['year']}-{$contratinsertion['Contratinsertion']['datevalidation_ci']['month']}-{$contratinsertion['Contratinsertion']['datevalidation_ci']['day']}",
						'OR' => array(
							'Nonrespectsanctionep93.propopdo_id IN (
									SELECT propospdos.id
										FROM propospdos
										WHERE propospdos.personne_id = \''.$contratinsertion['Contratinsertion']['personne_id'].'\'
								)',
							'Nonrespectsanctionep93.orientstruct_id IN (
									SELECT orientsstructs.id
										FROM orientsstructs
										WHERE orientsstructs.personne_id = \''.$contratinsertion['Contratinsertion']['personne_id'].'\'
								)',
							'Nonrespectsanctionep93.contratinsertion_id IN (
									SELECT contratsinsertion.id
										FROM contratsinsertion
										WHERE contratsinsertion.personne_id = \''.$contratinsertion['Contratinsertion']['personne_id'].'\'
								)',
						)
					)
						)
				);

				if( !empty( $nonrespectssanctionseps93 ) ) {
					$ids = Set::extract( $nonrespectssanctionseps93, '/Nonrespectsanctionep93/id' );

					$success = $this->updateAll(
									array(
								'"Nonrespectsanctionep93"."sortienvcontrat"' => '\'1\'',
								'"Nonrespectsanctionep93"."active"' => '\'0\''
									), array( '"Nonrespectsanctionep93"."id"' => $ids )
							) && $success;
				}

				// 2°) On a un dossier d'EP crée, mais celui-ci n'est pas encore attaché à une commission,
				// ou alors, la commission n'a pas encore été validée (attention: on ne vérifie pas les dates).
				$dossierep = $this->Dossierep->find(
						'first', array(
					'fields' => array(
						'Dossierep.id',
						'Passagecommissionep.id',
						'Nonrespectsanctionep93.id'
					),
					'conditions' => array(
						'Dossierep.id NOT IN ( '.$this->Dossierep->Passagecommissionep->sq(
								array(
									'fields' => array(
										'passagescommissionseps.dossierep_id'
									),
									'alias' => 'passagescommissionseps',
									'conditions' => array(
										'dossierseps.themeep' => 'nonrespectssanctionseps93',
										'dossierseps.personne_id' => $contratinsertion['Contratinsertion']['personne_id'],
										'commissionseps.etatcommissionep' => array( 'valide', 'presence', 'decisionep', 'traiteep', 'decisioncg', 'traite', 'annule', 'reporte' )
									),
									'joins' => array(
										array_words_replace(
												$this->Dossierep->Passagecommissionep->join( 'Dossierep', array( 'type' => 'INNER' ) ), array(
											'Passagecommissionep' => 'passagescommissionseps',
											'Dossierep' => 'dossierseps',
												)
										),
										array_words_replace(
												$this->Dossierep->Passagecommissionep->join( 'Commissionep', array( 'type' => 'INNER' ) ), array(
											'Passagecommissionep' => 'passagescommissionseps',
											'Commissionep' => 'commissionseps',
												)
										),
									),
								)
						).' )',
						'Dossierep.personne_id' => $contratinsertion['Contratinsertion']['personne_id'],
						'Nonrespectsanctionep93.origine' => array( 'orientstruct', 'contratinsertion' ),
						'Nonrespectsanctionep93.sortienvcontrat <>' => '1',
						'Nonrespectsanctionep93.active' => '0',
						'Relancenonrespectsanctionep93.id IN ( '.$this->Relancenonrespectsanctionep93->sqDerniere( 'Nonrespectsanctionep93.id' ).' )',
						'Relancenonrespectsanctionep93.daterelance <=' => "{$contratinsertion['Contratinsertion']['datevalidation_ci']['year']}-{$contratinsertion['Contratinsertion']['datevalidation_ci']['month']}-{$contratinsertion['Contratinsertion']['datevalidation_ci']['day']}",
						'OR' => array(
							'Nonrespectsanctionep93.propopdo_id IN (
									SELECT propospdos.id
										FROM propospdos
										WHERE propospdos.personne_id = \''.$contratinsertion['Contratinsertion']['personne_id'].'\'
								)',
							'Nonrespectsanctionep93.orientstruct_id IN (
									SELECT orientsstructs.id
										FROM orientsstructs
										WHERE orientsstructs.personne_id = \''.$contratinsertion['Contratinsertion']['personne_id'].'\'
								)',
							'Nonrespectsanctionep93.contratinsertion_id IN (
									SELECT contratsinsertion.id
										FROM contratsinsertion
										WHERE contratsinsertion.personne_id = \''.$contratinsertion['Contratinsertion']['personne_id'].'\'
								)',
						)
					),
					'joins' => array(
						$this->Dossierep->join( 'Nonrespectsanctionep93', array( 'type' => 'INNER' ) ),
						$this->Dossierep->join( 'Passagecommissionep', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Relancenonrespectsanctionep93' ),
					)
						)
				);

				if( !empty( $dossierep ) ) {
					$ids = Set::extract( $dossierep, '/Nonrespectsanctionep93/id' );
					$success = $this->updateAll(
									array(
								'"Nonrespectsanctionep93"."sortienvcontrat"' => '\'1\'',
								'"Nonrespectsanctionep93"."active"' => '\'0\'',
								'"Nonrespectsanctionep93"."dossierep_id"' => null,
									), array( '"Nonrespectsanctionep93"."id"' => $ids )
							) && $success;

					if( !empty( $dossierep['Passagecommissionep']['id'] ) ) {
						$success = $this->Dossierep->Passagecommissionep->delete( $dossierep['Passagecommissionep']['id'] ) && $success;
					}
					$success = $this->Dossierep->delete( $dossierep['Dossierep']['id'] ) && $success;
				}
			}

			return $success;
		}

		/**
		 * Permet de savoir si un allocataire est en cours de procédure de relance pour cette thématique.
		 *
		 * @param integer $personne_id L'id technique de l'allocataire.
		 * @return boolean
		 */
		public function enProcedureRelance( $personne_id ) {
			return (
				$this->find(
					'count',
					array(
						'contain' => array(
							'Dossierep',
							'Orientstruct',
							'Contratinsertion',
							'Propopdo',
						),
						'conditions' => array(
							'OR' => array(
								array(
									'Dossierep.personne_id' => $personne_id,
									'Dossierep.id NOT IN ( '.$this->Dossierep->Passagecommissionep->sq(
											array(
												'alias' => 'passagescommissionseps',
												'fields' => array(
													'passagescommissionseps.dossierep_id'
												),
												'conditions' => array(
													'passagescommissionseps.etatdossierep' => 'traite'
												)
											)
									).' )'
								),
								array(
									'Nonrespectsanctionep93.active' => 1,
									'OR' => array(
										array(
											'Orientstruct.personne_id' => $personne_id,
											'Nonrespectsanctionep93.origine' => 'orientstruct'
										),
										array(
											'Contratinsertion.personne_id' => $personne_id,
											'Nonrespectsanctionep93.origine' => 'contratinsertion'
										),
										array(
											'Propopdo.personne_id' => $personne_id,
											'Nonrespectsanctionep93.origine' => 'pdo'
										)
									)
								),
							)
						)
					)
				) > 0
			);
		}
	}
?>