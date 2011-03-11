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

	class Saisineepreorientsr93 extends AppModel
	{
		public $name = 'Saisineepreorientsr93';

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
			'Motifreorient' => array(
				'className' => 'Motifreorient',
				'foreignKey' => 'motifreorient_id',
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

		public $hasMany = array(
			'Nvsrepreorientsr93' => array(
				'className' => 'Nvsrepreorientsr93',
				'foreignKey' => 'saisineepreorientsr93_id',
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

		public function ajoutPossible( $personne_id ) {
			return $this->Orientstruct->ajoutPossible( $personne_id );
		}

		/**
		* TODO: comment finaliser l'orientation précédente ?
		*/

		public function finaliser( $seanceep_id, $etape, $user_id ) {
			$dossierseps = $this->find(
				'all',
				array(
					'conditions' => array(
						'Dossierep.seanceep_id' => $seanceep_id
					),
					'contain' => array(
						'Nvsrepreorientsr93' => array(
							'conditions' => array(
								'Nvsrepreorientsr93.etape' => $etape
							)
						),
						'Dossierep',
						'Orientstruct',
					)
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $dossierep['Nvsrepreorientsr93'][0]['decision'] == 'accepte' ) {

					// Nouvelle orientation
					$orientstruct = array(
						'Orientstruct' => array(
							'personne_id' => $dossierep['Orientstruct']['personne_id'],
							'typeorient_id' => $dossierep['Nvsrepreorientsr93'][0]['typeorient_id'],
							'structurereferente_id' => $dossierep['Nvsrepreorientsr93'][0]['structurereferente_id'],
							'date_propo' => date( 'Y-m-d' ),
							'date_valid' => date( 'Y-m-d' ),
							'statut_orient' => 'Orienté',
							'user_id' => $user_id,
						)
					);

					// Si on avait choisi une personne référente et que le passage en EP
					// valide la structure à laquelle cette personne est attachée, alors,
					// on recopie cette personne -> FIXME: dans orientsstructs ou dans personnes_referents
					if( !empty( $dossierep['Saisineepreorientsr93']['referent_id'] ) && $dossierep['Saisineepreorientsr93']['structurereferente_id'] == $dossierep['Nvsrepreorientsr93'][0]['structurereferente_id'] ) {
						$orientstruct['Orientstruct']['referent_id'] = $dossierep['Saisineepreorientsr93']['referent_id'];
					}

					// La date de proposition de l'orientation devient la date de demande de la réorientation.
					if( !empty( $dossierep['Saisineepreorientsr93']['datedemande'] ) ) {
						$orientstruct['Orientstruct']['date_propo'] = $dossierep['Saisineepreorientsr93']['datedemande'];
					}

					$this->Orientstruct->create( $orientstruct );
					$success = $this->Orientstruct->save() && $success;

					// Recherche dernier CER
					$dernierCerId = $this->Orientstruct->Personne->Contratinsertion->find(
						'first',
						array(
							'fields' => array( 'Contratinsertion.id' ),
							'conditions' => array(
								'Contratinsertion.personne_id' => $dossierep['Orientstruct']['personne_id']
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
							'"PersonneReferent"."personne_id"' => $dossierep['Orientstruct']['personne_id'],
							'"PersonneReferent"."dfdesignation" IS NULL'
						)
					);
				}

				// FIXME: à continuer
				//$success = $this->generatePdfDecisionEp( $dossierep['Dossierep']['id'] ) && $success;
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
					'Dossierep.id IN (
						SELECT saisinesepsreorientsrs93.dossierep_id
							FROM saisinesepsreorientsrs93
							/*WHERE saisinesepsreorientsrs93.accordaccueil = \'1\'
								AND saisinesepsreorientsrs93.accordallocataire = \'1\'*/
					)'

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
						'Nvsrepreorientsr93' => array(
							'order' => array( 'etape DESC' )
						),
						'Motifreorient',
						'Typeorient',
						'Structurereferente',
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
						)
					),
				)
			);
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			// FIXME: filtrer les données
			$themeData = Set::extract( $data, '/Nvsrepreorientsr93' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				$success = $this->Nvsrepreorientsr93->saveAll( $themeData, array( 'atomic' => false ) );

				$this->Dossierep->updateAll(
					array( 'Dossierep.etapedossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Dossierep"."id"' => Set::extract( $data, '/Saisineepreorientsr93/dossierep_id' ) )
				);

				return $success;
			}
		}

		/**
		* Prépare les données du formulaire d'un niveau de décision
		* en prenant en compte les données du bilan ou du niveau de décision
		* précédent.
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
				$formData['Saisineepreorientsr93'][$key]['id'] = @$datas[$key]['Saisineepreorientsr93']['id'];
				$formData['Saisineepreorientsr93'][$key]['dossierep_id'] = @$datas[$key]['Saisineepreorientsr93']['dossierep_id'];
				$formData['Nvsrepreorientsr93'][$key]['saisineepreorientsr93_id'] = @$datas[$key]['Saisineepreorientsr93']['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Saisineepreorientsr93']['Nvsrepreorientsr93'][0]['etape'] == $niveauDecision ) {
					$formData['Nvsrepreorientsr93'][$key] = @$dossierep['Saisineepreorientsr93']['Nvsrepreorientsr93'][0];
					$formData['Nvsrepreorientsr93'][$key]['structurereferente_id'] = implode(
						'_',
						array(
							$formData['Nvsrepreorientsr93'][$key]['typeorient_id'],
							$formData['Nvsrepreorientsr93'][$key]['structurereferente_id']
						)
					);
				}

				// On ajoute les enregistrements de cette étape -> FIXME: manque les id ?
				else {
					if( $niveauDecision == 'ep' ) {
						if( !empty( $datas[$key]['Saisineepreorientsr93']['Nvsrepreorientsr93'][0] ) ) { // Modification
							$formData['Nvsrepreorientsr93'][$key]['decision'] = @$datas[$key]['Saisineepreorientsr93']['Nvsrepreorientsr93'][0]['decision'];

							$formData['Nvsrepreorientsr93'][$key]['typeorient_id'] = @$datas[$key]['Saisineepreorientsr93']['Nvsrepreorientsr93'][0]['typeorient_id'];
							$formData['Nvsrepreorientsr93'][$key]['structurereferente_id'] = implode(
								'_',
								array(
									@$datas[$key]['Saisineepreorientsr93']['Nvsrepreorientsr93'][0]['typeorient_id'],
									@$datas[$key]['Saisineepreorientsr93']['Nvsrepreorientsr93'][0]['structurereferente_id']
								)
							);
						}
						else {
							$formData['Nvsrepreorientsr93'][$key]['typeorient_id'] = $dossierep[$this->alias]['typeorient_id'];
							$formData['Nvsrepreorientsr93'][$key]['structurereferente_id'] = implode(
								'_',
								array(
									$dossierep[$this->alias]['typeorient_id'],
									$dossierep[$this->alias]['structurereferente_id']
								)
							);
							// Si accords -> accepté par défaut, sinon, refusé par défaut
							$accord = ( $dossierep[$this->alias]['accordaccueil'] && $dossierep[$this->alias]['accordallocataire'] );
							$formData['Nvsrepreorientsr93'][$key]['decision'] = ( $accord ? 'accepte' : 'refuse' );
						}
					}
					else if( $niveauDecision == 'cg' ) {
						if( !empty( $datas[$key]['Saisineepreorientsr93']['Nvsrepreorientsr93'][1] ) ) { // Modification
							$formData['Nvsrepreorientsr93'][$key]['decision'] = @$datas[$key]['Saisineepreorientsr93']['Nvsrepreorientsr93'][1]['decision'];

							$formData['Nvsrepreorientsr93'][$key]['typeorient_id'] = @$datas[$key]['Saisineepreorientsr93']['Nvsrepreorientsr93'][1]['typeorient_id'];
							$formData['Nvsrepreorientsr93'][$key]['structurereferente_id'] = implode(
								'_',
								array(
									@$datas[$key]['Saisineepreorientsr93']['Nvsrepreorientsr93'][1]['typeorient_id'],
									@$datas[$key]['Saisineepreorientsr93']['Nvsrepreorientsr93'][1]['structurereferente_id']
								)
							);
						}
						else {
							$formData['Nvsrepreorientsr93'][$key]['decision'] = $dossierep[$this->alias]['Nvsrepreorientsr93'][0]['decision'];
							if( $formData['Nvsrepreorientsr93'][$key]['decision'] == 'refuse' ) {
								$formData['Nvsrepreorientsr93'][$key]['typeorient_id'] = $dossierep[$this->alias]['typeorient_id'];
								$formData['Nvsrepreorientsr93'][$key]['structurereferente_id'] = implode(
									'_',
									array(
										$dossierep[$this->alias]['typeorient_id'],
										$dossierep[$this->alias]['structurereferente_id']
									)
								);
							}
							else {
								$formData['Nvsrepreorientsr93'][$key]['typeorient_id'] = $dossierep[$this->alias]['Nvsrepreorientsr93'][0]['typeorient_id'];
								$formData['Nvsrepreorientsr93'][$key]['structurereferente_id'] = implode(
									'_',
									array(
										$dossierep[$this->alias]['Nvsrepreorientsr93'][0]['typeorient_id'],
										$dossierep[$this->alias]['Nvsrepreorientsr93'][0]['structurereferente_id']
									)
								);
							}
						}
					}
				}
			}
// debug( $formData );
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
					'table'      => 'nvsrsepsreorientsrs93',
					'alias'      => 'Nvsrepreorientsr93',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( "{$this->alias}.id = Nvsrepreorientsr93.saisineepreorientsr93_id" ),
					'order'		 => array( 'Nvsrepreorientsr93.etape DESC' ), // INFO: d'abord CG, puis EP
					'limit'		 => 1
				),
				array(
					'table'      => 'typesorients',
					'alias'      => 'Typeorient',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( "Nvsrepreorientsr93.typeorient_id = Typeorient.id" ),
				),
				array(
					'table'      => 'structuresreferentes',
					'alias'      => 'Structurereferente',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( "Nvsrepreorientsr93.structurereferente_id = Structurereferente.id" ),
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

			$modeledoc = "{$this->alias}/decision_{$gedooo_data['Nvsrepreorientsr93']['decision']}.odt";

			$pdf = $this->ged( $gedooo_data, $modeledoc );
			$success = true;

			if( $pdf ) {
				$pdfModel = ClassRegistry::init( 'Pdf' );
				$pdfModel->create(
					array(
						'Pdf' => array(
							'modele' => $this->alias,
							'modeledoc' => $modeledoc,
							'fk_value' => $gedooo_data['Nvsrepreorientsr93']['id'],
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
				'Saisineepreorientsr93' => array(
					'Nvsrepreorientsr93' => array(
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
					'Saisineepreorientsr93.id',
					'Saisineepreorientsr93.dossierep_id',
					'Saisineepreorientsr93.orientstruct_id',
					'Saisineepreorientsr93.typeorient_id',
					'Saisineepreorientsr93.structurereferente_id',
					'Saisineepreorientsr93.datedemande',
					'Saisineepreorientsr93.referent_id',
					'Saisineepreorientsr93.motifreorient_id',
					'Saisineepreorientsr93.commentaire',
					'Saisineepreorientsr93.accordaccueil',
					'Saisineepreorientsr93.desaccordaccueil',
					'Saisineepreorientsr93.accordallocataire',
					'Saisineepreorientsr93.urgent',
					'Saisineepreorientsr93.created',
					'Saisineepreorientsr93.modified',
					'Nvsrepreorientsr93.id',
					'Nvsrepreorientsr93.saisineepreorientsr93_id',
					'Nvsrepreorientsr93.etape',
					'Nvsrepreorientsr93.decision',
					'Nvsrepreorientsr93.typeorient_id',
					'Nvsrepreorientsr93.structurereferente_id',
					'Nvsrepreorientsr93.referent_id',
					'Nvsrepreorientsr93.commentaire',
					'Nvsrepreorientsr93.created',
					'Nvsrepreorientsr93.modified',
				),
				'joins' => array(
					array(
						'table'      => 'saisinesepsreorientsrs93',
						'alias'      => 'Saisineepreorientsr93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Saisineepreorientsr93.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'nvsrsepsreorientsrs93',
						'alias'      => 'Nvsrepreorientsr93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Nvsrepreorientsr93.saisineepreorientsr93_id = Saisineepreorientsr93.id',
							'Nvsrepreorientsr93.etape' => 'ep'
						),
					)
				)
			);
		}
	}
?>