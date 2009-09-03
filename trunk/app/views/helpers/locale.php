<?php
    class LocaleHelper extends AppHelper
    {
        var $helpers = array( 'Time' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function date( $format, $date ) {
            //return h( ( empty( $date ) ) ? null : $this->Time->format( __( $format, true ), $date ) );
            return h( ( empty( $date ) ) ? null : strftime( __( $format, true ), strtotime( $date ) ) );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function money( $amount ) {
            return h( ( empty( $amount ) ) ? null : money_format( '%.2n', $amount ) );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function number( $number, $precision = 0 ) {
            return str_replace( ' ', '&nbsp;', h( ( empty( $number ) ) ? null : number_format( $number, $precision, ',', ' ' ) ) );
        }
    }
?>