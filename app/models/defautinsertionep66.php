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

	class Defautinsertionep66 extends AppModel
	{
		public $name = 'Defautinsertionep66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
// 			'Formattable' => array(
// 				'suffix' => array(
// 					'structurereferente_id'
// 				)
// 			),
			'Enumerable' => array(
				'fields' => array(
					'origine',
					'type'
				)
			)
		);

		public $belongsTo = array(
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
				'foreignKey' => 'bilanparcours66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'dossierep_id',
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
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'orientstruct_id',
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
			'Decisiondefautinsertionep66' => array(
				'className' => 'Decisiondefautinsertionep66',
				'foreignKey' => 'defautinsertionep66_id',
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

		/**
		*
		*/
		public function containQueryData() {
			return array(
				'Defautinsertionep66' => array(
					'Decisiondefautinsertionep66'=>array(
						'Typeorient',
						'Structurereferente'
					),
				)
			);
		}

		/**
		* INFO: Fonction inutile dans cette saisine donc elle retourne simplement true
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
					'Dossierep.seanceep_id' => $seanceep_id,
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
// 						'Typeorient',
// 						'Structurereferente',
						'Bilanparcours66' => array(
// 							'Orientstruct' => array(
// 								'Typeorient',
// 								'Structurereferente',
// 							),
						),
						'Contratinsertion',
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
						),
						'Historiqueetatpe',
						'Decisiondefautinsertionep66'
					)
				)
			);
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			// FIXME: filtrer les données
			$themeData = Set::extract( $data, '/Decisiondefautinsertionep66' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				$this->Decisiondefautinsertionep66->begin();

				$success = $this->Decisiondefautinsertionep66->saveAll( $themeData, array( 'atomic' => false ) );

				$this->Dossierep->updateAll(
					array( 'Dossierep.etapedossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Dossierep"."id"' => Set::extract( $data, '/Defautinsertionep66/dossierep_id' ) )
				);

				if( $success ) {
					$this->Decisiondefautinsertionep66->commit();
				}
				else {
					$this->Decisiondefautinsertionep66->rollback();
				}

				return $success;
			}
		}

		/**
		*
		*/

		public function saveDecisionUnique( $data, $niveauDecision ) {
			return true;
		}

		/**
		* Prépare les données du formulaire d'un niveau de décision
		* en prenant en compte les données du bilan ou du niveau de décision
		* précédent.
		*
		* @param integer $seanceep_id L'id technique de la séance d'EP
		* @param array $datas Les données des dossiers
		* @param string $niveauDecision Le niveau de décision pour lequel il
		* 	faut préparer les données du formulaire
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
				$formData[$this->alias][$key] = $dossierep[$this->alias];

				if( $niveauDecision == 'ep' ) {
					if( @$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'][count(@$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'])-1]['etape'] == 'ep' ) {
						$record = @$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'][count(@$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'])-1];
					}
					else {
						$record = @$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'][0];
					}

					if( $record['etape'] == $niveauDecision ) {
						$formData['Decisiondefautinsertionep66'][$key]['id'] = $record['id'];
						$formData['Decisiondefautinsertionep66'][$key]['etape'] = $record['etape'];
					}

					$formData['Decisiondefautinsertionep66'][$key]['defautinsertionep66_id'] = @$dossierep['Defautinsertionep66']['id'];
					$formData['Decisiondefautinsertionep66'][$key]['decision'] = $record['decision'];
					$formData['Decisiondefautinsertionep66'][$key]['typeorient_id'] = $record['typeorient_id'];
					$formData['Decisiondefautinsertionep66'][$key]['structurereferente_id'] = implode( '_', array( $record['typeorient_id'], $record['structurereferente_id'] ) );
					$formData['Decisiondefautinsertionep66'][$key]['referent_id'] = implode( '_', array( $record['structurereferente_id'], $record['referent_id'] ) );
				}
				else {
					if( @$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'][count(@$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'])-1]['etape'] == 'cg' ) {
						$record = @$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'][count(@$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'])-1];
					}
					else {
						$record = @$dossierep['Defautinsertionep66']['Decisiondefautinsertionep66'][0];
					}

					if( $record['etape'] == $niveauDecision ) {
						$formData['Decisiondefautinsertionep66'][$key]['id'] = $record['id'];
						$formData['Decisiondefautinsertionep66'][$key]['etape'] = $record['etape'];
					}

					$formData['Decisiondefautinsertionep66'][$key]['defautinsertionep66_id'] = @$dossierep['Defautinsertionep66']['id'];
					$formData['Decisiondefautinsertionep66'][$key]['decision'] = $record['decision'];
					$formData['Decisiondefautinsertionep66'][$key]['typeorient_id'] = $record['typeorient_id'];
					$formData['Decisiondefautinsertionep66'][$key]['structurereferente_id'] = implode( '_', array( $record['typeorient_id'], $record['structurereferente_id'] ) );
					$formData['Decisiondefautinsertionep66'][$key]['referent_id'] = implode( '_', array( $record['structurereferente_id'], $record['referent_id'] ) );
				}
			}
// debug( $formData );
			/*if( $niveauDecision == 'ep' ) {
				foreach( $datas as $key => $dossierep ) {
					if (isset($dossierep[$this->alias]['Nvsrepreorient66'][0]['id'])) {
						$formData['Nvsrepreorient66'][$key]['id'] = $dossierep[$this->alias]['Nvsrepreorient66'][0]['id'];
						$formData['Nvsrepreorient66'][$key]['typeorient_id'] = $dossierep[$this->alias]['Nvsrepreorient66'][0]['typeorient_id'];
						$formData['Nvsrepreorient66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Nvsrepreorient66'][0]['typeorient_id'],
								$dossierep[$this->alias]['Nvsrepreorient66'][0]['structurereferente_id']
							)
						);
					}
					else {
						$formData['Nvsrepreorient66'][$key]['typeorient_id'] = $dossierep[$this->alias]['typeorient_id'];
						$formData['Nvsrepreorient66'][$key]['structurereferente_id'] = implode(
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
					if (isset($dossierep[$this->alias]['Nvsrepreorient66'][1]['id'])) {
						$formData['Nvsrepreorient66'][$key]['id'] = $dossierep[$this->alias]['Nvsrepreorient66'][1]['id'];
						$formData['Nvsrepreorient66'][$key]['decision'] = $dossierep[$this->alias]['Nvsrepreorient66'][1]['decision'];
						$formData['Nvsrepreorient66'][$key]['typeorient_id'] = $dossierep[$this->alias]['Nvsrepreorient66'][1]['typeorient_id'];
						$formData['Nvsrepreorient66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Nvsrepreorient66'][1]['typeorient_id'],
								$dossierep[$this->alias]['Nvsrepreorient66'][1]['structurereferente_id']
							)
						);
					}
					else {
						$formData['Nvsrepreorient66'][$key]['decision'] = $dossierep[$this->alias]['Nvsrepreorient66'][0]['decision'];
						$formData['Nvsrepreorient66'][$key]['typeorient_id'] = $dossierep[$this->alias]['Nvsrepreorient66'][0]['typeorient_id'];
						$formData['Nvsrepreorient66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Nvsrepreorient66'][0]['typeorient_id'],
								$dossierep[$this->alias]['Nvsrepreorient66'][0]['structurereferente_id']
							)
						);
					}
				}
			}*/

			return $formData;
		}

		public function prepareFormDataUnique( $dossierep_id, $dossierep, $niveauDecision ) {
			$formData = array();
			return $formData;
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
						'Decisiondefautinsertionep66' => array(
							'conditions' => array(
								'Decisiondefautinsertionep66.etape' => $etape
							)
						),
						'Dossierep'
					)
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $niveauDecisionFinale == $etape ) {
					$defautinsertionep66 = array( 'Defautinsertionep66' => $dossierep['Defautinsertionep66'] );
					if( !isset( $dossierep['Decisiondefautinsertionep66'][0]['decision'] ) ) {
						$success = false;
					}
					$defautinsertionep66['Defautinsertionep66']['decision'] = @$dossierep['Decisiondefautinsertionep66'][0]['decision'];

					// Si réorientation, alors passage en EP Parcours "Réorientation ou maintien d'orientation"
					if( $defautinsertionep66['Defautinsertionep66']['decision'] == 'reorientationprofverssoc' || $defautinsertionep66['Defautinsertionep66']['decision'] == 'reorientationsocversprof' ) {
						$oBilanparcours66 = ClassRegistry::init( 'Bilanparcours66' );

						$orientsstruct = $oBilanparcours66->Orientstruct->find(
							'first',
							array(
								'fields' => array(
									'Orientstruct.id',
									'Orientstruct.typeorient_id',
									'Orientstruct.structurereferente_id',
									'Orientstruct.referent_id'
								),
								'conditions' => array(
									'Orientstruct.personne_id' => $dossierep['Dossierep']['personne_id'],
									'Orientstruct.statut_orient' => 'Orienté',
									'Orientstruct.date_valid IS NOT NULL',
								),
								'order' => array( 'Orientstruct.date_valid DESC' ),
								'contain' => false
							)
						);
						// FIXME: si on ne trouve pas l'orientation ?

						// FIXME: referent_id -> champ obligatoire
						// FIXME: si la structure ne possède pas de référent ???
						if( empty( $orientsstruct['Orientstruct']['referent_id'] ) ) {
							$referent = $oBilanparcours66->Orientstruct->Referent->find(
								'first',
								array(
									'fields' => array( 'Referent.id' ),
									'conditions' => array(
										'Referent.structurereferente_id' => $orientsstruct['Orientstruct']['structurereferente_id']
									),
									'contain' => false
								)
							);

							$orientsstruct['Orientstruct']['referent_id'] = $referent['Referent']['id'];
						}

						$nvdossierep = array(
							/*'Dossierep' => array(
								'personne_id' => $dossierep['Dossierep']['personne_id'],
								// FIXME: changer le nom du thème (reorientationsmaintiens66) ? 2 sous-thèmes: venant du bilan + veant réorientation suite décision commission audition
								// FIXME: faire une nouvelle thématique
								'themeep' => 'saisinesepsbilansparcours66'
							),*/
							'Bilanparcours66' => array(
								'personne_id' => $dossierep['Dossierep']['personne_id'],
								'typeformulaire' => 'cg',
								'orientstruct_id' => $orientsstruct['Orientstruct']['id'],
								'structurereferente_id' => $orientsstruct['Orientstruct']['structurereferente_id'],// FIXME: ?
								'referent_id' => $orientsstruct['Orientstruct']['referent_id'],// FIXME: ?
								'presenceallocataire' => 1,// FIXME: vient des détails de la séance
								'motifsaisine' => 'Proposition de réorientation suite à un passage en EP pour défaut d\'insertion',
								'proposition' => 'parcours',
								'choixparcours' => 'reorientation',
								'reorientation' => ( $defautinsertionep66['Defautinsertionep66']['decision'] == 'reorientationprofverssoc' ? 'PS' : 'SP' ),
								'observbenef' => '', // TODO, vient des observations lors de la séance
								'datebilan' =>date( 'Y-m-d' ),
								'saisineepparcours' => '1',
								'maintienorientation' => 0,
								'origine' => 'Defautinsertionep66'// FIXME: Champ "bidon", à rajouter au schéma ?
								// FIXME: présence allocataire déduite de la présence à l'EP
								//Rédigé par ???
							),
							// FIXME: bilan de parcours arrangé ? nouvelle thématique ?
							'Saisineepbilanparcours66' => array(
								'typeorient_id' => @$dossierep['Decisiondefautinsertionep66'][0]['typeorient_id'],
								'structurereferente_id' => @$dossierep['Decisiondefautinsertionep66'][0]['structurereferente_id'],
							)
						);

						$success = $oBilanparcours66->sauvegardeBilan( $nvdossierep ) && $success;
					}
					/*// TODO Si maintien, alors, RDV référent
					else if( $defautinsertionep66['Defautinsertionep66']['decision'] == 'maintien' ) {
					}*/

					$this->create( $defautinsertionep66 );
					$success = $this->save() && $success;
				}
			}

			return $success;
		}

		/**
		* FIXME: et qui n'ont pas de dossier EP en cours de traitement pour cette thématique
		* FIXME: et qui ne sont pas passés en EP pour ce motif dans un délai de moins de 1 mois (paramétrable)
		*/

		protected function _qdSelection( $datas ) {
			$queryData = array(
				'fields' => array(
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Orientstruct.id',
					'Orientstruct.personne_id',
					'Orientstruct.date_valid',
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
					array(
						'table'      => 'orientsstructs', // FIXME:
						'alias'      => 'Orientstruct',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Orientstruct.personne_id',
							// La dernière
							'Orientstruct.id IN (
										SELECT o.id
											FROM orientsstructs AS o
											WHERE
												o.personne_id = Personne.id
												AND o.date_valid IS NOT NULL
											ORDER BY o.date_valid DESC
											LIMIT 1
							)',
							// en emploi
							'Orientstruct.typeorient_id IN (
								SELECT t.id
									FROM typesorients AS t
									WHERE t.parentid IS NOT NULL
										AND t.lib_type_orient LIKE \'Emploi %\'
							)'// FIXME
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
								AND dossierseps.etapedossierep IN ( \'seance\', \'decisionep\', \'decisioncg\' )
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
								AND dossierseps.themeep = \'defautsinsertionseps66\'
								AND seanceseps.dateseance >= \''.date( 'Y-m-d', strtotime( '-2 mons' ) ).'\'
					)'
				) // FIXME: paramétrage
			);
			
			$nom = Set::classicExtract( $datas, 'Personne.nom' );
			$prenom = Set::classicExtract( $datas, 'Personne.prenom' );
			$dtnai = null;
			if ( !empty( $datas['Personne']['dtnai']['day'] ) && !empty( $datas['Personne']['dtnai']['month'] ) && !empty( $datas['Personne']['dtnai']['year'] ) ) {
				$dtnai = implode( '-', array( Set::classicExtract( $datas, 'Personne.dtnai.year' ), Set::classicExtract( $datas, 'Personne.dtnai.month' ), Set::classicExtract( $datas, 'Personne.dtnai.day' ) ) );
			}
			$nir = Set::classicExtract( $datas, 'Personne.nir' );
			$matricule = Set::classicExtract( $datas, 'Dossier.matricule' );
			$locaadr = Set::classicExtract( $datas, 'Adresse.locaadr' );
			$numcomptt = Set::classicExtract( $datas, 'Adresse.numcomptt' );
			$canton = Set::classicExtract( $datas, 'Adresse.canton' );
			
			if ( !empty( $nom ) ) {
				$queryData['conditions'][] = array( 'Personne.nom' => $nom );
			}
			if ( !empty( $prenom ) ) {
				$queryData['conditions'][] = array( 'Personne.prenom' => $prenom );
			}
			if ( !empty( $dtnai ) ) {
				$queryData['conditions'][] = array( 'Personne.dtnai' => $dtnai );
			}
			if ( !empty( $nir ) ) {
				$queryData['conditions'][] = array( 'Personne.nir' => $nir );
			}
			if ( !empty( $matricule ) ) {
				$queryData['conditions'][] = array( 'Dossier.matricule' => $matricule );
			}
			if ( !empty( $locaadr ) ) {
				$queryData['conditions'][] = array( 'Adresse.locaadr' => $locaadr );
			}
			if ( !empty( $numcomptt ) ) {
				$queryData['conditions'][] = array( 'Adresse.numcomptt' => $numcomptt );
			}
			if ( !empty( $canton ) ) {
				$queryData['conditions'][] = array( 'Adresse.canton' => $canton );
			}
			
			return $queryData;
		}
		
		/**
		*
		*/
		
		public function qdNonInscrits( $datas ) {
			$queryData = $this->_qdSelection( $datas );
			$qdNonInscrits = $this->Historiqueetatpe->Informationpe->qdNonInscrits();
			
			$queryData['fields'] = array_merge( $queryData['fields'] ,$qdNonInscrits['fields'] );
			$queryData['joins'] = array_merge( $queryData['joins'] ,$qdNonInscrits['joins'] );
			$queryData['conditions'] = array_merge( $queryData['conditions'] ,$qdNonInscrits['conditions'] );
			$queryData['order'] = $qdNonInscrits['order'];

			return $queryData;
		}

		/*public function qdNonInscrits( $datas = array() ) {
			$queryData = $this->_qdSelection( $datas );
			// FIXME: à pouvoir paramétrer dans le webrsa.inc
			// FIXME: et qui ne sont pas passés dans une EP pour ce motif depuis au moins 1 mois (?)
			$queryData['conditions'][] = 'Orientstruct.date_valid > \''.date( 'Y-m-d', strtotime( '-2 month' ) ).'\'';
			$queryData['conditions'][] = 'Personne.id NOT IN (
					SELECT
							personnes.id
						FROM informationspe
							INNER JOIN historiqueetatspe ON (
								informationspe.id = historiqueetatspe.informationpe_id
								AND historiqueetatspe.id IN (
											SELECT h.id
												FROM historiqueetatspe AS h
												WHERE h.informationpe_id = informationspe.id
												ORDER BY h.date DESC
												LIMIT 1
								)
							)
							INNER JOIN personnes ON (
								(
									personnes.nir IS NOT NULL
									AND informationspe.nir IS NOT NULL
									AND personnes.nir = informationspe.nir
								)
								OR (
									personnes.nom = informationspe.nom
									AND personnes.prenom = informationspe.prenom
									AND personnes.dtnai = informationspe.dtnai
								)
							)
						WHERE
							personnes.id = Personne.id
							AND historiqueetatspe.etat = \'inscription\'
							AND historiqueetatspe.date >= Orientstruct.date_valid
				)';

			$queryData['order'] = array( 'Orientstruct.date_valid ASC' );

			return $queryData;
		}*/

		/**
		*
		*/
		
		public function qdRadies( $datas ) {
			// FIXME: et qui ne sont pas passés dans une EP pour ce motif depuis au moins 1 mois (?)
			$queryData = $this->_qdSelection( $datas );
			$qdRadies = $this->Historiqueetatpe->Informationpe->qdRadies();
			$queryData['fields'] = array_merge( $queryData['fields'] ,$qdRadies['fields'] );
			$queryData['joins'] = array_merge( $queryData['joins'] ,$qdRadies['joins'] );
			$queryData['conditions'] = array_merge( $queryData['conditions'] ,$qdRadies['conditions'] );
			$queryData['order'] = $qdRadies['order'];
			
			return $queryData;
		}

		/*public function qdRadies( $datas ) {
			// FIXME: et qui ne sont pas passés dans une EP pour ce motif depuis au moins 1 mois (?)
			$queryData = $this->_qdSelection( $datas );
			$queryData['joins'][] = array(
				'table'      => 'informationspe', // FIXME:
				'alias'      => 'Informationpe',
				'type'       => 'INNER',
				'foreignKey' => false,
				'conditions' => array(
					'OR' => array(
						array(
							'Informationpe.nir IS NOT NULL',
							'Personne.nir IS NOT NULL',
							'Informationpe.nir = Personne.nir',
						),
						array(
							'Informationpe.nom = Personne.nom',
							'Informationpe.prenom = Personne.prenom',
							'Informationpe.dtnai = Personne.dtnai',
						)
					)
				)
			);
			$queryData['joins'][] = array(
				'table'      => 'historiqueetatspe', // FIXME:
				'alias'      => 'Historiqueetatpe',
				'type'       => 'INNER',
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
			);

			// FIXME: seulement pour certains motifs
			$queryData['conditions']['Historiqueetatpe.etat'] = 'radiation';
			$queryData['order'] = array( 'Historiqueetatpe.date ASC' );
			
			return $queryData;
		}*/

		/**
		*
		*/

		public function qdProcesVerbal() {
			return array(
				'fields' => array(
					'Defautinsertionep66.id',
					'Defautinsertionep66.dossierep_id',
					'Defautinsertionep66.bilanparcours66_id',
					'Defautinsertionep66.contratinsertion_id',
					'Defautinsertionep66.orientstruct_id',
					'Defautinsertionep66.origine',
					'Defautinsertionep66.type',
					'Defautinsertionep66.historiqueetatpe_id',
					'Defautinsertionep66.created',
					'Defautinsertionep66.modified',
					//
					'Decisiondefautinsertionep66.id',
					'Decisiondefautinsertionep66.defautinsertionep66_id',
					'Decisiondefautinsertionep66.typeorient_id',
					'Decisiondefautinsertionep66.structurereferente_id',
					'Decisiondefautinsertionep66.etape',
					'Decisiondefautinsertionep66.decision',
					'Decisiondefautinsertionep66.commentaire',
					'Decisiondefautinsertionep66.created',
					'Decisiondefautinsertionep66.modified',
				),
				'joins' => array(
					array(
						'table'      => 'defautsinsertionseps66',
						'alias'      => 'Defautinsertionep66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Defautinsertionep66.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'decisionsdefautsinsertionseps66',
						'alias'      => 'Decisiondefautinsertionep66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisiondefautinsertionep66.defautinsertionep66_id = Defautinsertionep66.id',
							'Decisiondefautinsertionep66.etape' => 'ep'
						),
					),
				)
			);
		}
	}
?>