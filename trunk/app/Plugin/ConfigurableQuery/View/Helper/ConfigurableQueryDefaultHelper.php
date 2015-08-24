<?php
	/**
	 * Code source de la classe DefaultDefaultHelper.
	 *
	 * PHP 5.4
	 *
	 * @package Default
	 * @subpackage View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'DefaultDefaultHelper', 'Default.View/Helper' );
	App::uses( 'SearchProgressivePagination', 'Search.Utility' );

	/**
	 * La classe DefaultDefaultHelper ...
	 *
	 * @package Default
	 * @subpackage View.Helper
	 */
	class ConfigurableQueryDefaultHelper extends DefaultDefaultHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'DefaultAction' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryAction'
			),
			'DefaultCsv' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryCsv'
			),
			'DefaultForm' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryForm'
			),
			'DefaultHtml' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryHtml'
			),
			'DefaultPaginator' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryPaginator'
			),
			'DefaultTable' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryTable'
			)
		);

		public function configuredParams( array $params = array() ) {
			// FIXME: prefix ConfigurableQuery, par défaut, possibilité de mettre à NULL
			return $params + array(
				'keyPrefix' => 'ConfigurableQuery',
				'key' => Inflector::camelize( $this->request->params['controller'] ).".{$this->request->params['action']}"
			);
		}

		public function normalizeConfiguredFields( array $fields ) {
			$fields = Hash::normalize( $fields );

			foreach( $fields as $fieldName => $params ) {
				$params = (array)$params;

				if( !isset( $params['type'] ) && strstr( $fieldName, '/' ) === false ) {
					$fields[$fieldName]['type'] = $this->DefaultTable->DefaultTableCell->DefaultData->type( $fieldName );
				}
				if( !isset( $params['label'] ) ) {
					$fields[$fieldName]['label'] = __m( $fieldName );
				}
			}

			return $fields;
		}

		public function configuredFields( array $params = array() ) {
			$params = $this->configuredParams( $params );
			$fields = $this->normalizeConfiguredFields( (array)Configure::read( "{$params['keyPrefix']}{$params['key']}" ) );

			return $fields;
		}

		/**
		 *
		 * @param array $data
		 * @param array $params
		 * @return string
		 */
		public function configuredCsv( array $results, array $params = array() ) {
			$params = $this->configuredParams( $params );
			$fields = $this->configuredFields( $params );

			return $this->DefaultCsv->render( $results, $fields, $params );
		}

		public function configuredIndex( array $results, array $params = array() ) {
			$params = $this->configuredParams( $params );
			$params += array(
				'format' => SearchProgressivePagination::format( !Hash::get( $this->request->data, 'Search.Pagination.nombre_total' ) )
			);
			$fields = $this->configuredFields( array( 'key' => $params['key'].'.fields', 'keyPrefix' => $params['keyPrefix'] ) );

			$header = (array)Configure::read( "{$params['keyPrefix']}{$params['key']}".'.header' );
			if( !empty( $header ) ) {
				$params['header'] = $header;
			}

			// FIXME: normaliser
			$innerTable = (array)Configure::read( "{$params['keyPrefix']}{$params['key']}".'.innerTable' );
			if( !empty( $innerTable ) ) {
				$params['innerTable'] = $this->normalizeConfiguredFields( $innerTable );
			}

			$this->DefaultPaginator->options(
				array( 'url' => Hash::flatten( (array)$this->request->data, '__' ) )
			);

			unset( $params['keyPrefix'] );

			return $this->index(
				$results,
				$fields,
				$params
			);
		}
	}
?>