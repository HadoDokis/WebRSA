<?php
//     $csv->addRow( $headers );

//     foreach( $data as $row ){
//         $csv->addRow( array_values( $row ) );
//     }

    $csv->addGrid( $dataPers );
    $csv->addGrid( $dataDos );
    $csv->addGrid( $dataAdr );
    $csv->addGrid( $dataOri );
    echo $csv->render( true );
?>