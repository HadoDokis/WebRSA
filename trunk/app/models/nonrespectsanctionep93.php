<?php
	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Nonrespectsanctionep93 extends AppModel
	{
		public $name = 'Nonrespectsanctionep93';

		public $recursive = -1;

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'origine',
					'decision' => array( 'domain' => 'decisionnonrespectsanctionep93' ),
				)
			),
			'Autovalidate',
			'ValidateTranslate'
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
			),
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Relancenonrespectsanctionep93' => array(
				'className' => 'Relancenonrespectsanctionep93',
				'foreignKey' => 'nonrespectsanctionep93_id',
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
			'Decisionnonrespectsanctionep93' => array(
				'className' => 'Decisionnonrespectsanctionep93',
				'foreignKey' => 'nonrespectsanctionep93_id',
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

		/**
		* INFO: Fonction inutile pour cette thématique donc elle retourne simplement true
		*/

		public function verrouiller( $seanceep_id, $etape ) {
			return true;
		}

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
					'Dossierep.seanceep_id' => $seanceep_id
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
							'propopdo_id',
							'orientstruct_id',
							'contratinsertion_id',
							'origine',
							'rgpassage',
							'active',
							'created',
							'modified'

						),
						'Decisionnonrespectsanctionep93' => array(
							'order' => array( 'etape DESC' )
						),
						/*'Nvsrepreorientsr93',
						'Motifreorient',
						'Typeorient',
						'Structurereferente',*/
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
						)
					),
				)
			);
		}

		/**
		* FIXME
		*
		* @param integer $seanceep_id L'id technique de la séance d'EP
		* @param array $datas Les données des dossiers
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut préparer les données du formulaire
		* @return array
		* @access public
		*/

		public function prepareFormData( $seanceep_id, $datas, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Seanceep->themesTraites( $seanceep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			$formData = array();
			foreach( $datas as $key => $dossierep ) {
				$formData['Nonrespectsanctionep93'][$key]['id'] = @$datas[$key]['Nonrespectsanctionep93']['id'];
				$formData['Nonrespectsanctionep93'][$key]['dossierep_id'] = @$datas[$key]['Nonrespectsanctionep93']['dossierep_id'];
				$formData['Decisionnonrespectsanctionep93'][$key]['nonrespectsanctionep93_id'] = @$datas[$key]['Nonrespectsanctionep93']['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'][0]['etape'] == $niveauDecision ) {
					$formData['Decisionnonrespectsanctionep93'][$key] = @$dossierep['Nonrespectsanctionep93']['Decisionnonrespectsanctionep93'][0];
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
			}
// debug( $formData );

			return $formData;
		}

		/**
		* TODO: docs
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			// FIXME: filtrer les données
			$themeData = Set::extract( $data, '/Decisionnonrespectsanctionep93' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				foreach( array_keys( $themeData ) as $key ) {
					// On complètre /on nettoie si ce n'est pas envoyé par le formulaire
					if( $themeData[$key]['Decisionnonrespectsanctionep93']['decision'] == '1reduction' ) {
						$themeData[$key]['Decisionnonrespectsanctionep93']['dureesursis'] = null;
						$themeData[$key]['Decisionnonrespectsanctionep93']['montantreduction'] = Configure::read( 'Nonrespectsanctionep93.montantReduction' );
					}
					else if( $themeData[$key]['Decisionnonrespectsanctionep93']['decision'] == '1sursis' ) {
						$themeData[$key]['Decisionnonrespectsanctionep93']['montantreduction'] = null;
						$themeData[$key]['Decisionnonrespectsanctionep93']['dureesursis'] = Configure::read( 'Nonrespectsanctionep93.dureeSursis' );
					}
					else if( $themeData[$key]['Decisionnonrespectsanctionep93']['decision'] == '1maintien' ) {
						$themeData[$key]['Decisionnonrespectsanctionep93']['montantreduction'] = null;
						$themeData[$key]['Decisionnonrespectsanctionep93']['dureesursis'] = null;
					}
					// FIXME: la même chose pour l'étape 2
				}

				$success = $this->Decisionnonrespectsanctionep93->saveAll( $themeData, array( 'atomic' => false ) );

				$this->Dossierep->updateAll(
					array( 'Dossierep.etapedossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Dossierep"."id"' => Set::extract( $data, '/Nonrespectsanctionep93/dossierep_id' ) )
				);
				return $success;
			}
		}

		/**
		* TODO: docs
		*/

		public function finaliser( $seanceep_id, $etape ) {
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
						'Dossierep.themeep' => Inflector::tableize( $this->alias ),//FIXME: ailleurs aussi
					),
					'contain' => array(
						'Decisionnonrespectsanctionep93' => array(
							'conditions' => array(
								'Decisionnonrespectsanctionep93.etape' => $etape
							)
						),
						'Dossierep'
					)
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $niveauDecisionFinale == $etape ) {
					$nonrespectsanctionep93 = array( 'Nonrespectsanctionep93' => $dossierep['Nonrespectsanctionep93'] );
					$nonrespectsanctionep93['Nonrespectsanctionep93']['active'] = 0;
					if( !isset( $dossierep['Decisionnonrespectsanctionep93'][0]['decision'] ) ) {
						$success = false;
					}

					// Copie de la décision
					$nonrespectsanctionep93['Nonrespectsanctionep93']['decision'] = @$dossierep['Decisionnonrespectsanctionep93'][0]['decision'];
					$nonrespectsanctionep93['Nonrespectsanctionep93']['montantreduction'] = @$dossierep['Decisionnonrespectsanctionep93'][0]['montantreduction'];
					$nonrespectsanctionep93['Nonrespectsanctionep93']['dureesursis'] = @$dossierep['Decisionnonrespectsanctionep93'][0]['dureesursis'];

					/*if( $nonrespectsanctionep93['Nonrespectsanctionep93']['decision'] == '1reduction' ) { // FIXME: vient de la dernière décision
						$nonrespectsanctionep93['Nonrespectsanctionep93']['montantreduction'] = Configure::read( 'Nonrespectsanctionep93.montantReduction' );
					}
					else if( $nonrespectsanctionep93['Nonrespectsanctionep93']['decision'] == '1sursis' ) {
						$nonrespectsanctionep93['Nonrespectsanctionep93']['dureesursis'] = Configure::read( 'Nonrespectsanctionep93.dureeSursis' );
					}*/

					$this->create( $nonrespectsanctionep93 ); // TODO: un saveAll ?
					$success = $this->save() && $success;
				}
			}

			return $success;
		}

		/**
		*
		*/

		public function containPourPv() {
			return array(
				'Nonrespectsanctionep93' => array(
					'Decisionnonrespectsanctionep93' => array(
						/*'fields' => array(
							'( CAST( decision AS TEXT ) || montantreduction ) AS avis'
						),*/
						'conditions' => array(
							'etape' => 'ep'
						),
					)
				),
			);
		}

		/**
		*
		*/

		public function qdProcesVerbal() {
			return array(
				'fields' => array(
					'Nonrespectsanctionep93.id',
					'Nonrespectsanctionep93.dossierep_id',
					'Nonrespectsanctionep93.propopdo_id',
					'Nonrespectsanctionep93.orientstruct_id',
					'Nonrespectsanctionep93.contratinsertion_id',
					'Nonrespectsanctionep93.origine',
					'Nonrespectsanctionep93.decision',
					'Nonrespectsanctionep93.rgpassage',
					'Nonrespectsanctionep93.montantreduction',
					'Nonrespectsanctionep93.dureesursis',
					'Nonrespectsanctionep93.sortienvcontrat',
					'Nonrespectsanctionep93.active',
					'Nonrespectsanctionep93.created',
					'Nonrespectsanctionep93.modified',
					'Decisionnonrespectsanctionep93.id',
					'Decisionnonrespectsanctionep93.nonrespectsanctionep93_id',
					'Decisionnonrespectsanctionep93.etape',
					'Decisionnonrespectsanctionep93.decision',
					'Decisionnonrespectsanctionep93.montantreduction',
					'Decisionnonrespectsanctionep93.dureesursis',
					'Decisionnonrespectsanctionep93.commentaire',
					'Decisionnonrespectsanctionep93.created',
					'Decisionnonrespectsanctionep93.modified',
				),
				'joins' => array(
					array(
						'table'      => 'nonrespectssanctionseps93',
						'alias'      => 'Nonrespectsanctionep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Nonrespectsanctionep93.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'decisionsnonrespectssanctionseps93',
						'alias'      => 'Decisionnonrespectsanctionep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionnonrespectsanctionep93.nonrespectsanctionep93_id = Nonrespectsanctionep93.id',
							'Decisionnonrespectsanctionep93.etape' => 'ep'
						),
					),
				)
			);
		}
	}
?>
