<?php
	/**
	* Séance d'équipe pluridisciplinaire.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Seanceep extends AppModel
	{
		public $name = 'Seanceep';

		public $displayField = 'dateseance';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id'
				)
			),
			'Enumerable' => array(
				'fields' => array(
					'finalisee'
				)
			),
			'Gedooo'
		);

		public $belongsTo = array(
			'Ep' => array(
				'className' => 'Ep',
				'foreignKey' => 'ep_id',
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
		);

		public $hasMany = array(
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'seanceep_id',
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

		public $hasAndBelongsToMany = array(
			'Membreep' => array(
				'className' => 'Membreep',
				'joinTable' => 'membreseps_seanceseps',
				'foreignKey' => 'seanceep_id',
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
				'with' => 'MembreepSeanceep'
			)
		);

		public function search( $criteresseanceep ) {
			/// Conditions de base

			$conditions = array();

			if ( isset($criteresseanceep['Ep']['regroupementep_id']) && !empty($criteresseanceep['Ep']['regroupementep_id']) ) {
				$conditions[] = array('Ep.regroupementep_id'=>$criteresseanceep['Ep']['regroupementep_id']);
			}

			if ( isset($criteresseanceep['Seanceep']['name']) && !empty($criteresseanceep['Seanceep']['name']) ) {
				$conditions[] = array('Seanceep.name'=>$criteresseanceep['Seanceep']['name']);
			}

			if ( isset($criteresseanceep['Seanceep']['identifiant']) && !empty($criteresseanceep['Seanceep']['identifiant']) ) {
				$conditions[] = array('Seanceep.identifiant'=>$criteresseanceep['Seanceep']['identifiant']);
			}

			if ( isset($criteresseanceep['Seanceep']['structurereferente_id']) && !empty($criteresseanceep['Seanceep']['structurereferente_id']) ) {
				$conditions[] = array( 'Seanceep.structurereferente_id' => preg_replace( '/^([0-9]+_)*([0-9]+)$/', '\2', $criteresseanceep['Seanceep']['structurereferente_id'] ) );
			}

			if ( isset($criteresseanceep['Structurereferente']['ville']) && !empty($criteresseanceep['Structurereferente']['ville']) ) {
				$conditions[] = array('Structurereferente.ville'=>$criteresseanceep['Structurereferente']['ville']);
			}

			/// Critères sur le Comité - date du comité
			if( isset( $criteresseanceep['Seanceep']['dateseance'] ) && !empty( $criteresseanceep['Seanceep']['dateseance'] ) ) {
				$valid_from = ( valid_int( $criteresseanceep['Seanceep']['dateseance_from']['year'] ) && valid_int( $criteresseanceep['Seanceep']['dateseance_from']['month'] ) && valid_int( $criteresseanceep['Seanceep']['dateseance_from']['day'] ) );
				$valid_to = ( valid_int( $criteresseanceep['Seanceep']['dateseance_to']['year'] ) && valid_int( $criteresseanceep['Seanceep']['dateseance_to']['month'] ) && valid_int( $criteresseanceep['Seanceep']['dateseance_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Seanceep.dateseance BETWEEN \''.implode( '-', array( $criteresseanceep['Seanceep']['dateseance_from']['year'], $criteresseanceep['Seanceep']['dateseance_from']['month'], $criteresseanceep['Seanceep']['dateseance_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresseanceep['Seanceep']['dateseance_to']['year'], $criteresseanceep['Seanceep']['dateseance_to']['month'], $criteresseanceep['Seanceep']['dateseance_to']['day'] ) ).'\'';
				}
			}

			$query = array(
				'fields' => array(
					'Seanceep.id',
					'Seanceep.name',
					'Seanceep.identifiant',
					'Seanceep.structurereferente_id',
					'Seanceep.dateseance',
					'Seanceep.finalisee',
					'Seanceep.observations'
				),
				'contain'=>array(
					'Structurereferente',
					'Ep' => array(
						'fields'=>array(
							'id',
							'name',
							'identifiant'
						),
						'Regroupementep'
					)
				),
				'order' => array( '"Seanceep"."dateseance" ASC' ),
				'conditions' => $conditions
			);

			return $query;
		}

		/**
		* Renvoie un array associatif contenant les thèmes traités par l'équipe
		* ainsi que le niveau de décision pour chacun de ces thèmes.
		*
		* @param integer $id L'id technique de la séance d'EP
		* @return array
		* @access public
		*/

		public function themesTraites( $id ) {
			$ep = $this->find(
				'first',
				array(
					'conditions' => array(
						"{$this->alias}.{$this->primaryKey}" => $id
					),
					'contain' => array( 'Ep' )
				)
			);

			$themes = $this->Ep->themes();
			$themesTraites = array();

			foreach( $themes as $theme ) {
				if( in_array( $ep['Ep'][$theme], array( 'ep', 'cg' ) ) ) {
					$themesTraites[$theme] = $ep['Ep'][$theme];
				}
			}
			return $themesTraites;
		}

		/**
		* Sauvegarde des avis/décisions par liste d'une séance d'EP, au niveau ep ou cg
		*
		* @param integer $seanceep_id L'id technique de la séance d'EP
		* @param array $data Les données à sauvegarder
		* @param string $niveauDecision Le niveau de décision pour lequel il faut sauvegarder
		* @return boolean
		* @access public
		*/

		public function saveDecisions( $seanceep_id, $data, $niveauDecision ) {
			$success = true;

			foreach( $this->themesTraites( $seanceep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				$success = $this->Dossierep->{$model}->saveDecisions( $data, $niveauDecision ) && $success;
			}

			return $success;
		}

		/**
		* Retourne la liste des dossiers de la séance d'EP, groupés par thème,
		* pour les dossiers qui doivent passer par liste.
		*
		* @param integer $seanceep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut les dossiers à passer par liste.
		* @return array
		* @access public
		*/

		public function dossiersParListe( $seanceep_id, $niveauDecision ) {
			$dossiers = array();

			foreach( $this->themesTraites( $seanceep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				$queryData = $this->Dossierep->{$model}->qdDossiersParListe( $seanceep_id, $niveauDecision );
				$dossiers[$model]['liste'] = array();
				if( !empty( $queryData ) ) {
					$dossiers[$model]['liste'] = $this->Dossierep->find( 'all', $queryData );
				}
			}

			return $dossiers;
		}

		/**
		* Retourne les données par défaut du formulaire de traitement par liste,
		* pour une séance donnée, pour des dossiers données et à un niveau de
		* décision donné.
		*
		* @param integer $seanceep_id L'id technique de la séance d'EP
		* @param array $dossiers Array de résultats de requêtes CakePHP pour
		* 	chacun des thèmes, par liste.
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg')
		*	pour lequel on veut obtenir les données par défaut du formulaire de
		*	traitement.
		* @return array
		* @access public
		*/

		public function prepareFormData( $seanceep_id, $dossiers, $niveauDecision ) {
			$data = array();

			foreach( $this->themesTraites( $seanceep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );

				$data = Set::merge(
					$data,
					$this->Dossierep->{$model}->prepareFormData(
						$seanceep_id,
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
		* @param integer $seanceep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg')
		*	pour lequel on veut finaliser les décisions.
		* @return boolean
		* @access public
		*/

		public function finaliser( $seanceep_id, $niveauDecision ) {
			$success = true;
			$themesTraites = $this->themesTraites( $seanceep_id );

			// Recherche des dossiers pas encore traités à cette étape
			$totalErrors = 0;
			foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
				$themeTraite = Inflector::tableize( $themeTraite );
				$conditions = array(
					'Dossierep.seanceep_id' => $seanceep_id,
					'Dossierep.themeep' => $themeTraite,
					'Dossierep.etapedossierep NOT' => array(
						"decision{$niveauDecision}",
						"traite",
					)
				);

				$totalErrors += $this->Dossierep->find( 'count', array( 'conditions' => $conditions ) );
			}

			if( empty( $totalErrors ) ) {
				foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
					$themeTraite = Inflector::tableize( $themeTraite );

					if( $niveauDecision == $niveauDecisionTheme ) {
						$this->Dossierep->updateAll(
							array( 'Dossierep.etapedossierep' => '\'traite\'' ),
							array(
								'"Dossierep"."seanceep_id"' => $seanceep_id,
								'"Dossierep"."themeep"' => $themeTraite
							)
						);
					}
					else if( $niveauDecisionTheme == 'cg' && $niveauDecision == 'ep' ) {
						$this->Dossierep->updateAll(
							array( 'Dossierep.etapedossierep' => '\'decisioncg\'' ),
							array(
								'"Dossierep"."seanceep_id"' => $seanceep_id,
								'"Dossierep"."themeep"' => $themeTraite
							)
						);
					}
				}

				$seanceep = $this->find(
					'first',
					array(
						'conditions' => array(
							'Seanceep.id' => $seanceep_id
						)
					)
				);

				if( $niveauDecision == 'cg' ) {
					$seanceep['Seanceep']['finalisee'] = 'cg';
					// Finalisation de chacun des dossiers
					foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
						if( $niveauDecisionTheme == $niveauDecision ) {
							$themeTraite = Inflector::tableize( $themeTraite );
							$model = Inflector::classify( $themeTraite );
							$success = $this->Dossierep->{$model}->finaliser( $seanceep_id, $niveauDecisionTheme ) && $success;
						}
					}
				}
				else {
					$niveauxDecisionsSeance = array_keys( $themesTraites );
					$seanceep['Seanceep']['finalisee'] = 'ep';
					if( !in_array( 'cg', $niveauxDecisionsSeance ) ) {
						// Finalisation de chacun des dossiers
						foreach( $themesTraites as $themeTraite => $niveauDecisionTheme ) {
							$themeTraite = Inflector::tableize( $themeTraite );
							$model = Inflector::classify( $themeTraite );
							if( $niveauDecisionTheme == $niveauDecision ) {
								$success = $this->Dossierep->{$model}->finaliser( $seanceep_id, $niveauDecisionTheme ) && $success;
							}
							else {
								$success = $this->Dossierep->{$model}->verrouiller( $seanceep_id, $niveauDecision ) && $success;
							}
						}
					}
				}

				$this->create( $seanceep );
				$success = $this->save() && $success;
			}

			return $success && empty( $totalErrors );
		}

		/**
		 * Savoir si la séance est cloturée ou non (suivant le thème l'EP et le CG ce sont prononcés)
		 */

		public function clotureSeance($datas) {
			$cloture = true;

			foreach( $this->themesTraites( $datas['Seanceep']['id'] ) as $theme => $decision ) {
				$cloture = ($datas['Ep'][$theme]==$datas['Seanceep']['finalisee']) && $cloture;
			}

			return $cloture;
		}

		/**
		*
		*/

		public function getPdfPv( $seanceep_id ) {
			/*
				seanceep_id,
				seanceep_identifiant,
				seanceep_name,
				seanceep_ep_id,
				seanceep_structurereferente_id,
				seanceep_dateseance,
				seanceep_salle,
				seanceep_observations,
				seanceep_finalisee,
				structurereferente_id,
				structurereferente_typeorient_id,
				structurereferente_lib_struc,
				structurereferente_num_voie,
				structurereferente_type_voie,
				structurereferente_nom_voie,
				structurereferente_code_postal,
				structurereferente_ville,
				structurereferente_code_insee,
				structurereferente_filtre_zone_geo,
				structurereferente_contratengagement,
				structurereferente_apre,
				structurereferente_orientation,
				structurereferente_pdo,
			*/
 			$seanceep_data = $this->find(
				'first',
				array(
					'conditions' => array(
						'Seanceep.id' => $seanceep_id
					),
					'contain' => array(
						'Structurereferente'
					)
				)
			);

			$queryData = array(
				'fields' => array(
					'Dossierep.id',
					'Dossierep.personne_id',
					'Dossierep.seanceep_id',
					'Dossierep.etapedossierep',
					'Dossierep.themeep',
					'Dossierep.created',
					'Dossierep.modified',
					//
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
				),
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.personne_id = Personne.id" ),
					),
				),
				'conditions' => array(
					'Dossierep.seanceep_id' => $seanceep_id
				)
			);

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			foreach( $this->themesTraites( $seanceep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );

				$options = Set::merge( $options, $this->Dossierep->{$model}->enums() );
				$modeleDecisions = array( 'Nonrespectsanctionep93' => 'Decisionnonrespectsanctionep93' );// FIXME: à supprimer après le renommage des tables
				if( isset( $modeleDecisions[$model] ) ) {
					$options = Set::merge( $options, $this->Dossierep->{$model}->{$modeleDecisions[$model]}->enums() );
				}

				foreach( array( 'fields', 'joins' ) as $key ) {
					$qdModele = $this->Dossierep->{$model}->qdProcesVerbal();
					$queryData[$key] = array_merge( $queryData[$key], $qdModele[$key] );
				}
			}
			$options = Set::merge( $options, $this->enums() );
			$options = Set::merge( $options, $this->Dossierep->enums() );
			$options = Set::merge( $options, $this->Membreep->enums() );
			$options = Set::merge( $options, $this->MembreepSeanceep->enums() );

 			$dossierseps = $this->Dossierep->find( 'all', $queryData );
			// FIXME: faire la traduction des enums dans les modèles correspondants ?

			// present, excuse, FIXME: remplace_par
 			$presencesTmp = $this->MembreepSeanceep->find(
				'all',
				array(
					'conditions' => array(
						'MembreepSeanceep.seanceep_id' => $seanceep_id
					),
					'contain' => array(
						'Membreep' => array(
							'Fonctionmembreep'
						)
					)
				)
			);

			// FIXME: presence -> obliger de prendre les présences avant d'imprimer le PV
			$presences = array();
			foreach( $presencesTmp as $presence ) {
				$presences["Presences_{$presence['MembreepSeanceep']['presence']}"][] = array( 'Membreep' => $presence['Membreep'] );
			}
			foreach( $options['MembreepSeanceep']['presence'] as $typepresence => $libelle ) {
				if( !isset( $presences["Presences_{$typepresence}"] ) ) {
					$presences["Presences_{$typepresence}"] = array();
				}
				$seanceep_data["presences_{$typepresence}_count"] = count( $presences["Presences_{$typepresence}"] );
			}

			return $this->ged(
				array_merge(
					array(
						$seanceep_data,
						'Decisionseps' => $dossierseps,
					),
					$presences
				),
				"{$this->alias}/pv.odt",
				true,
				$options
			);
		}

		/**
		*
		*/

		public function getPdfOrdreDuJour( $seanceep_id ) {
 			$seanceep_data = $this->find(
				'first',
				array(
					'conditions' => array(
						'Seanceep.id' => $seanceep_id
					),
					'contain' => array(
						'Structurereferente'
					)
				)
			);

			$queryData = array(
				'fields' => array(
					'Dossierep.id',
					'Dossierep.personne_id',
					'Dossierep.seanceep_id',
					'Dossierep.etapedossierep',
					'Dossierep.themeep',
					'Dossierep.created',
					'Dossierep.modified',
					//
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
				),
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.personne_id = Personne.id" ),
					),
				),
				'conditions' => array(
					'Dossierep.seanceep_id' => $seanceep_id
				)
			);

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			$options = Set::merge( $options, $this->enums() );
			$options = Set::merge( $options, $this->Dossierep->enums() );
			$options = Set::merge( $options, $this->Membreep->enums() );
			$options = Set::merge( $options, $this->MembreepSeanceep->enums() );

 			$dossierseps = $this->Dossierep->find( 'all', $queryData );
			// FIXME: faire la traduction des enums dans les modèles correspondants ?

			// present, excuse, FIXME: remplace_par
 			$reponsesTmp = $this->MembreepSeanceep->find(
				'all',
				array(
					'conditions' => array(
						'MembreepSeanceep.seanceep_id' => $seanceep_id
					),
					'contain' => array(
						'Membreep' => array(
							'Fonctionmembreep'
						)
					)
				)
			);

			// FIXME: presence -> obliger de prendre les présences avant d'imprimer le PV
			$reponses = array();
			foreach( $reponsesTmp as $reponse ) {
				$reponses["Reponses_{$reponse['MembreepSeanceep']['reponse']}"][] = array( 'Membreep' => $reponse['Membreep'] );
			}
			foreach( $options['MembreepSeanceep']['reponse'] as $typereponse => $libelle ) {
				if( !isset( $reponses["Reponses_{$typereponse}"] ) ) {
					$reponses["Reponses_{$typereponse}"] = array();
				}
				$seanceep_data["reponses_{$typereponse}_count"] = count( $reponses["Reponses_{$typereponse}"] );
			}

			return $this->ged(
				array_merge(
					array(
						$seanceep_data,
						'Dossierseps' => $dossierseps,
					),
					$reponses
				),
				"{$this->alias}/ordedujour.odt",
				true,
				$options
			);
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
			$primaryKey = Set::classicExtract( $this->data, "{$this->alias}.{$this->primaryKey}" );
			$identifiant = Set::classicExtract( $this->data, "{$this->alias}.identifiant" );

			if( empty( $primaryKey ) && empty( $identifiant ) ) {
				$this->data[$this->alias]['identifiant'] = $this->identifiant();
			}

			return true;
		}
	}
?>
