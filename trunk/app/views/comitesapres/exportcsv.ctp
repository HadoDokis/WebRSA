<?php
    $csv->preserveLeadingZerosInExcel = true;

    $csv->addRow( array( 'Intitulé du comité', 'Lieu du comité', 'Date du comité',  'Heure du comité', 'Observations du comité' ) );

    foreach( $comitesapres as $comiteapre ) {

        $row = array(
            Set::classicExtract( $comiteapre, 'Comiteapre.intitulecomite' ),
            Set::classicExtract( $comiteapre, 'Comiteapre.lieucomite' ),
            $locale->date( 'Date::short', Set::classicExtract( $comiteapre, 'Comiteapre.datecomite' ) ),
            $locale->date( 'Time::short', Set::classicExtract( $comiteapre, 'Comiteapre.heurecomite' ) ),
            Set::classicExtract( $comiteapre, 'Comiteapre.observationcomite' ),
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'comitesapres-'.date( 'Ymd-Hhm' ).'.csv' );
?>