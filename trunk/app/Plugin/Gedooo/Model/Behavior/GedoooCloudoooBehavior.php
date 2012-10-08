<?php
	/**
	 *
	 * PHP 5.3
	 *
	 * FIXME:
	 *	- une classe abstraite parente pour la méthode ged
	 *	- rendre la méthode toPdf publique ?
	 *	- @package
	 *
	 * @package       App.Model.Behavior
	 */
	App::import( 'Behavior', 'Gedooo.GedoooFusionConverter' );

	class GedoooCloudoooBehavior extends GedoooFusionConverterBehavior
	{
		/**
		 *
		 * @param string $fileName
		 * @param string $format
		 * @return string
		 */
		public function gedConversion( Model $model, $fileName, $format ) {
			// FIXME: http://pear.php.net/manual/en/package.webservices.xml-rpc.examples.php -> vérifier la présecence
			// pear upgrade
			// pear install xml_rpc / var_dump(class_exists('System', false));

			require_once 'XML/RPC.php'; // INFO: extension pear/pecl ?

			$content = base64_encode( file_get_contents( $fileName ) );
			$fileinfo = pathinfo( $fileName );
			if( $fileinfo['extension'] == 'pdf' )
				$fileinfo['extension'] = 'odt';

			$params = array(
				new XML_RPC_Value( $content, 'string' ),
				new XML_RPC_Value( $fileinfo['extension'], 'string' ),
				new XML_RPC_Value( $format, 'string' ),
				new XML_RPC_Value( false, 'boolean' ),
				new XML_RPC_Value( true, 'boolean' )
			);

			$url = Configure::read( 'Gedooo.cloudooo_host' ).':'.Configure::read( 'Gedooo.cloudooo_port' );

			$msg = new XML_RPC_Message( 'convertFile', $params );
			$cli = new XML_RPC_Client( '/', $url );
			$resp = $cli->send( $msg );
			// FIXME: PHP Notice:  Trying to get property of non-object in /home/cbuffin/www/webrsa/trunk/app/plugins/gedooo/models/behaviors/gedooo_cloudooo.php on line 42
			return (base64_decode( @$resp->xv->me['string'] ));
		}

		/**
		 * Retourne la liste des clés de configuration.
		 *
		 * @return array
		 */
		public function gedConfigureKeys( Model $model ) {
			return array_merge(
				parent::gedConfigureKeys( $model ),
				array(
					'Gedooo.cloudooo_host' => 'string',
					'Gedooo.cloudooo_port' => 'string'
				)
			);
		}

		/**
		 * @return array
		 */
		public function gedTests( Model $model ) {
			App::import( 'Model', 'Appchecks.Check' );
			$Check = ClassRegistry::init( 'Appchecks.Check' );

			$results = parent::gedTests( $model );
			$results['ping_cloudooo'] = $Check->socket( Configure::read( 'Gedooo.cloudooo_host' ), Configure::read( 'Gedooo.cloudooo_port' ) );

			return $results;
		}
	}
?>