<?php
	/**
	 * Code source de la classe DefaultUtility.
	 *
	 * PHP 5.4
	 *
	 * @package Default
	 * @subpackage Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe DefaultUtility ...
	 *
	 * @package Default
	 * @subpackage Utility
	 */
	class DefaultUtility
	{
		/**
		 * Retourne la chaîne de caractères $string dont les occurences de
		 * #Model.champ# ont été remplacées par leur valeur extraite depuis $data.
		 *
		 * @param array $data
		 * @param string $string
		 */
		public static function evaluateString( array $data, $string ) {
			if( strpos( $string, '#' ) !== false ) {
				if( preg_match_all( '/(#[^#]+#)/', $string, $out ) ) {
					$tokens = $out[0];
					foreach( array_unique( $tokens ) as $token ) {
						$token = trim( $token, '#' );
						$string = str_replace( "#{$token}#", Hash::get( $data, $token ), $string );
					}
				}
			}

			return $string;
		}

		/**
		 * Retourne le paramètre $mixed dont les occurences de #Model.champ# ont
		 * été remplacées par leur valeur extraite depuis $data.
		 *
		 * @see Hash::get()
		 *
		 * @param array $data
		 * @param string|array $mixed
		 * @return string|array
		 */
		public static function evaluate( array $data, $mixed ) {
			if( is_array( $mixed ) ) {
				$array = array();
				if( !empty( $mixed ) ) {
					foreach( $mixed as $key => $value ) {
						$array[self::evaluateString( $data, $key )] = self::evaluate( $data, $value );
					}
				}
				return $array;
			}

			return self::evaluateString( $data, $mixed );
		}

		/**
		 *
		 * @todo Vérifier la prise en compte des liens dans les plugins.
		 *
		 * @param string $path
		 * @param array $htmlAttributes
		 * @param array $data
		 * @return array
		 */
		public static function linkParams( $path, array $htmlAttributes, array $data = array() ) {
			$tokens = explode( '/', $path );
			$plugin = null;
			if( strpos( $tokens[1], '.' ) !== false ) {
				$controllerTokens = explode( '.', $tokens[1] );
				$plugin = $controllerTokens[0];
				unset( $controllerTokens[0] );

				$tokens[1] = implode( '.', $controllerTokens );
			}

			$url = $explodedUrl = array(
				'plugin' => Inflector::underscore( $plugin ),
				'controller' => Inflector::underscore( $tokens[1] ),
				'action' => Inflector::underscore( $tokens[2] ),
			) + array_slice( $tokens, 3 );

			$controller = $url['controller'];
			$action = $url['action'];

			// Does action have a prefix ?
			if( strpos( $url['action'], '_' ) !== false ) {
				$actionTokens = explode( '_', $url['action'] );
				if( in_array( $actionTokens[0], (array)Configure::read( 'Routing.prefixes' ) ) ) {
					$url['prefix'] = $actionTokens[0];
					$url[$actionTokens[0]] = true;
					unset( $actionTokens[0] );
					$url['action'] = implode( '_', $actionTokens );
				}
			}

			if( !empty( $data ) ) {
				$url = self::evaluate( $data, $url );
			}

			// Traitement d'un hash dans les valeurs, ex: array( 0 => '6#content' ) => array( 0 => '6', #' => 'content' )
			foreach( $url as $key => $value ) {
				if( ( strpos( $value, '#' ) !== false ) && preg_match( '/^([^#]*)#(.*)$/', $value, $matches ) ) {
					$url[$key] = $matches[1];
					$url['#'] = $matches[2];
				}
			}

			$domain = ( isset( $htmlAttributes['domain'] ) ? $htmlAttributes['domain'] : Inflector::underscore( $controller ) );

			$msgid = '/'
				.implode(
					'.',
					Hash::filter(
						array(
							Inflector::camelize( $plugin ),
							Inflector::camelize( $controller )
						)
					)
				)
				."/{$action}";

			if( isset( $htmlAttributes['title'] ) && $htmlAttributes['title'] === true ) {
				$htmlAttributes['title'] = __d( $domain, $path );
			}
			if( isset( $htmlAttributes['confirm'] ) && $htmlAttributes['confirm'] === true ) {
				$htmlAttributes['confirm'] = __d( $domain, "{$path} ?" );
			}

			$htmlAttributes = self::evaluate( $data, $htmlAttributes );

			return array(
				__d( $domain, $msgid ),
				$url,
				$htmlAttributes
			);
		}
	}
?>