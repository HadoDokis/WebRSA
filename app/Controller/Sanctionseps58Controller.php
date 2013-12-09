<?php
	/**
	 * Fichier source de la classe Sanctionseps58Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	* Gestion des dossiers d'EP pour "Non respect et sanctions" (CG 58).
	 *
	 * @package app.Controller
	 */
	class Sanctionseps58Controller extends AppController
	{
		public $helpers = array( 'Default2', 'Csv', 'Search');

		public $components = array(
			'Jetons2',
			'DossiersMenus',
			'Search.Prg' => array(
				'actions' => array(
					'selectionradies' => array( 'filter' => 'Pagination' ),
					'selectionnoninscrits' => array( 'filter' => 'Pagination' )
				)
			)
		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'deleteNonrespectcer' => 'delete',
			'exportcsv' => 'read',
			'nonrespectcer' => 'create',
			'selectionnoninscrits' => 'create',
			'selectionradies' => 'create',
		);

		/**
		 *
		 * @param string $qdName
		 * @param string $origine
		 */
		protected function _selectionPassageSanctionep58( $qdName, $origine ) {
			if( !empty( $this->request->data ) ) {
				if( $qdName == 'qdNonInscrits' ) {
					$modelName = 'Orientstruct';
//					$foreignKey = 'orientstruct_id';
				}
				else {
					$modelName = 'Historiqueetatpe';
//					$foreignKey = 'historiqueetatpe_id';
				}

				if( isset( $this->request->data[$modelName] ) ) {
					$success = true;
					$this->Sanctionep58->begin();

					foreach( $this->request->data[$modelName] as $key => $item ) {
						// La personne était-elle sélectionnée précédemment ?
						$dossierep_id = Hash::get( $this->request->data, "Dossierep.{$key}.id" );
						/*$alreadyChecked = $this->Sanctionep58->Dossierep->find(
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
									'Dossierep.personne_id' => $this->request->data['Personne'][$key]['id'],
									'Sanctionep58.origine' => $origine
								),
								'contain' => array(
									'Sanctionep58'
								)
							)
						);*/

						// Personnes non cochées que l'on sélectionne
						if( empty( $dossierep_id ) && !empty( $item['chosen'] ) ) {
							$dossierep = array(
								'Dossierep' => array(
									'themeep' => 'sanctionseps58',
									'personne_id' => $this->request->data['Personne'][$key]['id']
								)
							);
							$this->Sanctionep58->Dossierep->create( $dossierep );
							$success = $this->Sanctionep58->Dossierep->save() && $success;

							$sanctionep58 = array(
								'Sanctionep58' => array(
									'dossierep_id' => $this->Sanctionep58->Dossierep->id,
									'orientstruct_id' => $this->request->data['Orientstruct'][$key]['id'],
									'origine' => $origine
								)
							);

							if( $qdName == 'qdRadies' ) {
								$sanctionep58['Sanctionep58']['historiqueetatpe_id'] = $item['id'];
							}

							$this->Sanctionep58->create( $sanctionep58 );
							$success = $this->Sanctionep58->save() && $success;
						}
						// Personnes précédemment sélectionnées, que l'on désélectionne
						else if( !empty( $dossierep_id ) && empty( $item['chosen'] ) ) {
							// FIXME: on supprime des décisions dans les déjà cochés!!
							$success = $this->Sanctionep58->Dossierep->delete( $dossierep_id, true ) && $success;
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
			}

			$queryData = $this->Sanctionep58->{$qdName}();
			$queryData['limit'] = 10;

			$this->paginate = array( 'Personne' => $queryData );
			$progressivePaginate = !Hash::get( $this->request->data, 'Pagination.nombre_total' );
			$personnes = $this->paginate( $this->Sanctionep58->Dossierep->Personne, array(), array(), $progressivePaginate );

			// FIXME: quels sont les sélectionnés!!!
			$this->request->data = null;

			$this->set( 'etatdosrsa', ClassRegistry::init('Option')->etatdosrsa( ClassRegistry::init('Situationdossierrsa')->etatOuvert()) );
			$this->set( compact( 'personnes' ) );
			$this->render( $origine );
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
		 * @param integer $contratinsertion_id
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

			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $contratinsertion['Contratinsertion']['personne_id'] ) );

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
		 * @param integer $sanctionep58_id
		 */
		public function deleteNonrespectcer( $sanctionep58_id ) {
			$dossierep = $this->Sanctionep58->find(
				'first',
				array(
					'conditions' => array(
						'Sanctionep58.id' => $sanctionep58_id
					),
					'contain' => array(
						'Dossierep'
					)
				)
			);

			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->Sanctionep58->Dossierep->personneId( $dossierep['Sanctionep58']['dossierep_id'] ) ) );

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
		 *
		 * @param string $qdName
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