<?php
	/**
	* FIXME
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class Nonrespectssanctionseps93Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Csv' );

		public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );

		/**
		*
		*/

		protected function _setOptions() {
			$options = Set::merge(
				$this->Nonrespectsanctionep93->enums(),
				$this->Nonrespectsanctionep93->Dossierep->enums(),
				$this->Nonrespectsanctionep93->Dossierep->Passagecommissionep->Decisionnonrespectsanctionep93->enums()
			);
			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		protected function _queryData( $searchData ) {
			$searchMode = Set::classicExtract( $searchData, 'Nonrespectsanctionep93.mode' );

			$conditions = array( 'Dossierep.themeep' => 'nonrespectssanctionseps93' );

			if( $searchMode == 'traite' ) {
				$conditions[][] = 'Dossierep.id NOT IN ( '.$this->Nonrespectsanctionep93->Dossierep->Passagecommissionep->sq(
					array(
						'alias' => 'passagescommissionseps',
						'fields' => array( 'passagescommissionseps.dossierep_id' ),
						'conditions' => array(
							'NOT' => array(
								'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
							),
							'passagescommissionseps.dossierep_id = Dossierep.id'
						),
					)
				).' )';

				$searchDossierepSeanceepId = Set::classicExtract( $searchData, 'Dossierep.commissionep_id' );
				if( !empty( $searchDossierepSeanceepId ) ) {
					$conditions[][] = 'Dossierep.id IN ( '.$this->Nonrespectsanctionep93->Dossierep->Passagecommissionep->sq(
						array(
							'alias' => 'passagescommissionseps',
							'fields' => array( 'passagescommissionseps.dossierep_id' ),
							'conditions' => array(
								'passagescommissionseps.commissionep_id' => $searchDossierepSeanceepId,
								'passagescommissionseps.dossierep_id = Dossierep.id'
							),
						)
					).' )';
				}
			}
			else {
				$conditions[][] = 'Dossierep.id IN ( '.$this->Nonrespectsanctionep93->Dossierep->Passagecommissionep->sq(
					array(
						'alias' => 'passagescommissionseps',
						'fields' => array( 'passagescommissionseps.dossierep_id' ),
						'conditions' => array(
							'NOT' => array(
								'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
							),
							'passagescommissionseps.dossierep_id = Dossierep.id'
						),
					)
				).' )';
			}

			return array(
				'fields' => array(
					'Dossier.matricule',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nir',
					'Nonrespectsanctionep93.contratinsertion_id',
					'Contratinsertion.df_ci',
					'Orientstruct.date_valid',
					'Commissionep.dateseance',
					'Nonrespectsanctionep93.origine',
					'Nonrespectsanctionep93.rgpassage',
					'Nonrespectsanctionep93.decision',
					'Nonrespectsanctionep93.montantreduction',
					'Nonrespectsanctionep93.dureesursis',
					'Decisionnonrespectsanctionep93.decision',
					'Decisionnonrespectsanctionep93.montantreduction',
					'Decisionnonrespectsanctionep93.dureesursis',
				),
				'joins' => array(
					array(
						'table'      => 'dossierseps',
						'alias'      => 'Dossierep',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Nonrespectsanctionep93.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Dossierep.personne_id = Personne.id' ),
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' ),
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.dossier_id = Dossier.id' ),
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Adressefoyer.foyer_id = Foyer.id',
							'Adressefoyer.rgadr' => '01'
						),
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Adressefoyer.adresse_id = Adresse.id' ),
					),
					array(
						'table'      => 'passagescommissionseps',
						'alias'      => 'Passagecommissionep',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Passagecommissionep.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'commissionseps',
						'alias'      => 'Commissionep',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Passagecommissionep.commissionep_id = Commissionep.id' ),
					),
					array(
						'table'      => 'orientsstructs',
						'alias'      => 'Orientstruct',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Nonrespectsanctionep93.orientstruct_id = Orientstruct.id' ),
					),
					array(
						'table'      => 'contratsinsertion',
						'alias'      => 'Contratinsertion',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Nonrespectsanctionep93.contratinsertion_id = Contratinsertion.id' ),
					),
					array(
						'table'      => 'decisionsnonrespectssanctionseps93',
						'alias'      => 'Decisionnonrespectsanctionep93',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionnonrespectsanctionep93.passagecommissionep_id = Passagecommissionep.id',
							'Decisionnonrespectsanctionep93.etape = ( '
								.$this->Nonrespectsanctionep93->Dossierep->Passagecommissionep->Decisionnonrespectsanctionep93->sq(
									array(
										'alias' => 'decisionsnonrespectssanctionseps93',
										'fields' => array( 'decisionsnonrespectssanctionseps93.etape' ),
										'conditions' => array(
											'decisionsnonrespectssanctionseps93.passagecommissionep_id = Passagecommissionep.id',
										),
										'order' => ( 'decisionsnonrespectssanctionseps93.etape DESC' ),
										'limit' => 1
									)
								)
							.' )',
						),
					),
				),
				'conditions' => $conditions,
				'order' => array( 'Nonrespectsanctionep93.created DESC' )
			);
		}

		/**
		*
		*/

		public function index() {
			$searchData = Set::classicExtract( $this->data, 'Search' );
			$searchMode = Set::classicExtract( $searchData, 'Nonrespectsanctionep93.mode' );

			if( !empty( $searchData ) ) {
				$queryData = $this->_queryData( $searchData );
				$queryData['limit'] = 10;

				$this->paginate = $queryData;

				$this->set( 'nonrespectssanctionseps93', $this->paginate( $this->Nonrespectsanctionep93 ) );
			}

			// INFO: containable ne fonctionne pas avec les find('list')
			$commissionseps = array();
			$tmpSeanceseps = $this->Nonrespectsanctionep93->Dossierep->Passagecommissionep->Commissionep->find(
				'all',
				array(
					'fields' => array(
						'Commissionep.id',
						'Commissionep.dateseance',
						'Ep.name'
					),
					'contain' => array(
						'Ep'
					),
					'order' => array( 'Ep.name ASC', 'Commissionep.dateseance DESC' )
				)
			);

			if( !empty( $tmpSeanceseps ) ) {
				foreach( $tmpSeanceseps as $key => $commissionep ) {
					$commissionseps[$commissionep['Ep']['name']][$commissionep['Commissionep']['id']] = $commissionep['Commissionep']['dateseance'];
				}
			}

			$this->_setOptions();
			$options = Set::merge(
				array( 'Dossierep' => array( 'commissionep_id' => $commissionseps ) ),
				$this->viewVars['options']
			);
			$this->set( compact( 'options' ) );

			$view = implode( '_', Set::filter( array( 'index', $searchMode ) ) );
			$this->render( null, null, $view );
		}

		/**
		*
		*/

		public function selectionradies() {
// 			$this->_selectionPassageNonrespectsanctionep93( 'qdRadies', 'radiepe' );

			if( !empty( $this->data ) ) {
				$success = true;
				$this->Nonrespectsanctionep93->begin();

				foreach( $this->data['Historiqueetatpe'] as $key => $item ) {
					// La personne était-elle sélectionnée précédemment ?
					$alreadyChecked = $this->Nonrespectsanctionep93->Dossierep->find(
						'first',
						array(
							'conditions' => array(
								// état 'cree' ?
								'Dossierep.id NOT IN ( '
									.$this->Nonrespectsanctionep93->Dossierep->Passagecommissionep->sq(
										array(
											'alias' => 'passagescommissionseps',
											'fields' => array( 'passagescommissionseps.dossierep_id' ),
											'conditions' => array(
												'passagescommissionseps.dossierep_id = Dossierep.id',
												'passagescommissionseps.etatdossierep <>' => 'reporte'
											)
										)
									)
								.' )',
								'Dossierep.themeep' => 'nonrespectssanctionseps93',
								'Dossierep.personne_id' => $this->data['Personne'][$key]['id'],
								'Nonrespectsanctionep93.origine' => 'radiepe'
							),
							'contain' => array(
								'Nonrespectsanctionep93'
							)
						)
					);

					// Personnes non cochées que l'on sélectionne
					if( empty( $alreadyChecked ) && !empty( $item['chosen'] ) ) {
						$dossierep = array(
							'Dossierep' => array(
								'themeep' => 'nonrespectssanctionseps93',
								'personne_id' => $this->data['Personne'][$key]['id']
							)
						);
						$this->Nonrespectsanctionep93->Dossierep->create( $dossierep );
						$success = $this->Nonrespectsanctionep93->Dossierep->save() && $success;

						$rgpassage = $this->Nonrespectsanctionep93->find(
							'count',
							array(
								'conditions' => array(
									'Nonrespectsanctionep93.origine' => 'radiepe',
									'historiqueetatpe_id' => $item['id']
								),
								'contain' => false
							)
						);

						///FIXME : à corriger plus tard probablement
						if ( $rgpassage >= 1 ) {
							$rgpassage = 2;
						}
						else {
							$rgpassage = 1;
						}

						$nonrespectsanctionep93 = array(
							'Nonrespectsanctionep93' => array(
								'dossierep_id' => $this->Nonrespectsanctionep93->Dossierep->id,
								'historiqueetatpe_id' => $item['id'],
								'origine' => 'radiepe',
								'rgpassage' => $rgpassage,
								'active' => 0
							)
						);

						$this->Nonrespectsanctionep93->create( $nonrespectsanctionep93 );
						$success = $this->Nonrespectsanctionep93->save() && $success;
					}
					// Personnes précédemment sélectionnées, que l'on désélectionne
					else if( !empty( $alreadyChecked ) && empty( $item['chosen'] ) ) {
						$success = $this->Nonrespectsanctionep93->Dossierep->delete( $alreadyChecked['Dossierep']['id'], true ) && $success;
					}
					// Personnes précédemment sélectionnées, que l'on garde sélectionnées -> rien à faire
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->data = array();
					$this->Nonrespectsanctionep93->commit();
				}
				else {
					$this->Nonrespectsanctionep93->rollback();
				}
			}

			$queryData = $this->Nonrespectsanctionep93->qdRadies();
			$queryData['limit'] = 10;

			$this->paginate = array( 'Personne' => $queryData );
			$personnes = $this->paginate( $this->Nonrespectsanctionep93->Dossierep->Personne );

			if( empty( $this->data ) ) {
				// Pré-remplissage des cases à cocher avec les dossiers sélectionnés,
				// qui ne sont pas encore assocés à une séance. -> FIXME permettre jusqu'à l'étape avisep ?
				$dossiers = $this->Nonrespectsanctionep93->Dossierep->find(
					'all',
					array(
						'conditions' => array(
							// état 'cree' ?
							'Dossierep.id NOT IN ( '
								.$this->Nonrespectsanctionep93->Dossierep->Passagecommissionep->sq(
									array(
										'alias' => 'passagescommissionseps',
										'fields' => array( 'passagescommissionseps.dossierep_id' ),
										'conditions' => array(
											'passagescommissionseps.dossierep_id = Dossierep.id',
											'passagescommissionseps.etatdossierep <>' => 'reporte'
										)
									)
								)
							.' )',
							'Dossierep.themeep' => 'nonrespectssanctionseps93',
							///FIXME !!!!!!!!!!!!!!!!!
							'Dossierep.personne_id' => Set::extract( '/Personne/id', $personnes ),
							'Nonrespectsanctionep93.origine' => 'radiepe'
						),
						'contain' => array(
							'Nonrespectsanctionep93'
						)
					)
				);

				if( !empty( $dossiers ) ) {
					$checked = Set::extract( '/Dossierep/personne_id', $dossiers );

					foreach( $personnes as $i => $personne ) {
						$this->data['Historiqueetatpe'][$i]['id'] = $personne['Historiqueetatpe']['id'];
						$this->data['Personne'][$i]['id'] = $personne['Personne']['id'];
						if( in_array( $personne['Personne']['id'], $checked ) ) {
							$this->data['Historiqueetatpe'][$i]['chosen'] = '1';
						}
						else {
							$this->data['Historiqueetatpe'][$i]['chosen'] = '0';
						}
					}
				}
			}

			$this->set( compact( 'personnes' ) );
		}

		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$searchData = Set::classicExtract( Xset::bump( $this->params['named'], '__' ), 'Search' );
			$searchMode = Set::classicExtract( $searchData, 'Nonrespectsanctionep93.mode' );

			$dossiers = $this->Nonrespectsanctionep93->find( 'all', $this->_queryData( $searchData ) );

			$this->layout = ''; // FIXME ?
			$this->set( compact( 'headers', 'dossiers' ) );
			$this->_setOptions();
		}
	}
?>