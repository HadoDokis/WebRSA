<?php
    App::import( 'Sanitize' );

	class Nonorientationpro extends AppModel {

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable'
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
						'Adresse.codepos'
					),
					'conditions' => array(
						'Orientstruct.date_valid <=' => date( 'Y-m-d', strtotime( '- '.$nbmois.' month', time() ) ),
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
								'Typeorient.lib_type_orient LIKE' => 'Emploi%'
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
							'orientstruct_id' => $dossier['orientstruct_id']
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
				/*'fields' => array(
					'Dossierep.id',
					'Dossierep.personne_id',
					'Dossierep.seanceep_id',
					'Dossierep.etapedossierep',
					'Dossierep.themeep',
					'Dossierep.created',
					'Dossierep.modified',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.numvoie',
					'Adresse.typevoie',
					'Adresse.nomvoie'
				),*/
				'conditions' => array(
					'Dossierep.themeep' => Inflector::tableize( $this->alias ),
					'Dossierep.seanceep_id' => $seanceep_id
				),
				/*'joins' => array(
					array(
						'table' => 'personnes',
						'alias' => 'Personne',
						'type' => 'INNER',
						'conditions' => array(
							'Dossierep.personne_id = Personne.id'
						)
					),
					array(
						'table' => 'foyers',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'conditions' => array(
							'Personne.foyer_id = Foyer.id'
						)
					),
					array(
						'table' => 'adressesfoyers',
						'alias' => 'Adressefoyer',
						'type' => 'INNER',
						'conditions' => array(
							'Adressefoyer.foyer_id = Foyer.id',
							'Adressefoyer.rgadr' => '01'
						)
					),
					array(
						'table' => 'adresses',
						'alias' => 'Adresse',
						'type' => 'INNER',
						'conditions' => array(
							'Adressefoyer.adresse_id = Adresse.id'
						)
					),
					array(
						'table' => Inflector::tableize( $this->alias ),
						'alias' => $this->alias,
						'type' => 'INNER',
						'conditions' => array(
							$this->alias.'.dossierep_id = Dossierep.id'
						)
					),
					array(
						'table' => 'decisions'.Inflector::tableize( $this->alias ),
						'alias' => 'Decision'.Inflector::underscore( $this->alias ),
						'type' => 'LEFT OUTER',
						'conditions' => array(
							'Decision'.Inflector::underscore( $this->alias ).'.'.Inflector::underscore( $this->alias ).'_id = '.$this->alias.'.id'
						)
					),
					array(
						'table' => 'orientsstructs',
						'alias' => 'Orientstruct',
						'type' => 'INNER',
						'conditions' => array(
							$this->alias.'.orientstruct_id = Orientstruct.id'
						)
					),
					array(
						'table' => 'typesorients',
						'alias' => 'Typeorient',
						'type' => 'INNER',
						'conditions' => array(
							'Orientstruct.typeorient_id = Typeorient.id'
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
					)
				)*/
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

			return $success;
		}
		
	}
?>