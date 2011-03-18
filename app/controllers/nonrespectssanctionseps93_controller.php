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
				$this->Nonrespectsanctionep93->Dossierep->enums()
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
				$conditions[]['Dossierep.etapedossierep'] = 'traite';

				$searchDossierepSeanceepId = Set::classicExtract( $searchData, 'Dossierep.seanceep_id' );
				if( !empty( $searchDossierepSeanceepId ) ) {
					$conditions[]['Dossierep.seanceep_id'] = $searchDossierepSeanceepId;
				}
			}
			else {
				$conditions[]['Dossierep.etapedossierep <>'] = 'traite';
			}

			return array(
				'contain' => array(
					'Dossierep' => array(
						'Personne' => array(
							'Foyer' => array(
								'Dossier',
								'Adressefoyer' => array(
									'conditions' => array( 'Adressefoyer.rgadr' => '01' ),
									'Adresse'
								)
							)
						),
						'Seanceep'
					),
					'Orientstruct',
					'Contratinsertion',
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
			$seanceseps = array();
			$tmpSeanceseps = $this->Nonrespectsanctionep93->Dossierep->Seanceep->find(
				'all',
				array(
					'fields' => array(
						'Seanceep.id',
						'Seanceep.dateseance',
						'Ep.name'
					),
					'contain' => array(
						'Ep'
					),
					'order' => array( 'Ep.name ASC', 'Seanceep.dateseance DESC' )
				)
			);

			if( !empty( $tmpSeanceseps ) ) {
				foreach( $tmpSeanceseps as $key => $seanceep ) {
					$seanceseps[$seanceep['Ep']['name']][$seanceep['Seanceep']['id']] = $seanceep['Seanceep']['dateseance'];
				}
			}

			$this->_setOptions();
			$options = Set::merge(
				array( 'Dossierep' => array( 'seanceep_id' => $seanceseps ) ),
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
								'Dossierep.etapedossierep' => 'cree',
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
									'Nonrespectsanctionep93.origine' => 'radiepe'
								),
								'joins' => array(
									array(
										'table' => 'decisionssanctionseps58',
										'alias' => 'Decisionsanctionep58',
										'type' => 'INNER',
										'conditions' => array(
											'Nonrespectsanctionep93.id = Decisionsanctionep58.sanctionep58_id'
										)
									)
								),
								'contain' => false
							)
						);
						
						///FIXME : à corriger plus tard probablement
						if ( $rgpassage >= 2 ) {
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
								'rgpassage' => $rgpassage
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
							'Dossierep.etapedossierep' => 'cree',
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