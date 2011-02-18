<?php

	class Regressionorientationep extends AppModel
	{
		public $name = 'Regressionorientationep';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'typeorient_id',
					'structurereferente_id'
				)
			)
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
		* Querydata permettant d'obtenir les dossiers qui doivent être traités
		* par liste pour la thématique de ce modèle.
		*
		* TODO: une autre liste pour avoir un tableau permettant d'accéder à la fiche
		* TODO: que ceux avec accord, les autres en individuel
		*
		* @param integer $seanceep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut les dossiers à passer par liste.
		* @return array
		* @access public
		*/

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
					'Dossierep.seanceep_id' => $seanceep_id,
				),
				'contain' => array(
					'Personne' => array(
						///FIXME : ça marche pas !!!!
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
						'Decision'.Inflector::underscore( $this->alias ) => array(
							'Typeorient',
							'Structurereferente',
						),
					)
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
			if( $niveauDecision == 'ep' ) {
				foreach( $datas as $key => $dossierep ) {
					$formData[$this->alias][$key] = $dossierep[$this->alias];

					if (isset($dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][0]['id'])) {
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['id'] = $dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][0]['id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = $dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'],
								$dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][0]['structurereferente_id']
							)
						);
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['commentaire'] = $dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][0]['commentaire'];
					}
					else {
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = $dossierep[$this->alias]['typeorient_id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['typeorient_id'],
								$dossierep[$this->alias]['structurereferente_id']
							)
						);
					}
				}
			}
			else if( $niveauDecision == 'cg' ) {
				foreach( $datas as $key => $dossierep ) {
					$formData[$this->alias][$key] = $dossierep[$this->alias];

					if (isset($dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][1]['id'])) {
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['id'] = $dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][1]['id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['decision'] = $dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][1]['decision'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = $dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][1]['typeorient_id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][1]['typeorient_id'],
								$dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][1]['structurereferente_id']
							)
						);
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['commentaire'] = $dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][0]['commentaire'];
					}
					else {
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['decision'] = $dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][0]['decision'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = $dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'],
								$dossierep[$this->alias]['Decision'.Inflector::underscore( $this->alias )][0]['structurereferente_id']
							)
						);
					}
				}
			}
// debug( $formData );
			return $formData;
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			// FIXME: filtrer les données
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
								'conditions' => array(
									$this->alias.'.id' => $datas['Decision'.Inflector::underscore( $this->alias )][Inflector::underscore( $this->alias ).'_id']
								),
								
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
				
				$success = $this->{'Decision'.Inflector::underscore( $this->alias )}->saveAll( $themeData, array( 'atomic' => false ) );

				$this->Dossierep->updateAll(
					array( 'Dossierep.etapedossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Dossierep"."id"' => Set::extract( $data, "/{$this->alias}/dossierep_id" ) )
				);

				return $success;
			}
		}
		
	}
?>