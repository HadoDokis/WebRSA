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
	App::uses('Router', 'Routing');

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
 			if( !empty( $data ) ) { // FIXME: il faudrait le faire après, mais ne pas se tromper avec les #content
				$url = self::toArray( self::evaluate( $data, $path ) );
			}
			else {
				$url = self::toArray( $path );
			}

			$controller = Hash::get( $url, 'controller' );
			$action = Hash::get( $url, 'action' );
			$plugin = Hash::get( $url, 'plugin' );
			$prefix = Hash::get( $url, 'prefix' );

			// -----------------------------------------------------------------

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
				.'/'.( !empty( $prefix ) ? "{$prefix}_" : null ).$action;

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

		/**
		 * Retourne une URL "canonique" à partir d'une URL sous forme de string ou
		 * d'array.
		 *
		 * Exemples d'URLs "canoniques":
		 * <pre>
		 * /Users/index
		 * /Test.Users/admin_index/666/Saerch__active:1
		 * </pre>
		 *
		 * @param mixed $url
		 * @return string
		 */
		public static function toString( $url ) {
			if( !is_array( $url ) ) {
				$parsed = Router::parse( $url );
			}
			else {
				$request = (array)Router::getRequest();

				$parsed = $url;
				$parsed['plugin'] = isset( $parsed['plugin'] ) ? $parsed['plugin'] : $request['params']['plugin'];
				$parsed['controller'] = isset( $parsed['controller'] ) ? $parsed['controller'] : $request['params']['controller'];
				if( isset( $request['params']['prefix'] ) ) {
					$parsed['prefix'] = isset( $parsed['prefix'] ) ? $parsed['prefix'] : $request['params']['prefix'];
				}
				$parsed['action'] = isset( $parsed['action'] ) ? $parsed['action'] : $request['params']['action'];

				// TODO: compléter les autres paramètres si besoin
				$named = array();
				$pass = array();
				foreach( $parsed as $key => $value ) {
					if( !is_string( $key ) || !in_array( $key, array( 'controller', 'action', 'prefix', 'plugin' ) ) ) {
						if( is_integer( $key ) ) {
							$pass[] = $value;
							unset( $parsed[$key] );
						}
						else if( !isset( $parsed['prefix'] ) || $parsed['prefix'] != $key ) {
							$named[$key] = $value;
							unset( $parsed[$key] );
						}
					}
				}
				$parsed['named'] = $named;
				$parsed['pass'] = $pass;
			}

			if( empty( $parsed ) ) {
				return $url;
			}

			$parsed['controller'] = Inflector::camelize( $parsed['controller'] );
			if( !is_null( $parsed['plugin'] ) ) {
				$parsed['controller'] = Inflector::camelize( $parsed['plugin'] ).'.'.$parsed['controller'];
			}
			if( isset( $parsed['prefix'] ) && !empty( $parsed['prefix'] ) && !empty( $parsed[$parsed['prefix']] ) ) {
				$parsed['action'] = $parsed['prefix'].'_'.$parsed['action'];
			}

			$return = "/{$parsed['controller']}/{$parsed['action']}";

			if( !empty( $parsed['pass'] ) ) {
				$return .= '/'.implode( '/', $parsed['pass'] );
			}

			if( !empty( $parsed['named'] ) ) {
				$return .= '/'.str_replace( '=', ':', http_build_query( $parsed['named'], null, '/' ) );
			}

			return $return;
		}

		/**
		 *
		 * @param string $url
		 */
		public static function toArray( $path ) {
			$tokens = explode( '/', $path );
			$plugin = null;

			if( strpos( $tokens[1], '.' ) !== false ) {
				$controllerTokens = explode( '.', $tokens[1] );
				$plugin = $controllerTokens[0];
				unset( $controllerTokens[0] );

				$tokens[1] = implode( '.', $controllerTokens );
			}

			if( strpos( $tokens[2], '#' ) !== false ) {
				if( preg_match( '/^([^#]*)#(#[^#]+#.*)$/', $tokens[2], $matches ) ) {
					$tokens[2] = $matches[1];
					$tokens[] = "#{$matches[2]}";
				}
				// TODO
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

			foreach( $url as $key => $value ) {
				// CakePHP named parameters
				if( is_numeric( $key ) && ( strpos( $value, ':' ) !== false ) && preg_match( '/^([^:]*):(.*)$/', $value, $matches ) ) {
					unset( $url[$key] );
					$url[$matches[1]] = $matches[2];
				}
				// Traitement d'un hash dans les valeurs, ex: array( 0 => '6#content' ) => array( 0 => '6', #' => 'content' )
				if( ( strpos( $value, '#' ) !== false ) && preg_match( '/^([^#]*)#([^#]*)$/', $value, $matches ) ) {
					$url[$key] = $matches[1];
					$url['#'] = $matches[2];
				}
				if( ( strpos( $value, '#' ) !== false ) && preg_match( '/^#(.*)$/', $value, $matches ) ) {
					unset( $url[$key] );
					$url['#'] = $matches[1];
				}
			}

			return $url;
		}
	}
?>