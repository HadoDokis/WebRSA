<?php
	App::import( 'Sanitize' );

	class Nonorientationproep extends AppModel {

		public $useTable = false;

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable'
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
			)
		);

		public function searchNonReoriente($datas) {
			$conditions = array();
			$nbmois = Set::classicExtract($datas, 'Filtre.dureenonreorientation');

			/// Critères sur le CI - date de saisi contrat
			if( isset( $datas['Filtre']['df_ci_from'] ) && !empty( $datas['Filtre']['df_ci_from'] ) ) {
				$valid_from = ( valid_int( $datas['Filtre']['df_ci_from']['year'] ) && valid_int( $datas['Filtre']['df_ci_from']['month'] ) && valid_int( $datas['Filtre']['df_ci_from']['day'] ) );
				$valid_to = ( valid_int( $datas['Filtre']['df_ci_to']['year'] ) && valid_int( $datas['Filtre']['df_ci_to']['month'] ) && valid_int( $datas['Filtre']['df_ci_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Contratinsertion.df_ci BETWEEN \''.implode( '-', array( $datas['Filtre']['df_ci_from']['year'], $datas['Filtre']['df_ci_from']['month'], $datas['Filtre']['df_ci_from']['day'] ) ).'\' AND \''.implode( '-', array( $datas['Filtre']['df_ci_to']['year'], $datas['Filtre']['df_ci_to']['month'], $datas['Filtre']['df_ci_to']['day'] ) ).'\'';
				}
			}


			$this->Orientstruct = ClassRegistry::init( 'Orientstruct' );

			$modelName = $this->alias;
			$modelTable = Inflector::tableize( $modelName );

			if( Configure::read( 'Cg.departement' ) == 58 ){
				$conditionsContrat = $conditions;
			}
			else {
				$conditionsContrat = array( 'Contratinsertion.df_ci <=' => date( 'Y-m-d', strtotime( '- '.$nbmois.' month', time() ) ) );
			}

			$conditions = array(
				$conditionsContrat,
				'Orientstruct.statut_orient' => 'Orienté',
				'Orientstruct.id NOT IN (
					SELECT "'.$modelTable.'"."orientstruct_id"
					FROM "'.$modelTable.'"
						INNER JOIN "dossierseps" ON ( "dossierseps"."id" = "'.$modelTable.'"."dossierep_id" )
					WHERE "dossierseps"."id" NOT IN (
						SELECT "passagescommissionseps"."dossierep_id"
						FROM passagescommissionseps
						WHERE "passagescommissionseps"."etatdossierep" = \'traite\'
					)
					AND "dossierseps"."themeep" = \''.$modelTable.'\'
				)',
				'Orientstruct.id NOT IN (
					SELECT '.Inflector::tableize( $this->alias ).'.orientstruct_id
						FROM '.Inflector::tableize( $this->alias ).'
							INNER JOIN dossierseps ON (
								'.Inflector::tableize( $this->alias ).'.dossierep_id = dossierseps.id
							)
						WHERE
							'.Inflector::tableize( $this->alias ).'.orientstruct_id = Orientstruct.id
							AND dossierseps.id IN (
								SELECT "passagescommissionseps"."dossierep_id"
								FROM passagescommissionseps
								WHERE "passagescommissionseps"."etatdossierep" = \'traite\'
							)
							AND ( DATE( NOW() ) - (
								SELECT CAST( decisions'.Inflector::tableize( $this->alias ).'.modified AS DATE )
									FROM decisions'.Inflector::tableize( $this->alias ).'
										INNER JOIN passagescommissionseps ON ( decisions'.Inflector::tableize( $this->alias ).'.passagecommissionep_id = passagescommissionseps.id )
										INNER JOIN dossierseps ON ( passagescommissionseps.dossierep_id = dossierseps.id )
									ORDER BY modified DESC
									LIMIT 1
							) ) <= '.Configure::read( $this->alias.'.delaiCreationContrat' ).'
				)',
				// La dernière
				'Orientstruct.id IN (
							SELECT o.id
								FROM orientsstructs AS o
								WHERE
									o.personne_id = Personne.id
									AND o.date_valid IS NOT NULL
								ORDER BY o.date_valid DESC
								LIMIT 1
				)'
			);


			$cohorte = array(
				'fields' => array(
					'Orientstruct.id',
					'Orientstruct.date_valid',
					'Orientstruct.user_id',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Referent.qual',
					'Referent.nom',
					'Referent.prenom',
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Dossier.numdemrsa',
					'Adresse.codepos',
					'Contratinsertion.df_ci',
					'( DATE( NOW() ) - "Contratinsertion"."df_ci" ) AS "Contratinsertion__nbjours"'
				),
				'conditions' => $conditions,
				'joins' => array(
					array(
						'table' => 'structuresreferentes',
						'alias' => 'Structurereferente',
						'type' => 'INNER',
						'conditions' => array(
							'Orientstruct.structurereferente_id = Structurereferente.id'
						)
					),
					array(
						'table' => 'typesorients',
						'alias' => 'Typeorient',
						'type' => 'INNER',
						'conditions' => array(
							'Structurereferente.typeorient_id = Typeorient.id',
							'Typeorient.lib_type_orient NOT LIKE' => 'Emploi%'
						)
					),
					array(
						'table' => 'referents',
						'alias' => 'Referent',
						'type' => 'LEFT OUTER',
						'conditions' => array(
							'Orientstruct.referent_id = Referent.id'
						)
					),
					array(
						'table' => 'personnes',
						'alias' => 'Personne',
						'type' => 'INNER',
						'conditions' => array(
							'Orientstruct.personne_id = Personne.id'
						)
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest' => 'RSA',
							'Prestation.rolepers' => array( 'DEM', 'CJT' ),
						)
					),
					array(
						'table'      => 'calculsdroitsrsa',
						'alias'      => 'Calculdroitrsa',
						'type'       => 'INNER',
						'conditions' => array(
							'Personne.id = Calculdroitrsa.personne_id',
							'Calculdroitrsa.toppersdrodevorsa' => 1
						)
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'conditions' => array(
							'Dossier.id = Situationdossierrsa.dossier_id',
							'Situationdossierrsa.etatdosrsa' => $this->Orientstruct->Personne->Foyer->Dossier->Situationdossierrsa->etatOuvert()
						)
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'INNER',
						'conditions' => array(
							'Adressefoyer.foyer_id = Foyer.id',
							'Adressefoyer.rgadr' => '01'
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'conditions' => array( 'Adressefoyer.adresse_id = Adresse.id' )
					),
					array(
						'table'      => 'contratsinsertion',
						'alias'      => 'Contratinsertion',
						'type'       => 'INNER',
						'conditions' => array(
							'Contratinsertion.personne_id = Orientstruct.personne_id',
							'Contratinsertion.structurereferente_id = Orientstruct.structurereferente_id'
						)
					)
				),
				'contain' => false,
				'order' => array( 'Contratinsertion.nbjours DESC' )
			);
			return $cohorte;
		}

		public function saveCohorte( $datas ) {
			$success = true;

			foreach( $datas['Nonorientationproep'] as $dossier ) {
				if ( $dossier['passageep'] == 1 ) {
					$dossierep = array(
						'Dossierep' => array(
							'personne_id' => $dossier['personne_id'],
							'etapedossierep' => 'cree',
							'themeep' => Inflector::tableize( $this->alias )
						)
					);
					$this->Dossierep->create( $dossierep );
					$success = $this->Dossierep->save() && $success;

					$nonorientationproep = array(
						$this->alias => array(
							'dossierep_id' => $this->Dossierep->id,
							'orientstruct_id' => $dossier['orientstruct_id'],
							'user_id' => ( isset( $dossier['user_id'] ) ) ? $dossier['user_id'] : null
						)
					);
					$this->create( $nonorientationproep );
					$success = $this->save() && $success;
				}
			}

			return $success;
		}

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
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
							'Referent'
						)
					),
					'Passagecommissionep' => array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id
						),
						'Decision'.Inflector::underscore( $this->alias ) => array(
							'Typeorient',
							'Structurereferente',
							'order' => array( 'etape DESC' )
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
// debug($datas);
			$formData = array();
			foreach( $datas as $key => $dossierep ) {
				$formData['Decision'.Inflector::underscore( $this->alias )][$key]['passagecommissionep_id'] = $dossierep['Passagecommissionep'][0]['id'];
				if( $niveauDecision == 'ep' ) {
					if( isset( $datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0] ) ) { // Modification
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['decision'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['decision'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['raisonnonpassage'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['raisonnonpassage'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'].'_'.@$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['structurereferente_id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'];
					}
				}
				elseif( $niveauDecision == 'cg' ) {
					if( !empty( $datas[$key][$this->alias]['Decision'.Inflector::underscore( $this->alias )][1] ) ) { // Modification
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['id'];
					}
					$formData['Decision'.Inflector::underscore( $this->alias )][$key]['decision'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['decision'];
					$formData['Decision'.Inflector::underscore( $this->alias )][$key]['raisonnonpassage'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['raisonnonpassage'];
					$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'].'_'.@$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['structurereferente_id'];
					$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'];
				}
			}
// debug( $formData );

			return $formData;
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			$success = true;
			if ( isset( $data['Decision'.Inflector::underscore( $this->alias )] ) && !empty( $data['Decision'.Inflector::underscore( $this->alias )] ) ) {
				foreach( $data['Decision'.Inflector::underscore( $this->alias )] as $key => $values ) {
					if ( isset( $values['structurereferente_id'] ) ) $structurereferente = explode( '_', $values['structurereferente_id'] );
					if ( isset( $structurereferente[1] ) && $values['decision'] == 'reorientation' ) {
						$data['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = $structurereferente[1];
					}
					else {
						$data['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = null;
						$data['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = null;
					}
				}
				$success = $this->Dossierep->Passagecommissionep->{'Decision'.Inflector::underscore($this->alias)}->saveAll( Set::extract( $data, '/'.'Decision'.Inflector::underscore( $this->alias ) ), array( 'atomic' => false ) );

				$this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => Set::extract( $data, '/Decision'.Inflector::underscore( $this->alias ).'/passagecommissionep_id' ) )
				);
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
		*
		*/

		public function finaliser( $commissionep_id, $etape, $user_id = null ) {
			$decisionModelName = 'Decisionnonorientationproep'.Configure::read( 'Cg.departement' );

			$commissionep = $this->Dossierep->Commissionep->find(
				'first',
				array(
					'conditions' => array( 'Commissionep.id' => $commissionep_id ),
					'contain' => array( 'Ep' )
				)
			);

			$niveauDecisionFinale = $commissionep['Ep'][Inflector::underscore( $this->alias )];

			$dossierseps = $this->find(
				'all',
				array(
					'conditions' => array(
						'Dossierep.commissionep_id' => $commissionep_id,
						'Dossierep.themeep' => Inflector::tableize( $this->alias )
					),
					'contain' => array(
						$decisionModelName => array(
							'conditions' => array(
								$decisionModelName.'.etape' => $etape
							)
						),
						'Dossierep'
					)
				)
			);

			$success = true;

			if( $niveauDecisionFinale == $etape ) {
				foreach( $dossierseps as $dossierep ) {
					if( !isset( $dossierep[$decisionModelName][0]['decision'] ) ) {
						$success = false;
					}
					elseif ( $dossierep[$decisionModelName][0]['decision'] == 'reorientation' ) {
						list($date_propo, $heure_propo) = explode( ' ', $dossierep[$this->alias]['created'] );
						list($date_valid, $heure_valid) = explode( ' ', $commissionep['Commissionep']['dateseance'] );
						$orientstruct = array(
							'Orientstruct' => array(
								'personne_id' => $dossierep['Dossierep']['personne_id'],
								'typeorient_id' => @$dossierep[$decisionModelName][0]['typeorient_id'],
								'structurereferente_id' => @$dossierep[$decisionModelName][0]['structurereferente_id'],
								'date_propo' => $date_propo,
								'date_valid' => $date_valid,
								'statut_orient' => 'Orienté',
								'rgorient' => $this->Orientstruct->rgorientMax( $dossierep['Dossierep']['personne_id'] ) + 1,
								'etatorient' => 'decision',
								'user_id' => $user_id
							)
						);

						$this->Orientstruct->create( $orientstruct );
						$success = $this->Orientstruct->save() && $success;
			}
				}
			}

			return $success;
		}

	}
?>