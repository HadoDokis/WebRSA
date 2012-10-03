<?php
	require_once( ABSTRACTMODELS.'thematiqueep.php' );

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
	class Defautinsertionep66 extends Thematiqueep
	{
		public $name = 'Defautinsertionep66';

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'origine',
					'type'
				)
			),
			'Formattable',
			'Gedooo.Gedooo',
			'Conditionnable',
			'ModelesodtConditionnables' => array(
				66 => array(
					// Courrier d'information
					'%s/bilanparcours_courrierinformationavantep.odt',
					'%s/noninscriptionpe_courrierinformationavantep.odt',
					'%s/radiationpe_courrierinformationavantep.odt',
					// Convocation EP
					'Commissionep/convocationep_beneficiaire.odt',
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
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'cui_id',
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
					'Dossierep.id IN ( '.$this->Dossierep->Passagecommissionep->sq(
						array(
							'fields' => array( 'passagescommissionseps.dossierep_id' ),
							'alias' => 'passagescommissionseps',
							'conditions' => array(
								'passagescommissionseps.commissionep_id' => $commissionep_id
							)
						)
					).' )'
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
						'Bilanparcours66',
						'Contratinsertion',
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
						),
						'Historiqueetatpe'
					),
					'Passagecommissionep' => array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id
						),
						'Decisiondefautinsertionep66' => array(
							'order' => array( 'Decisiondefautinsertionep66.etape DESC' )
						)
					)
				)
			);
		}

		/**
		 * Sauvegarde des décisions de la commission (avant validation )
		 * @param array $data données de la commission EP à sauvegarder 
		 * @param string $niveauDecision ep ou cg, selon le type de commission
		 * @return boolean
		 * FIXME: type_positionbilan -> {eplaudit,eplparc,attcga,attct,ajourne,annule} => ajouter traite
		 */

		 public function saveDecisions( $data, $niveauDecision ) {
			// Filtrage des données
			$themeData = Set::extract( $data, '/Decisiondefautinsertionep66' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				$success = $this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->saveAll( $themeData, array( 'atomic' => false ) );

				$passagescommissionseps_ids = Set::extract( $themeData, '/Decision'.Inflector::underscore( $this->alias ).'/passagecommissionep_id' );

				// Mise à jour de l'état du passage en commission EP
				$success = $this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => $passagescommissionseps_ids )
				) && $success;

				// Mise à jour de la position du bilan de parcours
				$success = $this->Bilanparcours66->updatePositionBilanDecisionsEp( $this->name, $themeData, $niveauDecision, $passagescommissionseps_ids ) && $success;
				
				
// debug($data);

				// INFO: On ne crée un dossier PCG qu'après la validation au niveau EP
				// Aucune action ne se fait à ce niveau par le CG
				$niveauAvis = $data['Decisiondefautinsertionep66'][0]['etape'];
				if( $niveauAvis == 'ep' ){
					$commissionep_id = Set::classicExtract( $this->_commissionepIdParPassagecommissionId( $passagescommissionseps_ids ), 'Commissionep.id' );
					
					$dateseanceCommission = Set::classicExtract( $this->_commissionepIdParPassagecommissionId( $passagescommissionseps_ids ), 'Commissionep.dateseance' );
					
					if( !empty( $commissionep_id ) && !empty( $dateseanceCommission ) ) {
						$success = $this->_generateDossierpcg( $commissionep_id, $dateseanceCommission, 'ep' ) && $success;
					}
				}

				return $success;
			}
		}

		/**
		 * Récupère l'id de la commission d'EP à partir d'une liste d'ids
		 * d'entrées de passages en commissions EP. 
		 * @param array $passagescommissionseps_ids Ids des passages commissions des dossiers eps
		 *
		 */
		protected function _commissionepIdParPassagecommissionId( $passagescommissionseps_ids ) {
			
			return $this->Dossierep->Passagecommissionep->Commissionep->find(
				'first',
				array(
					'fields' => array( 'Commissionep.id', 'Commissionep.dateseance' ),
					'conditions' => array(
						'Commissionep.id IN ('
							.$this->Dossierep->Passagecommissionep->sq(
								array(
									'alias' => 'passagescommissionseps',
									'fields' => array( 'passagescommissionseps.commissionep_id' ),
									'conditions' => array(
										'passagescommissionseps.id' => $passagescommissionseps_ids
									),
									'contain' => false
								)
							)
						.')'
					),
					'contain' => false
				)
			);
		}
		
		
		/**
		 *	Génération d'un dossier PCG une fois l'avis de l'EP validé
		 * @param integer $commissionep_id L'id technique de la séance d'EP
		 * @param date $dateseanceCommission La date de la séance pour la date
		 *  de création de la PDO (dossierpcg.datereceptionpdo
		 * @param string $etape Etape à laquelle cette opération a lieu = ep
		 * @return array
		 * @access protected
		 */
		protected function _generateDossierpcg( $commissionep_id, $dateseanceCommission, $etape ) {
			
			$dossierseps = $this->find(
				'all',
				array(
					'conditions' => array(
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
						.' )',
						'Dossierep.themeep' => Inflector::tableize( $this->alias ),//FIXME: ailleurs aussi
					),
					'contain' => array(
						'Dossierep' => array(
							'Passagecommissionep' => array(
								'Decisiondefautinsertionep66' => array(
									'conditions' => array(
										'Decisiondefautinsertionep66.etape' => $etape
									)
								)
							)
						)
					)
				)
			);
			
// 			debug($dossierseps);
// 			die();
			$success = true;
			foreach( $dossierseps as $i => $dossierep ) {

				$defautinsertionep66 = array( 'Defautinsertionep66' => $dossierep['Defautinsertionep66'] );
				$foyer = $this->Bilanparcours66->Orientstruct->Personne->Foyer->find(
					'first',
					array(
						'fields' => array(
							'Foyer.id'
						),
						'conditions' => array(
							'Bilanparcours66.id' => $defautinsertionep66['Defautinsertionep66']['bilanparcours66_id']
						),
						'joins' => array(
							array(
								'table' => 'personnes',
								'alias' => 'Personne',
								'type' => 'INNER',
								'conditions' => array( 'Personne.foyer_id = Foyer.id' )
							),
							array(
								'table' => 'orientsstructs',
								'alias' => 'Orientstruct',
								'type' => 'LEFT OUTER',
								'conditions' => array( 'Orientstruct.personne_id = Personne.id' )
							),
							array(
								'table' => 'bilansparcours66',
								'alias' => 'Bilanparcours66',
								'type' => 'INNER',
								'conditions' => array( 'Bilanparcours66.personne_id = Personne.id' )
							)
						),
						'contain' => false
					)
				);

				$originepdo = $this->Bilanparcours66->Dossierpcg66->Originepdo->find(
					'first',
					array(
						'fields' => array(
							'Originepdo.id'
						),
						'conditions' => array(
							'Originepdo.originepcg' => 'O'
						),
						'contain' => false
					)
				);

				$typepdo = $this->Bilanparcours66->Dossierpcg66->Typepdo->find(
					'first',
					array(
						'fields' => array(
							'Typepdo.id'
						),
						'conditions' => array(
							'Typepdo.originepcg' => 'O'
						),
						'contain' => false
					)
				);

				// Paramétrage incorrect
				if( empty( $originepdo ) || empty( $typepdo ) ) {
					$validationErrors = array();
					$originePdoMessage = 'aucune origine PDO n\'est origine par défaut d\'un dossier PDO venant d\'une EP';
					$typePdoMessage = 'aucune type de dossier PDO n\'est origine par défaut d\'un dossier PDO venant d\'une EP';

					if( empty( $originepdo ) && empty( $typepdo ) ) {
						$validationErrors[$i] = array( 'decision' => "Problème de paramétrage: {$originePdoMessage} et {$typePdoMessage}." );
					}
					else if( empty( $originepdo ) ) {
						$validationErrors[$i] = array( 'decision' => "Problème de paramétrage: {$originePdoMessage}." );
					}
					else if( empty( $typepdo ) ) {
						$validationErrors[$i] = array( 'decision' => "Problème de paramétrage: {$typePdoMessage}." );
					}

					$this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->validationErrors = Set::merge(
						$this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->validationErrors,
						$validationErrors
					);

					$success = false;
				}

				$dossier = $this->Bilanparcours66->Orientstruct->Personne->Foyer->Dossier->find(
					'first',
					array(
						'fields' => array(
							'Dossier.fonorg'
						),
						'conditions' => array(
							'Bilanparcours66.id' => $defautinsertionep66['Defautinsertionep66']['bilanparcours66_id']
						),
						'joins' => array(
							array(
								'table' => 'foyers',
								'alias' => 'Foyer',
								'type' => 'INNER',
								'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
							),
							array(
								'table' => 'personnes',
								'alias' => 'Personne',
								'type' => 'INNER',
								'conditions' => array( 'Personne.foyer_id = Foyer.id' )
							),
							array(
								'table' => 'orientsstructs',
								'alias' => 'Orientstruct',
								'type' => 'LEFT OUTER',
								'conditions' => array( 'Orientstruct.personne_id = Personne.id' )
							),
							array(
								'table' => 'bilansparcours66',
								'alias' => 'Bilanparcours66',
								'type' => 'INNER',
								'conditions' => array( 'Bilanparcours66.personne_id = Personne.id' )
							)
						),
						'contain' => false
					)
				);

				$dossierpcg66 = array(
					'Dossierpcg66' => array(
						'foyer_id' => $foyer['Foyer']['id'],
						'originepdo_id' => $originepdo['Originepdo']['id'],
						'typepdo_id' => $typepdo['Typepdo']['id'],
						'orgpayeur' => $dossier['Dossier']['fonorg'],
						'datereceptionpdo' => $dateseanceCommission,
						'haspiecejointe' => 0,
						'bilanparcours66_id' => $defautinsertionep66['Defautinsertionep66']['bilanparcours66_id'],
						'decisiondefautinsertionep66_id' => $dossierep['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['id']
					)
				);
				
				$nbDossierPCG66PourDecisiondefautinsertion66 = $this->Bilanparcours66->Dossierpcg66->find(
					'count',
					array(
						'conditions' => array(
							'Dossierpcg66.decisiondefautinsertionep66_id' => $this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->id
						)
					)
				);

				// Le dossier PCG du foyer de l'allocataire n'existe pas encore
				// pour ce foyer et cette décision de thématique EP donc on peut le créer
				if( $nbDossierPCG66PourDecisiondefautinsertion66 == 0 ) {
					$this->Bilanparcours66->Dossierpcg66->create( $dossierpcg66 );
					$success = $this->Bilanparcours66->Dossierpcg66->save() && $success;
				}
			}
			return $success;
		}
		
		
		
		/**
		* Prépare les données du formulaire d'un niveau de décision
		* en prenant en compte les données du bilan ou du niveau de décision
		* précédent.
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param array $datas Les données des dossiers
		* @param string $niveauDecision Le niveau de décision pour lequel il
		* 	faut préparer les données du formulaire
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
				$formData['Decisiondefautinsertionep66'][$key]['passagecommissionep_id'] = @$datas[$key]['Passagecommissionep'][0]['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['etape'] == $niveauDecision ) {
					$formData['Decisiondefautinsertionep66'][$key] = @$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0];
					$formData['Decisiondefautinsertionep66'][$key]['referent_id'] = implode( '_', array( $formData['Decisiondefautinsertionep66'][$key]['structurereferente_id'], $formData['Decisiondefautinsertionep66'][$key]['referent_id'] ) );
					$formData['Decisiondefautinsertionep66'][$key]['structurereferente_id'] = implode( '_', array( $formData['Decisiondefautinsertionep66'][$key]['typeorient_id'], $formData['Decisiondefautinsertionep66'][$key]['structurereferente_id'] ) );
				}
				// On ajoute les enregistrements de cette étape
				else {
					if( $niveauDecision == 'cg' ) {
						$formData['Decisiondefautinsertionep66'][$key]['decision'] = $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['decision'];
						$formData['Decisiondefautinsertionep66'][$key]['raisonnonpassage'] = $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['raisonnonpassage'];
						$formData['Decisiondefautinsertionep66'][$key]['commentaire'] = $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['commentaire'];
						$formData['Decisiondefautinsertionep66'][$key]['decisionsup'] = $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['decisionsup'];
						$formData['Decisiondefautinsertionep66'][$key]['typeorient_id'] = $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['typeorient_id'];
						$formData['Decisiondefautinsertionep66'][$key]['referent_id'] = implode( '_', array( $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['structurereferente_id'], $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['referent_id'] ) );
						$formData['Decisiondefautinsertionep66'][$key]['structurereferente_id'] = implode( '_', array( $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['typeorient_id'], $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['structurereferente_id'] ) );
					}
				}
			}
			return $formData;
		}

		public function prepareFormDataUnique( $dossierep_id, $dossierep, $niveauDecision ) {
			$formData = array();
			return $formData;
		}

		/**
		* TODO: docs
		*/

		public function finaliser( $commissionep_id, $etape, $user_id ) {
			$commissionep = $this->Dossierep->Passagecommissionep->Commissionep->find(
				'first',
				array(
					'conditions' => array( 'Commissionep.id' => $commissionep_id ),
					'contain' => array(
						'Ep' => array(
							'Regroupementep'
						)
					)
				)
			);

			$niveauDecisionFinale = $commissionep['Ep']['Regroupementep'][Inflector::underscore( $this->alias )];

			$dossierseps = $this->find(
				'all',
				array(
					'conditions' => array(
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
						.' )',
						'Dossierep.themeep' => Inflector::tableize( $this->alias ),//FIXME: ailleurs aussi
					),
					'contain' => array(
						'Dossierep' => array(
							'Passagecommissionep' => array(
								'Decisiondefautinsertionep66' => array(
									'conditions' => array(
										'Decisiondefautinsertionep66.etape' => $etape
									)
								)
							)
						)
					)
				)
			);


			$success = true;
			foreach( $dossierseps as $i => $dossierep ) {
				if( $niveauDecisionFinale == "decision{$etape}" ) {
					$defautinsertionep66 = array( 'Defautinsertionep66' => $dossierep['Defautinsertionep66'] );
					if( !isset( $dossierep['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['decision'] ) ) {
						$success = false;
					}
					$defautinsertionep66['Defautinsertionep66']['decision'] = @$dossierep['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['decision'];

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
						$referent_id = $orientsstruct['Orientstruct']['referent_id'];

						// FIXME: si on ne trouve pas l'orientation ?

						// FIXME: referent_id -> champ obligatoire
						// FIXME: si la structure ne possède pas de référent ???
						if( empty( $orientsstruct['Orientstruct']['referent_id'] ) ) {
							$bilanparcours66 = $oBilanparcours66->find(
								'first',
								array(
									'fields' => array(
										'Bilanparcours66.orientstruct_id',
										'Bilanparcours66.structurereferente_id',
										'Bilanparcours66.referent_id'
									),
									'conditions' => array(
										'Bilanparcours66.id' => $dossierep['Defautinsertionep66']['bilanparcours66_id']
									),
									'contain' => false
								)
							);

							if( !empty( $bilanparcours66 ) ) {
								$referent_id = $bilanparcours66['Bilanparcours66']['referent_id'];
							}
						}
						
						// En cas de demande de réorientation, l'EPL Audition va statuer et générer l'orientation
						$orientstruct = array(
							'Orientstruct' => array(
								'personne_id' => $dossierep['Dossierep']['personne_id'],
								'typeorient_id' => $dossierep['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['typeorient_id'],
								'structurereferente_id' => $dossierep['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['structurereferente_id'],
								'referent_id' => $referent_id,
								'date_propo' => date( 'Y-m-d' ),
								'date_valid' => date( 'Y-m-d' ),
								'statut_orient' => 'Orienté',
								'user_id' => $user_id
							)
						);
						$this->Bilanparcours66->Orientstruct->create( $orientstruct );
						$success = $this->Bilanparcours66->Orientstruct->save() && $success;

						// Mise à jour de l'enregistrement de la thématique avec l'id de la nouvelle orientation
						$success = $this->updateAll(
							array( "\"{$this->alias}\".\"nvorientstruct_id\"" => $this->Bilanparcours66->Orientstruct->id ),
							array( "\"{$this->alias}\".\"id\"" => $dossierep[$this->alias]['id'] )
						) && $success;

					}

					//	Ancien emplacement de la génération du dossierpcg66

					$this->create( $defautinsertionep66 );
					$success = $this->save() && $success;
				}
			}
			return $success;
		}

		/**
		* FIXME: et qui ne sont pas passés en EP pour ce motif dans un délai de moins de 1 mois (paramétrable)
		*/

		protected function _qdSelection( $datas, $mesCodesInsee, $filtre_zone_geo ) {

			$Situationdossierrsa = ClassRegistry::init( 'Situationdossierrsa' );
			$queryData = array(
				'fields' => array(
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Foyer.enerreur',
					'Orientstruct.id',
					'Orientstruct.personne_id',
					'Orientstruct.date_valid',
					'"Situationdossierrsa"."etatdosrsa"',
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
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id /*AND ( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )*/' )
						/*'conditions' => array(
							'Situationdossierrsa.dossier_id = Dossier.id',
							'Situationdossierrsa.etatdosrsa' => array( 'Z', '2', '3', '4' )
						)*/
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
									WHERE t.lib_type_orient LIKE \'Emploi %\'
							)'// FIXME
						)
					),
				),
				'conditions' => array(
					// On ne veut pas les personnes ayant un dossier d'EP en cours de traitement
					'Personne.id NOT IN ('.$this->Dossierep->sq(
						array(
							'fields' => array( 'dossierseps1.personne_id' ),
							'alias' => 'dossierseps1',
							'conditions' => array(
								'dossierseps1.personne_id = Personne.id',
								'dossierseps1.themeep' => 'defautsinsertionseps66',
								'dossierseps1.id IN ('.$this->Dossierep->Passagecommissionep->sq(
									array(
										'fields' => array( 'passagescommissionseps1.dossierep_id' ),
										'alias' => 'passagescommissionseps1',
										'conditions' => array(
											'passagescommissionseps1.dossierep_id = dossierseps1.id',
											'passagescommissionseps1.etatdossierep' => array( 'associe', 'decisionep', 'decisioncg', 'reporte' )
										)
									)
								).')'
							)
						)
					).')',
					// Ni celles qui ont un dossier d'EP ayant été traité en commission plus récemment que 2 mois
					'Personne.id NOT IN ('.$this->Dossierep->sq(
						array(
							'fields' => array( 'dossierseps2.personne_id' ),
							'alias' => 'dossierseps2',
							'conditions' => array(
								'dossierseps2.personne_id = Personne.id',
								'dossierseps2.themeep' => 'defautsinsertionseps66',
								'dossierseps2.id IN ('.$this->Dossierep->Passagecommissionep->sq(
									array(
										'fields' => array( 'passagescommissionseps2.dossierep_id' ),
										'alias' => 'passagescommissionseps2',
										'conditions' => array(
											'passagescommissionseps2.dossierep_id = dossierseps2.id',
											'passagescommissionseps2.etatdossierep' => array( 'traite', 'annule' )
										),
										'joins' => array(
											array(
												'table' => 'commissionseps',
												'alias' => 'commissionseps',
												'type' => 'INNER',
												'conditions' => array(
													'commissionseps.id = passagescommissionseps2.commissionep_id',
													'commissionseps.dateseance >=' => date( 'Y-m-d', strtotime( '-2 mons' ) ) // FIXME: paramétrage
												)
											)
										)
									)
								).')'
							)
						)
					).')',
					// Ni celles dont le dossier n'a pas encore été associé à une commission
					'Personne.id NOT IN ('.$this->Dossierep->sq(
						array(
							'fields' => array( 'dossierseps3.personne_id' ),
							'alias' => 'dossierseps3',
							'conditions' => array(
								'dossierseps3.personne_id = Personne.id',
								'dossierseps3.themeep' => 'defautsinsertionseps66',
								'dossierseps3.id NOT IN ('.$this->Dossierep->Passagecommissionep->sq(
									array(
										'fields' => array( 'passagescommissionseps3.dossierep_id' ),
										'alias' => 'passagescommissionseps3',
										'conditions' => array(
											'passagescommissionseps3.dossierep_id = dossierseps3.id',
										)
									)
								).')'
							)
						)
					).')',
				)
			);

			
			// On a un filtre par défaut sur l'état du dossier si celui-ci n'est pas renseigné dans le formulaire.
			$Situationdossierrsa = ClassRegistry::init( 'Situationdossierrsa' );			
			$etatdossier = Set::extract( $datas, 'Situationdossierrsa.etatdosrsa' );
			if( !isset( $datas['Situationdossierrsa']['etatdosrsa'] ) || empty( $datas['Situationdossierrsa']['etatdosrsa'] ) ) {
				$datas['Situationdossierrsa']['etatdosrsa']  = $Situationdossierrsa->etatOuvert();
			}
			
			/// Filtre zone géographique
			$queryData['conditions'][] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );
			$queryData['conditions'][] = $this->conditionsAdresse( $queryData['conditions'], $datas, $filtre_zone_geo, $mesCodesInsee );
			$queryData['conditions'][] = $this->conditionsPersonneFoyerDossier( $queryData['conditions'], $datas );
			$queryData['conditions'][] = $this->conditionsDernierDossierAllocataire( $queryData['conditions'], $datas );
			
			if( isset( $datas['Orientstruct']['date_valid'] ) && !empty( $datas['Orientstruct']['date_valid'] ) ) {
				if( valid_int( $datas['Orientstruct']['date_valid']['year'] ) ) {
				$queryData['conditions'][] = 'EXTRACT(YEAR FROM Orientstruct.date_valid) = '.$datas['Orientstruct']['date_valid']['year'];
				}
				if( valid_int( $datas['Orientstruct']['date_valid']['month'] ) ) {
					$queryData['conditions'][] = 'EXTRACT(MONTH FROM Orientstruct.date_valid) = '.$datas['Orientstruct']['date_valid']['month'];
				}
			}

			$identifiantpe = Set::classicExtract( $datas, 'Historiqueetatpe.identifiantpe' );

			if ( !empty( $identifiantpe ) ) {
				$queryData['conditions'][] = ClassRegistry::init( 'Historiqueetatpe' )->conditionIdentifiantpe( $identifiantpe );
			}


			return $queryData;
		}

		/**
		*
		*/

		public function qdNonInscrits( $datas, $mesCodesInsee, $filtre_zone_geo ) {
			$queryData = $this->_qdSelection( $datas, $mesCodesInsee, $filtre_zone_geo );
			$qdNonInscrits = $this->Historiqueetatpe->Informationpe->qdNonInscrits();

			$queryData['fields'] = array_merge( $queryData['fields'] ,$qdNonInscrits['fields'] );
			$queryData['joins'] = array_merge( $queryData['joins'] ,$qdNonInscrits['joins'] );
			$queryData['conditions'] = array_merge( $queryData['conditions'] ,$qdNonInscrits['conditions'] );
			$queryData['order'] = $qdNonInscrits['order'];

			return $queryData;
		}

		/**
		*
		*/

		public function qdRadies( $datas, $mesCodesInsee, $filtre_zone_geo ) {
			// FIXME: et qui ne sont pas passés dans une EP pour ce motif depuis au moins 1 mois (?)
			$queryData = $this->_qdSelection( $datas, $mesCodesInsee, $filtre_zone_geo );
			$qdRadies = $this->Historiqueetatpe->Informationpe->qdRadies();
			$queryData['fields'] = array_merge( $queryData['fields'] ,$qdRadies['fields'] );
			$queryData['joins'] = array_merge( $queryData['joins'] ,$qdRadies['joins'] );
			$queryData['conditions'] = array_merge( $queryData['conditions'] ,$qdRadies['conditions'] );
			$queryData['order'] = $qdRadies['order'];

			return $queryData;
		}

		/**
		 * Retourne une partie de querydata concernant la thématique pour le PV d'EP.
		 *
		 * @return array
		 */
		public function qdProcesVerbal() {
			$querydata = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->fields(),
					$this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->Typeorient->fields(),
					$this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->Structurereferente->fields(),
					$this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->Referent->fields()
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
							'Decisiondefautinsertionep66.passagecommissionep_id = Passagecommissionep.id',
							'Decisiondefautinsertionep66.etape' => 'ep'
						),
					),
					$this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) ),
					$this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
					$this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->join( 'Referent', array( 'type' => 'LEFT OUTER' ) )

				)
			);

			$modeleDecisionPart = 'decdefins'.Configure::read( 'Cg.departement' );
			return array_words_replace(
				$querydata,
				array(
					'Typeorient' => "Typeorient{$modeleDecisionPart}",
					'Structurereferente' => "Structurereferente{$modeleDecisionPart}",
					'Referent' => "Referent{$modeleDecisionPart}",
				)
			);
		}

		/**
		* Récupération des informations propres au dossier devant passer en EP
		* avant liaison avec la commission d'EP
		*/

		public function getCourrierInformationPdf( $dossierep_id ) {
			$gedooo_data = $this->find(
				'first',
				array(
					'conditions' => array( 'Dossierep.id' => $dossierep_id ),
					'contain' => array(
						'Dossierep' => array(
							'Personne'
						),
						'Bilanparcours66',
						'Contratinsertion',
						'Orientstruct'
					)
				)
			);

			$this->id = $gedooo_data['Defautinsertionep66']['id'];
			$this->saveField( 'dateimpressionconvoc', date( 'Y-m-d' ) );

			return $this->ged( $gedooo_data, "{$this->alias}/{$gedooo_data[$this->alias]['origine']}_courrierinformationavantep.odt" );
		}

		/**
		* Récupération du courrier de convocation à l'allocataire pour un passage
		* en commission donné.
		*/
		public function getConvocationBeneficiaireEpPdf( $passagecommissionep_id ) {
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$datas = Cache::read( $cacheKey );

			if( $datas === false ) {
				$datas = $this->_qdConvocationBeneficiaireEpPdf();

				Cache::write( $cacheKey, $datas );
			}

			$datas['querydata']['conditions']['Passagecommissionep.id'] = $passagecommissionep_id;
			$gedooo_data = $this->Dossierep->Passagecommissionep->find( 'first', $datas['querydata'] );
			$modeleOdt = 'Commissionep/convocationep_beneficiaire.odt';

			if( empty( $gedooo_data ) ) {
				return false;
			}

			return $this->ged(
				$gedooo_data,
				$modeleOdt,
				false,
				$datas['options']
			);
		}

		/**
		* Fonction retournant un querydata qui va permettre de retrouver des dossiers d'EP
		*/
		public function qdListeDossier( $commissionep_id = null ) {
			$return = array(
				'fields' => array(
					'Dossierep.id',
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.locaadr',
					'Dossierep.created',
					'Dossierep.themeep',
					'Passagecommissionep.id',
					'Passagecommissionep.commissionep_id',
					'Passagecommissionep.etatdossierep',
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

		/**
		* Récupération de la décision suite au passage en commission d'un dossier
		* d'EP pour un certain niveau de décision. On revoie la chaîne vide car on
		* n'est pas sensés imprimer de décision pour la commission.
		*/

		public function getDecisionPdf( $passagecommissionep_id  ) {
			return '';
		}

		/**
		*
		*/

		public function search( $mesCodesInsee, $filtre_zone_geo, $params ) {
			$conditions = array();

			$conditions = $this->conditionsAdresse( $conditions, $params, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $params );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $params );

			$conditions[] = "Defautinsertionep66.dateimpressionconvoc IS NULL";

			$query = array(
				'fields' => array(
					'Dossierep.id',
					'Dossierep.created',
					'Defautinsertionep66.id',
					'Dossier.matricule',
					'Personne.nir',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.numcomptt',
					'Adresse.locaadr'
				),
				'conditions' => $conditions,
				'joins' => array(
					array(
						'table' => 'defautsinsertionseps66',
						'alias' => 'Defautinsertionep66',
						'type' => 'INNER',
						'conditions' => array( 'Dossierep.id = Defautinsertionep66.dossierep_id' )
					),
					array(
						'alias' => 'Personne',
						'table' => 'personnes',
						'type' => 'INNER',
						'conditions' => array( 'Dossierep.personne_id = Personne.id' )
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
					)
				),
				'contain' => false
			);

			return $query;
		}

	}
?>