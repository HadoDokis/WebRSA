<?php
	/**
	* Saisines d'EP pour les PDOs pour le conseil général du
	* département 66.
	*
	* Une saisine regoupe plusieurs thèmes des EPs pour le CG 66.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Saisinepdoep66 extends AppModel
	{
		public $name = 'Saisinepdoep66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Gedooo'
		);

		public $belongsTo = array(
			'Traitementpdo' => array(
				'className' => 'Traitementpdo',
				'foreignKey' => 'traitementpdo_id',
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
			)
		);

		public $hasMany = array(
			'Decisionsaisinepdoep66' => array(
				'className' => 'Decisionsaisinepdoep66',
				'foreignKey' => 'saisinepdoep66_id',
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
		* TODO: comment finaliser l'orientation précédente ?
		*/

		public function finaliser( $commissionep_id, $etape ) {
			$success = true;

			if ($etape=='cg') {
				$dossierseps = $this->Dossierep->find(
					'all',
					array(
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
							'Passagecommissionep' => array(
								'Decisionsaisinepdoep66' => array(
									'order' => array(
										'Decisionsaisinepdoep66.etape DESC'
									)
								)
							),
							$this->alias => array(
								'Traitementpdo' => array(
									'Propopdo'
								)
							)
						)
					)
				);

				foreach( $dossierseps as $dossierep ) {
					$decisionpropopdo = array(
						'Decisionpropopdo' => array(
							'propopdo_id' => $dossierep[$this->alias]['Traitementpdo']['propopdo_id'],
							'datedecisionpdo' => @$dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['datedecisionpdo'],
							'decisionpdo_id' => @$dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['decisionpdo_id'],
							'commentairepdo' => @$dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['commentaire']
						)
					);
					$this->Traitementpdo->Propopdo->Decisionpropopdo->create($decisionpropopdo);
					$success = $this->Traitementpdo->Propopdo->Decisionpropopdo->save() && $success;

					/*$propopdo = $this->Traitementpdo->Propopdo->find(
						'first',
						array(
							'conditions' => array(
								'Propopdo.id' => $dossierep['Traitementpdo']['propopdo_id']
							),
							'contain' => false
						)
					);

					$propopdo['Propopdo']['decision'] = '1';
					$propopdo['Propopdo']['datedecisionpdo'] = $dossierep['Decisionsaisinepdoep66'][1]['datedecisionpdo'];
					$propopdo['Propopdo']['decisionpdo_id'] = $dossierep['Decisionsaisinepdoep66'][1]['decisionpdo_id'];
					//$propopdo['Propopdo']['motifpdo'] = $dossierep['Decisionsaisinepdoep66'][1]['motifpdo'];
					//$propopdo['Propopdo']['nonadmis'] = $dossierep['Decisionsaisinepdoep66'][1]['nonadmis'];
					$propopdo['Propopdo']['commentairepdo'] = $dossierep['Decisionsaisinepdoep66'][1]['commentaire'];

					$success = $this->Traitementpdo->Propopdo->save($propopdo) && $success;*/
				}
			}
			return $success;
		}

		/**
		* Clôture du traitement PDO précédent (celui qui a déclenché le passage en EP) au
		* niveau avis EP et ajout d'un nouveau traitement clos afin de signifier que
		* l'action de passage en EP est terminée.
		*/

		public function verrouiller( $commissionep_id, $etape ) {
			$success = true;

			if ($etape=='ep') {
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
							.' )'
						),
						'contain' => array(
							'Dossierep' => array(
								'Passagecommissionep' => array(
									'Decisionsaisinepdoep66',
									'Commissionep'
								)
							),
							'Traitementpdo' => array(
								'Descriptionpdo',
								'Propopdo'
							)
						)
					)
				);

				foreach( $dossierseps as $dossierep ) {
					$descriptionpdo_saisinepdo = $this->Traitementpdo->Descriptionpdo->find(
						'first',
						array(
							'fields' => array(
								'Descriptionpdo.id'
							),
							'conditions' => array(
								'Descriptionpdo.declencheep' => '1'
							),
							'contain' => false
						)
					);

					$traitementpdo['Traitementpdo']['descriptionpdo_id'] = $descriptionpdo_saisinepdo['Descriptionpdo']['id'];
					$traitementpdo['Traitementpdo']['traitementtypepdo_id'] = Configure::read( 'traitementClosId' );
					$dateseance = $dossierep['Dossierep']['Passagecommissionep'][0]['Commissionep']['dateseance'];
					list($jour, $heure) = explode(' ', $dateseance);
					$traitementpdo['Traitementpdo']['datereception'] = $jour;
					$traitementpdo['Traitementpdo']['personne_id'] = $dossierep['Traitementpdo']['personne_id'];
					$traitementpdo['Traitementpdo']['propopdo_id'] = $dossierep['Traitementpdo']['Propopdo']['id'];

					$this->Traitementpdo->create($traitementpdo);
					$success = $this->Traitementpdo->save() && $success;

					$this->Traitementpdo->id = $dossierep['Traitementpdo']['id'];
					$success = $this->Traitementpdo->saveField('clos', Configure::read( 'traitementClosId' )) && $success;
				}
			}

			return $success;
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
						'Traitementpdo' => array(
							'Descriptionpdo',
							'Propopdo' => array(
								'Situationpdo'
							)
						)
					),
					'Passagecommissionep' => array(
						'Decisionsaisinepdoep66'=>array(
							'order' => array(
								'Decisionsaisinepdoep66.etape DESC'
							),
							'Decisionpdo'
						)
					)
				)
			);
		}

		/**
		 *
		 */
		public function containQueryData() {
			return array(
				'Saisinepdoep66',
				'Passagecommissionep' => array(
					'conditions' => array(
						'Passagecommissionep.etatdossierep NOT' =>  array( 'annule', 'reporte' )
					),
					'Decisionsaisinepdoep66'=>array(
						'order' => array( 'Decisionsaisinepdoep66.etape DESC' ),
						'Decisionpdo'
					),
				)
			);
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			// FIXME: filtrer les données
			$themeData = Set::extract( $data, '/Decisionsaisinepdoep66' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				$success = $this->Decisionsaisinepdoep66->saveAll( $themeData, array( 'atomic' => false ) );

				$this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => Set::extract( $data, '/Decisionsaisinepdoep66/passagecommissionep_id' ) )
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
			if ( $niveauDecision == 'ep' ) {
				foreach( $datas as $key => $dossierep ) {
					if ( isset( $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['id'] ) ) {
						$formData['Decisionsaisinepdoep66'][$key]['id'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['id'];
						$formData['Decisionsaisinepdoep66'][$key]['decisionpdo_id'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['decisionpdo_id'];
						$formData['Decisionsaisinepdoep66'][$key]['commentaire'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['commentaire'];
						$formData['Decisionsaisinepdoep66'][$key]['decision'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['decision'];
						$formData['Decisionsaisinepdoep66'][$key]['raisonnonpassage'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['raisonnonpassage'];
					}
					else {
						$formData['Decisionsaisinepdoep66'][$key]['decision'] = 'avis';
					}
					$formData['Decisionsaisinepdoep66'][$key]['passagecommissionep_id'] = $dossierep['Passagecommissionep'][0]['id'];
				}
			}
			elseif ( $niveauDecision == 'cg' ) {
				foreach( $datas as $key => $dossierep ) {
					if ( isset( $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][1]['id'] ) ) {
						$formData['Decisionsaisinepdoep66'][$key]['id'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['id'];
					}
					$formData['Decisionsaisinepdoep66'][$key]['decisionpdo_id'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['decisionpdo_id'];
					$formData['Decisionsaisinepdoep66'][$key]['commentaire'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['commentaire'];
					$formData['Decisionsaisinepdoep66'][$key]['passagecommissionep_id'] = $dossierep['Passagecommissionep'][0]['id'];
					$formData['Decisionsaisinepdoep66'][$key]['decision'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['decision'];
						$formData['Decisionsaisinepdoep66'][$key]['raisonnonpassage'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['raisonnonpassage'];
				}
			}
			return $formData;
		}

		public function prepareFormDataUnique( $dossierep_id, $dossierep, $niveauDecision ) {
			$formData = array();
			if ($niveauDecision=='decisioncg') {
				if ( isset( $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][1] ) ) {
					$formData['Decisionsaisinepdoep66']['id'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['id'];
				}
				$formData['Decisionsaisinepdoep66']['decisionpdo_id'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['decisionpdo_id'];
				$formData['Decisionsaisinepdoep66']['commentaire'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['commentaire'];
				$formData['Decisionsaisinepdoep66']['passagecommissionep_id'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['passagecommissionep_id'];
				$formData['Decisionsaisinepdoep66']['decision'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['decision'];
				$formData['Decisionsaisinepdoep66']['raisonnonpassage'] = $dossierep['Passagecommissionep'][0]['Decisionsaisinepdoep66'][0]['raisonnonpassage'];

				$formData['Dossierep']['id'] = $dossierep['Dossierep']['id'];
			}
			return $formData;
		}

		/**
		*
		*/

		public function saveDecisionUnique( $data, $niveauDecision ) {
			$success = $this->Decisionsaisinepdoep66->save( $data, array( 'atomic' => false ) );

			$this->Dossierep->Passagecommissionep->updateAll(
				array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
				array( '"Passagecommissionep"."id"' => Set::extract( $data, '/Saisinepdoep66/dossierep_id' ) )
			);

			return $success;
		}
		/**
		*
		*/

		public function qdProcesVerbal() {
			return array(
				'fields' => array(
					'Saisinepdoep66.id',
					'Saisinepdoep66.dossierep_id',
					'Saisinepdoep66.traitementpdo_id',
					'Saisinepdoep66.created',
					'Saisinepdoep66.modified',
					//
					'Decisionsaisinepdoep66.id',
// 					'Decisionsaisinepdoep66.saisinepdoep66_id',
					'Decisionsaisinepdoep66.etape',
					'Decisionsaisinepdoep66.decisionpdo_id',
					'Decisionsaisinepdoep66.commentaire',
					'Decisionsaisinepdoep66.nonadmis',
					'Decisionsaisinepdoep66.motifpdo',
					'Decisionsaisinepdoep66.datedecisionpdo',
					'Decisionsaisinepdoep66.created',
					'Decisionsaisinepdoep66.modified',
					'Decisionsaisinepdoep66.raisonnonpassage',
					//
					'Decisionpdo.id',
					'Decisionpdo.libelle',
				),
				'joins' => array(
					array(
						'table'      => 'saisinespdoseps66',
						'alias'      => 'Saisinepdoep66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Saisinepdoep66.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'decisionssaisinespdoseps66',
						'alias'      => 'Decisionsaisinepdoep66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionsaisinepdoep66.passagecommissionep_id = Passagecommissionep.id',
							'Decisionsaisinepdoep66.etape' => 'ep'
						),
					),
					array(
						'table'      => 'decisionspdos',
						'alias'      => 'Decisionpdo',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Decisionsaisinepdoep66.decisionpdo_id = Decisionpdo.id', ),
					),
				)
			);
		}



        /**
        *    Récupération des informations propres au dossier devant passer en EP
        *   avant liaison avec la commission d'EP
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
                        'Traitementpdo' => array(
                            'Descriptionpdo',
                            'Propopdo' => array(
                                'Situationpdo'
                            )
                        )
                    )
                )
            );
            return $this->ged( $gedooo_data, "{$this->alias}/courrierinformationavantep.odt" );
        }

	}
?>
