<?php
	/**
	 * Code source de la classe Commissionep.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Séance d'équipe pluridisciplinaire.
	 *
	 * @package app.Model
	 */
	class Commissionep extends AppModel
	{
		public $name = 'Commissionep';

		public $displayField = 'dateseance';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'ValidateTranslate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'etatcommissionep'
				)
			),
			'Gedooo.Gedooo',
			'ModelesodtConditionnables' => array(
				58 => '%s/ordredujour_participant_58.odt',
				66 => '%s/ordredujour_participant_66.odt',
				93 => array(
					'%s/ordredujour_participant_93.odt',
					'%s/fichesynthese.odt',
				)
			)
		);

		public $belongsTo = array(
			'Ep' => array(
				'className' => 'Ep',
				'foreignKey' => 'ep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Passagecommissionep' => array(
				'className' => 'Passagecommissionep',
				'foreignKey' => 'commissionep_id',
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
			'CommissionepMembreep' => array(
				'className' => 'CommissionepMembreep',
				'foreignKey' => 'commissionep_id',
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

		public $hasAndBelongsToMany = array(
			'Membreep' => array(
				'className' => 'Membreep',
				'joinTable' => 'commissionseps_membreseps',
				'foreignKey' => 'commissionep_id',
				'associationForeignKey' => 'membreep_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'CommissionepMembreep'
			),
		);

		public $validate = array(
			'raisonannulation' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Ce champ est obligatoire.',
					'allowEmpty' => false,
					'required' => false
				)
			)
		);

		/**
		* Chemin relatif pour les modèles de documents .odt utilisés lors des
		* impressions. Utiliser %s pour remplacer par l'alias.
		*/
		public $modelesOdt = array(
			'%s/pv.odt',
			'%s/convocationep_participant.odt',
			'%s/decisionep.odt',
			'%s/convocationep_beneficiaire.odt',
		);

		public function search( $criteresseanceep, $filtre_zone_geo, $zonesgeographiques ) {
			/// Conditions de base

			$conditions = $this->Ep->sqRestrictionsZonesGeographiques(
				'Commissionep.ep_id',
				$filtre_zone_geo,
				$zonesgeographiques
			);

			if ( isset($criteresseanceep['Ep']['regroupementep_id']) && !empty($criteresseanceep['Ep']['regroupementep_id']) ) {
				$conditions[] = array('Ep.regroupementep_id'=>$criteresseanceep['Ep']['regroupementep_id']);
			}

			if ( isset($criteresseanceep['Commissionep']['name']) && !empty($criteresseanceep['Commissionep']['name']) ) {
				$conditions[] = array('Commissionep.name'=>$criteresseanceep['Commissionep']['name']);
			}

			if ( isset($criteresseanceep['Commissionep']['identifiant']) && !empty($criteresseanceep['Commissionep']['identifiant']) ) {
				$conditions[] = array('Commissionep.identifiant'=>$criteresseanceep['Commissionep']['identifiant']);
			}

			if ( isset($criteresseanceep['Structurereferente']['ville']) && !empty($criteresseanceep['Structurereferente']['ville']) ) {
				$conditions[] = array('Commissionep.villeseance'=>$criteresseanceep['Structurereferente']['ville']);
			}

			/// Critères sur le Comité - date du comité
			if( isset( $criteresseanceep['Commissionep']['dateseance'] ) && !empty( $criteresseanceep['Commissionep']['dateseance'] ) ) {
				$valid_from = ( valid_int( $criteresseanceep['Commissionep']['dateseance_from']['year'] ) && valid_int( $criteresseanceep['Commissionep']['dateseance_from']['month'] ) && valid_int( $criteresseanceep['Commissionep']['dateseance_from']['day'] ) );
				$valid_to = ( valid_int( $criteresseanceep['Commissionep']['dateseance_to']['year'] ) && valid_int( $criteresseanceep['Commissionep']['dateseance_to']['month'] ) && valid_int( $criteresseanceep['Commissionep']['dateseance_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Commissionep.dateseance BETWEEN \''.implode( '-', array( $criteresseanceep['Commissionep']['dateseance_from']['year'], $criteresseanceep['Commissionep']['dateseance_from']['month'], $criteresseanceep['Commissionep']['dateseance_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresseanceep['Commissionep']['dateseance_to']['year'], $criteresseanceep['Commissionep']['dateseance_to']['month'], $criteresseanceep['Commissionep']['dateseance_to']['day'] ) ).'\'';
				}
			}

			$query = array(
// 				'fields' => array(
// 					'Commissionep.id',
// 					'Commissionep.name',
// 					'Commissionep.identifiant',
// 					'Commissionep.dateseance',
// 					'Commissionep.etatcommissionep',
// 					'Commissionep.lieuseance',
// 					'Commissionep.observations'
// 				),
				'contain'=>array(
					'Ep' => array(
// 						'fields'=>array(
// 							'id',
// 							'name',
// 							'identifiant'
// 						),
						'Regroupementep'
					),
					'Membreep'
				),
				'order' => array( '"Commissionep"."dateseance" ASC' ),
				'conditions' => $conditions
			);

			return $query;
		}

		/**
		* Renvoie un array associatif contenant les thèmes traités par la commission
		* ainsi que le niveau de décision pour chacun de ces thèmes.
		*
		* @param integer $id L'id technique de la commission d'EP
		* @return array
		* @access public
		*/

		public function themesTraites( $id ) {
			$regroupementep = $this->Ep->Regroupementep->find(
				'first',
				array(
					'contain' => false,
					'conditions' => array(
						'Regroupementep.id IN ( '.
							$this->Ep->sq(
								array(
									'alias' => 'eps',
									'fields' => array( 'eps.regroupementep_id' ),
									'conditions' => array(
										'eps.id IN ( '.
											$this->sq(
												array(
													'alias' => 'commissionseps',
													'fields' => array( 'commissionseps.ep_id' ),
													'conditions' => array(
														'commissionseps.id' => $id
													)
												)
											)
										.' )'
									)
								)
							)
						.' )'
					)
				)
			);

			$themes = $this->Ep->themes();
			$themesTraites = array();

			foreach( $themes as $theme ) {
				if( in_array( $regroupementep['Regroupementep'][$theme], array( 'decisionep', 'decisioncg' ) ) ) {
					$themesTraites[$theme] = $regroupementep['Regroupementep'][$theme];
				}
			}

			return $themesTraites;
		}

		/**
		* Sauvegarde des avis/décisions par liste d'une séance d'EP, au niveau ep ou cg
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param array $data Les données à sauvegarder
		* @param string $niveauDecision Le niveau de décision pour lequel il faut sauvegarder
		* @return boolean
		* @access public
		*/

		public function saveDecisions( $commissionep_id, $data, $niveauDecision ) {
			$commissionep = $this->find( 'first', array( 'conditions' => array( 'Commissionep.id' => $commissionep_id ) ) );

			if( empty( $commissionep ) ) {
				return false;
			}

			$success = true;

			// Champs à conserver en cas d'annulation ou de report
			$champsAGarder = array( 'id', 'etape', 'passagecommissionep_id', 'user_id', 'created', 'modified' );
			$champsAGarderPourNonDecision = Set::merge( $champsAGarder, array( 'decision', 'decisionpcg', 'commentaire', 'raisonnonpassage' ) );

			foreach( $this->themesTraites( $commissionep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				$modeleDecision = Inflector::classify( "decision{$theme}" );
				if( isset( $data[$model] ) || isset( $data[$modeleDecision] ) && !empty( $data[$modeleDecision] ) ) {
					// Mise à NULL de certains champs de décision
					$champsDecision = array_keys( $this->Passagecommissionep->{$modeleDecision}->schema( true ) );
					$champsANull = array_fill_keys( array_diff( $champsDecision, $champsAGarder ), null );
					$champsANullPourNonDecision = array_diff( $champsDecision, $champsAGarderPourNonDecision );
					foreach( $data[$modeleDecision] as $i => $decision ) {
						// 1°) En cas d'annulation ou de report
						if( in_array( $decision['decision'], array( 'annule', 'reporte' ) ) ) {
							foreach( $champsANullPourNonDecision as $champ ) {
								if( isset( $data[$modeleDecision][$i][$champ] ) ) {
									$data[$modeleDecision][$i][$champ] = null;
								}
							}
						}
						// 2°) Dans les autres cas
						else {
							$data[$modeleDecision][$i] = Set::merge( $champsANull, $decision );
						}
					}

					$success = $this->Passagecommissionep->Dossierep->{$model}->saveDecisions( $data, $niveauDecision ) && $success;
				}
			}

			///FIXME : calculer si tous les dossiers ont bien une décision avant de changer l'état ?
			$this->id = $commissionep_id;
			$this->set( 'etatcommissionep', "decision{$niveauDecision}" );
			$success = $this->save() && $success;

			return $success;
		}

		/**
		* Retourne la liste des dossiers de la séance d'EP, groupés par thème,
		* pour les dossiers qui doivent passer par liste.
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('decisionep' ou 'decisioncg') pour
		*	lequel il faut les dossiers à passer par liste.
		* @return array
		* @access public
		*/

		public function dossiersParListe( $commissionep_id, $niveauDecision ) {
			$dossiers = array();

			foreach( $this->themesTraites( $commissionep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				$queryData = $this->Passagecommissionep->Dossierep->{$model}->qdDossiersParListe( $commissionep_id, $niveauDecision );
				$dossiers[$model]['liste'] = array();
				if( !empty( $queryData ) ) {
					$dossiers[$model]['liste'] = $this->Passagecommissionep->Dossierep->find( 'all', $queryData );
				}
			}

			return $dossiers;
		}

		/**
		* Retourne les données par défaut du formulaire de traitement par liste,
		* pour une séance donnée, pour des dossiers données et à un niveau de
		* décision donné.
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param array $dossiers Array de résultats de requêtes CakePHP pour
		* 	chacun des thèmes, par liste.
		* @param string $niveauDecision Le niveau de décision ('decisionep' ou 'decisioncg')
		*	pour lequel on veut obtenir les données par défaut du formulaire de
		*	traitement.
		* @return array
		* @access public
		*/

		public function prepareFormData( $commissionep_id, $dossiers, $niveauDecision ) {
			$data = array();

			foreach( $this->themesTraites( $commissionep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );

				$data = Set::merge(
					$data,
					$this->Passagecommissionep->Dossierep->{$model}->prepareFormData(
						$commissionep_id,
						$dossiers[$model]['liste'],
						$niveauDecision
					)
				);
			}

			return $data;
		}

		/**
		* Tentative de finalisation des décisions d'une séance donnée, pour un
		* niveau de décision donné.
		* Retourne false si tous les dossiers de la séance n'ont pas eu de décision
		* ou si la finalisation n'a pas pu avoir lieu.
		*
		* TODO: être plus précis dans la description de la fonction + faire une
		* doc précise pour les fonctions "finaliser" des différents modèles de
		* thèmes.
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('decisionep' ou 'decisioncg')
		*	pour lequel on veut finaliser les décisions.
		* @return boolean
		* @access public
		*/

		public function finaliser( $commissionep_id, $data, $niveauDecision, $user_id ) {
			$themesTraites = $this->themesTraites( $commissionep_id );

			// Première partie: revalidation "spéciale" des décisions
			foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
				$modeleDecision = Inflector::classify( "decision{$themeTraite}" );
				if( isset( $this->Passagecommissionep->{$modeleDecision}->validateFinalisation ) ) {
					// FIXME: pas possible de faire un merge avec les règles déduites par Autovalidate2 ?
					$this->Passagecommissionep->{$modeleDecision}->validate = $this->Passagecommissionep->{$modeleDecision}->validateFinalisation;
				}
			}

			if( !$this->saveDecisions( $commissionep_id, $data, $niveauDecision ) ) {
				return false;
			}

			// Deuxième partie: recherche des dossiers pas encore traités à cette étape
			$success = true;
			$totalErrors = 0;
			foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
// 				$themeTraite = Inflector::tableize( $themeTraite );

				// On est au niveau de décision final ou au niveau cg
				if( ( $niveauDecisionTheme == "decision{$niveauDecision}" ) || $niveauDecisionTheme == 'decisioncg' ) {
					/*$conditions = array(
						'Dossierep.themeep' => $themeTraite,
						'Dossierep.id IN ( '.$this->Passagecommissionep->sq(
							array(
								'alias' => 'passagescommissionseps',
								'fields' => array(
									'passagescommissionseps.dossierep_id'
								),
								'conditions' => array(
									'passagescommissionseps.commissionep_id' => $commissionep_id,
									'passagescommissionseps.etatdossierep <>' => "decision{$niveauDecision}",
								)
							)
						).' )',
					);*/
// 					$totalErrors += $this->Passagecommissionep->Dossierep->find( 'count', array( 'conditions' => $conditions ) );

					// FIXME: nbDossiersPourEtape et nbDossiersEtapeSuivante
					$themeTraite = Inflector::classify( $themeTraite );
					$totalErrors += $this->Passagecommissionep->Dossierep->{$themeTraite}->nbErreursFinaliserCg( $commissionep_id, $niveauDecision );
				}
			}

			if( empty( $totalErrors ) ) {
				$niveauMax = 'decisionep';
				foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
					$themeTraite = Inflector::tableize( $themeTraite );
					$tableDecisionTraite = "decisions".Inflector::underscore( $themeTraite );
					$modelDecisionTraite = Inflector::classify( $tableDecisionTraite );

					if( "decision{$niveauDecision}" == $niveauDecisionTheme ) {
						$this->Passagecommissionep->updateAll(
							array( 'Passagecommissionep.etatdossierep' => '\'traite\'' ),
							array(
								'"Passagecommissionep"."commissionep_id"' => $commissionep_id,
								'"Passagecommissionep"."id" NOT IN ( '. $this->Passagecommissionep->{$modelDecisionTraite}->sq(
									array(
										'fields' => array(
											"{$tableDecisionTraite}.passagecommissionep_id"
										),
										'alias' => "{$tableDecisionTraite}",
										'conditions' => array(
											"{$tableDecisionTraite}.decision" => array( 'reporte', 'annule' ),
											"{$tableDecisionTraite}.etape" => $niveauDecision
										)
									)
								).' )',
								'"Passagecommissionep"."dossierep_id" IN ( '. $this->Passagecommissionep->Dossierep->sq(
									array(
										'fields' => array(
											'dossierseps.id'
										),
										'alias' => 'dossierseps',
										'conditions' => array(
											'dossierseps.themeep' => $themeTraite
										)
									)
								).' )'
							)
						);

						$listeDecisions = array( 'annule', 'reporte' );
						foreach( $listeDecisions as $decision ) {
							$this->Passagecommissionep->updateAll(
								array( 'Passagecommissionep.etatdossierep' => "'{$decision}'" ),
								array(
									'"Passagecommissionep"."commissionep_id"' => $commissionep_id,
									'"Passagecommissionep"."id" IN ( '. $this->Passagecommissionep->{$modelDecisionTraite}->sq(
										array(
											'fields' => array(
												"{$tableDecisionTraite}.passagecommissionep_id"
											),
											'alias' => "{$tableDecisionTraite}",
											'conditions' => array(
												"{$tableDecisionTraite}.decision" => array( $decision ),
													"{$tableDecisionTraite}.etape" => $niveauDecision
											)
										)
									).' )'
								)
							);
						}

						if( $tableDecisionTraite == 'decisionsnonrespectssanctionseps93' || $tableDecisionTraite == 'decisionssignalementseps93' ) {
							$listeDecisions = array( '1pasavis', '2pasavis' );
							foreach( $listeDecisions as $decision ) {
								$this->Passagecommissionep->updateAll(
									array( 'Passagecommissionep.etatdossierep' => "'reporte'" ),
									array(
										'"Passagecommissionep"."commissionep_id"' => $commissionep_id,
										'"Passagecommissionep"."id" IN ( '. $this->Passagecommissionep->{$modelDecisionTraite}->sq(
											array(
												'fields' => array(
													"{$tableDecisionTraite}.passagecommissionep_id"
												),
												'alias' => "{$tableDecisionTraite}",
												'conditions' => array(
													"{$tableDecisionTraite}.decision" => array( $decision ),
														"{$tableDecisionTraite}.etape" => $niveauDecision
												)
											)
										).' )'
									)
								);
							}
						}

					}
					elseif( $niveauDecisionTheme == 'decisioncg' && "decision{$niveauDecision}" == 'decisionep' ) {
						$this->Passagecommissionep->updateAll(
							array( 'Passagecommissionep.etatdossierep' => '\'decisioncg\'' ),
							array(
								'"Passagecommissionep"."commissionep_id"' => $commissionep_id
							)
						);
					}

					if ( $niveauDecisionTheme == 'decisioncg' ) {
						$niveauMax = 'decisioncg';
					}
				}

				$commissionep = $this->find(
					'first',
					array(
						'conditions' => array(
							'Commissionep.id' => $commissionep_id
						)
					)
				);

				if( "decision{$niveauDecision}" == 'decisioncg' || ( $niveauMax == 'decisionep' && "decision{$niveauDecision}" == 'decisionep' ) ) {
					$commissionep['Commissionep']['etatcommissionep'] = 'traite';
					// Finalisation de chacun des dossiers
					foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
						if( $niveauDecisionTheme == "decision{$niveauDecision}" ) {
							$themeTraite = Inflector::tableize( $themeTraite );
							$model = Inflector::classify( $themeTraite );
							$success = $this->Passagecommissionep->Dossierep->{$model}->finaliser( $commissionep_id, $niveauDecision, $user_id ) && $success;
						}
					}
				}
				else {
					$niveauxDecisionsSeance = array_values( $themesTraites );
					$commissionep['Commissionep']['etatcommissionep'] = 'traiteep';
					if( !in_array( 'decisioncg', $niveauxDecisionsSeance ) ) {
						// Finalisation de chacun des dossiers
						foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
							$themeTraite = Inflector::tableize( $themeTraite );
							$model = Inflector::classify( $themeTraite );
							if( $niveauDecisionTheme == "decision{$niveauDecision}" ) {
								$success = $this->Passagecommissionep->Dossierep->{$model}->finaliser( $commissionep_id, $niveauDecisionTheme, $user_id ) && $success;
							}
							else {
								$success = $this->Passagecommissionep->Dossierep->{$model}->verrouiller( $commissionep_id, $niveauDecision, $user_id ) && $success;
							}
						}
					}
				}
				$this->id = $commissionep['Commissionep']['id'];
				$this->set( 'etatcommissionep', $commissionep['Commissionep']['etatcommissionep'] );
				$success = $this->save() && $success;
			}

			$success = $success && empty( $totalErrors );

			// FIXME: mettre en champs cachés les décisions de cette thématique au niveau CG lorsqu'au moins un dossier de cette thématique doit avoir une décision CG
			if( $success && Configure::read( 'Cg.departement' ) == 66 && $niveauDecision == 'ep' ) {
				$dataCg = $data;
				$nbDossierATraiterCg = 0;

				$containStoredDataCg = array();
				foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
					$modelName = Inflector::classify( $themeTraite );
					$modelDecisionName = 'Decision'.strtolower( $modelName );

					if( $niveauDecisionTheme == 'decisioncg' ) {
						$nbDossierATraiterCg += $this->Passagecommissionep->Dossierep->{$modelName}->nbDossiersATraiterCg( $commissionep_id );

						if( $nbDossierATraiterCg == 0 ) {
							foreach( $dataCg[$modelDecisionName] as $i => $dataDecision  ) {
								unset( $dataCg[$modelDecisionName][$i]['id'] );
								$dataCg[$modelDecisionName][$i]['etape'] = 'cg';
							}
						}
						
						$containStoredDataCg[$modelDecisionName] = array( 'conditions' => array( 'etape' => 'cg' ) );
					}
				}

				if( $nbDossierATraiterCg == 0 ) {
					$success = $this->saveDecisions( $commissionep_id, $dataCg, 'cg' ) && $success;
					$storedDataCg = $this->Passagecommissionep->find(
						'first',
						array(
							'conditions' => array(
								'Passagecommissionep.commissionep_id' => $commissionep_id
							),
							'contain' => $containStoredDataCg
						)
					);	
					unset( $storedDataCg['Passagecommissionep'] );

					$success = $this->finaliser( $commissionep_id, $storedDataCg, 'cg', $user_id ) && $success;
				}
			}
			
			return $success;

		}

		/**
		* Savoir si la séance est cloturée ou non (suivant le thème l'EP et le CG ce sont prononcés)
		*/

		public function clotureSeance($datas) {
			$cloture = true;

			foreach( $this->themesTraites( $datas['Commissionep']['id'] ) as $theme => $decision ) {
				$cloture = ($datas['Ep'][$theme]==$datas['Commissionep']['etatcommissionep']) && $cloture;
			}

			return $cloture;
		}

		/**
		* Change l'état de la commission d'EP entre 'cree' et 'associe'
		* S'il existe au moins un dossier associé et un membre ayant donné une réponse
		* "Confirmé" ou "Remplacé par", l'état devient associé, sinon l'état devient 'cree'
		*
		* FIXME: il faudrait une réponse pour tous les membres ?
		*
		* @param integer $commissionep_id L'identifiant technique de la commission d'EP
		* @return boolean
		*/

		public function changeEtatCreeAssocie( $commissionep_id ) {
			$commissionep = $this->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => array(
						'Ep',
						'CommissionepMembreep'
					)
				)
			);

			if( empty( $commissionep ) || !in_array( $commissionep['Commissionep']['etatcommissionep'], array( 'cree', 'quorum', 'associe' ) ) ) {
				return false;
			}

			$success = true;

			$nbDossierseps = $this->Passagecommissionep->find(
				'count',
				array(
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $commissionep_id
					)
				)
			);

			$nbMembresepsNonRenseignes = $this->CommissionepMembreep->find(
				'count',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id,
						'CommissionepMembreep.reponse' => array( 'nonrenseigne' ),
					)
				)
			);

			$nbMembresepsTotal = $this->CommissionepMembreep->find(
				'count',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id
					)
				)
			);

			$this->id = $commissionep_id;
			if( ( $nbDossierseps > 0 ) && ( $nbMembresepsNonRenseignes == 0 ) && ( $nbMembresepsTotal > 0 ) && ( $commissionep['Commissionep']['etatcommissionep'] == 'cree' || $commissionep['Commissionep']['etatcommissionep'] == 'quorum' ) ) {
				$this->set( 'etatcommissionep', 'associe' );
				$success = $this->save() && $success;
			}
			else if( ( ( $nbDossierseps == 0 ) || ( $nbMembresepsNonRenseignes > 0 ) || ( $nbMembresepsTotal == 0 ) ) && ( $commissionep['Commissionep']['etatcommissionep'] == 'associe' || $commissionep['Commissionep']['etatcommissionep'] == 'quorum' ) ) {
				$this->set( 'etatcommissionep', 'cree' );
				$success = $this->save() && $success;
			}
			return $success;
		}

		/**
		* Change l'état de la commission d'EP entre 'associe' et 'presence'
		* S'il existe au moins un membre présent à la commission
		*
		* FIXME: à modifier lors de la mise en place du corum
		*
		* @param integer $commissionep_id L'identifiant technique de la commission d'EP
		* @return boolean
		*/

		public function changeEtatAssociePresence( $commissionep_id ) {
			$commissionep = $this->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => array(
						'Ep',
						'CommissionepMembreep'
					)
				)
			);

			if( empty( $commissionep ) || !in_array( $commissionep['Commissionep']['etatcommissionep'], array( 'associe', 'valide', 'quorum', 'presence' ) ) ) {
				return false;
			}

			$success = true;
			$nbMembreseps = $this->CommissionepMembreep->find(
				'count',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id,
						'CommissionepMembreep.presence' => array( 'present', 'remplacepar' ),
					)
				)
			);

			$this->id = $commissionep_id;
			if( !empty( $nbMembreseps ) && in_array( $commissionep['Commissionep']['etatcommissionep'], array( 'associe', 'valide', 'quorum' ) ) ) {
				$this->set( 'etatcommissionep', 'presence' );
				$success = $this->save() && $success;
			}
			else if(  empty( $nbMembreseps ) && $commissionep['Commissionep']['etatcommissionep'] == 'presence' ) {
				if( Configure::read( 'Cg.departement' ) == 93 ) {
					$this->set( 'etatcommissionep', 'valide' );
				}
				else {
					$this->set( 'etatcommissionep', 'associe' );
				}
				$success = $this->save() && $success;
			}

			if ( Configure::read( 'Cg.departement' ) == 66 ) {
				$listeMembrePresentRemplace = array();
				foreach( $commissionep['CommissionepMembreep'] as $membre ) {
					if ( $membre['presence'] == 'present' || $membre['presence'] == 'remplacepar' ) {
						$listeMembrePresentRemplace[] = $membre['membreep_id'];
					}
				}

				$compositionValide = $this->Ep->Regroupementep->Compositionregroupementep->compositionValide( $commissionep['Ep']['regroupementep_id'], $listeMembrePresentRemplace );
				if( !$compositionValide['check'] ) {
					$this->set( 'etatcommissionep', 'quorum' );
					$success = $this->save() && $success;
				}
			}

			return $success;
		}

		/**
		 *
		 */
		public function checkEtat( $commissionep_id ) {
			$commissionep = $this->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => false
				)
			);

			return $commissionep['Commissionep']['etatcommissionep'];
		}

		/**
		* Retourne une chaîne de 12 caractères formattée comme suit:
		* CO, année sur 4 chiffres, mois sur 2 chiffres, nombre de commissions.
		*/

		public function identifiant() {
			return 'CO'.date( 'Ym' ).sprintf( "%010s",  $this->find( 'count' ) + 1 );
		}

		/**
		* Ajout de l'identifiant de la séance lors de la sauvegarde.
		*/

		public function beforeValidate( $options = array() ) {
			$return = parent::beforeValidate( $options );
			$primaryKey = Set::classicExtract( $this->data, "{$this->alias}.{$this->primaryKey}" );
			$identifiant = Set::classicExtract( $this->data, "{$this->alias}.identifiant" );

			if( empty( $primaryKey ) && empty( $identifiant ) && empty( $this->{$this->primaryKey} ) ) {
				$this->data[$this->alias]['identifiant'] = $this->identifiant();
			}

			return $return;
		}


		/**
		*
		*/

		public function getPdfPv( $commissionep_id, $participant_id = null, $user_id ) {
			$commissionep_data = $this->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => array(
						'Ep' => array(
							'Regroupementep'
						)
					)
				)
			);

			if( !is_null( $participant_id ) ) {
				$participant = $this->CommissionepMembreep->Membreep->find(
					'first',
					array(
						'conditions' => array(
							'Membreep.id' => $participant_id
						),
						'contain' => false
					)
				);
				
				if( !empty( $participant ) ) {
					$participant['Participant'] = $participant['Membreep'];
					unset($participant['Membreep']);
					$commissionep_data = Set::merge( $participant, $commissionep_data );
				}
			}
			

			$queryData = array(
				'fields' => array_merge(
					$this->Passagecommissionep->fields(),
					$this->Passagecommissionep->Dossierep->fields(),
					$this->Passagecommissionep->Dossierep->Personne->fields(),
					$this->Passagecommissionep->Dossierep->Personne->Foyer->Dossier->fields(),
					$this->Passagecommissionep->Dossierep->Personne->Foyer->Adressefoyer->Adresse->fields()
				),
				'joins' => array(
					$this->Passagecommissionep->Dossierep->join( 'Passagecommissionep', array( 'type' => 'INNER' ) ),
					$this->Passagecommissionep->Dossierep->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->Passagecommissionep->Dossierep->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->Passagecommissionep->Dossierep->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$this->Passagecommissionep->Dossierep->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$this->Passagecommissionep->Dossierep->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) )
				),
				'conditions' => array(
					'Passagecommissionep.dossierep_id = Dossierep.id',
					'Passagecommissionep.commissionep_id' => $commissionep_id,
					array(
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Passagecommissionep->Dossierep->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					)
				)
			);

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			foreach( $this->themesTraites( $commissionep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				if( in_array( 'Enumerable', $this->Passagecommissionep->Dossierep->{$model}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->{$model}->enums() );
				}

				$modeleDecision = Inflector::classify( "decision{$theme}" );
				if( in_array( 'Enumerable', $this->Passagecommissionep->{$modeleDecision}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Passagecommissionep->{$modeleDecision}->enums() );
				}

				foreach( array( 'fields', 'joins' ) as $key ) {
					$qdModele = $this->Passagecommissionep->Dossierep->{$model}->qdProcesVerbal();
					$queryData[$key] = array_merge( $queryData[$key], $qdModele[$key] );
				}
			}
			$options = Set::merge( $options, $this->enums() );
			$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->enums() );
			$options = Set::merge( $options, $this->Membreep->enums() );
			$options = Set::merge( $options, $this->CommissionepMembreep->enums() );

			$dossierseps = $this->Passagecommissionep->Dossierep->find( 'all', $queryData );
			// FIXME: faire la traduction des enums dans les modèles correspondants ?

			// present, excuse, FIXME: remplace_par
			$presencesTmp = $this->CommissionepMembreep->find(
				'all',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id
					),
					'contain' => array(
						'Membreep' => array(
							'Fonctionmembreep'
						),
						'Remplacanteffectifmembreep',
						'Remplacantmembreep'
					)
				)
			);

			// FIXME: presence -> obliger de prendre les présences avant d'imprimer le PV
			$presences = array();
			foreach( $presencesTmp as $presence ) {
				// Y-a-t'il eu un remplaçant effectif ?
				if( ( $presence['CommissionepMembreep']['presence'] == 'remplacepar' ) && !empty( $presence['CommissionepMembreep']['presencesuppleant_id'] ) ) {
					$presence['CommissionepMembreep']['presence'] = 'present';
					$presence['Membreep'] = Set::merge( $presence['Membreep'], $presence['Remplacanteffectifmembreep'] );
				}
				// C'est bizzarre, mais si c'était nécessaire avant, c'est juste plus propre
				else if( $presence['CommissionepMembreep']['presence'] == 'excuse' ) {
					if( isset( $presence['Remplacantmembreep']['id'] ) && !empty( $presence['Remplacantmembreep']['id'] ) ) {
						$presence['Membreep'] = Set::merge( $presence['Membreep'], $presence['Remplacantmembreep'] );
					}
				}

				$presences["Presences_{$presence['CommissionepMembreep']['presence']}"][] = array( 'Membreep' => $presence['Membreep'] );
			}

			foreach( $options['CommissionepMembreep']['presence'] as $typepresence => $libelle ) {
				if( !isset( $presences["Presences_{$typepresence}"] ) ) {
					$presences["Presences_{$typepresence}"] = array();
				}
				$commissionep_data["presences_{$typepresence}_count"] = count( $presences["Presences_{$typepresence}"] );
			}

			// Nb de dossiers d'EP par thématique
			$themes = array();
			foreach( $dossierseps as $key => $theme ) {
				$themes["Themes_{$theme['Dossierep']['themeep']}"][] = array( 'Dossierep' => $theme['Dossierep'] );
			}
			foreach( $options['Dossierep']['themeep'] as $theme => $libelleTheme ) {
				if( !isset( $themes["Themes_{$theme}"] ) ) {
					$themes["Themes_{$theme}"] = array();
				}
				$commissionep_data["nbdossiers_{$theme}_count"] = count( $themes["Themes_{$theme}"] );
			}

			$user = $this->Passagecommissionep->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => array(
						'Serviceinstructeur'
					)
				)
			);
			$commissionep_data = Set::merge( $commissionep_data, $user );

			$typeEp = $commissionep_data['Ep']['Regroupementep'];
			if( Configure::read( 'Cg.departement' ) != 66 ) {
				$pv = "pv.odt";
			}
			else {
				if( $typeEp['saisinebilanparcoursep66'] != 'nontraite' ) {
					$pv = "pv_parcours.odt";
				}
				else {
					$pv = "pv_audition.odt";
				}
			}

			return $this->ged(
				array_merge(
					array(
						$commissionep_data,
						'Decisionseps' => $dossierseps
					),
					$presences
				),
				"{$this->alias}/{$pv}",
				true,
				$options
			);
		}

		/**
		*
		*/

		protected function _qdFichesSynthetiques( $conditions ) {
			// Permet d'obtenir une et une seule entrée de la table informationspe
			$sqDerniereInformationpe = ClassRegistry::init( 'Informationpe' )->sqDerniere( 'Personne' );
			$conditions[] = array(
				'OR' => array(
					"Informationpe.id IS NULL",
					"Informationpe.id IN ( {$sqDerniereInformationpe} )"
				)
			);

			$querydata = array(
				'fields' => array(
					'Dossierep.themeep',
					'Foyer.sitfam',
					'(
						SELECT
								dossiers.dtdemrsa
							FROM personnes
								INNER JOIN prestations ON (
									personnes.id = prestations.personne_id
									AND prestations.natprest = \'RSA\'
								)
								INNER JOIN foyers ON (
									personnes.foyer_id = foyers.id
								)
								INNER JOIN dossiers ON (
									dossiers.id = foyers.dossier_id
								)
							WHERE
								prestations.rolepers IN ( \'DEM\', \'CJT\' )
								AND (
									(
										nir_correct13( "Personne"."nir" )
										AND nir_correct13( personnes.nir )
										AND SUBSTRING( TRIM( BOTH \' \' FROM personnes.nir ) FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH \' \' FROM "Personne"."nir" ) FROM 1 FOR 13 )
										AND personnes.dtnai = "Personne"."dtnai"
									)
									OR
									(
										UPPER(personnes.nom) = UPPER("Personne"."nom")
										AND UPPER(personnes.prenom) = UPPER("Personne"."prenom")
										AND personnes.dtnai = "Personne"."dtnai"
									)
								)
							ORDER BY dossiers.dtdemrsa ASC
							LIMIT 1
					) AS "Dossier__dtdemrsa"',
					'( CASE WHEN "Serviceinstructeur"."lib_service" IS NULL THEN \'Hors département\' ELSE "Serviceinstructeur"."lib_service" END ) AS "Serviceinstructeur__lib_service"',
					'',
					'Orientstruct.date_valid',
					'( CASE WHEN "Historiqueetatpe"."etat" IN ( NULL, \'cessation\' ) THEN \'Non\' ELSE \'Oui\' END ) AS "Historiqueetatpe__inscritpe"',
					'Adresse.locaadr',
				),
				'joins' => array(
					array(
						'table'      => 'passagescommissionseps',
						'alias'      => 'Passagecommissionep',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.id = Passagecommissionep.dossierep_id" ),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.personne_id = Personne.id" ),
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'suivisinstruction',
						'alias'      => 'Suiviinstruction',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Suiviinstruction.dossier_id = Dossier.id',
							'Suiviinstruction.id IN (
								'.ClassRegistry::init( 'Suiviinstruction' )->sq(
									array(
										'alias' => 'suivisinstruction',
										'fields' => array( 'suivisinstruction.id' ),
										'conditions' => array( 'suivisinstruction.dossier_id = Dossier.id' ),
										'order' => array( 'suivisinstruction.date_etat_instruction DESC' ),
										'limit' => 1,
									)
								).'
							)',

						)
					),
					array(
						'table'      => 'servicesinstructeurs',
						'alias'      => 'Serviceinstructeur',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Suiviinstruction.numdepins = Serviceinstructeur.numdepins',
							'Suiviinstruction.typeserins = Serviceinstructeur.typeserins',
							'Suiviinstruction.numcomins = Serviceinstructeur.numcomins',
							'Suiviinstruction.numagrins = Serviceinstructeur.numagrins',
						)
					),
					array(
						'table'      => 'orientsstructs',
						'alias'      => 'Orientstruct',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Orientstruct.personne_id = Personne.id',
							// TODO: sous-requête dans le modèle Orientstruct pour en connaître la dernière
							'Orientstruct.id IN (
								'.ClassRegistry::init( 'Orientstruct' )->sq(
									array(
										'alias' => 'orientsstructs',
										'fields' => array( 'orientsstructs.id' ),
										'conditions' => array(
											'orientsstructs.personne_id = Personne.id',
											'orientsstructs.statut_orient' => 'Orienté',
										),
										'order' => array( 'orientsstructs.date_valid DESC' ),
										'limit' => 1,
									)
								).'
							)',
						)
					),
					ClassRegistry::init( 'Informationpe' )->joinPersonneInformationpe(),
					array(
						'table'      => 'historiqueetatspe',
						'alias'      => 'Historiqueetatpe',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Historiqueetatpe.informationpe_id = Informationpe.id',
							'Historiqueetatpe.id IN (
										SELECT h.id
											FROM historiqueetatspe AS h
											WHERE h.informationpe_id = Informationpe.id
											ORDER BY h.date DESC
											LIMIT 1
							)'
						)
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
					)
				),
				'conditions' => $conditions,
				'order' => array(
					'Dossierep.themeep DESC',
					'Adresse.locaadr ASC'
				)
			);

			/*
			FIXME: utilisé dans getPdfOrdredujour mais également dans le fonction getFicheSynthese -> vérifier si ça ne casse pas
			OK - N° dossier: dossier_numdemrsa (pas encore présent)
			OK - Situation familiale: foyer_sitfam et foyer_nbenfants (pas encore présent)
			OK - Structure référente: structurereferente_lib_struc (pas encore présent) -> celle de l'orientation
			OK - Référent unique: referent_nom_complet (pas encore présent) -> personnes_referents
			OK - Si demande de maintien dans le social (Nonorientationproep58), proposition: si on parle de la structure, du référent et du contrat actuel, alors on peut l'ajouter (pas encore présent)oui c'est bien ça
			OK - Si réorientation, proposition: si on parle de la structure et du référent de la proposition d'orientation, alors on peut l'ajouter (pas encore présent)oui c'est ça
			*/
			if( Configure::read( 'Cg.departement' ) == 58 ) {
				// Nombre d'enfants
				$vfNbEnfants = $this->Passagecommissionep->Dossierep->Personne->Foyer->vfNbEnfants();
				$vfNbEnfants = "( {$vfNbEnfants} ) AS \"Foyer__nbenfants\"";
				$querydata['fields'][] = $vfNbEnfants;

				$querydata['fields'][] = 'Dossier.numdemrsa';

				// Structure référente
				$querydata['fields'] = array_merge(
					$querydata['fields'],
					$this->Passagecommissionep->Dossierep->Personne->Orientstruct->Structurereferente->fields()
				);
				$querydata['joins'][] = $this->Passagecommissionep->Dossierep->Personne->Orientstruct->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) );

				// Référent unique
				$querydata['fields'] = array_merge(
					$querydata['fields'],
					$this->Passagecommissionep->Dossierep->Personne->PersonneReferent->Referent->fields()
				);
				$querydata['fields'][] = $this->Passagecommissionep->Dossierep->Personne->PersonneReferent->Referent->sqVirtualField( 'nom_complet' );
				$querydata['joins'][] = $this->Passagecommissionep->Dossierep->Personne->join( 'PersonneReferent', array( 'type' => 'LEFT OUTER' ) );
				$querydata['joins'][] = $this->Passagecommissionep->Dossierep->Personne->PersonneReferent->join( 'Referent', array( 'type' => 'LEFT OUTER' ) );

				// Si demande de maintien dans le social (Nonorientationproep58), proposition: si on parle de la structure, du référent et du contrat actuel, alors on peut l'ajouter (pas encore présent)oui c'est bien ça
				$querydataNonorientationproep58 = array(
					'fields' => array_merge(
						$this->Passagecommissionep->Dossierep->Personne->Contratinsertion->fields(),
						$this->Passagecommissionep->Dossierep->Personne->Contratinsertion->Structurereferente->fields(),
						$this->Passagecommissionep->Dossierep->Personne->Contratinsertion->Referent->fields()
					),
					'joins' => array(
						array(
							'table'      => 'contratsinsertion',
							'alias'      => 'Contratinsertion',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Contratinsertion.personne_id = Orientstruct.personne_id',
//								'Contratinsertion.structurereferente_id = Orientstruct.structurereferente_id'
							)
						),
						$this->Passagecommissionep->Dossierep->Personne->Contratinsertion->join( 'Structurereferente', array( 'type' =>'LEFT OUTER' ) ),
						$this->Passagecommissionep->Dossierep->Personne->Contratinsertion->join( 'Referent', array( 'type' =>'LEFT OUTER' ) ),
					),
					'conditions' => array(
						'OR' => array(
							'Contratinsertion.id IS NULL',
							'Contratinsertion.id IN ( '.$this->Passagecommissionep->Dossierep->Personne->Contratinsertion->sqDernierContrat().' )'
						)
					)
				);
				$querydataNonorientationproep58 = array_words_replace(
					$querydataNonorientationproep58,
					array(
						'Structurereferente' => 'Structurereferentecer',
						'Referent' => 'Referentcer',
					)
				);
				$querydata['fields'] = array_merge( $querydata['fields'], $querydataNonorientationproep58['fields'] );
				$querydata['joins'] = array_merge( $querydata['joins'], $querydataNonorientationproep58['joins'] );
				$querydata['conditions'] = array_merge( $querydata['conditions'], $querydataNonorientationproep58['conditions'] );

				// Regressionorientationep58
				$querydataRegressionorientationep58 = array(
					'fields' => array_merge(
						$this->Passagecommissionep->Dossierep->Regressionorientationep58->fields(),
						$this->Passagecommissionep->Dossierep->Regressionorientationep58->Typeorient->fields(),
						$this->Passagecommissionep->Dossierep->Regressionorientationep58->Structurereferente->fields(),
						$this->Passagecommissionep->Dossierep->Regressionorientationep58->Referent->fields()
					),
					'joins' => array(
						$this->Passagecommissionep->Dossierep->join( 'Regressionorientationep58', array( 'type' => 'LEFT OUTER' ) ),
						$this->Passagecommissionep->Dossierep->Regressionorientationep58->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) ),
						$this->Passagecommissionep->Dossierep->Regressionorientationep58->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$this->Passagecommissionep->Dossierep->Regressionorientationep58->join( 'Referent', array( 'type' => 'LEFT OUTER' ) )
					)
				);
				$querydataRegressionorientationep58 = array_words_replace(
					$querydataRegressionorientationep58,
					array(
						'Typeorient' => 'Typeorientpropo',
						'Structurereferente' => 'Structurereferentepropo',
						'Referent' => 'Referentpropo',
					)
				);
				$querydata['fields'] = array_merge( $querydata['fields'], $querydataRegressionorientationep58['fields'] );
				$querydata['joins'] = array_merge( $querydata['joins'], $querydataRegressionorientationep58['joins'] );
			}

			return $querydata;
		}

		/**
		*   Impression de convocation pour un participant à une commission d'EP
		*/

		public function getPdfConvocationParticipant( $commissionep_id, $membreep_id, $user_id ) {
			$commissionep = $this->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => array(
						'Ep' => array(
							'Regroupementep'
						)
					)
				)
			);
            // FIXME: voir comment resortir la notion de prioritaire/facultatif

			$membreep = $this->Membreep->find(
				'first',
				array(
                    'fields' => array_merge(
                        $this->Membreep->fields(),
                        $this->Membreep->Fonctionmembreep->fields(),
                        $this->Membreep->Fonctionmembreep->Compositionregroupementep->fields()
                    ),
					'conditions' => array(
						'Membreep.id' => $membreep_id,
                        'Compositionregroupementep.regroupementep_id' => $commissionep['Ep']['Regroupementep']['id']
					),
                    'joins' => array(
                        $this->Membreep->join('Fonctionmembreep', array( 'type' => 'INNER' ) ),
                        $this->Membreep->Fonctionmembreep->join( 'Compositionregroupementep', array( 'type' => 'INNER' ) ),
                    ),
                    'contain' => false
				)
			);

			$convocation = Set::merge( $commissionep, $membreep );
			
			$user = $this->Passagecommissionep->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => array(
						'Serviceinstructeur'
					)
				)
			);
			$convocation = Set::merge( $convocation, $user );


			$options = $this->Membreep->enums();
			$options['Membreep']['typevoie'] = ClassRegistry::init( 'Option' )->typevoie();

            $modele = null;
            if( Configure::read( 'Cg.departement' ) == 66 ) {
            	if( $commissionep['Ep']['Regroupementep']['saisinebilanparcoursep66'] == 'nontraite' ){
					if( $convocation['Compositionregroupementep']['prioritaire'] == '1' ) {
						$modele = 'convocationep_participant_prioritaire.odt';
					}
					else {
						$modele = 'convocationep_participant_facultatif.odt';
					}
				}
				else {
					$modele = 'convocationep_participant.odt';
				}
            }
            else {
                $modele = 'convocationep_participant.odt';
            }

//debug($modele);
//die();
			return $this->ged(
				$convocation,
				"{$this->alias}/{$modele}",
				false,
				$options
			);

		}

		/**
		*   Impression de l'ordre du jour pour un participant à une commission d'EP
		*/

		public function getPdfOrdredujour( $commissionep_membreep_id, $user_id ) {
			// Participant auquel la convocation doit être envoyée
			$convocation = $this->CommissionepMembreep->find(
				'first',
				array(
					'conditions' => array(
						'CommissionepMembreep.id' => $commissionep_membreep_id
					),
					'contain' => array(
						'Commissionep' => array(
							'Ep' => array(
								'Regroupementep'
							)
						),
						'Membreep' => array(
							'Fonctionmembreep'
						)
					)
				)
			);

			// Si le membre est remplacé par un autre, il faut aller cherche le remplaçant
			if( $convocation['CommissionepMembreep']['reponse'] == 'remplacepar' ) {
				$membreep = $this->Membreep->find(
					'first',
					array(
						'conditions' => array(
							'Membreep.id' => $convocation['CommissionepMembreep']['reponsesuppleant_id']
						),
						'contain' => false
					)
				);
				$convocation = Set::merge( $convocation, $membreep );
			}

			$convocation = array( 'Participant' => $convocation['Membreep'], 'Commissionep' => $convocation['Commissionep'] );

			// FIXME: doc
			if ( Configure::read( 'Cg.departement' ) == 93 || Configure::read( 'Cg.departement' ) == 58 ) {
				$queryData = array(
					'fields' => array(
						'Dossierep.themeep',
						'Adresse.locaadr',
						'COUNT("Dossierep"."id") AS "nombre"',
					),
					'joins' => array(
						array(
							'table'      => 'passagescommissionseps',
							'alias'      => 'Passagecommissionep',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Dossierep.id = Passagecommissionep.dossierep_id" ),
						),
						array(
							'table'      => 'personnes',
							'alias'      => 'Personne',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Dossierep.personne_id = Personne.id" ),
						),
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Personne.foyer_id = Foyer.id' )
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
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Foyer.id = Adressefoyer.foyer_id',
								// FIXME: c'est un hack pour n'avoir qu'une seule adresse de rang 01 par foyer!
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
						)
					),
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $convocation['Commissionep']['id']
					),
					'group' => array(
						'Dossierep.themeep',
						'Adresse.locaadr'
					),
					'order' => array(
						'Adresse.locaadr ASC'
					)
				);
			}
			else {
				// Jointure spéciale sur Dossierep suivant la thématique
				$joinSaisinebilanparcoursep66 = $this->Passagecommissionep->Dossierep->Saisinebilanparcoursep66->join( 'Bilanparcours66', array( 'type' => 'LEFT OUTER' ) );
				$joinDefautinsertionep66 = $this->Passagecommissionep->Dossierep->Defautinsertionep66->join( 'Bilanparcours66', array( 'type' => 'LEFT OUTER' ) );

				$joinBilanparcours66 = $joinSaisinebilanparcoursep66;
				$joinBilanparcours66['conditions'] = array(
					'OR' => array(
						$joinSaisinebilanparcoursep66['conditions'],
						$joinDefautinsertionep66['conditions']
					)
				);
	
				$queryData = array(
					'fields' => array_merge(
						$this->Passagecommissionep->fields(),
						$this->Passagecommissionep->Dossierep->fields(),
						$this->Passagecommissionep->Dossierep->Personne->fields(),
						$this->Passagecommissionep->Dossierep->Personne->Foyer->Dossier->fields(),
						$this->Passagecommissionep->Dossierep->Personne->Foyer->Adressefoyer->Adresse->fields(),
						$this->Passagecommissionep->Dossierep->Saisinebilanparcoursep66->fields(),
						$this->Passagecommissionep->Dossierep->Defautinsertionep66->fields(),
						$this->Passagecommissionep->Dossierep->Defautinsertionep66->Bilanparcours66->fields(),
						$this->Passagecommissionep->Dossierep->Defautinsertionep66->Bilanparcours66->Referent->fields(),
						$this->Passagecommissionep->Dossierep->Defautinsertionep66->Bilanparcours66->Structurereferente->fields()/*,
						$this->Passagecommissionep->Dossierep->Defautinsertionep66->Bilanparcours66->Structurereferente->Permanence->fields()*/
					),
					'joins' => array(
						$this->Passagecommissionep->Dossierep->join( 'Defautinsertionep66', array( 'type' => 'LEFT OUTER' ) ),
						$this->Passagecommissionep->Dossierep->join( 'Saisinebilanparcoursep66', array( 'type' => 'LEFT OUTER' ) ),
						$joinBilanparcours66,
						$this->Passagecommissionep->Dossierep->Saisinebilanparcoursep66->Bilanparcours66->join( 'Referent', array( 'type' => 'INNER' ) ),
						$this->Passagecommissionep->Dossierep->Saisinebilanparcoursep66->Bilanparcours66->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
// 						$this->Passagecommissionep->Dossierep->Saisinebilanparcoursep66->Bilanparcours66->Structurereferente->join( 'Permanence', array( 'type' => 'INNER' ) ),
						$this->Passagecommissionep->Dossierep->join( 'Passagecommissionep', array( 'type' => 'INNER' ) ),
						$this->Passagecommissionep->Dossierep->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->Passagecommissionep->Dossierep->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Passagecommissionep->Dossierep->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Passagecommissionep->Dossierep->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Passagecommissionep->Dossierep->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) )
					),
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $convocation['Commissionep']['id'],
						array(
							'OR' => array(
								'Adressefoyer.id IS NULL',
								'Adressefoyer.id IN ( '.$this->Passagecommissionep->Dossierep->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
							)
						)
					)
				);
			}

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			$options = Set::merge( $options, $this->enums() );
			$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->enums() );
			$options = Set::merge( $options, $this->Membreep->enums() );
			$options['Participant'] = $options['Membreep'];
			$options = Set::merge( $options, $this->CommissionepMembreep->enums() );
			$options = Set::merge( $options, $this->Passagecommissionep->enums() );
			$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->Defautinsertionep66->enums() );
			$options['Participant']['typevoie'] = ClassRegistry::init( 'Option' )->typevoie();
			$options['Remplacantmembreep'] = $options['Membreep'];

			$dossierseps = $this->Passagecommissionep->Dossierep->find( 'all', $queryData );
			// FIXME: faire la traduction des enums dans les modèles correspondants ?

			// FIXME: documentation
			$themesTraites = $this->themesTraites( $convocation['Commissionep']['id'] );
			$themesTraites = array_keys( $themesTraites );
			sort( $themesTraites );

			if ( Configure::read( 'Cg.departement' ) == 93 || Configure::read( 'Cg.departement' ) == 58 ) {
				$dossiersParCommune = array();
				foreach( $dossierseps as $dossierep ) {
					$commune = $dossierep['Adresse']['locaadr'];
					if( !isset( $dossiersParCommune[$commune] ) ) {
						$dossiersParCommune[$commune] = array();
					}
					$dossiersParCommune[$commune][$dossierep['Dossierep']['themeep']] = $dossierep[0]['nombre'];
				}

				$dossierseps = array();
				$default = array();
				foreach( $themesTraites as $themeTraite ) {
					$default[Inflector::pluralize($themeTraite)] = 0;
				}

				foreach( $dossiersParCommune as $commune => $dossierParCommune ) {
					$dossierParCommune = array_merge( array( 'commune' => $commune ), $default, $dossierParCommune );
					$dossierParCommune['total'] = array_sum( $dossierParCommune );
					$dossierseps[] = $dossierParCommune;
				}
			}

			// present, excuse, FIXME: remplace_par
			$reponsesTmp = $this->CommissionepMembreep->find(
				'all',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $convocation['Commissionep']['id']
					),
					'contain' => array(
						'Membreep' => array(
							'Fonctionmembreep'
						),
						'Remplacantmembreep'
					)
				)
			);

			$reponses = array();
			foreach( $reponsesTmp as $reponse ) {
				$reponses["Reponses_{$reponse['CommissionepMembreep']['reponse']}"][] = array( 'Membreep' => $reponse['Membreep'], 'Remplacantmembreep' => $reponse['Remplacantmembreep'] );
			}
			foreach( $options['CommissionepMembreep']['reponse'] as $typereponse => $libelle ) {
				if( !isset( $reponses["Reponses_{$typereponse}"] ) ) {
					$reponses["Reponses_{$typereponse}"] = array();
				}
				$commissionep_data["reponses_{$typereponse}_count"] = count( $reponses["Reponses_{$typereponse}"] );
			}

			// Fiches synthétiques des dossiers d'EP
			$fichessynthetiques = $this->Passagecommissionep->Dossierep->find(
				'all',
				$this->_qdFichesSynthetiques( array( 'Passagecommissionep.commissionep_id' => $convocation['Commissionep']['id'] ) )
			);

			$options['Foyer']['sitfam'] = ClassRegistry::init( 'Option' )->sitfam();


			$user = $this->Passagecommissionep->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => array(
						'Serviceinstructeur'
					)
				)
			);

			$convocation = Set::merge( $convocation, $user );


			if( Configure::read( 'Cg.departement' ) == 58 ) {
				$options['Referentpropo']['qual'] = $options['Referentcer']['qual'] = $options['Referent']['qual'] = $options['Personne']['qual'];
				$options['Structurereferentepropo']['type_voie'] = $options['Structurereferentecer']['type_voie'] = $options['Structurereferente']['type_voie'] = $options['Participant']['typevoie'];
				$options['Type']['voie'] = $options['type']['voie'] = $options['Structurereferente']['type_voie'];
				$options['Contratinsertion']['duree_engag'] = $options['Duree']['engag'] = ClassRegistry::init( 'Option' )->duree_engag_cg58();
			}

			$typeEp = $convocation['Commissionep']['Ep']['Regroupementep'];
			if( Configure::read( 'Cg.departement' ) != 66 ) {
				$ordredujourodt = "ordredujour_participant_".Configure::read( 'Cg.departement' );
			}
			else {
				if( $typeEp['saisinebilanparcoursep66'] != 'nontraite' ) {
					$ordredujourodt = "ordredujour_participant_parcours";
				}
				else {
					$ordredujourodt = "ordredujour_participant_audition";
				}

			}

			return $this->ged(
				array_merge(
					array(
						$convocation,
						'Dossierseps' => $dossierseps,
						'Fichessynthetiques' => $fichessynthetiques
					),
					$reponses
				),
				"{$this->alias}/{$ordredujourodt}.odt",
				true,
				$options
			);
		}



		/**
		*
		*/

		public function getPdfDecision( $commissionep_id ) {

			$commissionep_data = $this->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => false
				)
			);

			$queryData = array(
				'fields' => array(
					'Dossierep.id',
					'Dossierep.personne_id',
					'Dossierep.themeep',
					'Dossierep.created',
					'Dossierep.modified',
					'Personne.id',
					'Personne.foyer_id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nomnai',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Personne.rgnai',
					'Personne.typedtnai',
					'Personne.nir',
					'Personne.topvalec',
					'Personne.sexe',
					'Personne.nati',
					'Personne.dtnati',
					'Personne.pieecpres',
					'Personne.idassedic',
					'Personne.numagenpoleemploi',
					'Personne.dtinscpoleemploi',
					'Personne.numfixe',
					'Personne.numport',
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Adresse.codepos',

				),
				'joins' => array(
					array(
						'table'      => 'passagescommissionseps',
						'alias'      => 'Passagecommissionep',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Passagecommissionep.dossierep_id = Dossierep.id',
							'Passagecommissionep.commissionep_id' => $commissionep_id,
						),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.personne_id = Personne.id" ),
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
					)
				)
			);

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			foreach( $this->themesTraites( $commissionep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				if( in_array( 'Enumerable', $this->Passagecommissionep->Dossierep->{$model}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->{$model}->enums() );
				}

				$modeleDecision = Inflector::classify( "decision{$theme}" );
				if( in_array( 'Enumerable', $this->Passagecommissionep->{$modeleDecision}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Passagecommissionep->{$modeleDecision}->enums() );
				}

				foreach( array( 'fields', 'joins' ) as $key ) {
					$qdModele = $this->Passagecommissionep->Dossierep->{$model}->qdProcesVerbal();
					$queryData[$key] = array_merge( $queryData[$key], $qdModele[$key] );
				}
			}
			$options = Set::merge( $options, $this->enums() );
			$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->enums() );
			$options = Set::merge( $options, $this->Membreep->enums() );
			$options = Set::merge( $options, $this->CommissionepMembreep->enums() );

			$dossierseps = $this->Passagecommissionep->Dossierep->find( 'all', $queryData );
			// FIXME: faire la traduction des enums dans les modèles correspondants ?

			return $this->ged(
				array_merge(
					array(
						$commissionep_data,
						'Dossierseps' => $dossierseps
					)
				),
				"{$this->alias}/decisionep.odt",
				true,
				$options
			);
		}

		/**
		* Impression de la fiche synthétique d'un allocataire pour un passage en commission d'EP
		*/

		public function getFicheSynthese( $commissionep_id, $dossierep_id, $anonymiser = false ) {
			$queryData = array(
				'fields' => array(
					'Dossierep.id',
					'Dossierep.personne_id',
					'Dossierep.themeep',
					'Dossierep.created',
					'Dossierep.modified',
					'Personne.id',
					'Personne.foyer_id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nomnai',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Personne.rgnai',
					'Personne.typedtnai',
					'Personne.nir',
					'Personne.topvalec',
					'Personne.sexe',
					'Personne.nati',
					'Personne.dtnati',
					'Personne.pieecpres',
					'Personne.idassedic',
					'Personne.numagenpoleemploi',
					'Personne.dtinscpoleemploi',
					'Personne.numfixe',
					'Personne.numport',
					'Dossier.matricule',
					'Foyer.sitfam',
					'Adresse.numvoie',
					'Adresse.typevoie',
					'Adresse.nomvoie',
					'Adresse.compladr',
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Adresse.codepos',

				),
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.personne_id = Personne.id" ),
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
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
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
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
					)
				),
				'conditions' => array(
					'Dossierep.id' => $dossierep_id
				)
			);

			// Fiches synthétiques des dossiers d'EP
			$dossierep = $this->Passagecommissionep->Dossierep->find( 'first', $queryData );

			$fichessynthetiques = $this->Passagecommissionep->Dossierep->find(
				'first',
				$this->_qdFichesSynthetiques( array( 'Passagecommissionep.commissionep_id' => $commissionep_id , 'Passagecommissionep.dossierep_id' => $dossierep_id ) )
			);

			$dataFiche = Set::merge( $dossierep, $fichessynthetiques );

			$modeleOption = ClassRegistry::init( 'Option' );

			$options['Foyer']['sitfam'] = $modeleOption->sitfam();
			$options['Personne']['qual'] = $modeleOption->qual();
			$options['Adresse']['typevoie'] = $modeleOption->typevoie();

			$dataFiche['Dossierep']['anonymiser'] = ( $anonymiser ? 1 : 0 );

			return $this->ged(
				$dataFiche,
				"{$this->alias}/fichesynthese.odt",
				false,
				$options
			);
		}

	}
?>
