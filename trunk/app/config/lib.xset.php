<?php
	class Xset extends Set
	{
		/**
		* @access public
		* @static
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
		* Retourne un array où les éléments de l'array d'entrée de type integer => valeur
		* sont remplacés par des éléments de type valeur => defaultValue.
		*
		* @param array $data Array d'entrée pouvant contenir des clés de type integer
		* @param mixed $defaultValue Valeur par défaut à assigner lorsqu'une valeur devient une clé
		* @return array Array dans laquelle il n'existe plus de clé de type integer
		* @access public
		* @static
		*/
		/// FIXME: Set::normalize ?
		/*static public function mkkeys( array $data, $defaultValue = array() ) {
			$newArray = array();

			if( !empty( $data ) ) {
				foreach( $data as $key => $value ) {
					if( is_int( $key ) ) {
						$newArray[$value] = $defaultValue;
					}
					else {
						$newArray[$key] = $value;
					}
				}
			}

			return $newArray;
		}*/

		/**
		* FIXME: docs -> contraire de flatten
		* @param array $data (eg. array( 'Foo__Bar' => 'value' ) )
		* @return array multisized array (eg. array( 'Foo' => array( 'Bar' => 'value' ) ) )
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
		* TODO: docs
		* TODO: tests unitaires
		* ...
		*
		* @param array $array
		* @param array $filterKeys
		* @return array $newArray
		*/

		function filterkeys( array $array, array $filterKeys, $remove = false ) { // FIXME ?
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
		* TODO: docs
		* TODO: tests unitaires
		* ...
		*
		* @param array $array
		* @param array $filterKeys
		* @return array $newArray
		*/

		function whitelist( array $array, array $filterKeys ) {
			return self::filterkeys( $array, $filterKeys, false );
		}

		/**
		* TODO: docs
		* TODO: tests unitaires
		* ...
		*
		* @param array $array
		* @param array $filterKeys
		* @return array $newArray
		*/

		function blacklist( array $array, array $filterKeys ) {
			return self::filterkeys( $array, $filterKeys, true );
		}

		/**
		* Vérifie si au moins une des valeurs des clés existe en tant que clé
		* dans le second paramètre
		* TODO: docs + tests
		*/

		function anykey( $needles, $haystack ) {
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
		* TODO: docs + tests
		*/

		/*function search( $subject, $pathPattern, $valuePattern = null ) {
			$paths = array();

			foreach( Xset::flatten( $subject ) as $path => $value ) {
				if( preg_match( $pathPattern, $path ) ) {
					if( func_num_args() == 2 || preg_match( $valuePattern, $value ) ) {
						$paths[] = $path;
					}
				}
			}

			return $paths;
		}*/

		/**
		* TODO: docs + tests
		* debug( Xset::inject( $this->modelClass, array_keys( $this->{$this->modelClass}->schema() ) ) );
		*/

		/*function inject( $string, array $array ) {
			$return = array();

			foreach( $array as $value ) {
				$return[] = "{$string}.{$value}";
			}

			return $return;
		}*/
	}
?>