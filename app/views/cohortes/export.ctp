<?php
    $xls->addRow( $headers );

    foreach( $data as $row ) {
        $xls->addRow( array_values( $row ) );
    }
    echo $xls->render();
?>