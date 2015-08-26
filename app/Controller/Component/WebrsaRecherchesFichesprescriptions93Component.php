<?php
	/**
	 * Code source de la classe WebrsaRecherchesFichesprescriptions93Component.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesFichesprescriptions93Component ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesFichesprescriptions93Component extends WebrsaRecherchesComponent
	{
		/**
		 * Retourne la valeur de modelName sur lequel faire la pagination,
		 * Ficheprescription93 ou Personne suivant la valeur du filtre
		 * Search.Ficheprescription93.exists.
		 *
		 * @return string
		 */
		protected function _getModelName() {
			$Controller = $this->_Collection->getController();

			if( Hash::get( $Controller->request->data, 'Search.Ficheprescription93.exists' ) ) {
				return 'Ficheprescription93';
			}

			return 'Personne';
		}

		/**
		 * Surcharge de la méthode params() pour définir la clé modelName à
		 * Ficheprescription93 ou Personne suivant la valeur du filtre
		 * Search.Ficheprescription93.exists.
		 *
		 * @see WebrsaRecherchesFichesprescriptions93Component::_getModelName()
		 *
		 * @param array $params
		 * @return array
		 */
		public function params( array $params = array() ) {
			$params += array(
				'modelName' => $this->_getModelName(),
				'structurereferente_id' => 'Referent.structurereferente_id'
			);

			return parent::params( $params );
		}

		/**
		 * Surcharge de la méthode getQuery afin de faire la requête sur le modèle
		 * Ficheprescription93 ou Personne suivant la valeur du filtre
		 * Search.Ficheprescription93.exists.
		 *
		 * @see WebrsaRecherchesFichesprescriptions93Component::_getModelName()
		 *
		 * @param string|array $keys
		 * @param array $params
		 * @return array
		 */
		public function getQuery( $keys, array $params = array() ) {
			$keys = (array)$keys;
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );

			$modelName = $this->_getModelName();
			$Controller->set( compact( 'modelName' ) );

			$cacheKey = $Controller->{$params['modelName']}->useDbConfig.'_'.$Controller->name.'_'.$Controller->action.'_'.$Controller->{$params['modelName']}->alias.'_searchQuery_'.$modelName;
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $Controller->{$params['modelRechercheName']}->searchQuery();
				$query = ConfigurableQueryFields::getFieldsByKeys( $keys, $query );

				// Optimisation: on attaque fichesprescriptions93 en premier lieu
				if( $modelName === 'Ficheprescription93' ) {
					foreach( $query['joins'] as $i => $join ) {
						if( $join['alias'] == 'Ficheprescription93' ) {
							unset( $query['joins'][$i] );
							array_unshift( $query['joins'], $Controller->Ficheprescription93->join( 'Personne', array( 'type' => 'INNER' ) ) );
						}
					}
				}

				Cache::write( $cacheKey, $query );
			}

			return $query;
		}

		/**
		 * @todo: faire la distinction search(index)/exportcsv (notamment dans Allocataires)
		 * @todo: mise en cache ?
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );

			return Hash::merge(
				parent::options( $params ),
				$Controller->Ficheprescription93->options( array( 'allocataire' => false, 'find' => true, 'autre' => false ) )
			);
		}
	}
?>