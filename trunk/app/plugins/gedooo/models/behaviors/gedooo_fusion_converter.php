<?php
	/**
	 *
	 * PHP 5.3
	 *
	 * @package       app.models.behaviors
	 */
	App::import( 'Behavior', array( 'Gedooo.GedoooClassic' ) );

	abstract class GedoooFusionConverterBehavior extends GedoooClassicBehavior
	{
		/**
		 * Setup this behavior with the specified configuration settings.
		 *
		 * @param Model $Model Model using this behavior
		 * @param array $config Configuration settings for $model
		 * @return void
		 */
		public function setup( &$Model, $config = array() ) {
			Configure::WRITE( 'GEDOOO_WSDL', GEDOOO_WSDL );
		}

		/**
		 *
		 * @param AppModel $Model
		 * @param type $datas
		 * @param type $document
		 * @param type $section
		 * @param type $options
		 * @return type
		 */
		public function gedFusion( &$Model, $datas, $document, $section = false, $options = array() ) {
			return parent::ged( $Model, $datas, $document, $section, $options );
		}

		/**
		 * @param AppModel $Model
		 * @param string $fileName
		 * @param string $format
		 */
		abstract public function gedConversion( &$Model, $fileName, $format );

		/**
		 *
		 * @param AppModel $Model
		 * @param array $datas
		 * @param string $document
		 * @param boolean $section
		 * @param array $options
		 * @return string
		 */
		public function ged( &$Model, $datas, $document, $section = false, $options = array() ) {
			Configure::write( 'GEDOOO_WSDL', GEDOOO_WSDL ); // FIXME ?

			$odt = $this->gedFusion( $Model, $datas, $document, $section, $options );
			if( empty( $odt ) ) {
				return false;
			}

			$fileName = tempnam( TMP, $document );
			if( $fileName === false ) {
				return false;
			}
			chmod( $fileName, 0775 );

			$success = file_put_contents( $fileName, $odt );
			if( $success === false ) {
				return false;
			}

			$pdf = $this->gedConversion( $Model, $fileName, 'pdf' );

			$success = unlink( $fileName );
			if( $success === false || empty( $pdf ) ) {
				return false;
			}

			return $pdf;
		}

		/**
		 * Retourne la liste des clés de configuration.
		 *
		 * @return array
		 */
		public function gedConfigureKeys( &$Model ) {
			return array(
				'Gedooo.wsdl' => 'string',
			);
		}
	}
?>