<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Signalementep93 extends AppModel
	{
		public $name = 'Signalementep93';

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
				// On ajoute les enregistrements de cette étape -> FIXME: manque les id ?
				else {
					if( $niveauDecision == 'ep' ) {
						if( !empty( $datas[$key]['Passagecommissionep'][0][$modeleDecisions][0] ) ) { // Modification
							$formData[$modeleDecisions][$key]['decision'] = @$datas[$key]['Passagecommissionep'][0][$modeleDecisions][0]['decision'];
						}
						else {
							if( ( $dossierep['Personne']['Foyer']['nbenfants'] > 0 ) || ( $dossierep['Personne']['Foyer']['sitfam'] == 'MAR' ) ) {
								$formData[$modeleDecisions][$key]['decision'] = '1maintien';
							}
							// FIXME: autre cas ?
						}
					}
					else if( $niveauDecision == 'cg' ) {
						if( !empty( $datas[$key]['Passagecommissionep'][0][$modeleDecisions][1] ) ) { // Modification
							$formData[$modeleDecisions][$key]['decision'] = @$datas[$key]['Passagecommissionep'][0][$modeleDecisions][1]['decision'];
							$formData[$modeleDecisions][$key]['raisonnonpassage'] = @$datas[$key]['Passagecommissionep'][0][$modeleDecisions][1]['raisonnonpassage'];
						}
						else {
							$formData[$modeleDecisions][$key]['decision'] = $dossierep['Passagecommissionep'][0][$modeleDecisions][0]['decision'];
							$formData[$modeleDecisions][$key]['raisonnonpassage'] = $dossierep['Passagecommissionep'][0][$modeleDecisions][0]['raisonnonpassage'];
						}
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
			$themeData = Set::extract( $data, '/Decisionsignalementep93' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				foreach( array_keys( $themeData ) as $key ) {
					// On complètre /on nettoie si ce n'est pas envoyé par le formulaire
					if( $themeData[$key]['Decisionsignalementep93']['decision'] == '1reduction' ) {
						$themeData[$key]['Decisionsignalementep93']['dureesursis'] = null;
						$themeData[$key]['Decisionsignalementep93']['montantreduction'] = Configure::read( 'Signalementep93.montantReduction' );
					}
					else if( $themeData[$key]['Decisionsignalementep93']['decision'] == '1sursis' ) {
						$themeData[$key]['Decisionsignalementep93']['montantreduction'] = null;
						$themeData[$key]['Decisionsignalementep93']['dureesursis'] = Configure::read( 'Signalementep93.dureeSursis' );
					}
					else if( in_array( $themeData[$key]['Decisionsignalementep93']['decision'],  array( '1maintien', '1pasavis', '1delai' ) ) ) {
						$themeData[$key]['Decisionsignalementep93']['montantreduction'] = null;
						$themeData[$key]['Decisionsignalementep93']['dureesursis'] = null;
					}
					// FIXME: la même chose pour l'étape 2
				}

				$success = $this->Dossierep->Passagecommissionep->Decisionsignalementep93->saveAll( $themeData, array( 'atomic' => false ) );
				$this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => Set::extract( $data, '/Decisionsignalementep93/passagecommissionep_id' ) )
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
						'Decisionsignalementep93.decision',
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
							'table' => 'signalementseps93',
							'alias' => $this->alias,
							'type' => 'INNER',
							'conditions' => array(
								'Signalementep93.dossierep_id = Dossierep.id'
							)
						),
						array(
							'table' => 'decisionssignalementseps93',
							'alias' => 'Decisionsignalementep93',
							'type' => 'INNER',
							'conditions' => array(
								'Decisionsignalementep93.passagecommissionep_id = Passagecommissionep.id',
								'Decisionsignalementep93.etape' => $etape
							)
						)
					),
					/*'contain' => array(
						'Decisionsignalementep93' => array(
							'conditions' => array(
								'Decisionsignalementep93.etape' => $etape
							)
						),
						'Dossierep'
					)*/
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $niveauDecisionFinale == $etape ) {
					$nonrespectsanctionep93 = array( $this->alias => $dossierep[$this->alias] );
					$nonrespectsanctionep93[$this->alias]['active'] = 0;
					if( !isset( $dossierep['Decisionsignalementep93'][0]['decision'] ) ) {
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

		public function qdProcesVerbal() {
			return array(
				'fields' => array(
					'Signalementep93.id',
					'Signalementep93.dossierep_id',
					'Signalementep93.contratinsertion_id',
					'Signalementep93.rang',
					'Signalementep93.created',
					'Signalementep93.modified',
					'Decisionsignalementep93.id',
// 					'Decisionsignalementep93.nonrespectsanctionep93_id',
					'Decisionsignalementep93.etape',
					'Decisionsignalementep93.decision',
					'Decisionsignalementep93.montantreduction',
					'Decisionsignalementep93.dureesursis',
					'Decisionsignalementep93.commentaire',
					'Decisionsignalementep93.created',
					'Decisionsignalementep93.modified',
				),
				'joins' => array(
					array(
						'table'      => 'signalementseps93',
						'alias'      => 'Signalementep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Signalementep93.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'decisionssignalementseps93',
						'alias'      => 'Decisionsignalementep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionsignalementep93.passagecommissionep_id = Passagecommissionep.id',
							'Decisionsignalementep93.etape' => 'ep'
						),
					),
				)
			);
		}


        /**
        *    Récupération des informations propres au dossier devant passer en EP
        *   avant liaison avec la commission d'EP
        */
        public function getCourrierInformationPdf( $dossierep_id ) {
            $gedooo_data = $this->find(
                'first',
                array(
                    'conditions' => array( 'Dossierep.id' => $dossierep_id ),
                    'contain' => array(
                        'Dossierep' => array(
                            'Personne'
                        ),
                        'Contratinsertion' => array(
                            'Structurereferente',
                        )
                    )
                )
            );
            return $this->ged( $gedooo_data, "{$this->alias}/courrierinformationavantep.odt" );
        }


	}
?>
