<?php
    App::import( 'Sanitize' );

	class Nonorientationpro extends AppModel {

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
			$nbmois = Set::classicExtract($datas, 'Filtre.dureenonreorientation');
			$this->Orientstruct = ClassRegistry::init( 'Orientstruct' );

			$modelName = $this->alias;
			$modelTable = Inflector::tableize( $modelName );

			$cohorte = $this->Orientstruct->find(
				'all',
				array(
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
					'conditions' => array(
						'Contratinsertion.df_ci <=' => date( 'Y-m-d', strtotime( '- '.$nbmois.' month', time() ) ),
						'Orientstruct.statut_orient' => 'Orienté',
						'Orientstruct.id NOT IN (
							SELECT "'.$modelTable.'"."orientstruct_id"
							FROM "'.$modelTable.'"
								INNER JOIN "dossierseps" ON ( "dossierseps"."id" = "'.$modelTable.'"."dossierep_id" )
							WHERE "dossierseps"."etapedossierep" != \'traite\'
							AND "dossierseps"."themeep" = \''.$modelTable.'\'
						)'
					),
					'joins' => array(
						array(
							'table' => 'typesorients',
							'alias' => 'Typeorient',
							'type' => 'INNER',
							'conditions' => array(
								'Orientstruct.typeorient_id = Typeorient.id',
								'Typeorient.lib_type_orient NOT LIKE' => 'Emploi%'
							)
						),
						array(
							'table' => 'structuresreferentes',
							'alias' => 'Structurereferente',
							'type' => 'INNER',
							'conditions' => array(
								'Orientstruct.structurereferente_id = Structurereferente.id'
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
							'foreignKey' => false,
							'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
						),
						array(
							'table'      => 'situationsdossiersrsa',
							'alias'      => 'Situationdossierrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Dossier.id = Situationdossierrsa.dossier_id',
								'Situationdossierrsa.etatdosrsa' => $this->Orientstruct->Personne->Foyer->Dossier->Situationdossierrsa->etatOuvert()
							)
						),
						array(
							'table'      => 'adressesfoyers',
							'alias'      => 'Adressefoyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Adressefoyer.foyer_id = Foyer.id',
								'Adressefoyer.rgadr' => '01'
							)
						),
						array(
							'table'      => 'adresses',
							'alias'      => 'Adresse',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Adressefoyer.adresse_id = Adresse.id' )
						),
						array(
							'table'      => 'contratsinsertion',
							'alias'      => 'Contratinsertion',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Contratinsertion.personne_id = Orientstruct.personne_id',
								'Contratinsertion.structurereferente_id = Orientstruct.structurereferente_id'
							)
						)
					),
					'contain' => false
				)
			);
			return $cohorte;
		}
		
		public function saveCohorte( $datas ) {
			$success = true;
			
			foreach( $datas['Nonorientationpro'] as $dossier ) {
				if ( $dossier['passageep'] == 1 ) {
					$dossierep = array(
						'Dossierep' => array(
							'personne_id' => $dossier['personne_id'],
							'etapedossierep' => 'cree',
							'themeep' => Inflector::tableize( $this->alias )
						)
					);
					$success = $this->Dossierep->save( $dossierep ) && $success;
					
					$nonorientationpro58 = array(
						'Nonorientationpro58' => array(
							'dossierep_id' => $this->Dossierep->id,
							'orientstruct_id' => $dossier['orientstruct_id'],
							'user_id' => $dossier['user_id']
						)
					);
					$success = $this->save( $nonorientationpro58 ) && $success;
				}
			}
			
			return $success;
		}

		public function qdDossiersParListe( $seanceep_id, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Seanceep->themesTraites( $seanceep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			return array(
				'conditions' => array(
					'Dossierep.themeep' => Inflector::tableize( $this->alias ),
					'Dossierep.seanceep_id' => $seanceep_id
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
						'Decision'.Inflector::underscore( $this->alias ) => array(
							'order' => array( 'etape DESC' )
						),
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
							'Referent'
						)
					),
				)
			);
		}

		public function prepareFormData( $seanceep_id, $datas, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Seanceep->themesTraites( $seanceep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			$formData = array();
			/*foreach( $datas as $key => $dossierep ) {
				$formData['Nonrespectsanctionep93'][$key]['id'] = @$datas[$key]['Nonrespectsanctionep93']['id'];
				$formData['Nonrespectsanctionep93'][$key]['dossierep_id'] = @$datas[$key]['Nonrespectsanctionep93']['dossierep_id'];
				$formData['Decisionnonrespectsanctionep93'][$key]['nonrespectsanctionep93_id'] = @$datas[$key]['Nonrespectsanctionep93']['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'][count(@$dossierep['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'])-1]['etape'] == $niveauDecision ) {
					$formData['Decisionnonrespectsanctionep93'][$key] = @$dossierep['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'][count(@$dossierep['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'])-1];
				}
				// On ajoute les enregistrements de cette étape -> FIXME: manque les id ?
				else {
					if( $niveauDecision == 'ep' ) {
						if( !empty( $datas[$key]['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'][0] ) ) { // Modification
							$formData['Decisionnonrespectsanctionep93'][$key]['decision'] = @$datas[$key]['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'][0]['decision'];
						}
						else {
							if( ( $dossierep['Personne']['Foyer']['nbenfants'] > 0 ) || ( $dossierep['Personne']['Foyer']['sitfam'] == 'MAR' ) ) {
								$formData['Decisionnonrespectsanctionep93'][$key]['decision'] = '1maintien';
							}
							// FIXME: autre cas ?
						}
					}
					else if( $niveauDecision == 'cg' ) {
						if( !empty( $datas[$key]['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'][1] ) ) { // Modification
							$formData['Decisionnonrespectsanctionep93'][$key]['decision'] = @$datas[$key]['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'][1]['decision'];
						}
						else {
							$formData['Decisionnonrespectsanctionep93'][$key]['decision'] = $dossierep['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'][0]['decision'];
						}
					}
				}
			}*/
// debug( $formData );

			return $formData;
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			$success = true;
			if ( isset( $data[$this->alias] ) && !empty( $data[$this->alias] ) && isset( $data['Decision'.Inflector::underscore( $this->alias )] ) && !empty( $data['Decision'.Inflector::underscore( $this->alias )] ) ) {
				foreach( $data['Decision'.Inflector::underscore( $this->alias )] as $key => $values ) {
					$structurereferente = explode( '_', $values['structurereferente_id'] );
					if ( isset( $structurereferente[1] ) && $values['decision'] == 'reorientation' ) {
						$data['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = $structurereferente[1];
					}
					elseif ( $values['decision'] != 'reorientation' ) {
						$data['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = null;
						$data['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = null;
					}
					$data['Decision'.Inflector::underscore( $this->alias )][$key][Inflector::underscore( $this->alias ).'_id'] = $data[$this->alias][$key]['id'];
				}
				
				$success = $this->{'Decision'.Inflector::underscore($this->alias)}->saveAll( Set::extract( $data, '/'.'Decision'.Inflector::underscore( $this->alias ) ), array( 'atomic' => false ) );
				
				$this->Dossierep->updateAll(
					array( 'Dossierep.etapedossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Dossierep"."id"' => Set::extract( $data, '/'.$this->alias.'/dossierep_id' ) )
				);
			}
			
			return $success;
		}

		/**
		* INFO: Fonction inutile dans cette saisine donc elle retourne simplement true
		*/

		public function verrouiller( $seanceep_id, $etape ) {
			return true;
		}

		/**
		* 
		*/

		public function finaliser( $seanceep_id, $etape ) {
			$decisionModelName = 'Decisionnonorientationpro'.Configure::read( 'Cg.departement' );
			
			$seanceep = $this->Dossierep->Seanceep->find(
				'first',
				array(
					'conditions' => array( 'Seanceep.id' => $seanceep_id ),
					'contain' => array( 'Ep' )
				)
			);
			
			$niveauDecisionFinale = $seanceep['Ep'][Inflector::underscore( $this->alias )];
			
			$dossierseps = $this->find(
				'all',
				array(
					'conditions' => array(
						'Dossierep.seanceep_id' => $seanceep_id,
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
						list($date_valid, $heure_valid) = explode( ' ', $seanceep['Seanceep']['dateseance'] );
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
								'user_id' => $dossier['Nonorientationpro58']['user_id']
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