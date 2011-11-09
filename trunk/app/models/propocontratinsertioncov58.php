<?php
	class Propocontratinsertioncov58 extends AppModel
	{
		public $name = 'Propocontratinsertioncov58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'Containable',
			'Enumerable' => array(
				'fields' => array(
					'num_contrat' => array( 'type' => 'num_contrat', 'domain' => 'propocontratinsertioncov58' )
				)
			),
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id' ),
			)
		);

		public $validate = array(
			'structurereferente_id' => array(
				array(
					'rule' => array( 'choixStructure', 'statut_orient' ),
					'message' => 'Champ obligatoire'
				)
			),
			'date_propo' => array(
				'notEmpty' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			),
			'date_valid' => array(
				'notEmpty' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			)
		);

		public $belongsTo = array(
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
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
			),
			'Dossiercov58' => array(
				'className' => 'Dossiercov58',
				'foreignKey' => 'dossiercov58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);



		/**
		* Fonction retournant un querydata qui va permettre de retrouver des dossiers de COV
		*/
		public function qdListeDossier( $cov58_id = null ) {
			$return = array(
				'fields' => array(
					'Dossiercov58.id',
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Dossier.numdemrsa',
					'Adresse.locaadr',
					'Structurereferente.lib_struc',
					'Passagecov58.id',
					'Passagecov58.etatdossiercov',
					'Passagecov58.cov58_id'
				)
			);

			if( !empty( $cov58_id ) ) {
				$join = array(
					'alias' => 'Dossiercov58',
					'table' => 'dossierscovs58',
					'type' => 'INNER',
					'conditions' => array(
						'Dossiercov58.id = '.$this->alias.'.dossiercov58_id'
					)
				);
			}
			else {
				$join = array(
					'alias' => $this->alias,
					'table' => Inflector::tableize( $this->alias ),
					'type' => 'INNER',
					'conditions' => array(
						'Dossiercov58.id = '.$this->alias.'.dossiercov58_id'
					)
				);
			}

			$return['joins'] = array(
				$join,
				array(
					'alias' => 'Structurereferente',
					'table' => 'structuresreferentes',
					'type' => 'INNER',
					'conditions' => array(
						'Structurereferente.id = Propocontratinsertioncov58.structurereferente_id'
					)
				),
				array(
					'alias' => 'Personne',
					'table' => 'personnes',
					'type' => 'INNER',
					'conditions' => array(
						'Dossiercov58.personne_id = Personne.id'
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
					'alias' => 'Passagecov58',
					'table' => 'passagescovs58',
					'type' => 'LEFT OUTER',
					'conditions' => Set::merge(
						array( 'Passagecov58.dossiercov58_id = Dossiercov58.id' ),
						empty( $cov58_id ) ? array() : array(
							'OR' => array(
								'Passagecov58.cov58_id IS NULL',
								'Passagecov58.cov58_id' => $cov58_id
							)
						)
					)
				)
			);
			return $return;
		}





		public function qdDossiersParListe( $cov58_id ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossiercov58->Passagecov58->Cov58->themesTraites( $cov58_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];

			return array(
				'conditions' => array(
					'Dossiercov58.themecov58' => Inflector::tableize( $this->alias ),
					'Dossiercov58.id IN ( '.
						$this->Dossiercov58->Passagecov58->sq(
							array(
								'fields' => array(
									'passagescovs58.dossiercov58_id'
								),
								'alias' => 'passagescovs58',
								'conditions' => array(
									'passagescovs58.cov58_id' => $cov58_id
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
						'Structurereferente',
						'Referent'
					),
					'Passagecov58' => array(
						'conditions' => array(
							'Passagecov58.cov58_id' => $cov58_id
						),
						'Decision'.Inflector::underscore( $this->alias ) => array(
							'Typeorient',
							'order' => array( 'etapecov DESC' )
						)
					)
				)
			);
		}





		/**
		*
		*/

		public function getFields() {
			return array(
				$this->alias.'.id',
				$this->alias.'.datedemande',
				'Structurereferente.lib_struc',
				'Referent.nom',
				'Referent.prenom',
				'Referent.qual'
			);
		}

		/**
		*
		*/

		public function getJoins() {
			return array(
				array(
					'table' => 'proposcontratsinsertioncovs58',
					'alias' => $this->alias,
					'type' => 'INNER',
					'conditions' => array(
						'Dossiercov58.id = Propocontratinsertioncov58.dossiercov58_id'
					)
				),
				array(
					'table' => 'structuresreferentes',
					'alias' => 'Structurereferente',
					'type' => 'INNER',
					'conditions' => array(
						'Propocontratinsertioncov58.structurereferente_id = Structurereferente.id'
					)
				),
				array(
					'table' => 'referents',
					'alias' => 'Referent',
					'type' => 'LEFT OUTER',
					'conditions' => array(
						'Propocontratinsertioncov58.referent_id = Referent.id'
					)
				)
			);
		}

		/**
		*
		*/

		public function ajoutPossible( $personne_id ) {
			$nbDossierscov = $this->Dossiercov58->find(
				'count',
				array(
					'conditions' => array(
						'Dossiercov58.personne_id' => $personne_id,
						'Dossiercov58.etapecov <>' => 'finalise'
					),
					'contain' => array(
						'Propocontratinsertioncov58'
					)
				)
			);

			$nbPersonnes = $this->Personne->find(
				'count',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id,
					),
					'joins' => array(
						array(
							'table'      => 'prestations',
							'alias'      => 'Prestation',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Prestation.personne_id',
								'Prestation.natprest = \'RSA\'',
								'Prestation.rolepers' => array( 'DEM', 'CJT' )
							)
						),
						array(
							'table'      => 'calculsdroitsrsa',
							'alias'      => 'Calculdroitrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Calculdroitrsa.personne_id',
								'Calculdroitrsa.toppersdrodevorsa' => 1
							)
						),
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
						),
						array(
							'table'      => 'dossiers',
							'alias'      => 'Dossier',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
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
					),
					'recursive' => -1
				)
			);
			return ( ( $nbDossierscov == 0 ) && ( $nbPersonnes == 1 ) );
		}

		/**
		*
		*/
		public function saveDecisions( $data ) {
			$modelDecisionName = 'Decision'.Inflector::underscore( $this->alias );


			$success = true;
			if ( isset( $data[$modelDecisionName] ) && !empty( $data[$modelDecisionName] ) ) {
				foreach( $data[$modelDecisionName] as $key => $values ) {
					
					$passagecov58 = $this->Dossiercov58->Passagecov58->find(
						'first',
						array(
							'fields' => array_merge(
								$this->Dossiercov58->Passagecov58->fields(),
								$this->Dossiercov58->Passagecov58->Cov58->fields(),
								$this->Dossiercov58->fields(),
								$this->fields()
							),
							'conditions' => array(
								'Passagecov58.id' => $values['passagecov58_id']
							),
							'joins' => array(
								$this->Dossiercov58->Passagecov58->join( 'Dossiercov58' ),
								$this->Dossiercov58->Passagecov58->join( 'Cov58' ),
								$this->Dossiercov58->join( $this->alias )
							)
						)
					);

					if( $values['decisioncov'] == 'valide' ){
						$contratinsertion = array(
							'Contratinsertion' => array(
								'personne_id' => $passagecov58['Dossiercov58']['personne_id'],
								'structurereferente_id' => $passagecov58['Propocontratinsertioncov58']['structurereferente_id'],
								'referent_id' => $passagecov58['Propocontratinsertioncov58']['referent_id'],
								'num_contrat' => $passagecov58['Propocontratinsertioncov58']['num_contrat'],
								'dd_ci' => $passagecov58['Propocontratinsertioncov58']['dd_ci'],
								'duree_engag' => $passagecov58['Propocontratinsertioncov58']['duree_engag'],
								'df_ci' => $passagecov58['Propocontratinsertioncov58']['df_ci'],
								'forme_ci' => $passagecov58['Propocontratinsertioncov58']['forme_ci'],
								'avisraison_ci' => $passagecov58['Propocontratinsertioncov58']['avisraison_ci'],
								'rg_ci' => $passagecov58['Propocontratinsertioncov58']['rg_ci'],
								'observ_ci' => $values['commentaire'],
								'date_saisi_ci' => $passagecov58['Propocontratinsertioncov58']['datedemande'],
								'datevalidation_ci' => $values['datevalidation'],
								'decision_ci' => 'V',
								'avenant_id' => $passagecov58['Propocontratinsertioncov58']['avenant_id']
							)
						);

						$this->Dossiercov58->Personne->Contratinsertion->create( $contratinsertion ) && $success;
						$success = $this->Dossiercov58->Personne->Contratinsertion->save() && $success;


					}
					else if( $values['decisioncov'] == 'refuse' ){
						$contratinsertion = array(
							'Contratinsertion' => array(
								'personne_id' => $passagecov58['Dossiercov58']['personne_id'],
								'structurereferente_id' => $passagecov58['Propocontratinsertioncov58']['structurereferente_id'],
								'referent_id' => $passagecov58['Propocontratinsertioncov58']['referent_id'],
								'num_contrat' => $passagecov58['Propocontratinsertioncov58']['num_contrat'],
								'dd_ci' => $passagecov58['Propocontratinsertioncov58']['dd_ci'],
								'duree_engag' => $passagecov58['Propocontratinsertioncov58']['duree_engag'],
								'df_ci' => $passagecov58['Propocontratinsertioncov58']['df_ci'],
								'forme_ci' => $passagecov58['Propocontratinsertioncov58']['forme_ci'],
								'avisraison_ci' => $passagecov58['Propocontratinsertioncov58']['avisraison_ci'],
								'rg_ci' => $passagecov58['Propocontratinsertioncov58']['rg_ci'],
								'observ_ci' => $values['commentaire'],
								'date_saisi_ci' => $passagecov58['Propocontratinsertioncov58']['datedemande'],
								'datevalidation_ci' => $values['datevalidation'],
								'decision_ci' => 'N',
								'avenant_id' => $passagecov58['Propocontratinsertioncov58']['avenant_id']
							)
						);

						$this->Dossiercov58->Personne->Contratinsertion->create( $contratinsertion ) && $success;
						$success = $this->Dossiercov58->Personne->Contratinsertion->save() && $success;


					}

					// Modification etat du dossier passé dans la COV
					if( in_array( $values['decisioncov'], array( 'valide', 'refuse' ) ) ){
						$this->Dossiercov58->Passagecov58->updateAll(
							array( 'Passagecov58.etatdossiercov' => '\'traite\'' ),
							array('"Passagecov58"."id"' => $passagecov58['Passagecov58']['id'] )
						);
					}
					else if( $values['decisioncov'] == 'annule' ){
						$this->Dossiercov58->Passagecov58->updateAll(
							array( 'Passagecov58.etatdossiercov' => '\'annule\'' ),
							array('"Passagecov58"."id"' => $passagecov58['Passagecov58']['id'] )
						);
					}
					else if( $values['decisioncov'] == 'reporte' ){
						$this->Dossiercov58->Passagecov58->updateAll(
							array( 'Passagecov58.etatdossiercov' => '\'reporte\'' ),
							array('"Passagecov58"."id"' => $passagecov58['Passagecov58']['id'] )
						);
					}
				}
// debug($contratinsertion);
				$success = $this->Dossiercov58->Passagecov58->{$modelDecisionName}->saveAll( Set::extract( $data, '/'.$modelDecisionName ), array( 'atomic' => false ) );

			}

			return $success;
		}

		/**
		*
		*/
		public function acteDecision($data) {
			$modelDecisionName = 'Decision'.Inflector::underscore( $this->alias );
			$success = true;

			if ( isset( $data[$modelDecisionName] ) && !empty( $data[$modelDecisionName] ) ) {
				foreach( $data[$modelDecisionName] as $key => $values ) {
					$passagecov58 = $this->Dossiercov58->Passagecov58->find(
						'first',
						array(
							'fields' => array_merge(
								$this->Dossiercov58->Passagecov58->fields(),
								$this->Dossiercov58->Passagecov58->Cov58->fields(),
								$this->Dossiercov58->fields(),
								$this->fields()
							),
							'conditions' => array(
								'Passagecov58.id' => $values['passagecov58_id']
							),
							'joins' => array(
								$this->Dossiercov58->Passagecov58->join( 'Dossiercov58' ),
								$this->Dossiercov58->Passagecov58->join( 'Cov58' ),
								$this->Dossiercov58->join( $this->alias )
							)
						)
					);


// 					list($datevalidation, $heure) = explode(' ', $values['datevalidation']);

					if( $values['decisioncov'] == 'valide' ){
						$contratinsertion = array(
							'Contratinsertion' => array(
								'personne_id' => $passagecov58['Dossiercov58']['personne_id'],
								'structurereferente_id' => $passagecov58['Propocontratinsertioncov58']['structurereferente_id'],
								'referent_id' => $passagecov58['Propocontratinsertioncov58']['referent_id'],
								'num_contrat' => $passagecov58['Propocontratinsertioncov58']['num_contrat'],
								'dd_ci' => $passagecov58['Propocontratinsertioncov58']['dd_ci'],
								'duree_engag' => $passagecov58['Propocontratinsertioncov58']['duree_engag'],
								'df_ci' => $passagecov58['Propocontratinsertioncov58']['df_ci'],
								'forme_ci' => $passagecov58['Propocontratinsertioncov58']['forme_ci'],
								'avisraison_ci' => $passagecov58['Propocontratinsertioncov58']['avisraison_ci'],
								'rg_ci' => $passagecov58['Propocontratinsertioncov58']['rg_ci'],
								'observ_ci' => $values['commentaire'],
								'date_saisi_ci' => $passagecov58['Propocontratinsertioncov58']['datedemande'],
								'datevalidation_ci' => $values['datevalidation'],
								'decision_ci' => 'V',
								'avenant_id' => $passagecov58['Propocontratinsertioncov58']['avenant_id']
							)
						);

					}
// debug($passagecov58);
// debug( $contratinsertion );
// die();
					$this->Dossiercov58->Personne->Contratinsertion->create( $contratinsertion ) && $success;
					$success = $this->Dossiercov58->Personne->Contratinsertion->save() && $success;
				}
			}


			return $success;
		}


		/**
		*
		*/

// 		public function qdProcesVerbal() {
// 			return array(
// 				'fields' => array(
// 					'Propocontratinsertioncov58.id',
// 					'Propocontratinsertioncov58.dossiercov58_id',
// 					'Propocontratinsertioncov58.structurereferente_id',
// 					'Propocontratinsertioncov58.referent_id',
// 					'Propocontratinsertioncov58.datedemande',
// 					'Propocontratinsertioncov58.num_contrat',
// 					'Propocontratinsertioncov58.dd_ci',
// 					'Propocontratinsertioncov58.duree_engag',
// 					'Propocontratinsertioncov58.df_ci',
// 					'Propocontratinsertioncov58.forme_ci',
// 					'Propocontratinsertioncov58.avisraison_ci',
// 					'Propocontratinsertioncov58.rg_ci',
// 					'Propocontratinsertioncov58.datevalidation',
// 					'Propocontratinsertioncov58.commentaire',
// 					'Propocontratinsertioncov58.decisioncov'
// 				),
// 				'joins' => array(
// 					array(
// 						'table'      => 'proposcontratsinsertioncovs58',
// 						'alias'      => 'Propocontratinsertioncov58',
// 						'type'       => 'LEFT OUTER',
// 						'foreignKey' => false,
// 						'conditions' => array( 'Propocontratinsertioncov58.dossiercov58_id = Dossiercov58.id' ),
// 					)
// 				)
// 			);
// 		}


		/**
		*
		*/

		public function qdProcesVerbal() {
			$modele = 'Propocontratinsertioncov58';
			$modeleDecisions = 'Decisionpropocontratinsertioncov58';

			return array(
				'fields' => array(
					"{$modele}.id",
					"{$modele}.dossiercov58_id",
					"{$modele}.structurereferente_id",
					"{$modele}.referent_id",
					"{$modele}.datedemande",
					"{$modele}.num_contrat",
					"{$modele}.dd_ci",
					"{$modele}.duree_engag",
					"{$modele}.df_ci",
					"{$modele}.forme_ci",
					"{$modele}.avisraison_ci",
					"{$modele}.rg_ci",
					"{$modele}.datevalidation",
					"{$modele}.commentaire",
					"{$modele}.decisioncov",
					"{$modele}.avenant_id",
					"{$modeleDecisions}.id",
					"{$modeleDecisions}.etapecov",
					"{$modeleDecisions}.decisioncov",
					"{$modeleDecisions}.datevalidation",
					"{$modeleDecisions}.commentaire",
					"{$modeleDecisions}.created",
					"{$modeleDecisions}.modified",
					"{$modeleDecisions}.passagecov58_id"
				),
				'joins' => array(
					array(
						'table'      => Inflector::tableize( $modele ),
						'alias'      => $modele,
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( "{$modele}.dossiercov58_id = Dossiercov58.id" ),
					),
					array(
						'table'      => Inflector::tableize( $modeleDecisions ),
						'alias'      => $modeleDecisions,
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							"{$modeleDecisions}.passagecov58_id = Passagecov58.id",
							"{$modeleDecisions}.etapecov" => 'finalise'
						),
					),
				)
			);
		}

		/**
		*
		*/

		public function qdOrdreDuJour() {
			return array(
				'fields' => array(
					'Propocontratinsertioncov58.id',
					'Propocontratinsertioncov58.dossiercov58_id',
					'Propocontratinsertioncov58.structurereferente_id',
					'Propocontratinsertioncov58.referent_id',
					'Propocontratinsertioncov58.datedemande',
					'Propocontratinsertioncov58.num_contrat',
					'Propocontratinsertioncov58.dd_ci',
					'Propocontratinsertioncov58.duree_engag',
					'Propocontratinsertioncov58.df_ci',
					'Propocontratinsertioncov58.forme_ci',
					'Propocontratinsertioncov58.avisraison_ci',
					'Propocontratinsertioncov58.rg_ci',
					'Propocontratinsertioncov58.datevalidation',
					'Propocontratinsertioncov58.commentaire',
					'Propocontratinsertioncov58.decisioncov'
				),
				'joins' => array(
					array(
						'table'      => 'proposcontratsinsertioncovs58',
						'alias'      => 'Propocontratinsertioncov58',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propocontratinsertioncov58.dossiercov58_id = Dossiercov58.id' ),
					)
				)
			);
		}

		/**
		*
		*/

		public function getPdfDecision( $dossiercov58_id ) {
			///INFO : pour le moment aucun courrier donc à faire dès qu'on en aura
			return false;
		}


	}
?>