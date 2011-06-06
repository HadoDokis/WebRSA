<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Signalementep extends AppModel
	{

		public $useTable = false;

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Gedooo'
		);

		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
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

			$modeleDecisions = Inflector::classify( 'decisions'.Inflector::tableize( $this->alias ) );

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
							'contratinsertion_id',
							'date',
							'motif',
							'rang',
							'created',
							'modified'

						),
						'Contratinsertion' => array(
							'Structurereferente',
						)
					),
					'Passagecommissionep' => array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id
						),
						$modeleDecisions => array(
							'order' => array( $modeleDecisions.'.etape DESC' ),
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

			$modeleDecisions = Inflector::classify( 'decisions'.Inflector::tableize( $this->alias ) );

			$formData = array();
			foreach( $datas as $key => $dossierep ) {
				$formData[$this->alias][$key]['id'] = @$datas[$key][$this->alias]['id'];
				$formData[$this->alias][$key]['dossierep_id'] = @$datas[$key][$this->alias]['dossierep_id'];
				$formData[$modeleDecisions][$key]['passagecommissionep_id'] = @$datas[$key]['Passagecommissionep'][0]['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Passagecommissionep'][0][$modeleDecisions][0]['etape'] == $niveauDecision ) {
					$formData[$modeleDecisions][$key] = @$dossierep['Passagecommissionep'][0][$modeleDecisions][0];
				}
				// On ajoute les enregistrements de cette étape
				else {
					if( $niveauDecision == 'ep' ) {
						if( ( $dossierep['Personne']['Foyer']['nbenfants'] > 0 ) || ( $dossierep['Personne']['Foyer']['sitfam'] == 'MAR' ) ) {
							$formData[$modeleDecisions][$key]['decision'] = '1maintien';
						}
					}
					elseif( $niveauDecision == 'cg' ) {
						$formData[$modeleDecisions][$key]['decision'] = $dossierep['Passagecommissionep'][0][$modeleDecisions][0]['decision'];
						$formData[$modeleDecisions][$key]['decisionpcg'] = 'valide';
						$formData[$modeleDecisions][$key]['raisonnonpassage'] = $dossierep['Passagecommissionep'][0][$modeleDecisions][0]['raisonnonpassage'];
						$formData[$modeleDecisions][$key]['commentaire'] = $dossierep['Passagecommissionep'][0][$modeleDecisions][0]['commentaire'];
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
			$modeleDecisions = Inflector::classify( 'decisions'.Inflector::tableize( $this->alias ) );

			$themeData = Set::extract( $data, "/{$modeleDecisions}" );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				foreach( array_keys( $themeData ) as $key ) {
					// On complètre /on nettoie si ce n'est pas envoyé par le formulaire
					if( $themeData[$key][$modeleDecisions]['decision'] == '1reduction' ) {
						$themeData[$key][$modeleDecisions]['dureesursis'] = null;
						$themeData[$key][$modeleDecisions]['montantreduction'] = Configure::read( "{$this->alias}.montantReduction" );
					}
					else if( $themeData[$key][$modeleDecisions]['decision'] == '1delai' ) {
						$themeData[$key][$modeleDecisions]['montantreduction'] = null;
						$themeData[$key][$modeleDecisions]['dureesursis'] = Configure::read( "{$this->alias}.dureeSursis" );
					}
					else if( in_array( $themeData[$key][$modeleDecisions]['decision'],  array( '1maintien', '1pasavis', '2pasavis', 'reporte' ) ) ) {
						$themeData[$key][$modeleDecisions]['montantreduction'] = null;
						$themeData[$key][$modeleDecisions]['dureesursis'] = null;
					}
					// FIXME: la même chose pour l'étape 2
				}

				$success = $this->Dossierep->Passagecommissionep->{$modeleDecisions}->saveAll( $themeData, array( 'atomic' => false ) );
				$this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => Set::extract( $data, "/{$modeleDecisions}/passagecommissionep_id" ) )
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

			$modeleDecisions = Inflector::classify( 'decisions'.Inflector::tableize( $this->alias ) );

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
						"{$modeleDecisions}.decision"
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
							'table' => Inflector::tableize( $this->alias ),
							'alias' => $this->alias,
							'type' => 'INNER',
							'conditions' => array(
								"{$this->alias}.dossierep_id = Dossierep.id"
							)
						),
						array(
							'table' => 'decisions'.Inflector::tableize( $this->alias ),
							'alias' => $modeleDecisions,
							'type' => 'INNER',
							'conditions' => array(
								"{$modeleDecisions}.passagecommissionep_id = Passagecommissionep.id",
								"{$modeleDecisions}.etape" => $etape
							)
						)
					),
					'contain' => false
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $niveauDecisionFinale == $etape ) {
					$nonrespectsanctionep = array( $this->alias => $dossierep[$this->alias] );
					$nonrespectsanctionep[$this->alias]['active'] = 0;
					if( !isset( $dossierep[$modeleDecisions][0]['decision'] ) ) {
						$success = false;
					}

					$this->create( $nonrespectsanctionep ); // TODO: un saveAll ?
					$success = $this->save() && $success;
				}
			}

			return $success;
		}

		/**
		*
		*/

		public function qdProcesVerbal() {
			$modeleDecisions = Inflector::classify( 'decisions'.Inflector::tableize( $this->alias ) );

			return array(
				'fields' => array(
					"{$this->alias}.id",
					"{$this->alias}.dossierep_id",
					"{$this->alias}.contratinsertion_id",
					"{$this->alias}.rang",
					"{$this->alias}.created",
					"{$this->alias}.modified",
					"{$this->alias}.motif",
					"{$modeleDecisions}.id",
					"{$modeleDecisions}.etape",
					"{$modeleDecisions}.decision",
					"{$modeleDecisions}.montantreduction",
					"{$modeleDecisions}.dureesursis",
					"{$modeleDecisions}.commentaire",
					"{$modeleDecisions}.raisonnonpassage",
					"{$modeleDecisions}.created",
					"{$modeleDecisions}.modified",
					"{$modeleDecisions}.raisonnonpassage",
				),
				'joins' => array(
					array(
						'table'      => Inflector::tableize( $this->alias ),
						'alias'      => $this->alias,
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( "{$this->alias}.dossierep_id = Dossierep.id" ),
					),
					array(
						'table'      => 'decisions'.Inflector::tableize( $this->alias ),
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
					'Dossier.numdemrsa',
					'Adresse.locaadr',
					'Contratinsertion.num_contrat',
					'Contratinsertion.dd_ci',
					'Contratinsertion.duree_engag',
					'Contratinsertion.df_ci',
					'Contratinsertion.structurereferente_id',
					'Contratinsertion.nature_projet',
					'Contratinsertion.type_demande',
					'Structurereferente.lib_struc',
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
						'alias' => 'Contratinsertion',
						'table' => 'contratsinsertion',
						'type' => 'INNER',
						'conditions' => array(
							'Contratinsertion.id = '.$this->alias.'.contratinsertion_id'
						)
					),
					array(
						'alias' => 'Structurereferente',
						'table' => 'structuresreferentes',
						'type' => 'INNER',
						'conditions' => array(
							'Structurereferente.id = Contratinsertion.structurereferente_id'
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