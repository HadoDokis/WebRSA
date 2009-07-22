<?php

    $csv->addGrid( $dataPers );
    $csv->addGrid( $dataDos );
    $csv->addGrid( $dataAdr );
    $csv->addGrid( $dataOri );

    echo $csv->render( true );
?>