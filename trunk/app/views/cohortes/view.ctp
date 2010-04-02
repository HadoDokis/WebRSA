<?php
    $csv->addRow( $headers );

    foreach( $data as $row ){
        $csv->addRow( array_values( $row ) );
    }
    echo $csv->render();
?>