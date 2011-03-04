<?php
	/**
	* FIXME
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class Sanctionseps58Controller extends AppController
	{
		public $helpers = array( 'Default2' );
		
		public function beforeFilter() {
			parent::beforeFilter();
		}

		/**
		*
		*/

		protected function _selectionPassageSanctionep58( $qdName, $origine ) {
			if( !empty( $this->data ) ) {
// debug($this->data);
				$success = true;
				$this->Sanctionep58->begin();

				foreach( $this->data['Historiqueetatpe'] as $key => $item ) {
					// La personne était-elle sélectionnée précédemment ?
					$alreadyChecked = $this->Sanctionep58->Dossierep->find(
						'first',
						array(
							'conditions' => array(
								'Dossierep.etapedossierep' => 'cree',
								'Dossierep.themeep' => 'sanctionseps58',
								'Dossierep.personne_id' => $this->data['Personne'][$key]['id'],
								'Sanctionep58.origine' => $origine
							),
							'contain' => array(
								'Sanctionep58'
							)
						)
					);

					// Personnes non cochées que l'on sélectionne
					if( empty( $alreadyChecked ) && !empty( $item['chosen'] ) ) {
						$dossierep = array(
							'Dossierep' => array(
								'themeep' => 'sanctionseps58',
								'personne_id' => $this->data['Personne'][$key]['id']
							)
						);
						$this->Sanctionep58->Dossierep->create( $dossierep );
						$success = $this->Sanctionep58->Dossierep->save() && $success;
						
						$rgsanction = $this->Sanctionep58->find(
							'count',
							array(
								'conditions' => array(
									'Sanctionep58.origine' => $origine,
									'Sanctionep58.historiqueetatpe_id' => $item['id']
								),
								'joins' => array(
									array(
										'table' => 'decisionssanctionseps58',
										'alias' => 'Decisionsanctionep58',
										'type' => 'INNER',
										'conditions' => array(
											'Sanctionep58.id = Decisionsanctionep58.sanctionep58_id',
											'Decisionsanctionep58.decision' => 'sanction'
										)
									)
								),
								'contain' => false
							)
						);
						
						$listesanctionep58 = $this->Sanctionep58->Listesanctionep58->find(
							'first',
							array(
								'conditions' => array(
									'Listesanctionep58.rang' => $rgsanction+1
								),
								'contain' => false
							)
						);
						
						$sanctionep58 = array(
							'Sanctionep58' => array(
								'dossierep_id' => $this->Sanctionep58->Dossierep->id,
								'historiqueetatpe_id' => $item['id'],
								'origine' => $origine,
								'listesanctionep58_id' => $listesanctionep58['Listesanctionep58']['id']
							)
						);

						/*if( $origine == 'radiepe' ) {
							$queryDataPersonne = $this->Sanctionep58->qdRadies();
							$queryDataPersonne['fields'][] = 'Historiqueetatpe.id';
							$queryDataPersonne['conditions']['Personne.id'] = $item['personne_id'];
							$historiqueetatpe = $this->Sanctionep58->Dossierep->Personne->find( 'first', $queryDataPersonne );

							$sanctionep58['Sanctionep58']['historiqueetatpe_id'] = $historiqueetatpe['Historiqueetatpe']['id'];
						}*/

						$this->Sanctionep58->create( $sanctionep58 );
						$success = $this->Sanctionep58->save() && $success;
					}
					// Personnes précédemment sélectionnées, que l'on désélectionne
					else if( !empty( $alreadyChecked ) && empty( $item['chosen'] ) ) {
						$success = $this->Sanctionep58->Dossierep->delete( $alreadyChecked['Dossierep']['id'], true ) && $success;
					}
					// Personnes précédemment sélectionnées, que l'on garde sélectionnées -> rien à faire
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Sanctionep58->commit();
				}
				else {
					$this->Sanctionep58->rollback();
				}
			}

			$queryData = $this->Sanctionep58->{$qdName}();
			$queryData['limit'] = 10;

			$this->paginate = array( 'Personne' => $queryData );
			$personnes = $this->paginate( $this->Sanctionep58->Dossierep->Personne );
			
			$this->data = null;

			/*if( empty( $this->data ) ) {
				// Pré-remplissage des cases à cocher avec les dossiers sélectionnés,
				// qui ne sont pas encore assocés à une séance. -> FIXME permettre jusqu'à l'étape avisep ?
				$dossiers = $this->Sanctionep58->Dossierep->find(
					'all',
					array(
						'conditions' => array(
							'Dossierep.etapedossierep' => 'cree',
							'Dossierep.themeep' => 'sanctionseps58',
							///FIXME !!!!!!!!!!!!!!!!!
							'Dossierep.personne_id' => Set::extract( '/Orientstruct/personne_id', $personnes ),
							'Sanctionep58.origine' => $origine
						),
						'contain' => array(
							'Sanctionep58'
						)
					)
				);

				if( !empty( $dossiers ) ) {
					$checked = Set::extract( '/Dossierep/personne_id', $dossiers );

					foreach( $personnes as $i => $personne ) {
						$this->data['Orientstruct'][$i]['id'] = $personne['Orientstruct']['id'];
						$this->data['Orientstruct'][$i]['personne_id'] = $personne['Orientstruct']['personne_id'];
						if( in_array( $personne['Orientstruct']['personne_id'], $checked ) ) {
							$this->data['Orientstruct'][$i]['chosen'] = '1';
						}
						else {
							$this->data['Orientstruct'][$i]['chosen'] = '0';
						}
					}
				}
			}*/

			$this->set( compact( 'personnes' ) );
            $this->render( $origine ); // FIXME: nom de la vue
		}

		/**
		*
		*/

		public function selectionnoninscrits() {
			$this->_selectionPassageSanctionep58( 'qdNonInscrits', 'noninscritpe' );
		}

		/**
		*
		*/

		public function selectionradies() {
			$this->_selectionPassageSanctionep58( 'qdRadies', 'radiepe' );
		}
	}
?>