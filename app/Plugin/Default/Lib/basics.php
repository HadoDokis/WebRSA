<?php
	/**
	 * Librairie du plugin Default.
	 *
	 * PHP 5.4
	 *
	 * @package AclUtilities
	 * @subpackage Lib
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	if( !function_exists( 'model_field') ) {
		/**
		 * Extracts model name and field name from a path.
		 *
		 * @param string $path ie. User.username, User.0.id
		 * @return array( string $model, string $field ) ie. array( 'User', 'username' ), array( 'User', 'id' )
		 */
		function model_field( $path ) {
			if( preg_match( "/(?<!\w)(\w+)(\.|\.[0-9]+\.)(\w+)$/", $path, $matches ) ) {
				return array( $matches[1], $matches[3] );
			}

			trigger_error( "Could not extract model and field names from the following path: \"{$path}\"", E_USER_WARNING );
			return null;
		}
	}
?>
