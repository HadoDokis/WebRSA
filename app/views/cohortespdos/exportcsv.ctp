<?php
    $csv->preserveLeadingZerosInExcel = true;

    foreach( $pdos as $pdo ) {
        $csv->addRow( array( $type_totalisation[$pdo['Totalisationacompte']['type_totalisation']] ) );
        $csv->addRow( array( 'RSA socle', $pdo['Totalisationacompte']['mttotsoclrsa'] ) );
        $csv->addRow( array( 'RSA socle majoré', $pdo['Totalisationacompte']['mttotsoclmajorsa'] ) );
        $csv->addRow( array( 'RSA local', $pdo['Totalisationacompte']['mttotlocalrsa'] ) );
        $csv->addRow( array( 'RSA socle total', $pdo['Totalisationacompte']['mttotrsa'] ) );
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'infosfinancieres-'.date( 'Ymd-Hhm' ).'.csv' );
?>