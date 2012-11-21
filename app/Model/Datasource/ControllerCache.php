<?php
	/**
	 *
	 * PHP 5.3
	 *
	 * @package app.Model.Datasource
	 */
	App::uses( 'AppController', 'Controller' );

	/**
	 *
	 *
	 * @package app.Model.Datasource
	 */
	class ControllerCache
	{
		protected static $_cacheKey = 'ControllerCache';
		protected static $_init = false;
		protected static $_aucunDroit = array( );
		protected static $_commeDroit = array( );

		/**
		 * Initialisation, lecture de notre cache si nécessaire.
		 *
		 * @return void
		 */
		public static function init() {
			if( !self::$_init ) {
				$cache = Cache::read( self::$_cacheKey );

				if( $cache === false ) {
					$aucunDroit = array();
					$commeDroit = array();

					$controllers = App::objects( 'controller');
					if( !empty( $controllers ) ) {
						foreach( $controllers as $controller ) {
							if( !in_array( $controller, array( 'AppController', 'PagesController' ) ) ) {
								App::uses( $controller, 'Controller' );

								$moduleAlias = preg_replace( '/Controller$/', '', $controller );

								if( class_exists( $controller ) ) {
									$subClassVars = get_class_vars( $controller );

									if( array_key_exists( 'aucunDroit', $subClassVars ) and !empty( $subClassVars['aucunDroit'] ) ) {
										foreach( (array)$subClassVars['aucunDroit'] as $action ) {
											$aucunDroit[] = "{$moduleAlias}:{$action}";
										}
									}

									if( array_key_exists( 'commeDroit', $subClassVars ) and !empty( $subClassVars['commeDroit'] ) ) {
										foreach( (array)$subClassVars['commeDroit'] as $actionSlave => $master ) {
											$commeDroit["{$moduleAlias}:{$actionSlave}"] = $master;
										}
									}
								}
							}
						}
					}
					$cache = array( 'aucunDroit' => $aucunDroit, 'commeDroit' => $commeDroit );

					Cache::write( self::$_cacheKey, $cache );
				}

				self::$_aucunDroit = (array)$cache['aucunDroit'];
				self::$_commeDroit = (array)$cache['commeDroit'];

				self::$_init = true;
			}
		}

		/**
		 * @param array
		 */
		public static function aucunDroit() {
			self::init();

			return self::$_aucunDroit;
		}

		/**
		 * @param array
		 */
		public static function commeDroit() {
			self::init();

			return self::$_commeDroit;
		}
	}
?>