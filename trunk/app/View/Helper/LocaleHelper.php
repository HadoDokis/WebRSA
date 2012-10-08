<?php
	class LocaleHelper extends AppHelper
	{
		/**
		*
		*/

		public function date( $format, $date ) {
			return h( ( empty( $date ) || !is_string( $date ) ) ? null : strftime( __( $format ), strtotime( $date ) ) );
		}

		/**
		*
		*/

		public function money( $amount ) {
			if( !is_numeric( $amount ) && !empty( $amount ) ) {
				trigger_error( __FUNCTION__." expects parameter 1 to be numeric", E_USER_WARNING );
				return null;
			}
			return h( ( !is_numeric( $amount ) && empty( $amount ) ) ? null : money_format( '%.2n', $amount ) );
		}

		/**
		*
		*/

		public function number( $number, $precision = 0 ) {
			if( !is_numeric( $number ) && !empty( $number ) ) {
				trigger_error( __FUNCTION__." expects parameter 1 to be numeric", E_USER_WARNING );
				return null;
			}
			if( !is_numeric( $precision ) && !empty( $precision ) ) {
				trigger_error( __FUNCTION__." expects parameter 2 to be numeric", E_USER_WARNING );
				return null;
			}
			return str_replace( ' ', '&nbsp;', h( ( !is_numeric( $number ) && empty( $number ) ) ? null : number_format( $number, $precision, ',', ' ' ) ) );
		}
	}
?>