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
		/**
		 * Helpers utilisés dans ce contrôleur.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Allocataires',
			'Csv',
			'Default2',
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
			'Search'
		);

		/**
		 * Coponents utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires',
			'Jetons2',
			'Cohortes',
			'DossiersMenus',
			'Search.Filtresdefaut' => array(
				'cohorte_radiespe',
				'cohorte_noninscritspe',
				'selectionradies',
				'selectionnoninscrits'
			),
			'Search.SearchPrg' => array(
				'actions' => array(
					'cohorte_radiespe' => array( 'filter' => 'Search' ),
					'cohorte_noninscritspe' => array( 'filter' => 'Search' ),
					'selectionradies' => array( 'filter' => 'Search' ),
					'selectionnoninscrits' => array( 'filter' => 'Search' )
				)
			)
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Sanctionep58' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'cohorte_radiespe' => 'create',
			'exportcsv_radiespe' => 'read',
			'cohorte_noninscritspe' => 'create',
			'exportcsv_noninscritspe' => 'read',
			'deleteNonrespectcer' => 'delete',
			'exportcsv' => 'read',
			'nonrespectcer' => 'create',
			'selectionnoninscrits' => 'create',
			'selectionradies' => 'create',
		);

		/**
		 *
		 * @var array
		 */
 		public $commeDroit = array(
			'cohorte_radiespe' => 'Sanctionseps58:selectionradies',
			'exportcsv_radiespe' => 'Sanctionseps58:exportcsv',
			'cohorte_noninscritspe' => 'Sanctionseps58:selectionnoninscrits',
			'exportcsv_noninscritspe' => 'Sanctionseps58:exportcsv',
		);

		/**
		 * @deprecated since 3.0.0
		 *
		 * @param string $qdName
		 * @param string $origine
		 */
		protected function _selectionPassageSanctionep58( $qdName, $origine ) {
			if( !empty( $this->request->data ) ) {
				$savedRequestData = $this->request->data;

				if( $qdName == 'qdNonInscrits' ) {
					$modelName = 'Orientstruct';
				}
				else {
					$modelName = 'Historiqueetatpe';
				}

				if( isset( $this->request->data[$modelName] ) ) {
					$success = true;
					$this->Sanctionep58->begin();

					foreach( $this->request->data[$modelName] as $key => $item ) {
						// La personne était-elle sélectionnée précédemment ?
						$dossierep_id = Hash::get( $this->request->data, "Dossierep.{$key}.id" );

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

			$queryData = $this->Allocataires->completeSearchQuery( $queryData );
			$queryData = ClassRegistry::init( 'Allocataire' )->searchConditions( $queryData, (array)Hash::get( $this->request->data, 'Search' ) );

			$this->paginate = array( 'Personne' => $queryData );
			$progressivePaginate = !Hash::get( $this->request->data, 'Search.Pagination.nombre_total' );
			$personnes = $this->paginate( $this->Sanctionep58->Dossierep->Personne, array(), array(), $progressivePaginate );

			// FIXME: quels sont les sélectionnés!!!
			if( isset( $savedRequestData ) ) {
				$this->request->data = array( 'Search' => (array)Hash::get( $savedRequestData, 'Search' ) );
			}
			else {
				$this->request->data = null;
			}

			$this->set( 'options', $this->Allocataires->options() );
			$this->set( 'etatdosrsa', ClassRegistry::init('Option')->etatdosrsa( ClassRegistry::init('Situationdossierrsa')->etatOuvert()) );
			$this->set( compact( 'personnes' ) );
			$this->render( $origine );
		}

		/**
		 * @deprecated since 3.0.0
		 */
		public function selectionnoninscrits() {
			$this->_selectionPassageSanctionep58( 'qdNonInscrits', 'noninscritpe' );
		}

		/**
		 * @deprecated since 3.0.0
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
		 * @deprecated since 3.0.0
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

			$search = (array)Hash::get( (array)Hash::expand( $this->request->params['named'], '__' ), 'Search' );

			$queryData = $this->Allocataires->completeSearchQuery( $queryData );
			$queryData = ClassRegistry::init( 'Allocataire' )->searchConditions( $queryData, $search );

			$personnes = $this->Sanctionep58->Dossierep->Personne->find( 'all', $queryData );

			$options = $this->Allocataires->options();

			$this->layout = null;

			$this->set( compact( 'options', 'personnes', 'nameTableauCsv' ) );
		}

		/**
		 * Cohorte de sélection des allocataires radiés de Pôle Emploi (nouveau).
		 */
		public function cohorte_radiespe() {
			$this->loadModel('Personne');

			$Cohortes = $this->Components->load( 'WebrsaCohortesSanctionseps58' );

			$Cohortes->cohorte(
				array(
					'modelName' => 'Personne',
					'modelRechercheName' => 'WebrsaCohorteSanctionep58Radiepe',
					'auto' => true
				)
			);
		}

		/**
		 * Export CSV desc résultats de la cohorte de sélection des allocataires
		 * radiés de Pôle Emploi (nouveau).
		 */
		public function exportcsv_radiespe() {
			$this->loadModel('Personne');

			$Cohortes = $this->Components->load( 'WebrsaCohortesSanctionseps58' );

			$Cohortes->exportcsv(
				array(
					'modelName' => 'Personne',
					'modelRechercheName' => 'WebrsaCohorteSanctionep58Radiepe'
				)
			);
		}

		/**
		 * Cohorte de sélection des allocataires non inscrits à Pôle Emploi
		 * (nouveau).
		 */
		public function cohorte_noninscritspe() {
			$this->loadModel('Personne');

			$Cohortes = $this->Components->load( 'WebrsaCohortesSanctionseps58' );

			$Cohortes->cohorte(
				array(
					'modelName' => 'Personne',
					'modelRechercheName' => 'WebrsaCohorteSanctionep58Noninscritpe',
					'auto' => true
				)
			);
		}

		/**
		 * Export CSV desc résultats de la cohorte de sélection des allocataires
		 * non inscrits à Pôle Emploi (nouveau).
		 */
		public function exportcsv_noninscritspe() {
			$this->loadModel('Personne');

			$Cohortes = $this->Components->load( 'WebrsaCohortesSanctionseps58' );

			$Cohortes->exportcsv(
				array(
					'modelName' => 'Personne',
					'modelRechercheName' => 'WebrsaCohorteSanctionep58Noninscritpe'
				)
			);
		}
	}
?>