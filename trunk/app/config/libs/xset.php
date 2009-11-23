<?php
	class Xset extends Set
	{
		/**
			@input  multisized array (eg. array( 'Foo' => array( 'Bar' => 'value' ) ) )
			@output unisized array (eg. array( 'Foo__Bar' => 'value' ) )
		*/
		function unisize( $array, $prefix = null ) {
			$newArray = array();
			if( is_array( $array ) && !empty( $array ) ) {
				foreach( $array as $key => $value ) {
					$newKey = ( !empty( $prefix ) ? $prefix.'__'.$key : $key );
					if( is_array( $value ) ) {
						$newArray = self::merge( $newArray, self::unisize( $value, $newKey ) );
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
		function multisize( array $array, $prefix = null ) {
			$newArray = array();
			if( is_array( $array ) && !empty( $array ) ) {
				foreach( $array as $key => $value ) {
					$newArray = Set::insert( $newArray, implode( '.', explode( '__', $key ) ), $value );
				}
			}
			return $newArray;
		}

		/**
		*	TODO: docs
		*	TODO: recursive
		*	TODO: corriger dans webrsa
		*	FIXME: trim ?
		*/

		function nullify( array $array ) {
			$newArray = array();
			foreach( $array as $key => $value ) {
				if( ( is_string( $value ) && strlen( trim( $value ) ) == 0 ) || ( !is_string( $value ) && !is_bool( $value )  && !is_numeric( $value ) && empty( $value ) ) ) {
					$newArray[$key] = null;
				}
				else {
					$newArray[$key] = $value;
				}
			}
			return $newArray;
		}
	}

	//************************************************************************************
	// FIXME: tests unitaires ?
	//************************************************************************************

// 	$a = array(
// 		'User' => array(
// 			'username' => 'cbuffin',
// 			'password' => '123456',
// 			'foo' => 4,
// 			'bar' => array(
// 				'baz' => array()
// 			)
// 		)
// 	);
//
// 	$b = array(
// 		'foo' => 'bar',
// 		'woot' => '',
// 		'waka' => array(),
// 		'baz' => false,
// 		'freu' => 0,
// 		'meu' => 0.0
// 	);
//
// 	dump( Xset::nullify( $a ) );
// 	debug( Xset::nullify( $a ) );
//
// 	dump( Xset::nullify( $b ) );
// 	debug( Xset::nullify( $b ) );
?>