<?php

    $csv->addGrid( $dataPers ); // Affichage des données Personne
    $csv->addGrid( $dataDos ); // Affichage des données des Dossiers
    $csv->addGrid( $dataAlloc ); // affichage des données financieres nous intéressant
    $csv->addGrid( $dataToExport ); // Affichage de toutes les infosfinancieres

    echo $csv->render( true );
?>