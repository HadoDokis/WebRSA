<?php
	/**
	 * Code source de la classe WebrsaRecherchesFichesprescriptions93NewComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesNewComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesFichesprescriptions93NewComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesFichesprescriptions93NewComponent extends WebrsaAbstractRecherchesNewComponent
	{
		/**
		 * Retourne la valeur de modelName sur lequel faire la pagination,
		 * Ficheprescription93 ou Personne suivant la valeur du filtre
		 * Search.Ficheprescription93.exists.
		 *
		 * @return string
		 */
		protected function _modelName() {
			$Controller = $this->_Collection->getController();

			if( Hash::get( $Controller->request->data, 'Search.Ficheprescription93.exists' ) ) {
				return 'Ficheprescription93';
			}

			return 'Personne';
		}

		protected function _params( array $params = array() ) {
			$defaults = array(
				'modelName' => $this->_modelName(),
				'structurereferente_id' => 'Referent.structurereferente_id'
			);

			return parent::_params( $params + $defaults );
		}

		protected function _queryBase( $keys, array $params ) {
			$Controller = $this->_Collection->getController();
			$query = parent::_queryBase( $keys, $params );

			$modelName = $this->_modelName();

			// Optimisation: on attaque fichesprescriptions93 en premier lieu
			if( $modelName === 'Ficheprescription93' ) {
				foreach( $query['joins'] as $i => $join ) {
					if( $join['alias'] === 'Ficheprescription93' ) {
						unset( $query['joins'][$i] );
						array_unshift( $query['joins'], $Controller->Ficheprescription93->join( 'Personne', array( 'type' => 'INNER' ) ) );
					}
				}
			}

			return $query;
		}

		protected function _optionsEnums( array $params ) {
			$Controller = $this->_Collection->getController();

			return Hash::merge(
				parent::_optionsEnums( $params ),
				$Controller->Ficheprescription93->options( array( 'allocataire' => false, 'find' => false, 'autre' => false, 'enums' => true ) )
			);
		}

		protected function _optionsRecords( array $params ) {
			$Controller = $this->_Collection->getController();

			return Hash::merge(
				parent::_optionsRecords( $params ),
				$Controller->Ficheprescription93->options( array( 'allocataire' => false, 'find' => true, 'autre' => true, 'enums' => false ) )
			);
		}

		protected function _optionsRecordsModels( array $params ) {
			return array_merge(
				parent::_optionsRecordsModels( $params ),
				array( 'Thematiquefp93', 'Modtransmfp93', 'Documentbeneffp93', 'Motifnonreceptionfp93', 'Motifnonretenuefp93', 'Motifnonsouhaitfp93', 'Motifnonintegrationfp93', 'Documentbeneffp93' )
			);
		}
	}
?>