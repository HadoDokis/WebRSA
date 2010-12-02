<?php
	class Xinflector extends Inflector
	{
		/**
		* Extracts model name and field name from a path.
		* @param string $path ie. User.username, User.0.id
		* @return array( string $model, string $field ) ie. array( 'User', 'username' ), array( 'User', 'id' )
		*/

		public function modelField( $path ) {
			if( preg_match( "/(?<!\w)(\w+)(\.|\.[0-9]+\.)(\w+)$/", $path, $matches ) ) {
				return array( $matches[1], $matches[3] );
			}

			trigger_error( "Could not extract model and field names from the following path: \"{$path}\"", E_USER_WARNING );
			return null;
		}

		/**
		* Concatenates model name and an array of field names to an array of paths.
		* @param string $model ie. 'User'
		* @param array $fields ie. array( 'id', 'username', .. )
		* @return array ie. array( 'User.id', 'User.username' )
		*/

		public function fieldNames( $model, array $fields ) {
			$return = array();
			foreach( $fields as $key => $field ) {
				$return[$key] = "{$model}.{$field}";
			}
			return $return;
		}
	}
?>