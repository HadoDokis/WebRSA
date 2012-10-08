<?php
	/**
	 *
	 * PHP 5.3
	 *
	 *	- une classe abstraite parente pour la méthode ged
	 *	- rendre la méthode toPdf publique ?
	 *	- @package
	 *
	 * @package       App.Model.Behavior
	 */
	App::import( 'Behavior', 'Gedooo.GedoooFusionConverter' );

	class GedoooUnoconvBehavior extends GedoooFusionConverterBehavior
	{
		/**
		 *
		 * @param string $fileName
		 * @param string $format
		 * @return string
		 */
		public function gedConversion( &$model, $fileName, $format ) {
			// lecture fichier exécutable de unoconv
			$convertorExec = Configure::read( 'Gedooo.unoconv_bin' );
			if( empty( $convertorExec ) ) {
				return false;
			}
			// exécution
			$fileName = escapeshellarg( $fileName );
			$cmd = "LANG=fr_FR.UTF-8; $convertorExec -f {$format} --stdout {$fileName}";
			$result = shell_exec( $cmd );

			// guess that if there is less than this characters probably an error
			if( strlen( $result ) < 10 ) {
				return false;
			}
			else {
				return ($result);
			}
		}

		/**
		 * Retourne la liste des clés de configuration.
		 *
		 * @return array
		 */
		public function gedConfigureKeys( &$model ) {
			return array_merge(
				parent::gedConfigureKeys( $model ),
				array(
					'Gedooo.unoconv_bin' => 'string',
				)
			);
		}

		/**
		 * @return array
		 */
		public function gedTests( &$model ) {
			App::import( 'Model', 'Appchecks.Check' );
			$Check = ClassRegistry::init( 'Check' );

			$results = parent::gedTests( $model );

			return array_merge(
				$results,
				$Check->binaries( (array)Configure::read( 'Gedooo.unoconv_bin' ) )
			);
		}
	}
?>