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

    $csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire',  'Commune de l\'allocataire', 'Date de demande d\'APRE', 'Nature de l\'aide', 'Type de demande APRE', 'Activité du bénéficiaire' ) );

    foreach( $apres as $apre ) {

        $naturesaide = array_keys( Set::classicExtract( $apre, 'Natureaide' ) );
        foreach( $naturesaide as $key => $natureaide ) {
            $naturesaide[$key] = Set::classicExtract( $natureAidesApres, $natureaide );
        }

        $row = array(
            Set::classicExtract( $apre, 'Dossier.numdemrsa' ),
            Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom'),
            Set::classicExtract( $apre, 'Adresse.locaadr' ),
            date_short( Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ),
            implode( ', ', $naturesaide ),
            Set::classicExtract( $options['typedemandeapre'], Set::classicExtract( $apre, 'Apre.typedemandeapre' ) ),
            Set::classicExtract( $options['activitebeneficiaire'], Set::classicExtract( $apre, 'Apre.activitebeneficiaire' ) ),
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'apres-'.date( 'Ymd-Hhm' ).'.csv' );
?>