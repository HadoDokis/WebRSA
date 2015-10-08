<?php
	/**
	 * Code source de la classe WebrsaAbstractRecherchesComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );
	App::uses( 'WebrsaAbstractMoteursComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaAbstractRecherchesComponent ...
	 *
	 * @package app.Controller.Component
	 */
	abstract class WebrsaAbstractRecherchesComponent extends WebrsaAbstractMoteursComponent
	{
		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires'
		);

		/**
		 *
		 * @param array $params
		 */
		public function search( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			$Controller->loadModel( $params['modelRechercheName'] );

			if( !empty( $Controller->request->data ) || $this->_needsAutoSearch( $params ) ) {
				if( $this->_needsAutoSearch( $params ) ) {
					$this->_autoSearch( $params );
				}

				$query = $this->_getQuery( $params );

				$Controller->{$params['modelName']}->forceVirtualFields = true;
				$results = $this->Allocataires->paginate( $query, $params['modelName'] );

				$Controller->set( compact( 'results' ) );
			}
			else {
				$this->_prepareFilter($params);
			}

			$options = $this->options( $params );
			$options = $this->_getFilteredOptions( $params, $options );
			$Controller->set( compact( 'options' ) );
		}
	}
?>