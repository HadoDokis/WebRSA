<?php
    class LocaleHelper extends AppHelper
    {
        var $helpers = array( 'Time' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function date( $format, $date ) {
            return h( ( empty( $date ) ) ? null : $this->Time->format( __( $format, true ), $date ) );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function money( $amount ) {
            return h( ( empty( $amount ) ) ? null : money_format( '%.2n', $amount ) );
        }
    }
?>