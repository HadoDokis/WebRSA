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
	App::uses( 'Router', 'Routing' );
	App::uses( 'DefaultUrl', 'Default.Utility' );

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
		 * @param string $path
		 * @param array $htmlAttributes
		 * @param array $data
		 * @return array
		 */
		public static function linkParams( $path, array $htmlAttributes, array $data = array() ) {
			$url = $path;
			// TODO: il faudrait le faire après, mais ne pas se tromper avec les #content
 			if( !empty( $data ) ) {
				$url = self::evaluate( $data, $url );
			}
			$url = DefaultUrl::toArray( $url );

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
	}
?>