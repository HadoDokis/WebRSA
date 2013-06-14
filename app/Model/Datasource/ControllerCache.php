<?php
	/**
	 * Code source de la classe ControllerCache.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model.Datasource
	 */
	App::uses( 'AppController', 'Controller' );

	/**
	 * La classe ControllerCache permet de connaître les actions des contrôleurs
	 * pour lesquelles il n'est pas nécessaire d'avoir de droit ("aucun droit")
	 * ou pour lesquelles les droits sont équivalents à ceux d'une autre action
	 * ("comme droit").
	 *
	 * Ces listes sont mises en cache.
	 *
	 * @package app.Model.Datasource
	 */
	class ControllerCache
	{
		/**
		 * Le nom de la clé sous laquelle le cache sera stocké.
		 *
		 * @var string
		 */
		protected static $_cacheKey = 'ControllerCache';

		/**
		 * Permet de savoir si la classe a déjà été initialisée.
		 *
		 * @var boolean
		 */
		protected static $_init = false;

		/**
		 * La liste des actions pour lesquelles aucun droit n'est requis (tout
		 * le monde a le droit).
		 *
		 * Exemple:
		 * <pre>
		 *	array(
		 *		'Users::login',
		 *	)
		 * </pre>
		 *
		 * @var array
		 */
		protected static $_aucunDroit = array( );

		/**
		 * La liste des correspondances "comme droit".
		 *
		 * Si on a:
		 * <pre>
		 *	array(
		 *		'Actions:add' => 'Actions:edit',
		 *	)
		 * </pre>
		 *
		 * @var array
		 */
		protected static $_commeDroit = array( );

		protected static $_crudMap = array( );

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
					$crudMap = array();

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

									if( array_key_exists( 'crudMap', $subClassVars ) and !empty( $subClassVars['crudMap'] ) ) {
										$crudMap["{$moduleAlias}"] = $subClassVars['crudMap'];
									}
								}
							}
						}
					}
					$cache = array(
						'aucunDroit' => $aucunDroit,
						'commeDroit' => $commeDroit,
						'crudMap' => $crudMap,
					);

					Cache::write( self::$_cacheKey, $cache );
				}

				self::$_aucunDroit = (array)$cache['aucunDroit'];
				self::$_commeDroit = (array)$cache['commeDroit'];
				self::$_crudMap = (array)$cache['crudMap'];

				self::$_init = true;
			}
		}

		/**
		 * Retourne la liste des actions pour lesquelles aucun droit n'est
		 * requis (tout le monde a le droit).
		 *
		 * Exemple:
		 * <pre>
		 *	array(
		 *		'Users::login',
		 *	)
		 * </pre>
		 *
		 * @return array
		 */
		public static function aucunDroit( $controllerName = null, $actionName = null ) {
			self::init();

			if( is_null( $controllerName ) ) {
				return self::$_aucunDroit;
			}

			return in_array( "{$controllerName}:{$actionName}", self::$_aucunDroit );
		}

		/**
		 * Retourne la liste des correspondances "comme droit".
		 *
		 * Exemple:
		 * <pre>
		 *	array(
		 *		'Actions:add' => 'Actions:edit',
		 *	)
		 * </pre>
		 *
		 * @return array
		 */
		public static function commeDroit( $controllerName = null, $actionName = null ) {
			self::init();

			if( is_null( $controllerName ) ) {
				return self::$_commeDroit;
			}

			return ( isset( self::$_commeDroit["{$controllerName}:{$actionName}"] ) ? self::$_commeDroit["{$controllerName}:{$actionName}"] : false );
		}

		/**
		 * @return array|string
		 */
		public static function crudMap( $controllerName = null, $actionName = null ) {
			self::init();

			if( is_null( $controllerName ) ) {
				return self::$_crudMap;
			}
			else if( is_null( $actionName ) ) {
				return (array)Hash::get( self::$_crudMap, $controllerName );
			}
			else {
				return Hash::get( self::$_crudMap, "{$controllerName}.{$actionName}" );
			}
		}
	}
?>