<?php
    $csv->preserveLeadingZerosInExcel = true;
// //     foreach( $identsflux as $identflux ){
//         $csv->addRow( array( 'MOIS TRAITEMENT', $identflux['Identificationflux']['dtcreaflux'] ) );
//     }

    foreach( $totsacoms as $totacom ) {
//         $csv->addRow( array( 'MOIS TRAITEMENT', $identsflux['Identificationflux']['dtcreaflux'] ) );
        $csv->addRow( array( $type_totalisation[$totacom['Totalisationacompte']['type_totalisation']] ) );
        $csv->addRow( array( 'RSA socle', $totacom['Totalisationacompte']['mttotsoclrsa'] ) );
        $csv->addRow( array( 'RSA socle majoré', $totacom['Totalisationacompte']['mttotsoclmajorsa'] ) );
        $csv->addRow( array( 'RSA local', $totacom['Totalisationacompte']['mttotlocalrsa'] ) );
        $csv->addRow( array( 'RSA socle total', $totacom['Totalisationacompte']['mttotrsa'] ) );
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'infosfinancieres-'.date( 'Ymd-Hhm' ).'.csv' );
?>