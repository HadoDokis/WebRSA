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
		public $helpers = array( 'Default2', 'Csv', 'Search');

		public function beforeFilter() {
			parent::beforeFilter();
		}




		/**
		*
		*/

		protected function _selectionPassageSanctionep58( $qdName, $origine ) {
			if( !empty( $this->data ) ) {
				$success = true;
				$this->Sanctionep58->begin();

				foreach( $this->data['Historiqueetatpe'] as $key => $item ) {
					// La personne était-elle sélectionnée précédemment ?
					$alreadyChecked = $this->Sanctionep58->Dossierep->find(
						'first',
						array(
							'conditions' => array(
								'Dossierep.id NOT IN ( '.$this->Sanctionep58->Dossierep->Passagecommissionep->sq(
									array(
										'fields' => array(
											'passagescommissionseps.dossierep_id'
										),
										'alias' => 'passagescommissionseps',
										'conditions' => array(
											'passagescommissionseps.etatdossierep' => array( 'associe', 'decisionep', 'decisioncg', 'reporte' )
										)
									)
								).' )',
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

						$sanctionep58 = array(
							'Sanctionep58' => array(
								'dossierep_id' => $this->Sanctionep58->Dossierep->id,
								'origine' => $origine
							)
						);

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

			$this->set( 'etatdosrsa', ClassRegistry::init('Option')->etatdosrsa( ClassRegistry::init('Situationdossierrsa')->etatOuvert()) );
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

		/**
		 *
		 */

		public function nonrespectcer( $contratinsertion_id ) {
			$contratinsertion = $this->Sanctionep58->Dossierep->Personne->Contratinsertion->find(
				'first',
				array(
					'fields' => array(
						'Contratinsertion.id',
						'Contratinsertion.personne_id'
					),
					'conditions' => array(
						'Contratinsertion.id' => $contratinsertion_id
					),
					'contain' => false
				)
			);

			$success = true;
			$this->Sanctionep58->begin();

			$dossierep = array(
				'Dossierep' => array(
					'themeep' => 'sanctionseps58',
					'personne_id' => $contratinsertion['Contratinsertion']['personne_id']
				)
			);
			$this->Sanctionep58->Dossierep->create( $dossierep );
			$success = $this->Sanctionep58->Dossierep->save() && $success;

			$sanctionep58 = array(
				'Sanctionep58' => array(
					'dossierep_id' => $this->Sanctionep58->Dossierep->id,
					'origine' => 'nonrespectcer',
					'contratinsertion_id' => $contratinsertion['Contratinsertion']['id']
				)
			);

			$this->Sanctionep58->create( $sanctionep58 );
			$success = $this->Sanctionep58->save() && $success;

			$this->_setFlashResult( 'Save', $success );
			if( $success ) {
				$this->Sanctionep58->commit();
			}
			else {
				$this->Sanctionep58->rollback();
			}

			$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id'] ) );
		}

		/**
		 *
		 */

		public function deleteNonrespectcer( $sanctionep58_id ) {
			$dossierep = $this->Sanctionep58->find(
				'first',
				array(
					'condtions' => array(
						'Sanctionep58.id' => $sanctionep58_id
					),
					'contain' => array(
						'Dossierep'
					)
				)
			);

			$success = true;
			$this->Sanctionep58->begin();

			$success = $this->Sanctionep58->delete( $dossierep['Sanctionep58']['id'] ) && $success;
			$success = $this->Sanctionep58->Dossierep->delete( $dossierep['Dossierep']['id'] ) && $success;

			$this->_setFlashResult( 'Save', $success );
			if( $success ) {
				$this->Sanctionep58->commit();
			}
			else {
				$this->Sanctionep58->rollback();
			}

			$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $dossierep['Dossierep']['personne_id'] ) );
		}





		/**
		* Export du tableau en CSV
		*/

		public function exportcsv( $qdName ) {

			$nameTableauCsv = null;
			if( $qdName == 'qdNonInscrits' ){
				$nameTableauCsv = 'noninscrits';
			}
			else if( $qdName == 'qdRadies' ){
				$nameTableauCsv = 'radies';
			}


			$queryData = $this->Sanctionep58->{$qdName}();

			$this->paginate = array( 'Personne' => $queryData );
			$personnes = $this->Sanctionep58->Dossierep->Personne->find( 'all', $queryData );

			$this->layout = ''; // FIXME ?

			$this->set( compact( 'personnes', 'nameTableauCsv' ) );

		}
	}
?>