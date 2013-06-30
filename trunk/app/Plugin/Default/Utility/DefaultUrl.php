<?php
	/**
	 * Code source de la classe DefaultUrl.
	 *
	 * PHP 5.4
	 *
	 * @package Default
	 * @subpackage Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Router', 'Routing' );

	/**
	 * La classe DefaultUrl permet de faire aisément des conversions entre
	 * représentations d'URL.
	 *
	 * Il existe 3 représentations d'URL:
	 *	- un array (comme dans HtmlHelper::link())
	 *	- un objet CakeRequest
	 *  - une chaîne de caractères du type: /Plugin.Controllers/prefix_action/param1/named:value/#anchor
	 *
	 * @package Default
	 * @subpackage Utility
	 */
	class DefaultUrl
	{
		/**
		 *
		 * @param type $url
		 * @return string
		 */
		public static function toString( $url ) {
//			debug( Router::normalize( $url ) ); // '/admin/plugin/controllers/action/param1/named:value##Model.field#'
//			debug( Router::url( $url ) ); // '/magazine/admin/plugin/controllers/action/param1/named:value##Model.field#'

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
				$hash = ( isset( $parsed['named']['#'] ) ? "#{$parsed['named']['#']}" : null );
				unset( $parsed['named']['#'] ); // TODO: utiliser une méthode de la classe router ?
				$return .= '/'.str_replace( '=', ':', http_build_query( $parsed['named'], null, '/' ) ).$hash;
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