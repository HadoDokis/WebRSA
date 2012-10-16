<?php
	/**
	* Commission d'orientation et validation (COV)
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Cov58 extends AppModel
	{
		public $name = 'Cov58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id'
				)
			),
			'Enumerable' => array(
				'fields' => array(
					'etatcov'
				)
			),
			'Gedooo.Gedooo',
			'ModelesodtConditionnables' => array(
				58 => array(
					'%s/ordredujour.odt',
					'%s/pv.odt',
				)
			)
		);

		public $belongsTo = array(
			'Sitecov58' => array(
				'className' => 'Sitecov58',
				'foreignKey' => 'sitecov58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Passagecov58' => array(
				'className' => 'Passagecov58',
				'foreignKey' => 'cov58_id',
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

		public function search( $criterescov58 ) {
			/// Conditions de base

			$conditions = array();

			if ( isset($criterescov58['Cov58']['name']) && !empty($criterescov58['Cov58']['name']) ) {
				$conditions[] = array( 'Cov58.name ILIKE' => $this->wildcard( $criterescov58['Cov58']['name'] ) );
			}

			if ( isset($criterescov58['Cov58']['sitecov58_id']) && !empty($criterescov58['Cov58']['sitecov58_id']) ) {
				$conditions[] = array( 'Cov58.sitecov58_id' => $criterescov58['Cov58']['sitecov58_id'] );
			}

			if ( isset($criterescov58['Cov58']['lieu']) && !empty($criterescov58['Cov58']['lieu']) ) {
				$conditions[] = array('Cov58.lieu'=>$criterescov58['Cov58']['lieu']);
			}

			/// Critères sur le Comité - date du comité
			if( isset( $criterescov58['Cov58']['datecommission'] ) && !empty( $criterescov58['Cov58']['datecommission'] ) ) {
				$valid_from = ( valid_int( $criterescov58['Cov58']['datecommission_from']['year'] ) && valid_int( $criterescov58['Cov58']['datecommission_from']['month'] ) && valid_int( $criterescov58['Cov58']['datecommission_from']['day'] ) );
				$valid_to = ( valid_int( $criterescov58['Cov58']['datecommission_to']['year'] ) && valid_int( $criterescov58['Cov58']['datecommission_to']['month'] ) && valid_int( $criterescov58['Cov58']['datecommission_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Cov58.datecommission BETWEEN \''.implode( '-', array( $criterescov58['Cov58']['datecommission_from']['year'], $criterescov58['Cov58']['datecommission_from']['month'], $criterescov58['Cov58']['datecommission_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterescov58['Cov58']['datecommission_to']['year'], $criterescov58['Cov58']['datecommission_to']['month'], $criterescov58['Cov58']['datecommission_to']['day'] ) ).'\'';
				}
			}

			$query = array(
				'fields' => array(
					'Cov58.id',
					'Cov58.name',
					'Cov58.datecommission',
					'Cov58.etatcov',
					'Cov58.observation'
				),
				'contain'=> array( 'Sitecov58' => array( 'fields' => array( 'name' ) ) ),
				'order' => array( '"Cov58"."datecommission" ASC' ),
				'conditions' => $conditions
			);

			return $query;
		}

		/**
		* Retourne la liste des dossiers de la séance d'une COV, groupés par thème,
		* pour les dossiers qui doivent passer par liste.
		*
		* @param integer $cov58_id L'id technique de la COV
		* @return array
		* @access public
		*/

		public function dossiersParListe( $cov58_id ) {
			$dossiers = array();

			foreach( $this->themesTraites( $cov58_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				$queryData = $this->Passagecov58->Dossiercov58->{$model}->qdDossiersParListe( $cov58_id );
				$dossiers[$model]['liste'] = array();
				if( !empty( $queryData ) ) {
					$dossiers[$model]['liste'] = $this->Passagecov58->Dossiercov58->find( 'all', $queryData );
				}
			}

			return $dossiers;
		}

		/**
		* Sauvegarde des avis/décisions par liste d'une séance d'EP, au niveau ep ou cg
		*
		* @param integer $cov58_id L'id technique de la séance d'EP
		* @param array $data Les données à sauvegarder
		* @return boolean
		* @access public
		*/

		public function saveDecisions( $cov58_id, $data ) {
			$cov58 = $this->find( 'first', array( 'conditions' => array( 'Cov58.id' => $cov58_id ) ) );

			if( empty( $cov58 ) ) {
				return false;
			}

			$success = true;

			// Champs à conserver en cas d'annulation ou de report
			$champsAGarder = array( 'id', 'etapecov', 'passagecov58_id', 'created', 'modified' );
			$champsAGarderPourNonDecision = Set::merge( $champsAGarder, array( 'decisioncov', 'commentaire' ) );

			foreach( $this->themesTraites( $cov58_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				$modeleDecision = Inflector::classify( "decision{$theme}" );

				if( isset( $data[$model] ) || isset( $data[$modeleDecision] ) && !empty( $data[$modeleDecision] ) ) {

					// Mise à NULL de certains champs de décision
					$champsDecision = array_keys( $this->Passagecov58->{$modeleDecision}->schema( true ) );
					$champsANull = array_fill_keys( array_diff( $champsDecision, $champsAGarder ), null );
					$champsANullPourNonDecision = array_diff( $champsDecision, $champsAGarderPourNonDecision );

					foreach( $data[$modeleDecision] as $i => $decision ) {
						// 1°) En cas d'annulation ou de report
						if( in_array( $decision['decisioncov'], array( 'annule', 'reporte' ) ) ) {
							foreach( $champsANullPourNonDecision as $champ ) {
								$data[$modeleDecision][$i][$champ] = null;
							}
						}
						// 2°) Dans les autres cas
						else {
							$data[$modeleDecision][$i] = Set::merge( $champsANull, $decision );
						}
					}
// debug( $data );
// die();
					$success = $this->Passagecov58->Dossiercov58->{$model}->saveDecisions( $data ) && $success;
				}
			}

			///FIXME : calculer si tous les dossiers ont bien une décision avant de changer l'état ?
			$this->id = $cov58_id;
			$this->set( 'etatcov', "finalise" );
			$success = $this->save() && $success;

			return $success;
		}


		/**
		*
		*/

		public function getPdfOrdreDuJour( $cov58_id ) {
			$cov58_data = $this->find(
				'first',
				array(
					'conditions' => array(
						'Cov58.id' => $cov58_id
					),
					'contain' => false
				)
			);

			$queryData = array(
				'fields' => array(
					'Dossiercov58.id',
					'Dossiercov58.personne_id',
					'Dossiercov58.themecov58_id',
					'Dossiercov58.themecov58',
					'Themecov58.id',
					'Themecov58.name',
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
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Adresse.codepos',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa'/*,
					'Typeorient.lib_type_orient',*/
				),
				'joins' => array(
					array(
						'table'      => 'themescovs58',
						'alias'      => 'Themecov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Themecov58.id = Dossiercov58.themecov58_id" ),
					),
					array(
						'table'      => 'passagescovs58',
						'alias'      => 'Passagecov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Passagecov58.dossiercov58_id = Dossiercov58.id" ),
					),
					array(
						'table'      => 'covs58',
						'alias'      => 'Cov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Cov58.id = Passagecov58.cov58_id" ),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossiercov58.personne_id = Personne.id" ),
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
				'contain' => false,
				'conditions' => array(
					'Cov58.id' => $cov58_id
				)
			);

			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
			foreach( $this->Passagecov58->Dossiercov58->Themecov58->themes() as $theme ) {
				$model = Inflector::classify( $theme );
				if( in_array( 'Enumerable', $this->Passagecov58->Dossiercov58->{$model}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Passagecov58->Dossiercov58->{$model}->enums() );
				}

				$qdModele = $this->Passagecov58->Dossiercov58->{$model}->qdOrdreDuJour();
				foreach( array( 'fields', 'joins', 'contain' ) as $key ) {
					if( isset( $qdModele[$key] ) ) {
						if( !isset( $queryData[$key] ) ) {
							$queryData[$key] = array();
						}
						$queryData[$key] = array_merge( (array)$queryData[$key], (array)$qdModele[$key] );
					}
				}
			}

			$options = Set::merge( $options, $this->enums() );

			$dossierscovs58 = $this->Passagecov58->Dossiercov58->find( 'all', $queryData );
			// FIXME: faire la traduction des enums dans les modèles correspondants ?
			$this->Informationpe = ClassRegistry::init( 'Informationpe' );
			foreach( $dossierscovs58 as $key => $dossiercov58 ) {

				$infope = $this->Informationpe->derniereInformation( $dossiercov58 );
				$dossierscovs58[$key]['Personne']['inscritpe'] = ( isset( $infope['Historiqueetatpe'][0]['etat'] ) && $infope['Historiqueetatpe'][0]['etat'] == 'inscription' ) ? 'Oui' : 'Non';

				// Traduction ...
				$dossierscovs58[$key]['Themecov58']['name'] = __d( 'dossiercov58', 'ENUM::THEMECOV::'.$dossiercov58['Themecov58']['name'] );
			}
// debug($dossierscovs58);
// die();
			return $this->ged(
				array_merge(
					array(
						$cov58_data,
						'Decisionscovs58' => $dossierscovs58
					)
				),
				"{$this->alias}/ordredujour.odt",
				true,
				$options
			);
		}




		/**
		* Change l'état de la commission de COV entre 'cree' et 'associe'
		* S'il existe au moins un dossier associé et un membre ayant donné une réponse
		* "Confirmé" ou "Remplacé par", l'état devient associé, sinon l'état devient 'cree'
		*
		* FIXME: il faudrait une réponse pour tous les membres ?
		*
		* @param integer $cov58_id L'identifiant technique de la commission d'EP
		* @return boolean
		*/

		public function changeEtatCreeAssocie( $cov58_id ) {
			$cov58 = $this->find(
				'first',
				array(
					'conditions' => array(
						'Cov58.id' => $cov58_id
					),
					'contain' => false
				)
			);

			if( empty( $cov58 ) || !in_array( $cov58['Cov58']['etatcov'], array( 'cree', 'associe' ) ) ) {
				return false;
			}
// debug($cov58);
// die();
			$success = true;

			$nbDossierscovs58 = $this->Passagecov58->find(
				'count',
				array(
					'conditions' => array(
						'Passagecov58.cov58_id' => $cov58_id
					)
				)
			);
// debug($nbDossierscovs58);
// die();
			$this->id = $cov58_id;
			if( ( $nbDossierscovs58 > 0 ) && ( $cov58['Cov58']['etatcov'] == 'cree' ) ) {
				$this->set( 'etatcov', 'associe' );
				$success = $this->save() && $success;

			}
			else if( ( ( $nbDossierscovs58 == 0 ) && ( $cov58['Cov58']['etatcov'] == 'associe' ) ) ) {
				$this->set( 'etatcov', 'cree' );
				$success = $this->save() && $success;
			}
			return $success;
		}

		public function themesTraites( $cov58_id ){
			$themecov58 = $this->Passagecov58->Dossiercov58->Themecov58->find(
				'first',
				array(
					'contain' => false,
					'conditions' => array(
						'Themecov58.id IN ( '.
							$this->Passagecov58->Dossiercov58->sq(
								array(
									'alias' => 'dossierscovs58',
									'fields' => array( 'dossierscovs58.themecov58_id' ),
									'conditions' => array(
										'dossierscovs58.id IN ( '.
											$this->sq(
												array(
													'alias' => 'covs58',
													'fields' => array( 'covs58.id' ),
													'conditions' => array(
														'covs58.id' => $cov58_id
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

			$themes = $this->Passagecov58->Dossiercov58->Themecov58->themes();
			$themesTraites = array();

			foreach( $themes as $theme ) {
// 				if( in_array( $themecov58['Themecov58']['name'], array( 'decisionep', 'decisioncg' ) ) ) {
					$themesTraites[$theme] = $themecov58['Themecov58'][$theme];
// 				}
			}

			return $themesTraites;
		}




		/**
		*
		*/

		public function getPdfPv( $cov58_id ) {
			$cov58_data = $this->find(
				'first',
				array(
					'conditions' => array(
						'Cov58.id' => $cov58_id
					),
					'contain' => false
				)
			);

			$queryData = array(
				'fields' => array(
					'Dossiercov58.id',
					'Dossiercov58.personne_id',
					'Dossiercov58.themecov58_id',
					'Themecov58.id',
					'Themecov58.name',
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
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Adresse.codepos',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
				),
				'conditions' => array(
					'Cov58.id' => $cov58_id
				),
				'joins' => array(
					array(
						'table'      => 'themescovs58',
						'alias'      => 'Themecov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Themecov58.id = Dossiercov58.themecov58_id" ),
					),
					array(
						'table'      => 'passagescovs58',
						'alias'      => 'Passagecov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Passagecov58.dossiercov58_id = Dossiercov58.id" ),
					),
					array(
						'table'      => 'covs58',
						'alias'      => 'Cov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Cov58.id = Passagecov58.cov58_id" ),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossiercov58.personne_id = Personne.id" ),
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
				'contain' => false
			);
			$options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );

			$themeClassNames = array();
			foreach( $this->Passagecov58->Dossiercov58->Themecov58->themes() as $theme ) {
				$model = Inflector::classify( $theme );
				$themeClassNames[] = $model;

				if( in_array( 'Enumerable', $this->Passagecov58->Dossiercov58->{$model}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Passagecov58->Dossiercov58->{$model}->enums() );
				}

				$qdModele = $this->Passagecov58->Dossiercov58->{$model}->qdProcesVerbal();
				foreach( array( 'fields', 'joins' ) as $key ) {
					$queryData[$key] = array_merge( $queryData[$key], $qdModele[$key] );
				}
			}
			$options = Set::merge( $options, $this->enums() );

			// Combinaison des jointures
			if( isset( $queryData['joins'] ) && !empty( $queryData['joins'] ) ) {
				$joins = array();
				$joinIndices = array();
				$mergedJoins = array();

				foreach( $queryData['joins'] as $join ) {

					$join['conditions'] = (array)$join['conditions'];
					if( !isset( $joinIndices[$join['alias']] ) ) {
						$joins[] = $join;
						$joinIndices[$join['alias']] = count( $joins ) - 1;
					}
					else {
						$mergedJoins[] = $joinIndices[$join['alias']];
						if( !isset( $joins[$joinIndices[$join['alias']]]['conditions']['OR'] ) ) {
							$joins[$joinIndices[$join['alias']]]['conditions'] = array(
								'OR' => array(
									$joins[$joinIndices[$join['alias']]]['conditions'],
									$join['conditions']
								)
							);
						}
						else {
							$joins[$joinIndices[$join['alias']]]['conditions']['OR'][] = $join['conditions'];
						}
					}
				}

				if( !empty( $mergedJoins ) ) {
					foreach( $mergedJoins as $indice ) {
						$join = $joins[$indice];
						unset( $joins[$indice] );
						$joins[] = $join;
					}
				}

				$queryData['joins'] = array_values( $joins );
			}

			$dossierscovs58 = $this->Passagecov58->Dossiercov58->find( 'all', $queryData );

			// Préparation d'un enregistrement vide
			if( !empty( $dossierscovs58 ) ) {
				$empty = array_keys( Set::flatten( $dossierscovs58[0] ) );
				$empty = Xset::bump( Set::normalize( $empty ) );
				foreach( $themeClassNames as $themeClassName ) {
					$empty[$themeClassName]['Typeorient'] = $empty['Typeorient'];
					$empty[$themeClassName]['Structurereferente'] = $empty['Structurereferente'];
				}
				unset( $empty['Typeorient'], $empty['Structurereferente'] );
			}

			// FIXME: faire la traduction des enums dans les modèles correspondants ?
			$this->Informationpe = ClassRegistry::init( 'Informationpe' );
			foreach( $dossierscovs58 as $key => $dossiercov58 ) {

				// Déplacement des données du type d'orientation et de la structure référente
				foreach( $themeClassNames as $themeClassName ) {
					if( !empty( $dossierscovs58[$key][$themeClassName]['id'] ) ) {
						$dossierscovs58[$key][$themeClassName]['Typeorient'] = $dossierscovs58[$key]['Typeorient'];
						$dossierscovs58[$key][$themeClassName]['Structurereferente'] = $dossierscovs58[$key]['Structurereferente'];

						unset( $dossierscovs58[$key]['Typeorient'], $dossierscovs58[$key]['Structurereferente'] );
					}
				}
				/*if( !empty( $dossierscovs58[$key]['Propoorientationcov58']['id'] ) ) {
					$dossierscovs58[$key]['Propoorientationcov58']['Typeorient'] = $dossierscovs58[$key]['Typeorient'];
					$dossierscovs58[$key]['Propoorientationcov58']['Structurereferente'] = $dossierscovs58[$key]['Structurereferente'];

					unset( $dossierscovs58[$key]['Typeorient'], $dossierscovs58[$key]['Structurereferente'] );
				}
				else if( !empty( $dossierscovs58[$key]['Propoorientsocialecov58']['id'] ) ) {
					$dossierscovs58[$key]['Propoorientsocialecov58']['Typeorient'] = $dossierscovs58[$key]['Typeorient'];
					$dossierscovs58[$key]['Propoorientsocialecov58']['Structurereferente'] = $dossierscovs58[$key]['Structurereferente'];

					unset( $dossierscovs58[$key]['Typeorient'], $dossierscovs58[$key]['Structurereferente'] );
				}*/

				// Ajout de données à NULL pour l'impression en sections
				$dossierscovs58[$key] = Set::merge( $empty, $dossierscovs58[$key] );

				/*$champsOrientation = array_keys( $dossierscovs58[$key]['Propoorientationcov58'] );
debug( $champsOrientation );
				$orientationVide = array_fill_keys( $champsOrientation, null );
				$orientationVide['Typeorient'] = array_fill_keys( array_keys( $dossierscovs58[$key]['Propoorientationcov58']['Typeorient'] ), null );
				$orientationVide['Structurereferente'] = array_fill_keys( array_keys( $dossierscovs58[$key]['Propoorientationcov58']['Structurereferente'] ), null );
debug( $orientationVide );*/

				/*if ( isset( $dossiercov58['Propoorientationcov58']['decisioncov'] ) && !empty( $dossiercov58['Propoorientationcov58']['decisioncov'] ) && $dossiercov58['Propoorientationcov58']['rgorient'] > 0 ) {
					$dossierscovs58[$key]['Proporeorientationcov58'] = $dossierscovs58[$key]['Propoorientationcov58'];
					$dossierscovs58[$key]['Propoorientationcov58'] = $orientationVide;
				}
				else {
					$dossierscovs58[$key]['Proporeorientationcov58'] = $orientationVide;
				}*/

				$infope = $this->Informationpe->derniereInformation( $dossiercov58 );
				$dossierscovs58[$key]['Personne']['inscritpe'] = ( isset( $infope['Historiqueetatpe'][0]['etat'] ) && $infope['Historiqueetatpe'][0]['etat'] == 'inscription' ) ? 'Oui' : 'Non';

				// Traduction ...
				$dossierscovs58[$key]['Themecov58']['name'] = __d( 'dossiercov58', 'ENUM::THEMECOV::'.$dossiercov58['Themecov58']['name'], true );
			}

			$decisionscovs = array( 'accepte' => 'Accepté', 'refus' => 'Refusé', 'ajourne' => 'Ajourné' );
			foreach( $themeClassNames as $themeClassName ) {
				$options[$themeClassName]['decisioncov'] = $decisionscovs;
			}
			/*$options['Proporeorientationcov58']['decisioncov'] = $decisionscovs;
			$options['Propocontratinsertioncov58']['decisioncov'] = $decisionscovs;
			$options['Propoorientsocialecov58']['decisioncov'] = $decisionscovs;*/

			return $this->ged(
				array_merge(
					array(
						$cov58_data,
						'Decisionscovs58' => $dossierscovs58,
					)
				),
				"{$this->alias}/pv.odt",
				true,
				$options
			);
		}
	}
?>