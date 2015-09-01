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
		
		/**
		 * On y stock les champs hidden avant leur retrait du tableau de résultat
		 * @var array
		 */
		public $hiddenFields = array();

		public function configuredParams( array $params = array() ) {
			return $params + array(
				'keyPrefix' => 'ConfigurableQuery',
				'key' => Inflector::camelize( $this->request->params['controller'] ).".{$this->request->params['action']}"
			);
		}

		public function normalizeConfiguredFields( array $fields ) {
			$fields = Hash::normalize( $fields );

			foreach( $fields as $fieldName => $params ) {
				$params = (array)$params;

				// Si c'est un champ caché, on ne l'utilisera pas dans la vue
				if( isset( $params['hidden'] ) && $params['hidden'] ) {
					$this->hiddenFields[$fieldName] = $fields[$fieldName];
					unset( $fields[$fieldName] );
				}
				else {
					if( !isset( $params['type'] ) && strstr( $fieldName, '/' ) === false ) {
						$fields[$fieldName]['type'] = $this->DefaultTable->DefaultTableCell->DefaultData->type( $fieldName );
					}
					if( !isset( $params['label'] ) ) {
						$fields[$fieldName]['label'] = __m( $fieldName );
					}
				}
			}

			return $fields;
		}

		/**
		 * Récupère les fields dans Config/CgXX/NomDuController.php
		 * 
		 * @param array $params
		 * @param array $insert Ajout possible de champs avant les actions
		 * @return type
		 */
		public function configuredFields( array $params = array(), array $insert = array() ) {
			$params = $this->configuredParams( $params );
			$configuredFields = (array)Configure::read( "{$params['keyPrefix']}{$params['key']}" );
			$fields = $this->normalizeConfiguredFields( $configuredFields );
			$insertedFields = array();
			$inserted = false;
			
			if ( !empty($insert) ) {
				$insert = $this->normalizeConfiguredFields($insert);
				
				// On cherche la première action pour l'insert
				foreach ( $fields as $key => $value ) {
					if( strstr( $key, '/' ) !== false && $inserted === false ) {
						$inserted = true;
						$insertedFields = array_merge($insertedFields, $insert);
					}
					
					$insertedFields[$key] = $value;
				}
				
				// S'il n'y a pas d'action, on insert à la fin
				if ( $inserted === false ) {
					$insertedFields = array_merge($insertedFields, $insert);
				}
			}
			else{
				$insertedFields = $fields;
			}

			return $insertedFields;
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

		public function configuredIndex( array $results, array $params = array(), array $insert = array() ) {
			$params = $this->configuredParams( $params );
			$params += array(
				'format' => SearchProgressivePagination::format( !Hash::get( $this->request->data, 'Search.Pagination.nombre_total' ) )
			);
			$fields = $this->configuredFields( array( 'key' => $params['key'].'.fields', 'keyPrefix' => $params['keyPrefix'] ), $insert );

			$header = (array)Configure::read( "{$params['keyPrefix']}{$params['key']}".'.header' );
			if( !empty( $header ) ) {
				$params['header'] = $header;
			}

			$innerTable = (array)Configure::read( "{$params['keyPrefix']}{$params['key']}".'.innerTable' );
			if( !empty( $innerTable ) ) {
				$params['innerTable'] = $this->normalizeConfiguredFields( $innerTable );
			}

			$this->DefaultPaginator->options(
				array( 'url' => Hash::flatten( (array)$this->request->data, '__' ) )
			);

			unset( $params['keyPrefix'] );
			
			// On génère le tableau de résultat
			return $this->index(
				$results,
				$fields,
				$params
			);
		}
		
		public function configuredCohorte( array $results, array $params = array(), array $insert = array() ) {
			$table = $this->configuredIndex($results, $params, $insert);
			
			// On ajoute les champs cachés à la fin
			foreach ($this->hiddenFields as $key => $hiddenField) {
				for ($i=0; $i<count($results); $i++) {
					$preformatedPath = preg_replace( '/^data\[(.*)\]$/', '\1', str_replace( '[]', "[{$i}]", $key ) );
					$fullPath = str_replace( '][', '.', $preformatedPath );
					if ( strpos($fullPath, '.') === false ) {
						break;
					}
					
					$path = str_replace( '][', '.', str_replace( "[{$i}]", '', $preformatedPath ) );
					$model_field = model_field( $path );
					$hiddenField += array(
						'value' => Hash::get($results, $i.'.'.$model_field[0].'.'.$model_field[1]),
					);
					$input = $this->DefaultTable->DefaultTableCell->input($fullPath, $hiddenField);
					$table .= count($input) ? $input[0] : '';
				}
			}
			
			// On ajoute des champs cachés contenant les filtres de recherche pour les cohortes
			foreach ( Hash::flatten( (array)$this->request->data, '.' ) as $path => $value ) {
				if ( strpos($path, '.') ) {
					$input = $this->DefaultTable->DefaultTableCell->input( $path, array( 'type' => 'hidden', 'value' => $value ) );
					$table .= count($input) ? $input[0] : '';
				}
			}
			
			return $table;
		}
	}
?>