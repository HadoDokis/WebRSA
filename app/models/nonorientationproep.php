<?php
	App::import( 'Sanitize' );

	class Nonorientationproep extends AppModel {

		public $useTable = false;

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable',
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
			)
		);

		public function searchNonReoriente( $mesCodesInsee, $filtre_zone_geo, $datas) {
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
									AND o.rgorient IS NOT NULL
								ORDER BY o.rgorient DESC
								LIMIT 1
				)'
			);

			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );

			if( Configure::read( 'Cg.departement' ) == 58 ) {

				// On souhaite n'afficher que les orientations en social ne possédant encore pas de dossier COV
				/*
					1°) On a un dossier COV en cours de passage (<> finalisé (accepté/refusé), <> reporté) // {cree,traitement,ajourne,finalise}
					2°) Si COV accepte -> on a un dossier en EP -> OK (voir plus haut)
					3°) Si COV refuse -> il doit réapparaître
					4°) ATTENTION: accepté/refusé -> nouvelle orientation
				*/
				$conditions[] = array(
					'Orientstruct.id NOT IN (
						SELECT "proposnonorientationsproscovs58"."orientstruct_id"
							FROM proposnonorientationsproscovs58
								INNER JOIN "dossierscovs58"
									ON ( "dossierscovs58"."id" = "proposnonorientationsproscovs58"."dossiercov58_id" )
							WHERE
								"dossierscovs58"."id" NOT IN (
									SELECT "passagescovs58"."dossiercov58_id"
									FROM passagescovs58
									WHERE "passagescovs58"."etatdossiercov" = \'traite\'
								)
								AND "dossierscovs58"."themecov58" = \'proposnonorientationsproscovs58\'
								AND "proposnonorientationsproscovs58"."orientstruct_id" = Orientstruct.id
					)'/*,
					'Orientstruct.id NOT IN (
						SELECT "proposnonorientationsproscovs58"."orientstruct_id"
							FROM proposnonorientationsproscovs58
								INNER JOIN dossierscovs58 ON (
									proposnonorientationsproscovs58.dossiercov58_id = dossierscovs58.id
								)
							WHERE
								"proposnonorientationsproscovs58"."orientstruct_id" = Orientstruct.id
								AND dossierscovs58.id IN (
									SELECT "passagescovs58"."dossiercov58_id"
									FROM passagescovs58
									WHERE "passagescovs58"."etatdossiercov" = \'traite\'
								)
								AND ( DATE( NOW() ) - (
									SELECT CAST( decisionsproposnonorientationsproscovs58.modified AS DATE )
										FROM decisionsproposnonorientationsproscovs58
											INNER JOIN passagescovs58 ON ( decisionsproposnonorientationsproscovs58.passagecov58_id = passagescovs58.id )
											INNER JOIN dossierscovs58 ON ( passagescovs58.dossiercov58_id = dossierscovs58.id )
										ORDER BY modified DESC
										LIMIT 1
								) ) <= '.Configure::read( $this->alias.'.delaiCreationContrat' ).'
					)'*/
				);

				$conditionJoinTypeorient = 'Typeorient.id <>';
				$valueJoinTypeorient = Configure::read( 'Typeorient.emploi_id' );
			}
			else {
				$conditionJoinTypeorient = 'Typeorient.lib_type_orient NOT LIKE';
				$valueJoinTypeorient = 'Emploi%';
			}

			$cohorte = array(
				'fields' => array(
					'Orientstruct.id',
					'Orientstruct.date_valid',
					'Orientstruct.user_id',
					'Typeorient.id',
					'Typeorient.lib_type_orient',
					'Structurereferente.id',
					'Structurereferente.lib_struc',
					'Foyer.enerreur',
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
							$conditionJoinTypeorient => $valueJoinTypeorient
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
				else if ( ( Configure::read( 'Cg.departement' ) == 58 ) && $dossier['passagecov'] == 1 ) {

					$themecov58 = $this->Orientstruct->Propononorientationprocov58->Dossiercov58->Themecov58->find(
						'first',
						array(
							'conditions' => array(
								'Themecov58.name' => Inflector::tableize($this->Orientstruct->Propononorientationprocov58->alias)
							),
							'contain' => false
						)
					);

					$dossiercov58 = array(
						'Dossiercov58' => array(
							'themecov58_id' => $themecov58['Themecov58']['id'],
							'themecov58' => 'proposnonorientationsproscovs58',
							'personne_id' => $dossier['personne_id']/*,
							'typeorient_id' => $dossier['typeorient_id'],
							'structurereferente_id' => $dossier['structurereferente_id'],
							'orientstruct_id' => $dossier['orientstruct_id']*/
						)
					);
					$this->Orientstruct->Propononorientationprocov58->Dossiercov58->create( $dossiercov58 );
					$success = $this->Orientstruct->Propononorientationprocov58->Dossiercov58->save() && $success;

					$propononorientationprocov58 = array(
						'Propononorientationprocov58' => array(
							'dossiercov58_id' => $this->Orientstruct->Propononorientationprocov58->Dossiercov58->id,
							'personne_id' => $dossier['personne_id'],
							'typeorient_id' => $dossier['typeorient_id'],
							'structurereferente_id' => $dossier['structurereferente_id'],
							'orientstruct_id' => $dossier['orientstruct_id'],
							'rgorient' => $this->Orientstruct->rgorientMax( $dossiercov58['Dossiercov58']['personne_id'] ) + 1,
							'datedemande' => date( 'd-m-Y' ),
							'user_id' => ( isset( $dossier['user_id'] ) ) ? $dossier['user_id'] : null
						)
					);
					$this->Orientstruct->Propononorientationprocov58->create( $propononorientationprocov58 );
					$success = $this->Orientstruct->Propononorientationprocov58->save() && $success;
				}
// debug( $propononorientationprocov58);

			}
			return $success; //FIXME
		}

		public function qdDossiersParListe( $commissionep_id, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			$querydata = array(
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
					),
				)
			);

			if( Configure::read( 'Cg.departement' ) == 58 ){
				$querydata['contain'][$this->alias] = array_merge(
					$querydata['contain'][$this->alias],
					array(
						'Decisionpropononorientationprocov58' => array(
							'Passagecov58' => array(
								'Cov58'
							)
						)
					)
				);
			}

			return $querydata;
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


				// On récupère l'orientation en question afin de trouver le typeorient_id, le structurereferente_id et le referent_id s'il existe
				$orientstruct = $this->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'Orientstruct.id' => $dossierep['Nonorientationproep58']['orientstruct_id']
						),
						'contain' => false
					)
				);

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['etape'] == $niveauDecision ) {
					$formData['Decision'.Inflector::underscore( $this->alias )][$key] = @$dossierep['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0];

					$formData['Decision'.Inflector::underscore( $this->alias )][$key]['referent_id'] = implode(
						'_',
						array(
							$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'],
							$formData['Decision'.Inflector::underscore( $this->alias )][$key]['referent_id']
						)
					);

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
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = $orientstruct['Orientstruct']['typeorient_id'];

						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['referent_id'] = implode(
							'_',
							array(
								$orientstruct['Orientstruct']['structurereferente_id'],
								$orientstruct['Orientstruct']['referent_id']
							)
						);

						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$orientstruct['Orientstruct']['typeorient_id'],
								$orientstruct['Orientstruct']['structurereferente_id']
							)
						);


					}
					elseif( $niveauDecision == 'cg' ) {
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['decision'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['decision'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['raisonnonpassage'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['raisonnonpassage'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['commentaire'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['commentaire'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'].'_'.@$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['structurereferente_id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['referent_id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['structurereferente_id'].'_'.@$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['referent_id'];
					}
				}
			}
// debug($formData);
			return $formData;
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			$success = true;
			if ( isset( $data['Decision'.Inflector::underscore( $this->alias )] ) && !empty( $data['Decision'.Inflector::underscore( $this->alias )] ) ) {
// 				foreach( $data['Decision'.Inflector::underscore( $this->alias )] as $key => $values ) {
// 					if ( isset( $values['structurereferente_id'] ) ) $structurereferente = explode( '_', $values['structurereferente_id'] );
// 					if ( isset( $structurereferente[1] ) && $values['decision'] == 'reorientation' ) {
// 						$data['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = $structurereferente[1];
// 					}
// 					else {
// 						$data['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = null;
// 						$data['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = null;
// 					}
// 				}

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

		public function qdProcesVerbal() {
			$modele = 'Nonorientationproep'.Configure::read( 'Cg.departement' );
			$modeleDecisions = 'Decisionnonorientationproep'.Configure::read( 'Cg.departement' );

			return array(
				'fields' => array(
					"{$modele}.id",
					"{$modele}.dossierep_id",
					"{$modele}.orientstruct_id",
					"{$modele}.created",
					"{$modele}.modified",
					"{$modele}.user_id",
					"{$modeleDecisions}.id",
					"{$modeleDecisions}.etape",
					"{$modeleDecisions}.decision",
					"{$modeleDecisions}.typeorient_id",
					"{$modeleDecisions}.structurereferente_id",
					"{$modeleDecisions}.commentaire",
					"{$modeleDecisions}.created",
					"{$modeleDecisions}.modified",
					"{$modeleDecisions}.passagecommissionep_id",
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
		* Récupération de la décision suite au passage en commission d'un dossier
		* d'EP pour un certain niveau de décision.
		* FIXME: spécifique par thématique
		*/

		public function getDecisionPdf( $passagecommissionep_id  ) {
			$modele = 'Nonorientationproep'.Configure::read( 'Cg.departement' );
			$modeleDecisions = 'Decisionnonorientationproep'.Configure::read( 'Cg.departement' );

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
							$modele
						),
						$modeleDecisions => array(
							'Typeorient',
							'Structurereferente',
							'order' => array(
								$modeleDecisions.'.etape DESC'
							),
							'limit' => 1
						)
					)
				)
			);

			if( empty( $gedooo_data ) || !isset( $gedooo_data[$modeleDecisions][0] ) || empty( $gedooo_data[$modeleDecisions][0] ) ) {
				return false;
			}

			// Choix du modèle de document
			$decision = $gedooo_data[$modeleDecisions][0]['decision'];

			if( $decision == 'annule' ) {
				$modeleOdt  = "{$this->alias}/decision_annule.odt";
			}
			else if( $decision == 'reporte' ) {
				$modeleOdt  = "{$this->alias}/decision_reporte.odt";
			}
			else {
				$modeleOdt  = "{$this->alias}/decision_autre.odt";
			}

			// Calcul de la date de fin de sursis si besoin
			$dateDepart = strtotime( $gedooo_data['Passagecommissionep']['impressiondecision'] );
			if( empty( $dateDepart ) ) {
				$dateDepart = mktime();
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
			$options = $this->Dossierep->Passagecommissionep->{$modeleDecisions}->enums();
			$options['Personne']['qual'] = ClassRegistry::init( 'Option' )->qual();
			$options['Adresse']['typevoie'] = ClassRegistry::init( 'Option' )->typevoie();

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
		public function qdListeDossier( $commissionep_id = null ) {
			$return = array(
				'fields' => array(
					'Dossierep.id',
					'Dossierep.created',
					'Dossierep.themeep',
					'Dossier.numdemrsa',
					'Dossier.matricule',
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.locaadr',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Orientstruct.date_valid',
					'Passagecommissionep.id',
					'Passagecommissionep.commissionep_id',
					'Passagecommissionep.etatdossierep'
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
					'alias' => 'Orientstruct',
					'table' => 'orientsstructs',
					'type' => 'INNER',
					'conditions' => array(
						'Orientstruct.id = '.$this->alias.'.orientstruct_id'
					)
				),
				array(
					'alias' => 'Structurereferente',
					'table' => 'structuresreferentes',
					'type' => 'INNER',
					'conditions' => array(
						'Structurereferente.id = Orientstruct.structurereferente_id'
					)
				),
				array(
					'alias' => 'Typeorient',
					'table' => 'typesorients',
					'type' => 'INNER',
					'conditions' => array(
						'Typeorient.id = Orientstruct.typeorient_id'
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
			);
			return $return;
		}
	}
?>