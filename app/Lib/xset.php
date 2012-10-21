<?php
	/**
	 * Code source de la classe Xset.
	 *
	 * PHP 5.3
	 *
	 * @package app.Lib
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Xset fournit des méthodes supplémentaires pour gérer les
	 * dictionnaires (associations nom => valeur(s)).
	 *
	 * @package app.Lib
	 */
	class Xset extends Set
	{
		/**
		 *
		 * @param array $data
		 * @return array
		 */
		static public function filterDeep( $data ) {
			if( !is_array( $data ) ) {
				return $data;
			}

			$data = self::flatten( $data );
			$data = self::filter( $data );
			return self::bump( $data );
		}

		/**
		 * Le contraire de Set::flatten().
		 *
		 * @param array $data (eg. array( 'Foo__Bar' => 'value' ) )
		 * @param string $separator (eg. '__' )
		 * @return array (eg. array( 'Foo' => array( 'Bar' => 'value' ) ) )
		 */
		static public function bump( $data, $separator = '.' ) {
			if( is_array( $data ) && !empty( $data ) ) {
				$newArray = array();
				foreach( $data as $key => $value ) {
					$newArray = Set::insert( $newArray, implode( '.', explode( $separator, $key ) ), $value );
				}
				return $newArray;
			}
			return $data;
		}

		/**
		 *
		 * @param array $array
		 * @param array $filterKeys
		 * @param boolean $remove
		 * @return array
		 */
		static public function filterkeys( array $array, array $filterKeys, $remove = false ) {
			$newArray = array();
			foreach( $array as $key => $value) {
				if( $remove && !in_array( $key, $filterKeys ) ) {
					$newArray[$key] = $value;
				}
				else if( !$remove && in_array( $key, $filterKeys ) ) {
					$newArray[$key] = $value;
				}
			}
			return $newArray;
		}

		/**
		 *
		 * @param array $array
		 * @param array $filterKeys
		 * @return array
		 */
		static public function whitelist( array $array, array $filterKeys ) {
			return self::filterkeys( $array, $filterKeys, false );
		}

		/**
		 *
		 * @param array $array
		 * @param array $filterKeys
		 * @return array
		 */
		static public function blacklist( array $array, array $filterKeys ) {
			return self::filterkeys( $array, $filterKeys, true );
		}

		/**
		 * Vérifie si au moins une des valeurs des clés existe en tant que clé
		 * dans le second paramètre.
		 *
		 * @param array $needles
		 * @param array $haystack
		 * @return boolean
		 */
		static public function anykey( $needles, $haystack ) {
			if( !is_array( $needles ) ) {
				$needles = array( $needles );
			}
			foreach( $needles as $needle ) {
				if( array_key_exists( $needle, $haystack ) ) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Remplace les chaînes vides par la valeur null.
		 *
		 * @param array $array
		 * @return array
		 */
		static public function nullify( array $array ) {
			$newArray = array();
			foreach( $array as $key => $value ) {
				if( ( is_string( $value ) && strlen( trim( $value ) ) == 0 ) || ( !is_string( $value ) && !is_bool( $value )  && !is_numeric( $value ) && empty( $value ) ) ) {
					$newArray[$key] = null;
				}
				else {
					$newArray[$key] = $value;
				}
			}
			return $newArray;
		}
	}
?>