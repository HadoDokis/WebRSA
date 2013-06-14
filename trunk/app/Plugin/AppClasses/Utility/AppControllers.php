<?php
	// TODO: mettre en cache
	class AppControllers
	{
		protected static $_init = false;
		protected static $_attributesCache = array();
		protected static $_methodsCache = array();

		/**
		 *
		 * @url http://php.net/manual/en/language.oop5.magic.php
		 *
		 * @var array
		 */
		protected static $_magicMethods = array(
			'__construct',
			'__destruct',
			'__call',
			'__callStatic',
			'__get',
			'__set',
			'__isset',
			'__unset',
			'__sleep',
			'__wakeup()',
			'__toString()',
			'__invoke',
			'__set_state',
			'__clone',
		);

		public static function init() {
			if( !self::$_init ) {
				self::$_attributesCache = array();
				self::$_methodsCache = array();

				App::uses( 'AppController', 'Controller' );
				$rawAppControllerMethods = get_class_methods( 'AppController' );

				$controllers = App::objects( 'controller' );

				foreach( $controllers as $controller ) {
					if( !in_array( $controller, array( 'AppController', 'PagesController' ) ) ) {
						App::uses( $controller, 'Controller' );

						if( class_exists( $controller ) ) {
							// Attributes
							$vars = get_class_vars( $controller );
							$vars['className'] = $controller;
							self::$_attributesCache[$controller] = $vars;

							// Methods
							$rawMethods = get_class_methods( $controller );
							$rawMethods = array_diff( $rawMethods, $rawAppControllerMethods );

							$methods = array( 'public' => array(), 'protected' => array(), 'private' => array(), 'magic' => array() );
							foreach( $rawMethods as $rawMethod ) {
								// Visibility
								if( strpos( $rawMethod, '__' ) === 0 ) {
									if( in_array( $rawMethod, self::$_magicMethods ) ) {
										$methods['magic'][] = $rawMethod;
									}
									else {
										$methods['private'][] = $rawMethod;
									}
								}
								else if( strpos( $rawMethod, '_' ) === 0 ) {
									$methods['protected'][] = $rawMethod;
								}
								else {
									$methods['public'][] = $rawMethod;
								}
								// commeDroit, aucunDroit, crudMap ?
							}

							self::$_methodsCache[$controller] = $methods;
						}
					}
				}

				// TODO: en faire quelque chose
//				debug( self::$_methodsCache );
				self::$_init = true;
			}
		}

		protected static function _extract( $path ) {
			self::init();

			// INFO: Hash::combine() en 2.2.4 -> incohérences
			return Set::combine( self::$_attributesCache, '{s}.className', $path );
		}

		public static function commeDroit() {
			return self::_extract( '{s}.commeDroit' );
		}

		public static function aucunDroit() {
			return self::_extract( '{s}.aucunDroit' );
		}

		public static function crudMap() {
			return self::_extract( '{s}.crudMap' );
		}

		public static function isRead( $controllerName, $action ) {
			return ( Hash::get( self::$_attributesCache, "{$controllerName}.crudMap.{$action}" ) === 'read' );
		}

		public static function isWrite( $controllerName, $action ) {
			return ( Hash::get( self::$_attributesCache, "{$controllerName}.crudMap.{$action}" ) !== 'read' );
		}
	}

//	App::uses( 'AppControllers', 'AppClasses.Utility' );
//	AppControllers::init();
////	debug( Hash::filter( (array)AppControllers::commeDroit() ) );
////	debug( Hash::filter( (array)AppControllers::aucunDroit() ) );
////	debug( Hash::filter( (array)AppControllers::crudMap() ) );
//
//	debug( AppControllers::isRead( 'DossiersController', 'edit' ) );
//	debug( AppControllers::isWrite( 'DossiersController', 'edit' ) );
//
//
//	$url = array( 'controller' => 'dossiers', 'action' => 'edit', $id );
//	if( AppControllers::isRead( $controllerName, $action ) );
//	if( AppControllers::isWrite( $controllerName, $action ) );
//	AppControllers::aucunDroit( $url )
//	AppControllers::commeDroit( $url )
?>
