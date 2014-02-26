<?php
	/**
	 * Code source de la classe DefaultCsvHelper.
	 *
	 * PHP 5.3
	 *
	 * @package Default
	 * @subpackage View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe DefaultCsvHelper ...
	 *
	 * @package Default
	 * @subpackage View.Helper
	 */
	class DefaultCsvHelper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Csv',
			'Default.DefaultData',
		);

		/**
		 * Effectue le rendu CSV.
		 *
		 * @todo Méthode trop complexe
		 * @todo Type de champ...
		 *
		 * @param array $datas
		 * @param array $fields
		 * @param array $params
		 * @return string
		 */
		public function render( array $datas, array $fields, array $params = array() ) {
			if( empty( $fields ) ) {
				return null;
			}

			$default = array(
				'domain' => Inflector::underscore( $this->request->params['controller'] ),
				'options' => array(),
				'headers' => true,
				'filename' => sprintf( "%s-%s-%s.csv", $this->request->params['controller'], $this->request->params['action'], date( 'Ymd-His' ) ),
			);
			$params += $default;

			$this->Csv->preserveLeadingZerosInExcel = true;

			$fields = Hash::normalize( $fields );

			// Recherche des types de données
			$types = array();
			foreach( $fields as $path => $attributes ) {
				$types[$path] = ( isset( $attributes['type'] ) ? $attributes['type'] : $this->DefaultData->type( $path ) );
			}

			// En-têtes du tableau
			if( $params['headers'] ) {
				$row = array();

				foreach( $fields as $path => $attributes ) {
					$row[] = __d( $params['domain'], $path );
				}

				$this->Csv->addRow( $row );
			}

			// Corps du tableau
			if( !empty( $datas ) ) {
				foreach( $datas as $i => $data ) {
					$row = array();

					foreach( $fields as $path => $attributes ) {
						$value = Hash::get( $data, $path );

						$value = $this->DefaultData->format( $value, $types[$path] );

						if( $value !== null && Hash::check( $params, "options.{$path}.{$value}" ) ) {
							$value = Hash::get( $params, "options.{$path}.{$value}" );
						}

						$row[] = $value;
					}

					$this->Csv->addRow( $row );
				}
			}

			return $this->Csv->render( $params['filename'] );
		}
	}
?>