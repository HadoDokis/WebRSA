<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	App::import( array( 'Model', 'Historiqueetatpe' ) );

	class Nonrespectsanctionep93 extends AppModel
	{
		public $name = 'Nonrespectsanctionep93';

		public $recursive = -1;

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
			'Gedooo'
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
			)
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
			),
// 			'Decisionnonrespectsanctionep93' => array(
// 				'className' => 'Decisionnonrespectsanctionep93',
// 				'foreignKey' => 'nonrespectsanctionep93_id',
// 				'dependent' => true,
// 				'conditions' => '',
// 				'fields' => '',
// 				'order' => '',
// 				'limit' => '',
// 				'offset' => '',
// 				'exclusive' => '',
// 				'finderQuery' => '',
// 				'counterQuery' => ''
// 			),
		);

		/**
		* INFO: Fonction inutile pour cette thématique donc elle retourne simplement true
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
					//'Dossierep.commissionep_id' => $commissionep_id
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
// 						'Decisionnonrespectsanctionep93' => array(
// 							'order' => array( 'etape DESC' )
// 						),
						/*'Decisionreorientationep93',
						'Motifreorientep93',
						'Typeorient',
						'Structurereferente',*/
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
		*	lequel il faut préparer les données du formulaire
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
				$formData[$this->alias][$key]['id'] = @$datas[$key][$this->alias]['id'];
				$formData[$this->alias][$key]['dossierep_id'] = @$datas[$key][$this->alias]['dossierep_id'];
				$formData['Decisionnonrespectsanctionep93'][$key]['passagecommissionep_id'] = @$datas[$key]['Passagecommissionep'][0]['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0]['etape'] == $niveauDecision ) {
					$formData['Decisionnonrespectsanctionep93'][$key] = @$dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0];
				}
				// On ajoute les enregistrements de cette étape
				else {
					if( $niveauDecision == 'ep' ) {
						if( !empty( $datas[$key]['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0] ) ) { // Modification
							$formData['Decisionnonrespectsanctionep93'][$key]['decision'] = @$datas[$key]['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0]['decision'];
						}
						elseif( ( $dossierep['Personne']['Foyer']['nbenfants'] > 0 ) || ( $dossierep['Personne']['Foyer']['sitfam'] == 'MAR' ) ) {
							$formData['Decisionnonrespectsanctionep93'][$key]['decision'] = '1maintien';
						}
					}
					elseif( $niveauDecision == 'cg' ) {
						$formData['Decisionnonrespectsanctionep93'][$key]['decision'] = $dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0]['decision'];
						$formData['Decisionnonrespectsanctionep93'][$key]['decisionpcg'] = 'valide';
						$formData['Decisionnonrespectsanctionep93'][$key]['raisonnonpassage'] = $dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0]['raisonnonpassage'];
					}
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
					else if( in_array( $themeData[$key]['Decisionnonrespectsanctionep93']['decision'],  array( '1maintien', '1pasavis', '2pasavis', 'reporte', 'annule' ) ) ) {
						$themeData[$key]['Decisionnonrespectsanctionep93']['montantreduction'] = null;
						$themeData[$key]['Decisionnonrespectsanctionep93']['dureesursis'] = null;
					}
					// FIXME: la même chose pour l'étape 2
				}

				$success = $this->Dossierep->Passagecommissionep->Decisionnonrespectsanctionep93->saveAll( $themeData, array( 'atomic' => false ) );
				$this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => Set::extract( $data, '/Decisionnonrespectsanctionep93/passagecommissionep_id' ) )
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
		* TODO: docs
		*/

		public function finaliser( $commissionep_id, $etape ) {
			$commissionep = $this->Dossierep->Passagecommissionep->Commissionep->find(
				'first',
				array(
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
				'all',
				array(
					'fields' => array(
						'Passagecommissionep.id',
						'Passagecommissionep.commissionep_id',
						'Passagecommissionep.dossierep_id',
						'Passagecommissionep.etatdossierep',
						'Dossierep.personne_id',
						'Decisionnonrespectsanctionep93.decision',
						/*'Decisionreorientationep93.typeorient_id',
						'Decisionreorientationep93.structurereferente_id',
						'Reorientationep93.structurereferente_id',
						'Reorientationep93.referent_id',
						'Reorientationep93.datedemande'*/
					),
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $commissionep_id
						/*'Dossierep.commissionep_id' => $commissionep_id,
						'Dossierep.themeep' => Inflector::tableize( $this->alias ),//FIXME: ailleurs aussi*/
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
					),
					/*'contain' => array(
						'Decisionnonrespectsanctionep93' => array(
							'conditions' => array(
								'Decisionnonrespectsanctionep93.etape' => $etape
							)
						),
						'Dossierep'
					)*/
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
						/*'fields' => array(
							'( CAST( decision AS TEXT ) || montantreduction ) AS avis'
						),*/
						'conditions' => array(
							'etape' => 'ep'
						),
					)
				),
			);
		}

		/**
		*
		*/

		public function qdProcesVerbal() {
			return array(
				'fields' => array(
					'Nonrespectsanctionep93.id',
					'Nonrespectsanctionep93.dossierep_id',
					'Nonrespectsanctionep93.propopdo_id',
					'Nonrespectsanctionep93.orientstruct_id',
					'Nonrespectsanctionep93.contratinsertion_id',
					'Nonrespectsanctionep93.origine',
					'Nonrespectsanctionep93.rgpassage',
					'Nonrespectsanctionep93.sortienvcontrat',
					'Nonrespectsanctionep93.active',
					'Nonrespectsanctionep93.created',
					'Nonrespectsanctionep93.modified',
					'Decisionnonrespectsanctionep93.id',
					'Decisionnonrespectsanctionep93.etape',
					'Decisionnonrespectsanctionep93.decision',
					'Decisionnonrespectsanctionep93.montantreduction',
					'Decisionnonrespectsanctionep93.dureesursis',
					'Decisionnonrespectsanctionep93.commentaire',
					'Decisionnonrespectsanctionep93.created',
					'Decisionnonrespectsanctionep93.modified',
					'Decisionnonrespectsanctionep93.raisonnonpassage',
				),
				'joins' => array(
					array(
						'table'      => 'nonrespectssanctionseps93',
						'alias'      => 'Nonrespectsanctionep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Nonrespectsanctionep93.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'decisionsnonrespectssanctionseps93',
						'alias'      => 'Decisionnonrespectsanctionep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionnonrespectsanctionep93.passagecommissionep_id = Passagecommissionep.id',
							'Decisionnonrespectsanctionep93.etape' => 'ep'
						),
					),
				)
			);
		}

		/**
		*
		*/

		public function qdRadies() {
			$queryData = array(
				'fields' => array(
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Adresse.locaadr',
					'Typeorient.lib_type_orient',
					'(CASE WHEN "Contratinsertion"."id" IS NOT NULL THEN true ELSE false END ) AS "Contratinsertion__present"'
				),
				'contain' => false,
				'joins' => array(
					array(
						'table'      => 'prestations', // FIXME:
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest' => 'RSA',
							'Prestation.rolepers' => array( 'DEM', 'CJT' ),
						)
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
                        'table'      => 'adressesfoyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Foyer.id = Adressefoyer.foyer_id',
                            // FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
                            'Adressefoyer.id IN (
                                '.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
                            )'
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
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Situationdossierrsa.dossier_id = Dossier.id',
							'Situationdossierrsa.etatdosrsa' => array( 'Z', '2', '3', '4' )
						)
					),
					array(
						'table'      => 'calculsdroitsrsa', // FIXME:
						'alias'      => 'Calculdroitrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Calculdroitrsa.personne_id',
							'Calculdroitrsa.toppersdrodevorsa' => '1',
						)
					),
					array(
						'table'      => 'orientsstructs', // FIXME:
						'alias'      => 'Orientstruct',
						'type'       => 'INNER',
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
							// en emploi
							'Orientstruct.typeorient_id IN (
								SELECT t.id
									FROM typesorients AS t
									WHERE t.lib_type_orient LIKE \'Emploi%\'
							)'// FIXME
						)
					),
					array(
                        'table'      => 'contratsinsertion', // FIXME:
                        'alias'      => 'Contratinsertion',
                        'type'       => 'LEFT OUTER',
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
                        'table'      => 'typesorients', // FIXME:
                        'alias'      => 'Typeorient',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Typeorient.id = Orientstruct.typeorient_id',
                        )
                    )
				),
				'conditions' => array(
					'Personne.id NOT IN ( '.
						$this->Dossierep->sq(
							array(
								'alias' => 'dossierseps',
								'fields' => array( 'dossierseps.personne_id' ),
								'conditions' => array(
									'dossierseps.personne_id = Personne.id',
									array(
										'OR' => array(
											'dossierseps.id NOT IN ( '.
												$this->Dossierep->Passagecommissionep->sq(
													array(
														'alias' => 'passagescommissionseps',
														'fields' => array( 'passagescommissionseps.dossierep_id' ),
														'conditions' => array(
															'NOT' => array(
																'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
															)
														)
													)
												)
											.' )',
											'dossierseps.id IN ( '.
												$this->Dossierep->Passagecommissionep->sq(
													array(
														'alias' => 'passagescommissionseps',
														'fields' => array( 'passagescommissionseps.dossierep_id' ),
														'conditions' => array(
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
						) .' )'
				)
			);

			$this->Historiqueetatpe = ClassRegistry::init('Historiqueetatpe');

			$qdRadies = $this->Historiqueetatpe->Informationpe->qdRadies();
			$queryData['fields'] = array_merge( $queryData['fields'] ,$qdRadies['fields'] );
			$queryData['joins'] = array_merge( $queryData['joins'] ,$qdRadies['joins'] );
			$queryData['conditions'] = array_merge( $queryData['conditions'] ,$qdRadies['conditions'] );
			$queryData['order'] = $qdRadies['order'];

			return $queryData;
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
							'Personne' => array(
								'Foyer' => array(
									'Adressefoyer' => array(
										'conditions' => array(
											'Adressefoyer.rgadr' => '01' // FIXME
										),
										'Adresse'
									)
								)
							),
							'Nonrespectsanctionep93'
						),
						'Commissionep'
					)
				)
			);

			// Traductions
			$options['Personne']['qual'] = ClassRegistry::init( 'Option' )->qual();
			$options['Adresse']['typevoie'] = ClassRegistry::init( 'Option' )->typevoie();

			// Modèle de document à utiliser:
			if( $gedooo_data['Dossierep']['Nonrespectsanctionep93']['rgpassage'] == 1 ) {
				$modeleOdt = "{$this->alias}/convocationep_beneficiaire_1er_passage.odt";
			}
			else {
				// On est déja passés pour cette raison et on recherche la décision
				$ancienPassage = $this->find(
					'first',
					array(
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
							'Nonrespectsanctionep93.dossierep_id <>' => $gedooo_data['Dossierep']['Nonrespectsanctionep93']['dossierep_id'],
							'Nonrespectsanctionep93.propopdo_id' => $gedooo_data['Dossierep']['Nonrespectsanctionep93']['propopdo_id'],
							'Nonrespectsanctionep93.orientstruct_id' => $gedooo_data['Dossierep']['Nonrespectsanctionep93']['orientstruct_id'],
							'Nonrespectsanctionep93.contratinsertion_id' => $gedooo_data['Dossierep']['Nonrespectsanctionep93']['contratinsertion_id'],
							'Nonrespectsanctionep93.origine' => $gedooo_data['Dossierep']['Nonrespectsanctionep93']['origine'],
							'Nonrespectsanctionep93.sortienvcontrat' => 0,
							'Nonrespectsanctionep93.active' => 0,
							'Nonrespectsanctionep93.historiqueetatpe_id' => $gedooo_data['Dossierep']['Nonrespectsanctionep93']['historiqueetatpe_id'],
						),
						'joins' => array(
							array(
								'table' => 'dossierseps',
								'alias' => 'Dossierep',
								'type' => 'INNER',
								'conditions' => array(
									'Nonrespectsanctionep93.dossierep_id = Dossierep.id'
								)
							),
							array(
								'table' => 'passagescommissionseps',
								'alias' => 'Passagecommissionep',
								'type' => 'INNER',
								'conditions' => array(
									'Passagecommissionep.dossierep_id = Dossierep.id'
								)
							),
							array(
								'table' => 'commissionseps',
								'alias' => 'Commissionep',
								'type' => 'INNER',
								'conditions' => array(
									'Passagecommissionep.commissionep_id = Commissionep.id'
								)
							),
							array(
								'table' => 'decisionsnonrespectssanctionseps93',
								'alias' => 'Decisionnonrespectsanctionep93',
								'type' => 'INNER',
								'conditions' => array(
									'Decisionnonrespectsanctionep93.passagecommissionep_id = Passagecommissionep.id'
								)
							),
						),
						'order' => array( 'Commissionep.dateseance DESC', 'Decisionnonrespectsanctionep93.etape DESC' ),
						'contain' => false
					)
				);

				// FIXME: date d'impression
				if( $ancienPassage['Decisionnonrespectsanctionep93']['decision'] == '1delai' ) {
					$modeleOdt = "{$this->alias}/convocationep_beneficiaire_2eme_passage_suite_delai.odt";
				}
				else if( in_array( $ancienPassage['Decisionnonrespectsanctionep93']['decision'], array( '1pasavis', '2pasavis', 'reporte' ) ) ) {
					$modeleOdt = "{$this->alias}/convocationep_beneficiaire_2eme_passage_suite_report.odt";
				}
				else {
					$modeleOdt = "{$this->alias}/convocationep_beneficiaire_2eme_passage.odt";
				}

				$gedooo_data['Decisionprecedente'] = $ancienPassage['Decisionnonrespectsanctionep93'];
				$gedooo_data['Decisionprecedente']['impressiondecision'] = $ancienPassage['Passagecommissionep']['impressiondecision'];

				$options = Set::merge( $options, ClassRegistry::init( 'Decisionnonrespectsanctionep93' )->enums() );
				$options['Decisionprecedente'] = $options['Decisionnonrespectsanctionep93'];
			}

			return $this->ged(
				$gedooo_data,
				$modeleOdt,
				false,
				$options
			);
		}

		/**
		* Récupération de la décision suite au passage en commission d'un dossier
		* d'EP pour un certain niveau de décision.
		* FIXME: spécifique par thématique
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
							'Nonrespectsanctionep93' => array(
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
						),
						'Decisionnonrespectsanctionep93' => array(
							/*'conditions' => array(
								'Decisionnonrespectsanctionep93.etape' => $etape
							)*/
							'order' => array(
								'Decisionnonrespectsanctionep93.etape DESC'
							),
							'limit' => 1
						)
					)
				)
			);

			if( empty( $gedooo_data ) || !isset( $gedooo_data['Decisionnonrespectsanctionep93'][0] ) || empty( $gedooo_data['Decisionnonrespectsanctionep93'][0] ) ) {
				return false;
			}

			if( $gedooo_data['Dossierep']['Nonrespectsanctionep93']['origine'] == 'radiepe' ) {
				// FIXME: faut-il s'assurer que la dernière orientation soit bien en emploi ?
				$orientstruct = $this->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'Orientstruct.personne_id' => $gedooo_data['Dossierep']['Personne']['id'],
							'Orientstruct.date_valid IS NOT NULL',
						),
						'contain' => array(
							'Structurereferente'
						),
						'order' => array( 'Orientstruct.date_valid DESC' )
					)
				);

				$gedooo_data['Dossierep']['Nonrespectsanctionep93']['Historiqueetatpe']['Orientstruct'] = $orientstruct['Orientstruct'];
				$gedooo_data['Dossierep']['Nonrespectsanctionep93']['Historiqueetatpe']['Orientstruct']['Structurereferente'] = $orientstruct['Structurereferente'];
			}

			// Choix du modèle de document
			$decision = $gedooo_data['Decisionnonrespectsanctionep93'][0]['decision'];
			$origine = $gedooo_data['Dossierep']['Nonrespectsanctionep93']['origine'];

			if( $decision == '1delai' ) {
				$modeleOdt  = "{$this->alias}/decision_delai.odt";
			}
			else if( $decision == '1reduction' ) {
				if( in_array( $origine, array( 'orientstruct', 'contratinsertion' ) ) ) {
					if( $origine == 'orientstruct' ) {
						$emploi = preg_match( '/Emploi/i', $gedooo_data['Dossierep']['Nonrespectsanctionep93']['Orientstruct']['Typeorient']['lib_type_orient'] );
					}
					else {
						$emploi = preg_match( '/Emploi/i', $gedooo_data['Dossierep']['Nonrespectsanctionep93']['Contratinsertion']['Structurereferente']['Typeorient']['lib_type_orient'] );
					}
					if( $emploi ) {
						$modeleOdt  = "{$this->alias}/decision_reduction_pdv.odt";
					}
					else {
						$modeleOdt  = "{$this->alias}/decision_reduction_ppae.odt";
					}
				}
				else if( $origine == 'radiepe' ) {
					$modeleOdt  = "{$this->alias}/decision_reduction_ppae.odt";
				}
				else if( $origine == 'pdo' ) {
					$modeleOdt  = "{$this->alias}/decision_reduction_pdv.odt";
				}
			}
			else if( in_array( $decision, array( '1maintien', '2maintien' ) ) ) {
				$modeleOdt  = "{$this->alias}/decision_maintien.odt";
			}
			else if( $decision == '2suspensiontotale' ) {
				$modeleOdt  = "{$this->alias}/decision_suspensiontotale.odt";
			}
			else if( $decision == '2suspensionpartielle' ) {
				$modeleOdt  = "{$this->alias}/decision_suspensionpartielle.odt";
			}
			else if( in_array( $decision, array( '1pasavis', '2pasavis', 'reporte' ) ) ) {
				$modeleOdt  = "{$this->alias}/decision_reporte.odt";
			}
			else if( $decision == 'annule' ) {
				$modeleOdt  = "{$this->alias}/decision_annule.odt";
			}

			// La structure référente, plutôt que de devoir conditionner la vue (le modèle ODT)
			if( $origine == 'orientstruct' ) {
				$gedooo_data['Structurereferente'] = $gedooo_data['Dossierep']['Nonrespectsanctionep93']['Orientstruct']['Structurereferente'];
			}
			else if( $origine == 'radiepe' ) {
				$gedooo_data['Structurereferente'] = $gedooo_data['Dossierep']['Nonrespectsanctionep93']['Historiqueetatpe']['Orientstruct']['Structurereferente'];
			}
			else if( $origine == 'contratinsertion' ) {
				$gedooo_data['Structurereferente'] = $gedooo_data['Dossierep']['Nonrespectsanctionep93']['Contratinsertion']['Structurereferente'];
			}
			else if( $origine == 'pdo' ) {
				$gedooo_data['Structurereferente'] = $gedooo_data['Dossierep']['Nonrespectsanctionep93']['Propopdo']['Structurereferente'];
			}

			// Calcul de la date de fin de sursis si besoin
			$dateDepart = strtotime( $gedooo_data['Passagecommissionep']['impressiondecision'] );
			if( empty( $dateDepart ) ) {
				$dateDepart = mktime();
			}

			if( $decision == '1delai' ) {
				$gedooo_data['Decisionnonrespectsanctionep93'][0]['datefinsursis'] = date( 'Y-m-d', ( $dateDepart + ( $gedooo_data['Decisionnonrespectsanctionep93'][0]['dureesursis'] * 24 * 60 * 60 ) ) );
			}
			else {
				// FIXME
				$gedooo_data['Decisionnonrespectsanctionep93'][0]['datefinsursis'] = date( 'Y-m-d', ( $dateDepart + ( Configure::read( 'Dossierep.nbJoursEntreDeuxPassages' ) * 24 * 60 * 60 ) ) );
			}

			// Calcul du mois de début de sanction si besoin
			if( in_array( $decision, array( '1reduction', '2suspensiontotale', '2suspensionpartielle' ) ) ) {
				$moisCourant = preg_replace( '/\-[0-9]+$/', '-01', date( 'Y-m-d', $dateDepart ) );
				$gedooo_data['Decisionnonrespectsanctionep93'][0]['moisdebutsanction'] = date( 'Y-m-d', strtotime( "+1 months", strtotime( $moisCourant ) ) );
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
			$options = $this->Dossierep->Passagecommissionep->Decisionnonrespectsanctionep93->enums();
			$options['Personne']['qual'] = ClassRegistry::init( 'Option' )->qual();
			$options['Adresse']['typevoie'] = ClassRegistry::init( 'Option' )->typevoie();
			$gedooo_data['Structurereferente']['type_voie'] = Set::enum( $gedooo_data['Structurereferente']['type_voie'], $options['Adresse']['typevoie'] );

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

		/**
		 * Fonction retournant un querydata qui va permettre de retrouver des dossiers d'EP
		 */
		public function qdListeDossier( $commissionep_id = null  ) {
			return array(
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
					$this->alias.'.origine',
					'Passagecommissionep.id',
					'Passagecommissionep.commissionep_id'
				),
				'joins' => array(
					array(
						'alias' => $this->alias,
						'table' => Inflector::tableize( $this->alias ),
						'type' => 'INNER',
						'conditions' => array(
							'Dossierep.id = '.$this->alias.'.dossierep_id'
						)
					),
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
				)
			);
		}
	}
?>
