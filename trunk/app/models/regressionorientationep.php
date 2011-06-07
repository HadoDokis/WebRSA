<?php
	class Regressionorientationep extends AppModel
	{
		public $name = 'Regressionorientationep';

		public $useTable = false;

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'typeorient_id',
					'structurereferente_id'
				)
			),
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
			)
		);

		/**
		* Retourne pour un personne_id donnée les queryDatas permettant de retrouver
		* ses réorientationseps93 si elle en a en cours
		*/
		
		public function qdReorientationEnCours( $personne_id ) {
			return array(
				'fields' => array(
					'Personne.nom',
					'Personne.prenom',
					$this->alias.'.id',
					$this->alias.'.datedemande',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Passagecommissionep.etatdossierep'
				),
				'conditions' => array(
					'Dossierep.personne_id' => $personne_id,
					'Dossierep.themeep' => Inflector::tableize( $this->alias ),
					'Dossierep.id NOT IN ( '.$this->Dossierep->Passagecommissionep->sq(
						array(
							'alias' => 'passagescommissionseps',
							'fields' => array(
								'passagescommissionseps.dossierep_id'
							),
							'conditions' => array(
								'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
							)
						)
					).' )'
				),
				'joins' => array(
					array(
						'table' => 'dossierseps',
						'alias' => 'Dossierep',
						'type' => 'INNER',
						'conditions' => array(
							$this->alias.'.dossierep_id = Dossierep.id'
						)
					),
					array(
						'table' => 'typesorients',
						'alias' => 'Typeorient',
						'type' => 'INNER',
						'conditions' => array(
							$this->alias.'.typeorient_id = Typeorient.id'
						)
					),
					array(
						'table' => 'structuresreferentes',
						'alias' => 'Structurereferente',
						'type' => 'INNER',
						'conditions' => array(
							$this->alias.'.structurereferente_id = Structurereferente.id'
						)
					),
					array(
						'table' => 'passagescommissionseps',
						'alias' => 'Passagecommissionep',
						'type' => 'LEFT OUTER',
						'conditions' => array(
							'Passagecommissionep.dossierep_id = Dossierep.id'
						)
					),
					array(
						'table' => 'commissionseps',
						'alias' => 'Commissionep',
						'type' => 'LEFT OUTER',
						'conditions' => array(
							'Passagecommissionep.commissionep_id = Commissionep.id'
						)
					),
					array(
						'table' => 'personnes',
						'alias' => 'Personne',
						'type' => 'INNER',
						'conditions' => array(
							'Dossierep.personne_id = Personne.id'
						)
					)
				),
				'contain' => false,
				'order' => array( 'Commissionep.dateseance DESC', 'Commissionep.id DESC' )
			);
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
						'Orientstruct' => array(
							'order' => array( 'Orientstruct.date_valid DESC' ),
							'limit' => 1,
							'Typeorient',
							'Structurereferente',
						),
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
					),
					'Passagecommissionep' => array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id
						),
						'Decision'.Inflector::underscore( $this->alias ) => array(
							'Typeorient',
							'Structurereferente',
							'order' => array( 'Decision'.Inflector::underscore( $this->alias ).'.etape DESC' )
						)
					)
				)
			);
		}

		public function prepareFormData( $commissionep_id, $datas, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			$formData = array();
			foreach( $datas as $key => $dossierep ) {
				$formData['Decision'.Inflector::underscore( $this->alias )][$key]['passagecommissionep_id'] = @$datas[$key]['Passagecommissionep'][0]['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['etape'] == $niveauDecision ) {
					$formData['Decision'.Inflector::underscore( $this->alias )][$key] = @$dossierep['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0];
					$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = implode(
						'_',
						array(
							$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'],
							$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id']
						)
					);
				}
				// On ajoute les enregistrements de cette étape
				else {
					if( $niveauDecision == 'ep' ) {
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = $dossierep[$this->alias]['typeorient_id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['typeorient_id'],
								$dossierep[$this->alias]['structurereferente_id']
							)
						);
					}
					elseif( $niveauDecision == 'cg' ) {
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['decision'] = $dossierep['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['decision'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['raisonnonpassage'] = $dossierep['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['raisonnonpassage'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['commentaire'] = $dossierep['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['commentaire'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = $dossierep['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'],
								$dossierep['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['structurereferente_id']
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

		public function saveDecisions( $data, $niveauDecision ) {
			$success = true;
			$themeData = Set::extract( $data, '/Decision'.Inflector::underscore( $this->alias ) );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				foreach( $themeData as $key => $datas ) {
					if ( !empty( $datas['Decision'.Inflector::underscore( $this->alias )]['structurereferente_id'] ) ) {
						list( $typeorient_id, $structurereferente_id ) = explode( '_', $datas['Decision'.Inflector::underscore( $this->alias )]['structurereferente_id'] );
						$themeData[$key]['Decision'.Inflector::underscore( $this->alias )]['typeorient_id'] = $typeorient_id;
						$themeData[$key]['Decision'.Inflector::underscore( $this->alias )]['structurereferente_id'] = $structurereferente_id;

						$regressionorientation = $this->find(
							'first',
							array(
								'fields' => array(
									$this->alias.'.structurereferente_id',
									$this->alias.'.referent_id'
								),
								'joins' => array(
									array(
										'table' => 'dossierseps',
										'alias' => 'Dossierep',
										'type' => 'INNER',
										'conditions' => array(
											$this->alias.'.dossierep_id = Dossierep.id'
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
										'table' => 'decisions'.Inflector::tableize( $this->alias ),
										'alias' => 'Decision'.Inflector::underscore( $this->alias ),
										'type' => 'INNER',
										'conditions' => array(
											'Decision'.Inflector::underscore( $this->alias ).'.passagecommissionep_id = Passagecommissionep.id'
										)
									)
								),
								'contain' => false
							)
						);

						if ( $regressionorientation[$this->alias]['structurereferente_id'] == $structurereferente_id ) {
							$themeData[$key]['Decision'.Inflector::underscore( $this->alias )]['referent_id'] = $regressionorientation[$this->alias]['referent_id'];
						}
						else {
							$themeData[$key]['Decision'.Inflector::underscore( $this->alias )]['referent_id'] = null;
						}
					}
				}

				$success = $this->Dossierep->Passagecommissionep->{'Decision'.Inflector::underscore( $this->alias )}->saveAll( $themeData, array( 'atomic' => false ) ) && $success;
				$this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => Set::extract( $data, '/Decision'.Inflector::underscore( $this->alias ).'/passagecommissionep_id' ) )
				);

				return $success;
			}
		}

		/**
		* INFO: Fonction inutile dans cette saisine donc elle retourne simplement true
		*/

		public function verrouiller( $commissionep_id, $etape ) {
			return true;
		}
		/**
		*
		*/

		public function qdProcesVerbal() {
			$modele = 'Regressionorientationep'.Configure::read( 'Cg.departement' );
			$modeleDecisions = 'Decisionregressionorientationep'.Configure::read( 'Cg.departement' );

			return array(
				'fields' => array(
					"{$modele}.id",
					"{$modele}.dossierep_id",
					"{$modele}.typeorient_id",
					"{$modele}.structurereferente_id",
					"{$modele}.datedemande",
					"{$modele}.referent_id",
					"{$modele}.user_id",
					"{$modele}.commentaire",
					"{$modele}.created",
					"{$modele}.modified",
					"{$modeleDecisions}.id",
					"{$modeleDecisions}.typeorient_id",
					"{$modeleDecisions}.structurereferente_id",
					"{$modeleDecisions}.referent_id",
					"{$modeleDecisions}.etape",
					"{$modeleDecisions}.commentaire",
					"{$modeleDecisions}.created",
					"{$modeleDecisions}.modified",
					"{$modeleDecisions}.passagecommissionep_id",
					"{$modeleDecisions}.decision",
					"{$modeleDecisions}.raisonnonpassage",
				),
				'joins' => array(
					array(
						'table'      => Inflector::tableize( $modele ),
						'alias'      => $modele,
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( "{$modele}.dossierep_id = Dossierep.id" ),
					),
					array(
						'table'      => Inflector::tableize( $modeleDecisions ),
						'alias'      => $modeleDecisions,
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							"{$modeleDecisions}.passagecommissionep_id = Passagecommissionep.id",
							"{$modeleDecisions}.etape" => 'ep'
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
			return array(
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