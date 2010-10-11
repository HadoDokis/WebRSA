<?php
/* SVN FILE: $Id: bootstrap.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 18:16:01 -0800 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */

    //@set_time_limit( 0 );
    @ini_set( 'memory_limit', '512M' );

    define( 'REQUIRED_MARK', '<abbr class="required" title="Champ obligatoire">*</abbr>' );

    // Messages en français
    App::import( 'l10n' );
    $this->L10n = new L10n();
    $this->L10n->get( 'fre' );

    // Dates, nombres, ... en français
    $locales = array( 'fr_FR', 'fr_FR@euro', 'fr', 'fr_FR.UTF8' );
    setlocale( LC_ALL, $locales );

    mb_internal_encoding( Configure::read( 'App.encoding' ) );
    mb_regex_encoding( Configure::read( 'App.encoding' ) );

    function valid_int( $value ) {
        return !( !is_numeric( $value ) || !( (int)$value == $value ) );
    }

    function date_short( $sqldate ) {
        if( !empty( $sqldate ) ) {
            return strftime( '%d/%m/%Y', strtotime( $sqldate ) );
        }
    }

    function required( $label ) {
        return h( $label ).' '.REQUIRED_MARK;
    }

    function cake_version() {
        $versionData = explode( "\n", file_get_contents( ROOT.DS.'cake'.DS.'VERSION.txt' ) );
        $version = explode( '.', $versionData[count( $versionData) - 1] );
        return implode( '.', $version );
    }

    /**
        Get real timeout (in seconds) based on core.php configuretion
    */
    function readTimeout() {
        return ini_get( 'session.gc_maxlifetime' );
        /*$timeout = Configure::read( 'Session.timeout' );
        switch( Configure::read( 'Security.level' ) ) {
            case 'high':    return ( $timeout * 10 );
            case 'medium':  return ( $timeout * 100 );
            case 'low':     return ( $timeout * 300 );
        }*/
    }

    function suffix( $value, $separator = '_' ) { // FIXME: preg_escape separator + erreur si plus d'un caractère
        return preg_replace( '/^(.*'.$separator.')([^'.$separator.']+)$/', '\2', $value );
    }

    function array_depth( $array ) {
        $max_depth = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = array_depth($value) + 1;

                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return $max_depth;
    }

    function dateComplete( $data, $key ) {
        $dateComplete = Set::extract( $data, $key );
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
        * ...
        *
        * @param array $array
        * @param array $filterKeys
        * @return array $newArray
    */

    /*function array_filter_keys( array $array, array $filterKeys, $remove = false ) { // FIXME ?
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
    }*/


    /**
        @input  multisized array (eg. array( 'Foo' => array( 'Bar' => 'value' ) ) )
        @output unisized array (eg. array( 'Foo__Bar' => 'value' ) )
    */
    function array_unisize( $array, $prefix = null ) {
        $newArray = array();
		if( is_array( $array ) && !empty( $array ) ) {
			foreach( $array as $key => $value ) {
				$newKey = ( !empty( $prefix ) ? $prefix.'__'.$key : $key );
				if( is_array( $value ) ) {
					$newArray = Set::merge( $newArray, array_unisize( $value, $newKey ) );
				}
				else {
					$newArray[$newKey] = $value;
				}
			}
		}
        return $newArray;
    }

    /**
        @output multisized array (eg. array( 'Foo' => array( 'Bar' => 'value' ) ) )
        @input  unisized array (eg. array( 'Foo__Bar' => 'value' ) )
    */
    function array_multisize( array $array, $prefix = null ) {
        $newArray = array();
		if( is_array( $array ) && !empty( $array ) ) {
			foreach( $array as $key => $value ) {
				$newArray = Set::insert( $newArray, implode( '.', explode( '__', $key ) ), $value );
			}
		}
        return $newArray;
    }

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
	* SOURCE: http://fr2.php.net/manual/fr/function.array-sum.php#58441
	*/

	function array_avg( $array ) {
		$avg = 0;
		if( !is_array( $array ) || count( $array ) == 0 ) {
			return false;
		}

		return ( array_sum( $array ) / count( $array ) );
	}

	function array_range( $low, $high, $step = 1 ) {
		$return = array();
		foreach( range( $low, $high, $step ) as $value ) {
			$return[$value] = $value;
		}
		return $return;
	}

    function array_intersects( array $needle, array $haystack ) {
        $return = array();
        foreach( $needle as $n ) {
            if( in_array( $n, $haystack ) ) {
                $return[] = $n;
            }
        }
        return $return;
    }

	/** ********************************************************************
	*	Vérifie si au moins une des valeurs des clés existe en tant que clé
	*	dans le second paramètre
	** ********************************************************************/

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

	/** ********************************************************************
	*
	** ********************************************************************/

	function nullify_empty_values( array $array ) {
		$newArray = array();
		foreach( $array as $key => $value ) {
			if( ( is_string( $value ) && strlen( trim( $value ) ) == 0 ) || ( !is_string( $value ) && empty( $value ) ) ) {
				$newArray[$key] = null;
			}
			else {
				$newArray[$key] = $value;
			}
		}
		return $newArray;
	}

    /** ************************************************************************
    *	Remplace les caractères accentués par des caractères non accentués
	*	dans une chaîne de caractères
	*	@param string $string chaîne de caractères à modifier
	*	@return string chaîne de caractères sans accents
	*	TODO: http://www.nabble.com/Accent-insensitive-search--td22583222.html
	*		(liste des accents)
    *** ***********************************************************************/

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
	* Retourne true pour un RIB bien formé, false pour un RIB mal formé
	* INFO: http://fr.wikipedia.org/wiki/Cl%C3%A9_RIB#Algorithme_de_calcul_qui_fonctionne_avec_des_entiers_32_bits
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

		foreach( $replacements as $number =>  $letters ) {
			foreach( $letters as $letter ) {
				$numcomptban = str_replace( $letter, $number, $numcomptban );
			}
		}

		$numcomptban1 = substr( $numcomptban, 0, 6 );
		$numcomptban2 = substr( $numcomptban, 6, 5 );

		return ( (int) $clerib == ( 97 - ( (89 * $etaban + 15 * $guiban + 76 * $numcomptban1 + 3 * $numcomptban2 ) % 97 ) ) );
	}

	/**
	* Calcul l'âge en années à partir de la date de naissance.
	* @param string $date date de naissance au format yyyy-mm-dd
	* @return integer âge en années
	*/

    function age( $date ) {
        list( $year, $month, $day ) = explode( '-', $date );
        $today = time();
        return date( 'Y', $today ) - $year + ( ( ( $month > date( 'm', $today ) ) || ( $month == date( 'm', $today ) && $day > date( 'd', $today ) ) ) ? -1 : 0 );
    }

    /** ************************************************************************
    *
    *** ***********************************************************************/

    require_once( 'webrsa.inc' );
    require_once( 'lib.basics.php' );
    require_once( 'lib.xset.php' );

    /** ************************************************************************
    *
    * Versioning
    *
    ** ************************************************************************/

    function core_version() {
        $versionData = explode( "\n", file_get_contents( ROOT.DS.'cake'.DS.'VERSION.txt' ) );
        $version = explode( '.', $versionData[count( $versionData) - 1] );
        return implode( '.', $version );
    }

    function app_version() {
        $versionData = explode( "\n", file_get_contents( ROOT.DS.'app'.DS.'VERSION.txt' ) );
        $version = explode( '.', $versionData[count( $versionData) - 1] );
        return implode( '.', $version );
    }

    /**
    * @param array $array
    * @param array $replacements
    * @return array
    */

    /*function recursive_key_value_preg_replace( array $array, array $replacements ) {
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
    }*/

    /**
    * INFO: http://fr.php.net/manual/fr/function.strpos.php#49739
    */

    /*function strallpos( $pajar, $aguja, $offset = 0, &$count = null ) {
        if( $offset > strlen( $pajar ) ) {
            trigger_error("strallpos(): Offset not contained in string.", E_USER_WARNING);
        }
        $match = array();
        for( $count = 0; ( ( $pos = strpos( $pajar, $aguja, $offset ) ) !== false ); $count++ ) {
            $match[] = $pos;
            $offset = $pos + strlen( $aguja );
        }
        return $match;
    }*/

	require_once( 'app.enumerable.php' );
	/// TODO: options par défaut pour dans les vues -> Configure::read( 'View.display.phone' );

	Configure::write( 'Typeable.phone', array( 'country' => 'fr', 'maxlength' => 14/*, 'rule' => 'phoneFr'*/ ) );
	Configure::write( 'Typeable.amount', array( 'currency' => 'EUR' ) );
	Configure::write( 'Typeable.date', array( 'dateFormat' => 'DMY' ) );
	Configure::write( 'Typeable.enum::presence',
		array(
			'type' => 'enum',
			'domain' => 'default',
			'options' => array( 'absent', 'present', 'excuse', 'remplace' )
		)
	);
	Configure::write( 'Typeable.email', array( 'rule' => 'email' ) );
//EOF
    /**
        * ...
        *
        * @param array $array
        * @param array $filterValues
        * @return array $newArray
    */

    function array_filter_values( array $array, array $filterValues, $remove = false ) { // FIXME ?
        $newArray = array();
        foreach( $array as $key => $value) {
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
	* Classes manipulation
	*/

	/// SOURCE: http://www.php.net/manual/en/function.get-class-methods.php#51795
	function get_overriden_methods($class) {
		$rClass = new ReflectionClass($class);
		$array = NULL;

		foreach ($rClass->getMethods() as $rMethod)
		{
			try
			{
				// attempt to find method in parent class
				new ReflectionMethod($rClass->getParentClass()->getName(),
									$rMethod->getName());
				// check whether method is explicitly defined in this class
				if ($rMethod->getDeclaringClass()->getName()
					== $rClass->getName())
				{
					// if so, then it is overriden, so add to array
					$array[] .=  $rMethod->getName();
				}
			}
			catch (exception $e)
			{    /* was not in parent class! */    }
		}

		return $array;
	}

	/// SOURCE: http://www.php.net/manual/en/function.get-class-methods.php#43379
	function get_this_class_methods($class){
		$array1 = get_class_methods($class);
		if($parent_class = get_parent_class($class)){
			$array2 = get_class_methods($parent_class);
			$array3 = array_diff($array1, $array2);
		}else{
			$array3 = $array1;
		}
		return($array3);
	}

	// Un shell ? On va charger la classe parente
	if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL && file_exists( APP.'/app_shell.php' ) ) {
		require_once( APP.'/../cake/console/libs/shell.php' );
		require_once( APP.'/app_shell.php' );
	}

	// Branche de CakePHP que l'on utilise: 1.1, 1.2, 1.3
	define( 'CAKE_BRANCH', preg_replace( '/^([0-9]+\.[0-9]+)(?![0-9]).*$/', '\1', Configure::version() ) );

	// -------------------------------------------------------------------------

	function is_behavior_name( $string ) {
		return ( strpos( $string, '_behavior' ) !== false );
	}

	function is_model_name( $string ) {
		return ( !is_behavior_name( $string ) && ( $string != 'view' ) );
	}

	function class_registry_models() {
		$keys = ClassRegistry::keys();
		return array_filter( $keys, 'is_model_name' );
	}

	function class_registry_models_count() {
		return count( class_registry_models() );
	}

	// -------------------------------------------------------------------------
?>
