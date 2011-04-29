<?php
    $csv->preserveLeadingZerosInExcel = true;

    $csv->addRow( array( 'Date de l\'entretien', 'Nom/Prénom allocataire', 'N° CAF', 'Commune de l\'allocataire', 'Structure référente', 'Référent', 'Type d\'entretien', 'Objet de l\'entretien', 'A revoir le' ) );

    foreach( $entretiens as $entretien ) {
        $row = array(
            Set::classicExtract( $entretien, 'Entretien.dateentretien' ),
            Set::classicExtract( $entretien, 'Personne.nom' ).' '.Set::classicExtract( $entretien, 'Personne.prenom'),
            Set::classicExtract( $entretien, 'Dossier.matricule' ),
            Set::classicExtract( $entretien, 'Adresse.locaadr' ),
            Set::classicExtract( $entretien, 'Structurereferente.lib_struc' ),
            Set::classicExtract( $entretien, 'Referent.qual' ).' '.Set::classicExtract( $entretien, 'Referent.nom').' '.Set::classicExtract( $entretien, 'Referent.prenom'),
            Set::enum( Set::classicExtract( $entretien, 'Entretien.typeentretien' ), $options['typeentretien'] ),
            Set::classicExtract( $entretien, 'Objetentretien.name' ),
            $locale->date( "Date::miniLettre", Set::classicExtract( $entretien, 'Entretien.arevoirle' ) )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'entretiens-'.date( 'Ymd-Hhm' ).'.csv' );
?>