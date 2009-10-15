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

    function valid_int( $value ) {
        return !( empty( $value ) || !is_numeric( $value ) || !( (int)$value == $value ) );
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

    function array_filter_keys( array $array, array $filterKeys, $remove = false ) { // FIXME ?
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

	function array_set_null_on_empty( array $array ) {
		$newArray = array();
		foreach( $array as $key => $value ) {
			if( empty( $value ) ) {
				$newArray[$key] = null;
			}
			else {
				$newArray[$key] = $value;
			}
		}
		return $newArray;
	}

    /** ************************************************************************
    *
    *** ***********************************************************************/

    require_once( 'webrsa.inc' );

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
//EOF
?>
