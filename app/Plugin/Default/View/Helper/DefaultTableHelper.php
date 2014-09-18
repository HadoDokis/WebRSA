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
		 * Permet de savoir si un champ est sous la forme /Controller/action,
		 * ce qui signifie un lien.
		 *
		 * @param string $field
		 * @return boolean
		 */
		protected function _isUrlField( $field ) {
			return ( strpos( $field, '/' ) === 0 );
		}

		/**
		 * Permet de savoir si un champ est sous la forme data[Model][field],
		 * ce qui signifie un champ de formulaire.
		 *
		 * @param string $field
		 * @return boolean
		 */
		protected function _isInputField( $field ) {
			return ( strpos( $field, 'data[' ) === 0 );
		}

		/**
		 * Permet de savoir si un champ est sous la forme Model.field, ce qui
		 * signifie de l'affichage formaté.
		 *
		 * @param string $field
		 * @return boolean
		 */
		protected function _isDataField( $field ) {
			return ( strstr( $field, '.' ) !== false )
				&& !$this->_isUrlField( $field )
				&& !$this->_isInputField( $field );
		}

		/**
		 * Retourne l'élément thead d'une table pour un ensemble d'enregistrements.
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
			$sort = ( isset( $params['sort'] ) ? $params['sort'] : true );

			foreach( $fields as $field => $attributes ) {
				$attributes = (array)$attributes;
				if( $this->_isUrlField( $field ) ) {
					$for = "{$tableId}ColumnActions";
				}
				else if( $this->_isInputField( $field ) ) {
					$for = "{$tableId}ColumnInput{$field}";
					$theadTr[] = array(
						__d( $domain, $field ) => array( 'id' => $for )
					);
				}
				else if( $this->_isDataField( $field ) ) {
					// INFO: la mise en cache n'a pas de sens ici
					list( $modelName, $fieldName ) = model_field( $field );

					$for = "{$tableId}Column{$modelName}".Inflector::camelize( $fieldName );

					$label = __d( $domain, "{$modelName}.{$fieldName}" );
					if( $sort ) {
						$label = $this->DefaultPaginator->sort( $field, $label );
					}

					$theadTr[] = array( $label => array( 'id' => $for ) );
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
					$path = str_replace( '[]', "[{$i}]", $path );

					if( $this->_isDataField( $path ) ) {
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
		 * Retourne les paramètres à utiliser pour une table.
		 *
		 * @param array $params
		 * @return array
		 */
		public function tableParams( array $params = array() ) {
			return array(
				'id' => ( isset( $params['id'] ) ? $params['id'] : $this->domId( "Table.{$this->request->params['controller']}.{$this->request->params['action']}" ) ),
				'class' => "{$this->request->params['controller']} {$this->request->params['action']}",
				'domain' => ( isset( $params['domain'] ) ? $params['domain'] : Inflector::underscore( $this->request->params['controller'] ) ),
				'sort' => ( isset( $params['sort'] ) ? $params['sort'] : true )
			);
		}

		/**
		 * Retourne une table complète (thead/tbody) pour un ensemble d'enregistrements.
		 *
		 * @param array $data
		 * @param array $fields
		 * @param array $params
		 * @return null
		 */
		public function index( array $data, array $fields, array $params = array() ) {
			if( empty( $data ) || empty( $fields ) ) {
				return null;
			}

			$tableParams = $this->tableParams( $params );
			unset( $params['id'] );

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
		 * @param array $data
		 * @param array $fields
		 * @param array $params
		 * @return null
		 */
		public function details( array $data, array $fields, array $params = array() ) {
			if( empty( $data ) || empty( $fields ) ) {
				return null;
			}

			$tableParams = $this->tableParams( $params );

			$tbody = $this->detailsTbody( $data, $fields, $tableParams + $params );

			return $this->DefaultHtml->tag( 'table', $tbody, array( 'id' => $tableParams['id'], 'class' => $tableParams['class'] ) );
		}
	}
?>