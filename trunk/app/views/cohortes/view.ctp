<?php
    $line = array('First Name', 'Last Name', 'Gender', 'City');
    $csv->addRow($line);

    $line = array('Adam', 'Royle', 'M', 'Brisbane');
    $csv->addRow($line);

    $line = array('Skrimpy', 'Bopimpy', 'M', 'North Sydney');
    $csv->addRow($line);

    $line = array('Sarah', 'Jincera"s', 'F', 'Melbourne');
    $csv->addRow($line);

    echo $csv->render( false );
?> 