<?php
	/**
	 * Fichier source de la classe AppHelper.
	 *
	 * PHP 5.3
	 *
	 * @package       app.View.Helper
	 */
	if( CAKE_BRANCH != '1.2' ) {
		App::uses( 'Helper', 'View' );
	}

	/**
	 * Class AppHelper
	 * La classe parente de tous les helpers.
	 *
	 * @package       app.View.Helper
	 */
	class AppHelper extends Helper
	{
		/**
		 * Retourne une clé de cache pour un nom de modèle donné.
		 *
		 * @param string $modelName
		 * @return string
		 */
		 protected function _cacheKey( $modelName ) {
			$thisClassName = Inflector::underscore( get_class( $this ) );
			$modelName = Inflector::tableize( $modelName );
			return "{$thisClassName}_{$modelName}";
		}
	}
?>