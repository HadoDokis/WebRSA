<?php
	/**
	 * Code source de la classe CriterescuisController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConfigurableQueryFields', 'Utility' );

	/**
	 * La classe CriterescuisController implémente un moteur de recherche par CUIs (CG 58, 66 et 93).
	 *
	 * @package app.Controller
	 */
	class CriterescuisController extends AppController
	{
		public $name = 'Criterescuis';

		/**
		 * Modèles utilisés
		 *
		 * @var array
		 */
		public $uses = array( 'Criterecui', 'Cui' );

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires',
			'Search.Filtresdefaut' => array( 'search' ),
			'Search.SearchPrg' => array(
				'actions' => array(
					'search' => array(
						'filter' => 'Search'
					)
				)
			)
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Allocataires',
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
			'Search.SearchForm',
			'Romev3',
		);

		// TODO: nom de méthode search()
		public function search() {
			if( !empty( $this->request->data ) ) {
				$search = (array)Hash::get( $this->request->data, 'Search' );
				$query = $this->Criterecui->search( $search );
				$query = $this->Allocataires->completeSearchQuery( $query );
				$query = ConfigurableQueryFields::getFieldsByKeys( array( 'Criterescuis.search.fields' ), $query );
				$query['limit'] = 10;

				$this->Cui->forceVirtualFields = true;
				$this->paginate = $query;
				$results = $this->paginate(
					$this->Cui,
					array(),
					array(),
					!Hash::get( $search, 'Pagination.nombre_total' )
				);
				$this->set( compact( 'results' ) );
			}
			
			$options = $this->_getOptions();
			$this->set( compact( 'options' ) );
		}
		
		protected function _getOptions() {
			// Tables suplémentaire pour un CG donné
			$cgDepartement = Configure::read( 'Cg.departement' );
			$modelCuiDpt = 'Cui' . $cgDepartement;
			$options = array();
			if( isset( $this->Cui->{$modelCuiDpt} ) ) {
				$options = Hash::merge( $options, $this->Cui->{$modelCuiDpt}->enums() );
				
				// Liste de modeles potentiel pour un CG donné
				$modelPotentiel = array(
					'Accompagnementcui' . $cgDepartement,
					'Decisioncui' . $cgDepartement,
					'Propositioncui' . $cgDepartement,
					'Rupturecui' . $cgDepartement,
					'Suspensioncui' . $cgDepartement,
				);
				
				foreach ( $modelPotentiel as $modelName ){
					if ( isset( $this->Cui->{$modelCuiDpt}->{$modelName} ) ){
						$options = Hash::merge( $options, $this->Cui->{$modelCuiDpt}->{$modelName}->enums() );
					}
				}
			}
			
			return Hash::merge(
				$options,
				$this->Allocataires->options(),
				$this->Cui->enums(),
				$this->Cui->Emailcui->options()
			);
		}
		
		/**
		 *
		 * @param array $search Les filtres venant du moteur de recherche
		 * @param array $fieldsConfigureKeys Le nom des clés de configuration dans
		 *	lesquelles récupérer les champs nécessaires.
		 * @return array
		 */
		protected function _getQuery( array $search, array $fieldsConfigureKeys ) {
			$query = $this->Criterecui->search( $search );
			$query = $this->Allocataires->completeSearchQuery( $query );

			$query = ConfigurableQueryFields::getFieldsByKeys( $fieldsConfigureKeys, $query );

			return $query;
		}
		
		/**
		 * Export du tableau de résultats de la cohorte en CSV.
		 */
		public function exportcsv() {
			$search = (array)Hash::get( Hash::expand( $this->request->params['named'], '__' ), 'Search' );

			$query = $this->_getQuery( $search, array( "{$this->name}.{$this->action}" ) );
			unset( $query['limit'] );

			$this->Cui->forceVirtualFields = true;
			$results = $this->Cui->find( 'all', $query );
			$options = $this->_getOptions();

			$this->layout = null;
			$this->set( compact( 'results', 'options' ) );
		}
	}
?>