<?php

    $csv->addGrid( $dataPers );
    $csv->addGrid( $dataDos );
    $csv->addGrid( $dataAdr );
    $csv->addGrid( $dataOri );

    $csv->addGrid( $dataToExport );
    echo $csv->render( true );
?>