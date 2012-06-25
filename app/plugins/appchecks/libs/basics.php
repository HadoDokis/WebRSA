<?php
	/**
	 * Retourne un nombre qui sera plus grand pour une version plus élevée.
	 *
	 * @see http://az.php.net/manual/en/function.phpversion.php (Exemple 2: PHP_VERSION_ID)
	 *
	 * @param string $version
	 * @return integer
	 */
	function version_id( $version ) {
		$version = explode( '.', $version );
		return ( @$version[0] * 10000 + @$version[1] * 100 + @$version[2] );
	}

	/**
	 *
	 * @param string $actual
	 * @param string $low
	 * @param string $high
	 * @return boolean
	 */
	function version_difference( $actual, $low, $high = null ) {
		$actual = version_id( $actual );
		$low = version_id( $low );
		$high = ( is_null( $high ) ? null : version_id( $high ) );

		$success = ( $actual >= $low );

		if( !is_null( $high ) ) {
			$success = ( $actual < $high );
		}

		return $success;
	}
?>
