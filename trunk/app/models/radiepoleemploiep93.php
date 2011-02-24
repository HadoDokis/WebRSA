<?php
	/**
	* Saisines d'EP pour les bilans de parcours pour le conseil général du
	* département 66.
	*
	* Une saisine regoupe plusieurs thèmes des EPs pour le CG 66.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Radiepoleemploiep93 extends AppModel
	{
		public $name = 'Radiepoleemploiep93';

		public $recursive = -1;

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
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
			'Historiqueetatpe' => array(
				'className' => 'Historiqueetatpe',
				'foreignKey' => 'historiqueetatpe_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Decisionradiepoleemploiep93' => array(
				'className' => 'Decisionradiepoleemploiep93',
				'foreignKey' => 'radiepoleemploiep93_id',
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
		*
		*/
		/*public function containQueryData() {
			return array(
				'Radiepoleemploiep93' => array(
					'Nvsrepreorient66'=>array(
						'Typeorient',
						'Structurereferente'
					),
				)
			);
		}*/

		/**
		* FIXME: et qui n'ont pas de dossier EP en cours de traitement pour cette thématique
		* FIXME: et qui ne sont pas passés en EP pour ce motif dans un délai de moins de 1 mois (paramétrable)
		*/

		protected function _qdSelection() {
			$queryData = array(
				'fields' => array(
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Historiqueetatpe.id',
					'Historiqueetatpe.informationpe_id',
					'Historiqueetatpe.etat',
					'Historiqueetatpe.date',
				),
				'contain' => false,
				'joins' => array(
					array(
						'table'      => 'prestations', // FIXME:
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest' => 'RSA',
							'Prestation.rolepers' => array( 'DEM', 'CJT' ),
						)
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
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Situationdossierrsa.dossier_id = Dossier.id',
							'Situationdossierrsa.etatdosrsa' => array( 'Z', '2', '3', '4' )
						)
					),
					array(
						'table'      => 'calculsdroitsrsa', // FIXME:
						'alias'      => 'Calculdroitrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Calculdroitrsa.personne_id',
							'Calculdroitrsa.toppersdrodevorsa' => '1',
						)
					),
				),
				'conditions' => array(
					'Personne.id NOT IN (
						SELECT
								dossierseps.personne_id
							FROM dossierseps
							WHERE
								dossierseps.personne_id = Personne.id
								AND dossierseps.etapedossierep IN ( \'cree\', \'seance\', \'decisionep\', \'decisioncg\' )
					)',
					'Personne.id NOT IN (
						SELECT
								dossierseps.personne_id
							FROM dossierseps
								INNER JOIN seanceseps ON (
									dossierseps.seanceep_id = seanceseps.id
								)
							WHERE
								dossierseps.personne_id = Personne.id
								AND dossierseps.etapedossierep = \'traite\'
								AND dossierseps.themeep = \'radiespoleemploieps93\'
								AND seanceseps.dateseance >= \''.date( 'Y-m-d', strtotime( '-2 mons' ) ).'\'
					)'
				) // FIXME: paramétrage
			);

			return $queryData;
		}

		/**
		*
		*/

		public function qdRadies() {
			// FIXME: et qui ne sont pas passés dans une EP pour ce motif depuis au moins 1 mois (?)
			$queryData = $this->_qdSelection();
			$qdRadies = $this->Historiqueetatpe->Informationpe->qdRadies();
			$queryData['joins'] = array_merge( $queryData['joins'] ,$qdRadies['joins'] );
			$queryData['conditions'] = array_merge( $queryData['conditions'] ,$qdRadies['conditions'] );
			$queryData['order'] = $qdRadies['order'];
			
			return $queryData;
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
							'created',
							'modified'

						),
						'Historiqueetatpe',
						'Decisionradiepoleemploiep93' => array(
							'order' => array( 'etape DESC' )
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
				$formData['Radiepoleemploiep93'][$key]['id'] = @$datas[$key]['Radiepoleemploiep93']['id'];
				$formData['Radiepoleemploiep93'][$key]['dossierep_id'] = @$datas[$key]['Radiepoleemploiep93']['dossierep_id'];
				$formData['Decisionradiepoleemploiep93'][$key]['radiepoleemploiep93_id'] = @$datas[$key]['Radiepoleemploiep93']['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Radiepoleemploiep93']['Decisionradiepoleemploiep93'][0]['etape'] == $niveauDecision ) {
					$formData['Decisionradiepoleemploiep93'][$key] = @$dossierep['Radiepoleemploiep93']['Decisionradiepoleemploiep93'][0];
				}
				// On ajoute les enregistrements de cette étape -> FIXME: manque les id ?
				else {
					if( $niveauDecision == 'ep' ) {
						if( isset( $datas[$key]['Radiepoleemploiep93']['Decisionradiepoleemploiep93'][0] ) && !empty( $datas[$key]['Radiepoleemploiep93']['Decisionradiepoleemploiep93'][0] ) ) { // Modification
							$formData['Decisionradiepoleemploiep93'][$key]['decision'] = @$datas[$key]['Radiepoleemploiep93']['Decisionradiepoleemploiep93'][0]['decision'];
						}
					}
					else if( $niveauDecision == 'cg' ) {
						if( !empty( $datas[$key]['Radiepoleemploiep93']['Decisionradiepoleemploiep93'][1] ) ) { // Modification
							$formData['Decisionradiepoleemploiep93'][$key]['decision'] = @$datas[$key]['Radiepoleemploiep93']['Decisionradiepoleemploiep93'][1]['decision'];
						}
						else {
							$formData['Decisionradiepoleemploiep93'][$key]['decision'] = $dossierep['Radiepoleemploiep93']['Decisionradiepoleemploiep93'][0]['decision'];
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
			$themeData = Set::extract( $data, '/Decisionradiepoleemploiep93' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				/*foreach( array_keys( $themeData ) as $key ) {
					// On complètre /on nettoie si ce n'est pas envoyé par le formulaire
					if( $themeData[$key]['Decisionradiepoleemploiep93']['decision'] == '1reduction' ) {
						$themeData[$key]['Decisionradiepoleemploiep93']['dureesursis'] = null;
						$themeData[$key]['Decisionradiepoleemploiep93']['montantreduction'] = Configure::read( 'Radiepoleemploiep93.montantReduction' );
					}
					else if( $themeData[$key]['Decisionradiepoleemploiep93']['decision'] == '1sursis' ) {
						$themeData[$key]['Decisionradiepoleemploiep93']['montantreduction'] = null;
						$themeData[$key]['Decisionradiepoleemploiep93']['dureesursis'] = Configure::read( 'Radiepoleemploiep93.dureeSursis' );
					}
					else if( $themeData[$key]['Decisionradiepoleemploiep93']['decision'] == '1maintien' ) {
						$themeData[$key]['Decisionradiepoleemploiep93']['montantreduction'] = null;
						$themeData[$key]['Decisionradiepoleemploiep93']['dureesursis'] = null;
					}
					// FIXME: la même chose pour l'étape 2
				}*/

				$success = $this->Decisionradiepoleemploiep93->saveAll( $themeData, array( 'atomic' => false ) );
				$this->Dossierep->updateAll(
					array( 'Dossierep.etapedossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Dossierep"."id"' => Set::extract( $data, '/Radiepoleemploiep93/dossierep_id' ) )
				);
				return $success;
			}
		}

		/**
		* INFO: Fonction inutile dans cette saisine donc elle retourne simplement true
		*/

		public function verrouiller( $seanceep_id, $etape ) {
			return true;
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
						'Decisionradiepoleemploiep93' => array(
							'conditions' => array(
								'Decisionradiepoleemploiep93.etape' => $etape
							)
						),
						'Dossierep'
					)
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $niveauDecisionFinale == $etape ) {
					$radiepoleemploiep93 = array( 'Radiepoleemploiep93' => $dossierep['Radiepoleemploiep93'] );
					if( !isset( $dossierep['Decisionradiepoleemploiep93'][0]['decision'] ) ) {
						$success = false;
					}
					$radiepoleemploiep93['Radiepoleemploiep93']['decision'] = @$dossierep['Decisionradiepoleemploiep93'][0]['decision'];

					$success = $this->save( $radiepoleemploiep93 ) && $success;
				}
			}

			return $success;
		}
	}
?>