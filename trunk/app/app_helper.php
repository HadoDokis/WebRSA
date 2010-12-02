<?php
	class AppHelper extends Helper
	{
		/**
		*
		*/

		protected function _cacheKey( $modelName ) {
			$thisClassName = Inflector::underscore( get_class( $this ) );
			$modelName = Inflector::tableize( $modelName );
			return "{$thisClassName}_{$modelName}";
		}
	}
?>