<?php
	/**
	 * Fonctions utilitaires de WebRSA.
	 *
	 * PHP 5.3
	 *
	 * @package app.Lib
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Retourne la version de CakePHP utilisée.
	 *
	 * @deprecated (pas / plus utilisée)
	 *
	 * @return string
	 */
	function core_version() {
		$versionData = explode( "\n", file_get_contents( ROOT.DS.'lib'.DS.'Cake'.DS.'VERSION.txt' ) );
		$version = explode( '.', $versionData[count( $versionData ) - 1] );
		return implode( '.', $version );
	}

	/**
	 * Retourne la version de WebRSA utilisée.
	 *
	 * @return string
	 */
	function app_version() {
		$versionData = explode( "\n", file_get_contents( ROOT.DS.'app'.DS.'VERSION.txt' ) );
		$version = explode( '.', $versionData[count( $versionData ) - 1] );
		return implode( '.', $version );
	}

	/**
	 * @deprecated (pas / plus utilisée)
	 */
	function __translate( $value ) {
		switch( gettype( $value ) ) {
			case 'NULL':
				$value = 'NULL';
				break;
			case 'boolean':
				$value = ( $value ? 'true' : 'false' );
				break;
			case 'integer':
			case 'double':
				$value = $value;
				break;
			case 'string':
				$value = "'$value'";
				break;
			default:
				trigger_error( gettype( $value ), E_USER_WARNING );
		}
		return $value;
	}

	/**
	 * @deprecated (pas / plus utilisée)
	 */
	function dump( $datas = false, $showHtml = false, $showFrom = true ) {
		if( Configure::read() > 0 ) {
			if( $showFrom ) {
				$calledFrom = debug_backtrace();
				echo '<strong>'.substr( str_replace( ROOT, '', $calledFrom[0]['file'] ), 1 ).'</strong>';
				echo ' (line <strong>'.$calledFrom[0]['line'].'</strong>)';
			}
			echo "\n<pre class=\"cake-debug\">\n";

			if( is_array( $datas ) ) {
				$datas = Hash::flatten( $datas, '_____' );

				foreach( $datas as $key => $value ) {
					$datas[$key] = __translate( $value );
				}

				$datas = Hash::expand( $datas, '_____' );
			}
			else {
				$datas = __translate( $datas );
			}

			$datas = print_r( $datas, true );

			if( $showHtml ) {
				$datas = str_replace( '<', '&lt;', str_replace( '>', '&gt;', $datas ) );
			}
			echo $datas."\n</pre>\n";
		}
	}

	/**
	 * Filtre le paramètre $array en fonction des clés contenues dans le paramètre
	 * $filterKeys.
	 *
	 * @param array $array
	 * @param array $filterKeys
	 * @param boolean $remove true pour ne garder les clés filtrées, false pour
	 *	les enlever.
	 * @return array
	 */
	function array_filter_keys( array $array, array $filterKeys, $remove = false ) {
		$newArray = array();
		foreach( $array as $key => $value ) {
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
	 * Effectue les remplacements contenus dans le paramètre $replacements (sous
	 * la forme clé => valeur, ce qui équivaut à pattern => remplacement ) des
	 * clés et des valeurs de array, de manière récursive, grâce à la fonction
	 * preg_replace.
	 *
	 * @param array $array
	 * @param array $replacements
	 * @return array
	 */
	function recursive_key_value_preg_replace( array $array, array $replacements ) {
		$newArray = array();
		foreach( $array as $key => $value ) {
			foreach( $replacements as $pattern => $replacement ) {
				$key = preg_replace( $pattern, $replacement, $key );
			}

			if( is_array( $value ) ) {
				$value = recursive_key_value_preg_replace( $value, $replacements );
			}
			else {
				foreach( $replacements as $pattern => $replacement ) {
					$value = preg_replace( $pattern, $replacement, $value );
				}
			}
			$newArray[$key] = $value;
		}
		return $newArray;
	}

	/**
	 *
	 * INFO: http://fr.php.net/manual/fr/function.strpos.php#49739
	 *
	 * @deprecated (pas / plus utilisée)
	 *
	 * @param type $pajar
	 * @param type $aguja
	 * @param type $offset
	 * @param int $count
	 * @return type
	 */
	function strallpos( $pajar, $aguja, $offset = 0, &$count = null ) {
		if( $offset > strlen( $pajar ) ) {
			trigger_error( "strallpos(): Offset not contained in string.", E_USER_WARNING );
		}
		$match = array();
		for( $count = 0; ( ( $pos = strpos( $pajar, $aguja, $offset ) ) !== false ); $count++ ) {
			$match[] = $pos;
			$offset = $pos + strlen( $aguja );
		}
		return $match;
	}

	/**
	 * Extrait le nom d'un modèle et d'un champ à partir d'un chemin.
	 *
	 * @param string $path ie. User.username, User.0.id
	 * @return array( string $model, string $field ) ie. array( 'User', 'username' ), array( 'User', 'id' )
	 */
	function model_field( $path, $throwError = true ) {
		if( preg_match( "/(?<!\w)(\w+)(\.|\.[0-9]+\.)(\w+)$/", $path, $matches ) ) {
			return array( $matches[1], $matches[3] );
		}

		if ( $throwError ) {
			trigger_error( "Could not extract model and field names from the following path: \"{$path}\"", E_USER_WARNING );
		}
		
		return null;
	}

	/**
	 * Retourne une représentation lisible par un être humain à partir d'un
	 * nombre d'octets.
	 *
	 * @see http://www.phpfront.com/php/Convert-Bytes-to-corresponding-size/
	 *
	 * @param integer $bytes
	 * @return string
	 */
	function byteSize( $bytes ) {
		$size = $bytes / 1024;
		if( $size < 1024 ) {
			$size = number_format( $size, 2 );
			$size .= ' KB';
		}
		else {
			if( $size / 1024 < 1024 ) {
				$size = number_format( $size / 1024, 2 );
				$size .= ' MB';
			}
			else if( $size / 1024 / 1024 < 1024 ) {
				$size = number_format( $size / 1024 / 1024, 2 );
				$size .= ' GB';
			}
		}
		return $size;
	}

	/**
	 * Vérifie que le paramètre passé soit bien un entier ou une chaîne de
	 * caractères représentant un entier.
	 *
	 * @param mixed $value
	 * @return boolean
	 */
	function valid_int( $value ) {
		return !(!is_numeric( $value ) || !( (int) $value == $value ) );
	}
	/**
	 * Vérifie que la valeur passée en paramètre corresponde bien à un array de
	 * date au format CakePHP (clés year, month, day uniquement, non vides).
	 *
	 * @param array $value
	 * @return boolean
	 */
	function valid_date( $value ) {
		return is_array( $value )
			&& count( $value ) === 3
			&& isset( $value['year'] ) && !empty( $value['year'] ) && valid_int( $value['year'] )
			&& isset( $value['month'] ) && !empty( $value['month'] ) && valid_int( $value['month'] )
			&& isset( $value['day'] ) && !empty( $value['day'] ) && valid_int( $value['day'] );
	}

	/**
	 * Retourne une date au format jj/mm/aaaa à partir d'une date au format SQL
	 * (ou autre).
	 *
	 * Voir la fonction strtotime() pour voir les formats acceptés en entrée.
	 *
	 * @param string $sqldate
	 * @return string
	 */
	function date_short( $sqldate ) {
		if( !empty( $sqldate ) ) {
			return strftime( '%d/%m/%Y', strtotime( $sqldate ) );
		}

		return null;
	}

	/**
	 *
	 * @param string $label
	 * @return string
	 */
	function required( $label ) {
		return h( $label ).' '.REQUIRED_MARK;
	}

	/**
	 * @deprecated (pas / plus utilisée)
	 */
	function cake_version() {
		$versionData = explode( "\n", file_get_contents( ROOT.DS.'cake'.DS.'VERSION.txt' ) );
		$version = explode( '.', $versionData[count( $versionData ) - 1] );
		return implode( '.', $version );
	}

	/**
	 * Retourne le nombre de secondes avant l'expiration de la session (basé sur
	 * la configuration du fichier app/Config/core.php).
	 *
	 * @return integer
	 */
	function readTimeout() {
		if( Configure::read( 'Session.save' ) == 'cake' ) {
			App::uses( 'CakeSession', 'Model/Datasource' );
			return ( CakeSession::$sessionTime - CakeSession::$time );
		}
		else {
			return ini_get( 'session.gc_maxlifetime' );
		}
	}

	/**
	 * Formate un timestamp (nombre entier de secondes) au format hh:mm:ss.
	 *
	 * @see http://snipplr.com/view.php?codeview&id=4688
	 *
	 * @param mixed $sec
	 * @param boolean $padHours
	 * @return string
	 */
	function sec2hms( $sec, $padHours = false ) {
		$hms = "";

		// there are 3600 seconds in an hour, so if we
		// divide total seconds by 3600 and throw away
		// the remainder, we've got the number of hours
		$hours = intval( intval( $sec ) / 3600 );

		// add to $hms, with a leading 0 if asked for
		$hms .= ($padHours) ? str_pad( $hours, 2, "0", STR_PAD_LEFT ).':' : $hours.':';

		// dividing the total seconds by 60 will give us
		// the number of minutes, but we're interested in
		// minutes past the hour: to get that, we need to
		// divide by 60 again and keep the remainder
		$minutes = intval( ($sec / 60) % 60 );

		// then add to $hms (with a leading 0 if needed)
		$hms .= str_pad( $minutes, 2, "0", STR_PAD_LEFT ).':';

		// seconds are simple - just divide the total
		// seconds by 60 and keep the remainder
		$seconds = intval( $sec % 60 );

		// add to $hms, again with a leading 0 if needed
		$hms .= str_pad( $seconds, 2, "0", STR_PAD_LEFT );

		return $hms;
	}

	/**
	 * Retourne la partie de $value qui précède la première occurence de $separator.
	 *
	 * @param string $value
	 * @param string $separator
	 * @return string
	 */
	function suffix( $value, $separator = '_' ) {
		$quoted_separator = preg_quote( $separator );
		$return = preg_replace( '/^(.*'.$quoted_separator.')([^'.$quoted_separator.']+)$/', '\2', $value );
		return ( $return != $separator ? $return : null );
	}

	/**
	 * Retourne la partie de $value qui suit la dernière occurence de $separator.
	 *
	 * @param string $value
	 * @param string $separator
	 * @return string
	 */
	function prefix( $value, $separator = '_' ) {
		$quoted_separator = preg_quote( $separator );
		$return = preg_replace( '/^([^'.$quoted_separator.']+)('.$quoted_separator.'.*)$/', '\1', $value );
		return ( $return != $separator ? $return : null );
	}

	/**
	 * Retourne la "profondeur" d'un tableau, c'est à dire le nombre maximum d'array
	 * imbriqués.
	 *
	 * @param array $array
	 * @return integer
	 */
	function array_depth( $array ) {
		$max_depth = 1;
		foreach( $array as $value ) {
			if( is_array( $value ) ) {
				$depth = array_depth( $value ) + 1;

				if( $depth > $max_depth ) {
					$max_depth = $depth;
				}
			}
		}
		return $max_depth;
	}

	/**
	 * Vérifie que pour un chemin donné, la valeur soit bien un tableau dont aucune
	 * des valeurs n'est vide.
	 *
	 * @param array $data
	 * @param string $key
	 * @return boolean
	 */
	function dateComplete( $data, $key ) {
		$dateComplete = Hash::get( $data, $key );
		if( !is_array( $dateComplete ) || empty( $dateComplete ) ) {
			return !empty( $dateComplete );
		}
		else {
			$empty = true;
			foreach( $dateComplete as $tmp ) {
				if( !empty( $tmp ) ) {
					$empty = false;
				}
			}
			return !$empty;
		}
	}

	/**
	 *
	 * @deprecated (pas / plus utilisée)
	 *
	 * @param string $outer_glue
	 * @param string $inner_glue
	 * @param array $array
	 * @param boolean $allowempty
	 * @return string
	 */
	function implode_assoc( $outer_glue, $inner_glue, $array, $allowempty = true ) {
		$ret = array();
		foreach( $array as $key => $value ) {
			if( !empty( $value ) || $allowempty ) {
				if( is_array( $value ) ) {
					$ret[] = $key.'[]'.$inner_glue.implode( $outer_glue.$key.'[]'.$inner_glue, $value );
				}
				else {
					$ret[] = $key.$inner_glue.$value;
				}
			}
		}
		return implode( $outer_glue, $ret );
	}

	/**
	 * Retourne la moyenne des éléments d'un tableau.
	 *
	 * @see http://fr2.php.net/manual/fr/function.array-sum.php#58441
	 *
	 * @param array $array
	 * @return boolean|float
	 */
	function array_avg( $array ) {
		$avg = 0;
		if( !is_array( $array ) || count( $array ) == 0 ) {
			return false;
		}

		return ( array_sum( $array ) / count( $array ) );
	}

	/**
	 * Retourne un tableau contenant un intervalle d'éléments, avec pour chacun
	 * d'entre eux, la même valeur en clé et en valeur.
	 *
	 * Pratique pour générer des listes d'années.
	 *
	 * @param integer $low
	 * @param integer $high
	 * @param integer $step
	 * @return array
	 */
	function array_range( $low, $high, $step = 1 ) {
		$return = array();
		foreach( range( $low, $high, $step ) as $value ) {
			$return[$value] = $value;
		}
		return $return;
	}

	/**
	 * Vérifie qu'au moins une des valeurs de $needle se trouve dans $haystack.
	 *
	 * @param array $needle
	 * @param array $haystack
	 * @return array
	 */
	function array_intersects( array $needle, array $haystack ) {
		$return = array();
		foreach( $needle as $n ) {
			if( in_array( $n, $haystack ) ) {
				$return[] = $n;
			}
		}
		return $return;
	}

	/**
	 * @deprecated (pas / plus utilisée)
	 *
	 * @param array $array
	 * @param array $filterValues
	 * @return array $newArray
	 */
	function array_filter_values( array $array, array $filterValues, $remove = false ) {
		$newArray = array();
		foreach( $array as $key => $value ) {
			if( $remove && !in_array( $value, $filterValues ) ) {
				$newArray[$key] = $value;
			}
			else if( !$remove && in_array( $value, $filterValues ) ) {
				$newArray[$key] = $value;
			}
		}
		return $newArray;
	}

	/**
	 * Classes manipulation.
	 *
	 * @deprecated (pas / plus utilisée)
	 *
	 * @see http://www.php.net/manual/en/function.get-class-methods.php#51795
	 *
	 * @param string|stdClass $class
	 * @return array
	 */
	function get_overriden_methods( $class ) {
		$rClass = new ReflectionClass( $class );
		$array = NULL;

		foreach( $rClass->getMethods() as $rMethod ) {
			try {
				// attempt to find method in parent class
				new ReflectionMethod( $rClass->getParentClass()->getName(), $rMethod->getName() );
				// check whether method is explicitly defined in this class
				if( $rMethod->getDeclaringClass()->getName() == $rClass->getName() ) {
					// if so, then it is overriden, so add to array
					$array[] .= $rMethod->getName();
				}
			}
			catch( exception $e ) { /* was not in parent class! */
			}
		}

		return $array;
	}

	/**
	 * @deprecated (pas / plus utilisée)
	 *
	 * @see http://www.php.net/manual/en/function.get-class-methods.php#43379
	 *
	 * @param stdClass $class
	 * @return type
	 */
	function get_this_class_methods( $class ) {
		$array1 = get_class_methods( $class );
		if( $parent_class = get_parent_class( $class ) ) {
			$array2 = get_class_methods( $parent_class );
			$array3 = array_diff( $array1, $array2 );
		}
		else {
			$array3 = $array1;
		}
		return($array3);
	}

	/**
	 * @deprecated (pas / plus utilisée)
	 *
	 * @param string $string
	 * @return boolean
	 */
	function is_behavior_name( $string ) {
		return ( strpos( $string, '_behavior' ) !== false );
	}

	/**
	 * @deprecated (pas / plus utilisée)
	 *
	 * @param string $string
	 * @return boolean
	 */
	function is_model_name( $string ) {
		return (!is_behavior_name( $string ) && ( $string != 'view' ) );
	}

	/**
	 * @deprecated (pas / plus utilisée)
	 *
	 * @return array
	 */
	function class_registry_models() {
		$keys = ClassRegistry::keys();
		return array_filter( $keys, 'is_model_name' );
	}

	/**
	 * @deprecated (pas / plus utilisée)
	 *
	 * @return integer
	 */
	function class_registry_models_count() {
		return count( class_registry_models() );
	}

	/**
	 * Vérifie qu'au moins une des valeurs des clés existe en tant que clé dans
	 * le second paramètre.
	 *
	 * @param string|array $keys
	 * @param array $search
	 * @return boolean
	 */
	function array_any_key_exists( $keys, $search ) {
		if( !is_array( $keys ) ) {
			$keys = array( $keys );
		}
		foreach( $keys as $key ) {
			if( array_key_exists( $key, $search ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @deprecated (pas / plus utilisée)
	 *
	 * @param array $array
	 * @return array
	 */
	function nullify_empty_values( array $array ) {
		$newArray = array();
		foreach( $array as $key => $value ) {
			if( ( is_string( $value ) && strlen( trim( $value ) ) == 0 ) || (!is_string( $value ) && empty( $value ) ) ) {
				$newArray[$key] = null;
			}
			else {
				$newArray[$key] = $value;
			}
		}
		return $newArray;
	}

	/**
	 * Remplace les caractères accentués par des caractères non accentués dans
	 * une chaîne de caractères.
	 *
	 * @info il faut utiliser les fonctions mb_internal_encoding et mb_regex_encoding
	 *	pour que le système sache quels encodages il traite, afin que le remplacement
	 *  d'accents se passe bien.
	 *
	 * @param string $string
	 * @return string
	 */
	function replace_accents( $string ) {
		$accents = array(
			'[ÂÀ]',
			'[âà]',
			'[Ç]',
			'[ç]',
			'[ÉÊÈË]',
			'[éêèë]',
			'[ÎÏ]',
			'[îï]',
			'[ÔÖ]',
			'[ôö]',
			'[ÛÙ]',
			'[ûù]'
		);

		$replace = array(
			'A',
			'a',
			'C',
			'c',
			'E',
			'e',
			'I',
			'i',
			'O',
			'o',
			'U',
			'u'
		);

		foreach( $accents as $key => $accent ) {
			$string = mb_ereg_replace( $accent, $replace[$key], $string );
		}

		return $string;
	}

	/**
	 * Remplace les caractères accentués par des caractères non accentués et met
	 * en majuscules dans une chaîne de caractères.
	 *
	 * @see replace_accents
	 *
	 * @param string $string
	 * @return string
	 */
	function noaccents_upper( $string ) {
		return strtoupper( replace_accents( $string ) );
	}

	/**
	 * Equivalent de la méthode AppHelper::domId()
	 *
	 * @param string $path
	 * @return string
	 */
	function domId( $path ) {
		return Inflector::camelize( str_replace( '.', '_', $path ) );
	}

	/**
	 * Retourne true pour un RIB bien formé, false pour un RIB mal formé.
	 *
	 * @see http://fr.wikipedia.org/wiki/Cl%C3%A9_RIB#Algorithme_de_calcul_qui_fonctionne_avec_des_entiers_32_bits
	 * @see http://ime-data.com/articles/banque.html
	 *
	 * @param string $etaban
	 * @param string $guiban
	 * @param string $numcomptban
	 * @param string $clerib
	 * @return boolean
	 */
	function validRib( $etaban, $guiban, $numcomptban, $clerib ) {
		$replacements = array(
			1 => array( 'A', 'J' ),
			2 => array( 'B', 'K', 'S' ),
			3 => array( 'C', 'L', 'T' ),
			4 => array( 'D', 'M', 'U' ),
			5 => array( 'E', 'N', 'V' ),
			6 => array( 'F', 'O', 'W' ),
			7 => array( 'G', 'P', 'X' ),
			8 => array( 'H', 'Q', 'Y' ),
			9 => array( 'I', 'R', 'Z' )
		);
		// 5, 5, 11, 2
		if( strlen( $etaban ) != 5 || strlen( $guiban ) != 5 || strlen( $numcomptban ) != 11 || strlen( $clerib ) != 2 ) {
			return false;
		}

		foreach( $replacements as $number => $letters ) {
			foreach( $letters as $letter ) {
				$numcomptban = str_replace( $letter, $number, $numcomptban );
			}
		}

		$numcomptban1 = substr( $numcomptban, 0, 6 );
		$numcomptban2 = substr( $numcomptban, 6, 5 );

		return ( (int) $clerib == ( 97 - ( (89 * $etaban + 15 * $guiban + 76 * $numcomptban1 + 3 * $numcomptban2 ) % 97 ) ) );
	}

	/**
	 * Calcule l'âge en années à partir de la date de naissance.
	 *
	 * @param string $date date de naissance au format yyyy-mm-dd
	 * @param string $today
	 * @return integer
	 */
	function age( $date, $today = null ) {
		if( empty( $date ) ) {
			return null;
		}

		list( $year, $month, $day ) = explode( '-', $date );
		if( is_null( $today ) ) {
			$today = time();
		}
		else {
			$today = strtotime( $today );
		}
		return date( 'Y', $today ) - $year + ( ( ( $month > date( 'm', $today ) ) || ( $month == date( 'm', $today ) && $day > date( 'd', $today ) ) ) ? -1 : 0 );
	}

	/**
	 * Calcule la clé d'un NIR sur 13 caractères.
	 *
	 * @param string $nir NIR sur 13 caractères
	 * @return string Clé du NIR, sur 2 caractères
	 */
	function cle_nir( $nir ) {
		$nir = strtoupper( $nir );

		if( !preg_match( '/^([0-9]+|[0-9]{5}2(A|B)[0-9]{6})$/', $nir ) ) {
			trigger_error( sprintf( __( 'Le NIR suivant n\'est pas composé que de chiffres: %s' ), $nir ), E_USER_WARNING );
		}

		if( strlen( $nir ) != 13 ) {
			trigger_error( sprintf( __( 'Le NIR suivant n\'est pas composé de 13 caractères: %s' ), $nir ), E_USER_WARNING );
		}

		$correction = 0;

		if( preg_match( '/^([0-9]{5}2)(A|B)([0-9]{6})/', $nir, $matches ) ) {
			if( $matches[2] == 'A' ) {
				$correction = 1000000;
			}
			else {
				$correction = 2000000;
			}
			$nir = preg_replace( '/(A|B)/', '0', $nir );
		}

		$modulo = bcmod( bcsub( $nir, $correction ), 97 );
		return str_pad( ( 97 - $modulo ), 2, '0', STR_PAD_LEFT );
	}

	/**
	 * Vérifie la validité d'un NIR sur 15 caractères
	 *
	 * @param string $nir NIR sur 15 caractères
	 * @return boolean
	 */
	function valid_nir( $nir ) {
		return preg_match(
				'/^(1|2|7|8)[0-9]{2}(0[1-9]|10|11|12|[2-9][0-9])((0[1-9]|[1-8][0-9]|9[0-5]|2A|2B)(00[1-9]|0[1-9][0-9]|[1-8][0-9][0-9]|9[0-8][0-9]|990)|(9[7-8][0-9])(0[1-9]|0[1-9]|[1-8][0-9]|90)|99(00[1-9]|0[1-9][0-9]|[1-8][0-9][0-9]|9[0-8][0-9]|990))(00[1-9]|0[1-9][0-9]|[1-9][0-9][0-9]|)(0[1-9]|[1-8][0-9]|9[0-7])$/i', $nir
		);
	}

	/**
	 * Remplace tous les "mots" se trouvant dans $replacement sous la forme
	 * avant => apres dans les clés et les valeurs de $subject, de manière récursive.
	 *
	 * Exemple:
	 * 	$subject = array( 'Foo.id' => array( 'Bar' => 1 ), 'Foobar' => array( 'Foo.bar = Bar.foo' ) );
	 * 	$replacement = array( 'Foo' => 'Baz' );
	 * 	Résultat: array( 'Baz.id' => array( 'Bar' => 1 ), 'Foobar' => array( 'Baz.bar = Bar.foo' ) );
	 *
	 * @param array $subject
	 * @param array $replacement
	 * @return array
	 */
	function array_words_replace( array $subject, array $replacement ) {
		$regexes = array();
		foreach( $replacement as $key => $value ) {
			$key = "/(?<!\.)(?<!\w)({$key})(?!\w)/";
			$regexes[$key] = $value;
		}
		return recursive_key_value_preg_replace( $subject, $regexes );
	}

	/**
	 * Retourne une chaine de caractère pour remplir un tableau associatif
	 * javascript à partir d'un tableau associatif PHP.
	 *
	 * @param string $array Tableau associatif
	 * @return string
	 */
	function php_associative_array_to_js( $array ) {
		$return = array();

		foreach( $array as $key => $value ) {
			if( is_array( $value ) ) {
				$value = php_associative_array_to_js( $value );
				$return[] = "\"{$key}\" : {$value}";
			}
			else {
				$return[] = "\"{$key}\" : \"{$value}\"";
			}
		}

		return "{ ".implode( ', ', $return )." }";
	}

	/**
	 * Retourne le chemin vers le binaire Apache2.
	 * Par défaut, retourne /usr/sbin/apache2, sinon retourne la valeur paramétrée
	 * dans le fichier app/Config/webrsa.inc, sous la clé 'apache_bin'.
	 *
	 * Exemple:
	 * <pre>Configure::write( 'apache_bin', '/usr/bin/apache2' );</pre>
	 * @return string
	 */
	function apache_bin() {
		$bin = Configure::read( 'apache_bin' );

		if( empty( $bin ) ) {
			$bin = '/usr/sbin/apache2';
		}

		return $bin;
	}

	/**
	 * Retourne le numéro de version Apache utilisé, que l'on soit en mode CGI
	 * ou mod_php (dans ce cas, on se sert de la fonction apache_get_version()).
	 *
	 * @return string
	 */
	function apache_version() {
		if( function_exists( 'apache_get_version' ) ) {
			$rawVersion = apache_get_version();
		}
		else {
			$rawVersion = 'Apache/0';
			$output = array();
			@exec( apache_bin().' -v', $output );
			if( !empty( $output ) ) {
				$rawVersion = $output[0];
			}
		}

		return preg_replace( '/^.*Apache\/([^ ]+) .*$/', '\1', $rawVersion );
	}

	/**
	 * Retourne la liste des modules chargés par Apache, que l'on soit en mode CGI
	 * ou mod_php (dans ce cas, on se sert de la fonction apache_get_modules()).
	 *
	 * @return array
	 */
	function apache_modules() {
		if( function_exists( 'apache_get_modules' ) ) {
			return apache_get_modules();
		}
		else {
			$return = array();
			$output = array();
			@exec( apache_bin().' -M', $output );
			if( !empty( $output ) ) {
				foreach( $output as $module ) {
					$return[] = 'mod_'.trim( preg_replace( '/^(.*)_module.*$/', '\1', $module ) );
				}
			}
			return $return;
		}
	}

	/**
	 * Retourne une date au format SQL (yyyy-mm-dd) à partir d'une date au format
	 * "tableau CakePHP" (contenant uniquement les clés year, month et day).
	 *
	 * @param array $date
	 * @return boolean|string Retourne false si le paramètre $date ne contient pas
	 *	uniquement les clés year, month et day.
	 */
	function date_cakephp_to_sql( array $date ) {
		if( valid_date( $date ) ) {
			return "{$date['year']}-{$date['month']}-{$date['day']}";
		}
		else {
			return false;
		}
	}

	/**
	 * Retourne une heure au format SQL (HH:ii:ss) à partir d'une date au format
	 * "tableau CakePHP" (contenant les clés hour, min, et parfois sec).
	 *
	 * @param array $date
	 * @return boolean|string Retourne false si le paramètre $time ne contient pas
	 *	les clés hour, min, et parfois sec.
	 */
	function time_cakephp_to_sql( array $time ) {
		$count = count( $time );

		if( $count == 2 && isset( $time['hour'] ) && isset( $time['min'] ) ) {
			$time['sec'] = '00';
			$count = count( $time );
		}

		if( ( count( $time ) == 3 ) && isset( $time['hour'] ) && isset( $time['min'] ) && isset( $time['sec'] ) ) {
			return "{$time['hour']}:{$time['min']}:{$time['sec']}";
		}
		else {
			return false;
		}
	}

	/**
	 * Retourne une date au format CakePHP (un array avec les clés year, month
	 * et day) à partir d'une date au format SQL (yyyy-mm-dd)
	 *
	 * @param string $date
	 * @return array
	 */
	function date_sql_to_cakephp( $date ) {
		$result = array( 'year' => null, 'month' => null, 'day' => null );

		$tokens = explode( '-', $date );
		if( count( $tokens ) === 3 ) {
			$result['year'] = $tokens[0];
			$result['month'] = $tokens[1];
			$result['day'] = $tokens[2];
		}

		return $result;
	}

	/**
	 * $a1 = array( 'foo', 'bar' );
	 * $a2 = array( 'foo', 'bar', 'baz' );
	 * $a3 = array( 'bar', 'baz' );
	 *
	 * debug( full_array_diff( $a1, $a1 ) ); // -> array()
	 * debug( full_array_diff( $a1, $a2 ) ); // -> array( 'baz' )
	 * debug( full_array_diff( $a1, $a3 ) ); // -> array( 'foo', 'baz' )
	 *
	 * @deprecated (pas / plus utilisée)
	 *
	 * @see http://fr.php.net/manual/en/function.array-diff.php#101613
	 */
	function full_array_diff( $left, $right ) {
		return array_diff( array_merge( $left, $right ), array_intersect( $left, $right ) );
	}

	/**
	 * Retourne la valeur du tableau $array à la position $index ou null si la
	 * position $index n'est pas présente dans le tableau $array.
	 *
	 * Fonction utilisée dans les vues à la place de Set::enum().
	 *
	 * @param array $array
	 * @param mixed $index
	 * @return mixed
	 */
	function value( $array, $index ) {
		$keys = array_keys( $array );
		$index = ( ( $index == null ) ? '' : $index );
		if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
			return $array[$index];
		}
		else {
			return null;
		}
	}

	/**
	 *
	 * Exemple:
	 * <pre>array_move_key( array( 0 => 'Test' ), 0, 2 )</pre>
	 * donne
	 * <pre>array( 2 => 'Test' )</pre>
	 *
	 * @deprecated (pas / plus utilisée)
	 *
	 * @param array $data
	 * @param integer $key_from
	 * @param integer $key_to
	 * @return array
	 */
	function array_move_key( $data, $key_from, $key_to ) {
		if( $key_from != $key_to ) {
			$data[$key_to] = $data[$key_from];
			unset( $data[$key_from] );
		}

		return $data;
	}

	/**
	 * Transforme un chaîne de caractères représentant une implosion de valeurs,
	 * chacune d'entre elle étant préfixée par le caractère "-" et étant séparée
	 * de la suivante par ""\n\r" en un tableau contenant chacune des valeurs non
	 * préfixée.
	 *
	 * Par exemple, un champ virtuel construit avec AppModel::vfListe().
	 *
	 * Exemple:
	 * <pre>vfListeToArray( '- CAF' ) </pre>
	 * donne
	 * <pre>array( 0 => 'CAF' )</pre>
	 *
	 * @param string $liste
	 * @param string $separator
	 * @return array
	 */
	function vfListeToArray( $liste, $separator = "\n\r-" ) {
		$liste = trim( $liste, '-' );
		return Hash::filter( explode( $separator, $liste ) );
	}

	/**
	 * Echapement des caractères spéciaux pour une utilisation de la chaîne en
	 * javascript:
	 *	- " devient \"
	 *	- \n devient \\n
	 *
	 * Exemple:
	 * <pre>
	 *      Bonjour Monsieur "Auzolat"
	 *      vous devez vous présenter ...</pre>
	 * donne
	 * <pre>Bonjour Monsieur \"Auzolat\" \\nvous devez vous présenter ...</pre>
	 * @param string $value
	 * @return string
	 */
	function js_escape( $value ) {
		$value = str_replace( '"', '\\"', $value );
		$value = str_replace( array( "\r\n", "\r", "\n" ), "\\n", $value );
		return $value;
	}

	/**
	 * Enlève la valeur de l'array autant de fois qu'elle s'y trouve.
	 *
	 * @param array $array
	 * @param mixed $value La valeur à rechercher
	 * @param boolean $strict Pour prendre les types en compte
	 * @param boolean $reorder true pour réordonner (via array_values)
	 * @return int Le nombre d'éléments enlevés à l'array
	 */
	function array_remove( array &$array, $value, $strict = false, $reorder = false ) {
		$removed = 0;

		$key = array_search( $value, $array, $strict );
		while( $key !== false ) {
			unset( $array[$key] );
			$removed++;
			$key = array_search( $value, $array, $strict );
		}

		if( $reorder ) {
			$array = array_values( $array );
		}

		return $removed;
	}

	/**
	 * Fonction utilitaire de trim (espaces, doubles quotes) pour une chaîne
	 * ou un array, de façon récursive.
	 *
	 * @param mixed $mixed
	 * @param string $charlist
	 * @return mixed
	 */
	function trim_mixed( $mixed, $charlist = " \t\n\r\0\x0B\"" ) {
		if( is_array( $mixed ) ) {
			foreach( $mixed as $key => $value ) {
				$mixed[$key] = trim_mixed( $value, $charlist );
			}
		}
		else {
			$mixed = trim( $mixed, $charlist );
		}

		return $mixed;
	}

	/**
	 * Permet de transformer une ligne de CSV en array, en faisant attention au
	 * délimiteur et au séparateur et en nettoyant les enregistrements (trim,
	 * transformation en valeur null des champs vides).
	 *
	 * @param string $line
	 * @param string $separator
	 * @param string $delimiter
	 * @return array
	 */
	function parse_csv_line( $line, $separator = ',', $delimiter = '"' ) {
		$return = array();

		$exploded = str_getcsv( $line, $separator, $delimiter );

		$return = trim_mixed( $exploded );
		foreach( $return as $i => $value ) {
			if( $value === '' ) {
				$return[$i] = null;
			}
		}

		return $return;
	}

	/**
	 * Retourne les chemins des clés de l'array pasé en paramètre jusqu'au niveau
	 * maximum.
	 *
	 * @param array $hash
	 * @param integer $max
	 * @param integer $current
	 * @return array
	 */
	function hash_keys( array $hash, $max = 2, $current = 1 ) {
		$result = array( );

		foreach( array_keys( $hash ) as $key ) {
			if( ( $current < $max ) && is_array( $hash[$key] ) ) {
				$result[$key] = Hash::normalize( hash_keys( $hash[$key], $max, $current + 1 ) );
			}
			else {
				$result[$key] = array( );
			}
		}

		return array_keys( Hash::flatten( $result ) );
	}

	/**
	 * Permet de savoir si un patron d'expression régulière est valide.
	 *
	 * @param string $pattern
	 * @return boolean
	 */
	function preg_test( $pattern ) {
		return ( @preg_match( $pattern, '' ) !== false );
	}


    $now   = time();
    $date2 = strtotime('2012-08-14 16:01:05');

    function dateDiff($date1, $date2){
        $diff = abs($date1 - $date2); // abs pour avoir la valeur absolute, ainsi éviter d'avoir une différence négative
        $retour = array();

        $tmp = $diff;
        $retour['second'] = $tmp % 60;

        $tmp = floor( ($tmp - $retour['second']) /60 );
        $retour['minute'] = $tmp % 60;

        $tmp = floor( ($tmp - $retour['minute'])/60 );
        $retour['hour'] = $tmp % 24;

        $tmp = floor( ($tmp - $retour['hour'])  /24 );
        $retour['day'] = $tmp;

        return $retour;
    }

	/**
	 * Permet de savoir si un nom de classe est utilisable par le département
	 * configuré, c'est à dire si le nom de classe se termine par le numéro du
	 * département ou ne se termine pas par deux ou trois chiffres.
	 *
	 * @see Configure Cg.departement
	 *
	 * @param string $className
	 * @return booelan
	 */
	function departement_uses_class( $className ) {
		$departement = Configure::read( 'Cg.departement' );
		return ( !preg_match( '/[0-9]{2,3}$/', $className ) || preg_match( '/'.$departement.'$/', $className ) );
	}

	/**
	 * Permet de dédoublonner les messages d'erreurs des règles de validation pour
	 * chacun des champs.
	 *
	 * @param array $validationErrors
	 * @return array
	 */
	function dedupe_validation_errors( array $validationErrors ) {
		foreach( $validationErrors as $fieldName => $errors ) {
			$found = array();

			foreach( $errors as $key => $error ) {
				if( !in_array( $error, $found ) ) {
					$found[] = $error;
				}
				else {
					unset( $validationErrors[$fieldName][$key] );
				}
			}
		}

		return $validationErrors;
	}

	/**
	 * Retourne un booléen permettant de savoir si l'on est en train d'effectuer
	 * des tests unitaires ou non, que l'on accède aux tests par l'URL ou la
	 * console.
	 *
	 * @return boolean
	 */
	function unittesting() {
		return (
			( strpos( $_SERVER['PHP_SELF'], 'test.php' ) !== false ) // URL
			|| ( isset( $_SERVER['SHELL'] ) && Hash::get( $_SERVER, 'argv.3' ) === 'test' ) // CLI
		);
	}
?>