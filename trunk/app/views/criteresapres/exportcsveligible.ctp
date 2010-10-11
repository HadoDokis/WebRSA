<?php
    $csv->preserveLeadingZerosInExcel = true;

    $csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire',  'Commune de l\'allocataire', 'Date de demande d\'APRE', 'Eligibilité', 'Etat du dossier APRE', 'Date de relance', 'Date du comité examen' ) );

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
            $locale->date( 'Date::short', Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ),
            Set::enum( Set::classicExtract( $apre, 'Apre.eligibiliteapre' ), $options['eligibiliteapre'] ),
            Set::enum( Set::classicExtract( $apre, 'Apre.etatdossierapre' ), $options['etatdossierapre'] ),
            $locale->date( 'Date::short', Set::classicExtract( $apre, 'Relanceapre.daterelance' ) ),
            $locale->date( 'Date::short', Set::classicExtract( $apre, 'Comiteapre.datecomite' ) ),
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'apres-'.date( 'Ymd-Hhm' ).'.csv' );
?>