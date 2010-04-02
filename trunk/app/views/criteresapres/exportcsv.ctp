<?php
    $csv->preserveLeadingZerosInExcel = true;

    $csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire',  'Commune de l\'allocataire', 'Date de demande d\'APRE', 'Nature de l\'aide', 'Type de demande APRE', 'Activité du bénéficiaire' ) );

    foreach( $apres as $apre ) {

        $aidesApre = array();
        $naturesaide = Set::classicExtract( $apre, 'Apre.Natureaide' );
        foreach( $naturesaide as $natureaide => $nombre ) {
            if( $nombre > 0 ) {
                $aidesApre[] = Set::classicExtract( $natureAidesApres, $natureaide );
            }
        }

        $row = array(
            Set::classicExtract( $apre, 'Dossier.numdemrsa' ),
            Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom'),
            Set::classicExtract( $apre, 'Adresse.locaadr' ),
            date_short( Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ),
            ( empty( $aidesApre ) ? null : implode( "\n", $aidesApre ) ),
            Set::classicExtract( $options['typedemandeapre'], Set::classicExtract( $apre, 'Apre.typedemandeapre' ) ),
            Set::classicExtract( $options['activitebeneficiaire'], Set::classicExtract( $apre, 'Apre.activitebeneficiaire' ) ),
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'apres-'.date( 'Ymd-Hhm' ).'.csv' );
?>