<?php
	/**
	* Saisines d'EP pour les réorientations proposées par les structures
	* référentes pour le conseil général du département 93.
	*
	* Il s'agit de l'un des thèmes des EPs pour le CG 93.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Reorientationep93 extends AppModel
	{
		public $name = 'Reorientationep93';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id',
					'referent_id',
				)
			),
			'Enumerable' => array(
				'fields' => array(
					'accordaccueil',
					'accordallocataire',
					'urgent',
				)
			),
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
			'Motifreorientep93' => array(
				'className' => 'Motifreorientep93',
				'foreignKey' => 'motifreorientep93_id',
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
			),
		);

		/**
		* Retourne pour un personne_id donnée les queryDatas permettant de retrouver
		* ses réorientationseps93 si elle en a en cours
		*/
		
		public function qdReorientationEnCours( $personne_id ) {
			return array(
				'fields' => array(
					'Personne.nom',
					'Personne.prenom',
					'Reorientationep93.id',
					'Reorientationep93.datedemande',
					'Orientstruct.rgorient',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Passagecommissionep.etatdossierep'
				),
				'conditions' => array(
					'Dossierep.personne_id' => $personne_id,
					'Dossierep.themeep' => 'reorientationseps93',
					'Dossierep.id NOT IN ( '.$this->Orientstruct->Personne->Dossierep->Passagecommissionep->sq(
						array(
							'alias' => 'passagescommissionseps',
							'fields' => array(
								'passagescommissionseps.dossierep_id'
							),
							'conditions' => array(
								'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
							)
						)
					).' )'
				),
				'joins' => array(
					array(
						'table' => 'dossierseps',
						'alias' => 'Dossierep',
						'type' => 'INNER',
						'conditions' => array(
							'Reorientationep93.dossierep_id = Dossierep.id'
						)
					),
					array(
						'table' => 'orientsstructs',
						'alias' => 'Orientstruct',
						'type' => 'INNER',
						'conditions' => array(
							'Reorientationep93.orientstruct_id = Orientstruct.id'
						)
					),
					array(
						'table' => 'typesorients',
						'alias' => 'Typeorient',
						'type' => 'INNER',
						'conditions' => array(
							'Reorientationep93.typeorient_id = Typeorient.id'
						)
					),
					array(
						'table' => 'structuresreferentes',
						'alias' => 'Structurereferente',
						'type' => 'INNER',
						'conditions' => array(
							'Reorientationep93.structurereferente_id = Structurereferente.id'
						)
					),
					array(
						'table' => 'passagescommissionseps',
						'alias' => 'Passagecommissionep',
						'type' => 'LEFT OUTER',
						'conditions' => array(
							'Passagecommissionep.dossierep_id = Dossierep.id'
						)
					),
					array(
						'table' => 'commissionseps',
						'alias' => 'Commissionep',
						'type' => 'LEFT OUTER',
						'conditions' => array(
							'Passagecommissionep.commissionep_id = Commissionep.id'
						)
					),
					array(
						'table' => 'personnes',
						'alias' => 'Personne',
						'type' => 'INNER',
						'conditions' => array(
							'Dossierep.personne_id = Personne.id'
						)
					)
				),
				'contain' => false,
				'order' => array( 'Commissionep.dateseance DESC', 'Commissionep.id DESC' )
			);
		}

		/**
		*
		*/

		public function ajoutPossible( $personne_id ) {
			return $this->Orientstruct->ajoutPossible( $personne_id );
		}

		/**
		* TODO: comment finaliser l'orientation précédente ?
		*/

		public function finaliser( $commissionep_id, $etape, $user_id ) {
			$dossierseps = $this->Dossierep->Passagecommissionep->find(
				'all',
				array(
					'fields' => array(
						'Passagecommissionep.id',
						'Passagecommissionep.commissionep_id',
						'Passagecommissionep.dossierep_id',
						'Passagecommissionep.etatdossierep',
						'Dossierep.personne_id',
						'Decisionreorientationep93.decision',
						'Decisionreorientationep93.typeorient_id',
						'Decisionreorientationep93.structurereferente_id',
						'Reorientationep93.structurereferente_id',
						'Reorientationep93.referent_id',
						'Reorientationep93.datedemande'
					),
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $commissionep_id
					),
					'joins' => array(
						array(
							'table' => 'dossierseps',
							'alias' => 'Dossierep',
							'type' => 'INNER',
							'conditions' => array(
								'Passagecommissionep.dossierep_id = Dossierep.id'
							)
						),
						array(
							'table' => 'reorientationseps93',
							'alias' => 'Reorientationep93',
							'type' => 'INNER',
							'conditions' => array(
								'Reorientationep93.dossierep_id = Dossierep.id'
							)
						),
						array(
							'table' => 'decisionsreorientationseps93',
							'alias' => 'Decisionreorientationep93',
							'type' => 'INNER',
							'conditions' => array(
								'Decisionreorientationep93.passagecommissionep_id = Passagecommissionep.id',
								'Decisionreorientationep93.etape' => $etape
							)
						)
					),
					'contain' => false
				)
			);
			
			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $dossierep['Decisionreorientationep93']['decision'] == 'accepte' ) {

					// Nouvelle orientation
					$orientstruct = array(
						'Orientstruct' => array(
							'personne_id' => $dossierep['Dossierep']['personne_id'],
							'typeorient_id' => $dossierep['Decisionreorientationep93']['typeorient_id'],
							'structurereferente_id' => $dossierep['Decisionreorientationep93']['structurereferente_id'],
							'date_propo' => date( 'Y-m-d' ),
							'date_valid' => date( 'Y-m-d' ),
							'statut_orient' => 'Orienté',
							'user_id' => $user_id,
						)
					);

					// Si on avait choisi une personne référente et que le passage en EP
					// valide la structure à laquelle cette personne est attachée, alors,
					// on recopie cette personne -> FIXME: dans orientsstructs ou dans personnes_referents
					if( !empty( $dossierep['Reorientationep93']['referent_id'] ) && $dossierep['Reorientationep93']['structurereferente_id'] == $dossierep['Decisionreorientationep93']['structurereferente_id'] ) {
						$orientstruct['Orientstruct']['referent_id'] = $dossierep['Reorientationep93']['referent_id'];
					}

					// La date de proposition de l'orientation devient la date de demande de la réorientation.
					if( !empty( $dossierep['Reorientationep93']['datedemande'] ) ) {
						$orientstruct['Orientstruct']['date_propo'] = $dossierep['Reorientationep93']['datedemande'];
					}

					$this->Orientstruct->create( $orientstruct );
					$success = $this->Orientstruct->save() && $success;

					// Recherche dernier CER
					$dernierCerId = $this->Orientstruct->Personne->Contratinsertion->find(
						'first',
						array(
							'fields' => array( 'Contratinsertion.id' ),
							'conditions' => array(
								'Contratinsertion.personne_id' => $dossierep['Dossierep']['personne_id']
							),
							array( 'Contratinsertion.df_ci DESC' )
						)
					);

					// Clôture anticipée du dernier CER
					$this->Orientstruct->Personne->Contratinsertion->updateAll(
						array( 'Contratinsertion.df_ci' => "'".date( 'Y-m-d' )."'" ),
						array( 'Contratinsertion.id' => $dernierCerId['Contratinsertion']['id'] )
					);

					// TODO
					/*$this->Orientstruct->Personne->Cui->updateAll(
						array( 'Cui.datefincontrat' => "'".date( 'Y-m-d' )."'" )
// 						array( '"Cui"."datefincontrat" IS NULL' )
					) ;*/

					// Fin de désignation du référent de la personne
					$this->Orientstruct->Personne->PersonneReferent->updateAll(
						array( 'PersonneReferent.dfdesignation' => "'".date( 'Y-m-d' )."'" ),
						array(
							'"PersonneReferent"."personne_id"' => $dossierep['Dossierep']['personne_id'],
							'"PersonneReferent"."dfdesignation" IS NULL'
						)
					);
				}
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
		* Querydata permettant d'obtenir les dossiers qui doivent être traités
		* par liste pour la thématique de ce modèle.
		*
		* TODO: une autre liste pour avoir un tableau permettant d'accéder à la fiche
		* TODO: que ceux avec accord, les autres en individuel
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut les dossiers à passer par liste.
		* @return array
		* @access public
		*/

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
						'Motifreorientep93',
						'Typeorient',
						'Structurereferente',
						'Orientstruct' => array(
							'conditions' => array(
								'Orientstruct.rgorient IS NOT NULL'
							),
							'Typeorient',
							'Structurereferente',
						)
					),
					'Passagecommissionep' => array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id
						),
						'Decisionreorientationep93' => array(
							'order' => array( 'Decisionreorientationep93.etape DESC' ),
							'Typeorient',
							'Structurereferente',
						)
					)
				)
			);
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			// FIXME: filtrer les données
			$themeData = Set::extract( $data, '/Decisionreorientationep93' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				$success = $this->Dossierep->Passagecommissionep->Decisionreorientationep93->saveAll( $themeData, array( 'atomic' => false ) );
				$this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => Set::extract( $data, '/Decisionreorientationep93/passagecommissionep_id' ) )
				);

				return $success;
			}
		}

		/**
		* Prépare les données du formulaire d'un niveau de décision
		* en prenant en compte les données du bilan ou du niveau de décision
		* précédent.
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param array $datas Les données des dossiers
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut préparer les données du formulaire
		* @return array
		* @access public
		*/

		public function prepareFormData( $commissionep_id, $datas, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			$formData = array();
			foreach( $datas as $key => $dossierep ) {
				$formData['Reorientationep93'][$key]['id'] = @$datas[$key]['Reorientationep93']['id'];
				$formData['Reorientationep93'][$key]['dossierep_id'] = @$datas[$key]['Reorientationep93']['dossierep_id'];
				$formData['Decisionreorientationep93'][$key]['passagecommissionep_id'] = @$datas[$key]['Passagecommissionep'][0]['id'];
				if( $niveauDecision == 'ep' ) {
					if( !empty( $datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0] ) ) { // Modification
						$formData['Decisionreorientationep93'][$key]['id'] = @$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['id'];
						$formData['Decisionreorientationep93'][$key]['decision'] = @$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['decision'];
						$formData['Decisionreorientationep93'][$key]['raisonnonpassage'] = @$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['raisonnonpassage'];
						$formData['Decisionreorientationep93'][$key]['commentaire'] = @$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['commentaire'];

						$formData['Decisionreorientationep93'][$key]['typeorient_id'] = @$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['typeorient_id'];
						$formData['Decisionreorientationep93'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								@$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['typeorient_id'],
								@$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['structurereferente_id']
							)
						);
					}
					else {
						$formData['Decisionreorientationep93'][$key]['typeorient_id'] = $dossierep[$this->alias]['typeorient_id'];
						$formData['Decisionreorientationep93'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['typeorient_id'],
								$dossierep[$this->alias]['structurereferente_id']
							)
						);
						// Si accords -> accepté par défaut, sinon, refusé par défaut
						$accord = ( $dossierep[$this->alias]['accordaccueil'] && $dossierep[$this->alias]['accordallocataire'] );
						$formData['Decisionreorientationep93'][$key]['decision'] = ( $accord ? 'accepte' : 'refuse' );
					}
				}
				else if( $niveauDecision == 'cg' ) {
					$formData['Decisionreorientationep93'][$key]['decision'] = $dossierep['Passagecommissionep'][0]['Decisionreorientationep93'][0]['decision'];
					$formData['Decisionreorientationep93'][$key]['raisonnonpassage'] = @$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['raisonnonpassage'];
					$formData['Decisionreorientationep93'][$key]['commentaire'] = @$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['commentaire'];
					
					if( !empty( $datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][1] ) ) { // Modification
						$formData['Decisionreorientationep93'][$key]['id'] = @$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['id'];
						$formData['Decisionreorientationep93'][$key]['decisionpcg'] = @$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['decisionpcg'];

						$formData['Decisionreorientationep93'][$key]['typeorient_id'] = @$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['typeorient_id'];
						$formData['Decisionreorientationep93'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								@$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['typeorient_id'],
								@$datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0]['structurereferente_id']
							)
						);
					}
					elseif( !empty( $datas[$key]['Passagecommissionep'][0]['Decisionreorientationep93'][0] ) ) {
						$formData['Decisionreorientationep93'][$key]['decisionpcg'] = 'valide';
						if( $formData['Decisionreorientationep93'][$key]['decision'] == 'refuse' ) {
							$formData['Decisionreorientationep93'][$key]['typeorient_id'] = $dossierep[$this->alias]['typeorient_id'];
							$formData['Decisionreorientationep93'][$key]['structurereferente_id'] = implode(
								'_',
								array(
									$dossierep[$this->alias]['typeorient_id'],
									$dossierep[$this->alias]['structurereferente_id']
								)
							);
						}
						else {
							$formData['Decisionreorientationep93'][$key]['typeorient_id'] = $dossierep['Passagecommissionep'][0]['Decisionreorientationep93'][0]['typeorient_id'];
							$formData['Decisionreorientationep93'][$key]['structurereferente_id'] = implode(
								'_',
								array(
									$dossierep['Passagecommissionep'][0]['Decisionreorientationep93'][0]['typeorient_id'],
									$dossierep['Passagecommissionep'][0]['Decisionreorientationep93'][0]['structurereferente_id']
								)
							);
						}
					}
				}
			}

			return $formData;
		}

		/**
		* FIXME: à continuer
		*/

		public function generatePdfDecisionEp( $dossierep_id ) {
			$joins = array(
				array(
					'table'      => 'dossierseps',
					'alias'      => 'Dossierep',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( "Dossierep.id = {$this->alias}.dossierep_id" ),
				),
				array(
					'table'      => 'personnes',
					'alias'      => 'Personne',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( "Dossierep.personne_id = Personne.id" ),
				),
				array(
					'table'      => 'foyers',
					'alias'      => 'Foyer',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( "Foyer.id = Personne.foyer_id" ),
				),
				array(
					'table'      => 'adressesfoyers',
					'alias'      => 'Adressefoyer',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array(
						"Foyer.id = Adressefoyer.foyer_id",
						"Adressefoyer.rgadr" => '01'
					),
					'limit'      => 1
				),
				array(
					'table'      => 'adresses',
					'alias'      => 'Adresse',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( "Adressefoyer.adresse_id = Adresse.id" ),
				),
				// Informations sur la décision
				array(
					'table'      => 'decisionsreorientationseps93',
					'alias'      => 'Decisionreorientationep93',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( "{$this->alias}.id = Decisionreorientationep93.reorientationep93_id" ),
					'order'		 => array( 'Decisionreorientationep93.etape DESC' ), // INFO: d'abord CG, puis EP
					'limit'		 => 1
				),
				array(
					'table'      => 'typesorients',
					'alias'      => 'Typeorient',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( "Decisionreorientationep93.typeorient_id = Typeorient.id" ),
				),
				array(
					'table'      => 'structuresreferentes',
					'alias'      => 'Structurereferente',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( "Decisionreorientationep93.structurereferente_id = Structurereferente.id" ),
				),
			);

			// FIXME -> une fois que les champs seront fixés, mettre en dur ... ou utiliser le cache ??
			$dbo = $this->getDataSource( $this->useDbConfig );
			$fields = array();
			foreach( $joins as $join ) {
				$fields = Set::merge( $fields, $dbo->fields( ClassRegistry::init( $join['alias'] ) ) );
			}

			$gedooo_data = $this->find(
				'first',
				array(
					'fields' => $fields,
					'conditions' => array(
						'Dossierep.id' => $dossierep_id
					),
					'joins' => $joins,
					'contain' => false
				)
			);

			//$gedooo_data = $this->getDataForPdf( $id );

			$modeledoc = "{$this->alias}/decision_{$gedooo_data['Decisionreorientationep93']['decision']}.odt";

			$pdf = $this->ged( $gedooo_data, $modeledoc );
			$success = true;

			if( $pdf ) {
				$pdfModel = ClassRegistry::init( 'Pdf' );
				$pdfModel->create(
					array(
						'Pdf' => array(
							'modele' => $this->alias,
							'modeledoc' => $modeledoc,
							'fk_value' => $gedooo_data['Decisionreorientationep93']['id'],
							'document' => $pdf
						)
					)
				);
				$success = $pdfModel->save() && $success;
			}
			else {
				$success = false;
			}

// 			return $success;
			return false;
		}

		/**
		*
		*/

		public function containPourPv() {
			return array(
				'Reorientationep93' => array(
					'Decisionreorientationep93' => array(
						'conditions' => array(
							'etape' => 'ep'
						),
						'Typeorient',
						'Structurereferente'
					)
				)
			);
		}

		/**
		*
		*/

		public function qdProcesVerbal() {
			return array(
				'fields' => array(
					'Reorientationep93.id',
					'Reorientationep93.dossierep_id',
					'Reorientationep93.orientstruct_id',
					'Reorientationep93.typeorient_id',
					'Reorientationep93.structurereferente_id',
					'Reorientationep93.datedemande',
					'Reorientationep93.referent_id',
					'Reorientationep93.motifreorientep93_id',
					'Reorientationep93.commentaire',
					'Reorientationep93.accordaccueil',
					'Reorientationep93.desaccordaccueil',
					'Reorientationep93.accordallocataire',
					'Reorientationep93.urgent',
					'Reorientationep93.created',
					'Reorientationep93.modified',
					'Decisionreorientationep93.id',
// 					'Decisionreorientationep93.reorientationep93_id',
					'Decisionreorientationep93.etape',
					'Decisionreorientationep93.decision',
					'Decisionreorientationep93.typeorient_id',
					'Decisionreorientationep93.structurereferente_id',
					'Decisionreorientationep93.referent_id',
					'Decisionreorientationep93.commentaire',
					'Decisionreorientationep93.created',
					'Decisionreorientationep93.modified',
					'Decisionreorientationep93.raisonnonpassage',
				),
				'joins' => array(
					array(
						'table'      => 'reorientationseps93',
						'alias'      => 'Reorientationep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Reorientationep93.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'decisionsreorientationseps93',
						'alias'      => 'Decisionreorientationep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionreorientationep93.passagecommissionep_id = Passagecommissionep.id',
							'Decisionreorientationep93.etape' => 'ep'
						),
					)
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
		* Fonction retournant un querydata qui va permettre de retrouver des dossiers d'EP
		*/

		public function qdListeDossier( $commissionep_id = null ) {
			return array(
				'fields' => array(
					'Dossierep.id',
					'Dossier.numdemrsa',
					'Dossier.matricule',
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Structurereferente.lib_struc',
					'Motifreorientep93.name',
					'Reorientationep93.accordaccueil',
					'Reorientationep93.accordallocataire',
					'Reorientationep93.urgent',
					'Reorientationep93.datedemande',
					'Passagecommissionep.id',
					'Passagecommissionep.commissionep_id'
				),
				'joins' => array(
					array(
						'alias' => $this->alias,
						'table' => Inflector::tableize( $this->alias ),
						'type' => 'INNER',
						'conditions' => array(
							'Dossierep.id = '.$this->alias.'.dossierep_id'
						)
					),
					array(
						'alias' => 'Structurereferente',
						'table' => 'structuresreferentes',
						'type' => 'INNER',
						'conditions' => array(
							'Structurereferente.id = '.$this->alias.'.structurereferente_id'
						)
					),
					array(
						'alias' => 'Motifreorientep93',
						'table' => 'motifsreorientseps93',
						'type' => 'INNER',
						'conditions' => array(
							'Motifreorientep93.id = '.$this->alias.'.motifreorientep93_id'
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
				)
			);
		}
	}
?>