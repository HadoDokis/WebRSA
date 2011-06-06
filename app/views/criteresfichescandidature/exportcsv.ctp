<?php
    $csv->preserveLeadingZerosInExcel = true;

    $csv->addRow( array( 'Date de signature de la fiche', 'Nom de la personne', 'N° CAF', 'Nom du prescripteur', 'Action engagée', 'Nom du partenaire', 'Position de la fiche de candidature' ) );

    foreach( $actionscandidatsPersonnes as $actioncandidat_personne ) {
        $row = array(
            $locale->date( 'Date::short', Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.datesignature' ) ),
            Set::classicExtract( $actioncandidat_personne, 'Personne.nom_complet' ),
            Set::classicExtract( $actioncandidat_personne, 'Dossier.matricule' ),
            Set::classicExtract( $actioncandidat_personne, 'Referent.nom_complet' ),
            Set::classicExtract( $actioncandidat_personne, 'Actioncandidat.name' ),
            Set::classicExtract( $actioncandidat_personne, 'Partenaire.libstruc' ),
            Set::enum( Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.positionfiche' ), $options['positionfiche'] )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'fiches-candidature'.date( 'Ymd-Hhm' ).'.csv' );
?>