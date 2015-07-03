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
	App::uses( 'DefaultTableHelper', 'Default.View/Helper' );

	/**
	 * La classe DefaultTableHelper ...
	 *
	 * @package Default
	 * @subpackage View.Helper
	 */
	class ConfigurableQueryTableHelper extends DefaultTableHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'DefaultTableCell' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryTableCell'
			),
			'DefaultHtml' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryHtml'
			),
			'DefaultPaginator' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryPaginator'
			)
		);

		public function thead( array $fields, array $params ) {
			$return = parent::thead( $fields, $params );

			// Début FIXME------------------------------------------------------
			$innerTable = Hash::get( $params, 'innerTable' );
			if( !empty( $innerTable ) ) {
				$return = str_replace( '</tr>', '<th class="innerTableHeader noprint">Informations complémentaires</th></tr>', $return );
			}
			// Fin FIXME------------------------------------------------------

			return $return;
		}

		public function getFields( $key ) {
			// FIXME: utiliser la fonction adéquate
			$fields = Hash::normalize( (array)Configure::read( $key ) );

			foreach( $fields as $fieldName => $p ) {
				$p = (array) $p;
				if( !isset( $p['type'] ) ) {
					$fields[$fieldName]['type'] = $this->DefaultTableCell->DefaultData->type( $fieldName );
				}
				if( !isset( $p['label'] ) ) {
					$fields[$fieldName]['label'] = __m( $fieldName );
				}
			}

			return $fields;
		}

		public function tr( $index, array $data, array $fields, array $params = array() ) {
			$return = parent::tr( $index, $data, $fields, $params );
			$tableId = Hash::get( $params, 'id' );

			$innerTable = Hash::get( $params, 'innerTable' );
			if( !empty( $innerTable ) ) {
				$innerTable = $this->details(
					$data,
					$innerTable,
					array(
						'options' => (array)Hash::get( $params, 'options' ),
						'class' => 'innerTable',
						'id' => "innerTable{$tableId}{$index}",
						'th' => true
					)
				);

				$return = str_replace( '</tr>', "<td class=\"innerTableCell noprint\">{$innerTable}</td></tr>", $return );
			}

			return $return;
		}

		/**
		 *
		 * @param array $data
		 * @param array $fields
		 * @param array $params
		 * @return string
		 */
		public function index( array $data, array $fields, array $params = array() ) {
			$innerTable = Hash::get( $params, 'innerTable' );
			if( !empty( $innerTable ) ) {
				$params = $this->addClass( $params, 'tooltips' );
			}

			$return = parent::index( $data, $fields, $params );

			// FIXME: marche pas, #searchResults ?
			/*// FIXME: on perd de l'information
			if( !empty( $innerTable ) ) {
				$class = '';
				// FIXME: simplement ajouter la classe tooltips
				if( preg_match( '/^<table([^>]*)>/i', $return, $matches ) ) {
					debug( $matches );
					if( preg_match( '/class="([^"]*)"/', $matches[1], $submatches ) ) {
						debug( $submatches[1] );
					}
					else {

					}
				}
				$return = preg_replace( '/^<table([^>])*>/', '<table id="searchResults" class="tooltips">', $return );
			}*/

			return $return;
		}
	}
?>