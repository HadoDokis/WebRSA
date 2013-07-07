<?php
	/**
	 * Code source de la classe DefaultTableHelper.
	 *
	 * PHP 5.4
	 *
	 * @package Default
	 * @subpackage View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe DefaultTableHelper ...
	 *
	 * @package Default
	 * @subpackage View.Helper
	 */
	class DefaultTableHelper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default.DefaultTableCell',
			'Default.DefaultHtml',
			'Default.DefaultPaginator'
		);

		/**
		 * Retourne l'élément thead d'une table pour un ensemble d'enregistrements.
		 *
		 * @todo Pouvoir placer des liens dans n'importe quelle colonne, en
		 * plus de actions.
		 * @todo Connaître le type de colonnes via le TableCellHelper
		 *
		 * @param array $fields
		 * @param array $params
		 * @return string
		 */
		public function thead( array $fields, array $params ) {
			if( empty( $fields ) ) {
				return null;
			}

			$fields = Set::normalize( $fields );
			$theadTr = array();
			$domain = Hash::get( $params, 'domain' );
			$tableId = Hash::get( $params, 'id' );

			$cacheModelField = array();

			foreach( $fields as $field => $attributes ) {
				$attributes = (array)$attributes;
				if( strpos( $field, '/' ) === 0 ) {
					$for = "{$tableId}ColumnActions";
				}
				else if( strpos( $field, 'data[' ) === 0 ) {
					$for = "{$tableId}ColumnInput{$field}";
					$theadTr[] = array(
						__d( $domain, $field ) => array( 'id' => $for )
					);
				}
				else {
					// INFO: la mise en cache n'a pas de sens ici
					list( $modelName, $fieldName ) = model_field( $field );

					$for = "{$tableId}Column{$modelName}".Inflector::camelize( $fieldName );
					$theadTr[] = array(
						$this->DefaultPaginator->sort( $field, __d( $domain, "{$modelName}.{$fieldName}" ) ) => array( 'id' => $for )
					);
				}
				$fields[$field] = $attributes + array( 'for' => $for );
			}

			$countDiff = count( $fields ) - count( $theadTr );
			if( $countDiff > 0 ) {
				$theadTr[] = array( __d( $domain, 'Actions' ) => array( 'colspan' => $countDiff, 'class' => 'actions', 'id' => "{$tableId}ColumnActions" ) );
			}

			return $this->DefaultHtml->tag( 'thead', $this->DefaultHtml->tableHeaders( $theadTr ) );
		}

		/**
		 * Retourne l'élément tbody d'une table pour un ensemble d'enregistrements.
		 *
		 * @param array $datas
		 * @param array $fields
		 * @param array $params
		 * @return string
		 */
		public function tbody( array $datas, array $fields, array $params = array() ) {
			if( empty( $datas ) || empty( $fields ) ) {
				return null;
			}

			$fields = Set::normalize( $fields );
			$trs = array();

			foreach( $datas as $i => $data ) {
				$this->DefaultTableCell->set( $data );
				$tr = array();

				foreach( $fields as $path => $attributes ) {
					if( strstr( $path, '[]' ) !== false ) {
						$path = str_replace( '[]', "[{$i}]", $path );
					}

					// TODO: que faire dans ces cas-là ?
					if( strstr( $path, ']' ) !== false ) {
					}
					else if( strstr( $path, '/' ) !== false ) {
					}
					else if( strstr( $path, '.' ) !== false ) {
						list( $modelName, $fieldName ) = model_field( $path );
						if( !isset( $attributes['options'] ) && isset( $params['options'][$modelName][$fieldName] ) ) {
							$attributes['options'] = $params['options'][$modelName][$fieldName];
						}
					}
					$tr[] = $this->DefaultTableCell->auto( $path, (array)$attributes );
				}

				$trs[] = $tr;
			}

			return $this->DefaultHtml->tag( 'tbody', $this->DefaultHtml->tableCells( $trs, array( 'class' => 'odd' ), array( 'class' => 'even' ), false, false ) );
		}

		/**
		 * Retourne une table complète (thead/tbody) pour un ensemble d'enregistrements.
		 *
		 * TODO: traduction des options
		 *
		 * @param array $data
		 * @param array $fields
		 * @param array $params
		 * @return null
		 */
		public function index( array $data, array $fields, array $params = array() ) { // TODO: $tableAttributes, $tableAttributes['domain']
			if( empty( $data ) || empty( $fields ) ) {
				return null;
			}

			$tableParams = array(
				'id' => $this->domId( "Table.{$this->request->params['controller']}.{$this->request->params['action']}" ),
				'class' => "{$this->request->params['controller']} {$this->request->params['action']}",// TODO: addClass
				'domain' => ( isset( $params['domain'] ) ? $params['domain'] : Inflector::underscore( $this->request->params['controller'] ) )
			);

			$thead = $this->thead( $fields, $tableParams + $params );
			$tbody = $this->tbody( $data, $fields, $tableParams + $params );

			return $this->DefaultHtml->tag( 'table', $thead.$tbody, array( 'id' => $tableParams['id'], 'class' => $tableParams['class'] ) );
		}

		/**
		 * Retourne le tbody une table de détails (verticale) pour un
		 * enregistrement particulier.
		 *
		 * @param array $data
		 * @param array $fields
		 * @param array $params
		 * @return string
		 */
		public function detailsTbody( array $data, array $fields, array $params = array() ) {
			if( empty( $data ) || empty( $fields ) ) {
				return null;
			}

			$this->DefaultTableCell->set( $data );
			$fields = Set::normalize( $fields );
			$trs = array();
			$domain = Hash::get( $params, 'domain' );

			foreach( $fields as $path => $attributes ) {
				// INFO: la mise en cache n'a pas de sens ici
				list( $modelName, $fieldName ) = model_field( $path );

				if( !isset( $attributes['options'] ) && isset( $params['options'][$modelName][$fieldName] ) ) {
					$attributes['options'] = $params['options'][$modelName][$fieldName];
				}

				$trs[] = array(
					__d( $domain, $path ), // INFO: pas possible me mettre un th de cette manière, avec tableCells
					$this->DefaultTableCell->auto( $path, (array)$attributes ),
				);
			}

			return $this->DefaultHtml->tag( 'tbody', $this->DefaultHtml->tableCells( $trs, array( 'class' => 'odd' ), array( 'class' => 'even' ), false, false ) );
		}

		/**
		 * Retourne une table de détails (verticale) pour un enregistrement
		 * particulier.
		 *
		 * TODO: traduction des options
		 *
		 * @param array $data
		 * @param array $fields
		 * @param array $params
		 * @return null
		 */
		public function details( array $data, array $fields, array $params = array() ) { // TODO: $tableAttributes, $tableAttributes['domain']
			if( empty( $data ) || empty( $fields ) ) {
				return null;
			}

			$tableParams = array(
				'id' => $this->domId( "Table.{$this->request->params['controller']}.{$this->request->params['action']}" ),
				'class' => "{$this->request->params['controller']} {$this->request->params['action']}",// TODO: addClass
				'domain' => ( isset( $params['domain'] ) ? $params['domain'] : Inflector::underscore( $this->request->params['controller'] ) )
			);

			$tbody = $this->detailsTbody( $data, $fields, $tableParams + $params );

			return $this->DefaultHtml->tag( 'table', $tbody, array( 'id' => $tableParams['id'], 'class' => $tableParams['class'] ) );
		}
	}
?>