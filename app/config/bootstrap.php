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

	// Branche de CakePHP que l'on utilise: 1.1, 1.2, 1.3
	define( 'CAKE_BRANCH', preg_replace( '/^([0-9]+\.[0-9]+)(?![0-9]).*$/', '\1', Configure::version() ) );

	define( 'REQUIRED_MARK', '<abbr class="required" title="Champ obligatoire">*</abbr>' );

	define( 'PHPGEDOOO_DIR', APP.'vendors'.DS.'phpgedooo'.DS );
	define( 'MODELESODT_DIR', PHPGEDOOO_DIR.'..'.DS.'modelesodt'.DS );
	define( 'GEDOOO_TEST_FILE', MODELESODT_DIR.'test_gedooo.odt' );

    // Messages en français
    App::import( 'l10n' );
    $this->L10n = new L10n();
    $this->L10n->get( 'fre' );

    // Dates, nombres, ... en français
    $locales = array( 'fr_FR', 'fr_FR@euro', 'fr', 'fr_FR.UTF8' );
    setlocale( LC_ALL, $locales );

    mb_internal_encoding( Configure::read( 'App.encoding' ) );
    mb_regex_encoding( Configure::read( 'App.encoding' ) );

	require_once( APP.DS.'vendors'.DS.'money_format.php' );

	// TODO: créer répertoire APP.'libs' + bouger les fichiers
	if( CAKE_BRANCH == '1.2' ) {
		// backport architecture et constantes 1.3
		define( 'APPLIBS', APP.'libs/' );
	}

    /** ************************************************************************
    *
    *** ***********************************************************************/

    require_once( CONFIGS.'webrsa.inc' );
    require_once( APPLIBS.'basics.php' );
    require_once( APPLIBS.'xset.php' );
    require_once( APPLIBS.'xinflector.php' );

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

	//require_once( CONFIGS.'app.enumerable.php' );
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

	// Un shell ? On va charger la classe parente
	if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL && file_exists( APP.'/app_shell.php' ) ) {
		require_once( APP.'/../cake/console/libs/shell.php' );
		require_once( APP.'/app_shell.php' );
	}

	// -------------------------------------------------------------------------
	// Ajout de répertoires pour les classes abstraites
	// -------------------------------------------------------------------------
	define( 'ABSTRACTMODELS', MODELS.'abstractclasses/' );
?>
