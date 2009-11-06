<?php
    $csv->preserveLeadingZerosInExcel = true;

    function value( $array, $index ) {
        $keys = array_keys( $array );
        $index = ( ( $index == null ) ? '' : $index );
        if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
            return $array[$index];
        }
        else {
            return null;
        }
    }

    $csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire',  'Commune de l\'allocataire', 'Date de demande d\'APRE', 'Type de demande APRE' ) );

    foreach( $apres as $apre ) {
        $row = array(
            Set::classicExtract( $apre, 'Dossier.numdemrsa' ),
            Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom'),
            Set::classicExtract( $apre, 'Adresse.locaadr' ),
            date_short( Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ),
            Set::classicExtract( $options['typedemandeapre'], Set::classicExtract( $apre, 'Apre.typedemandeapre' ) ),
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'apres-'.date( 'Ymd-Hhm' ).'.csv' );
?>