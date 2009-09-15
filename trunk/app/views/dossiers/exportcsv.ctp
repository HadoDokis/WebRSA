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

    $csv->addRow( array( 'N° Dossier', 'Date de demande', 'NIR', 'Nom/Prénom allocataire',  'Commune de l\'allocataire' ) );

    foreach( $dossiers as $dossier ) {
        $row = array(
            Set::extract( $dossier, 'Dossier.numdemrsa' ),
            Set::extract( $dossier, 'Dossier.dtdemrsa' ),
            Set::extract( $dossier, 'Personne.nir' ),
            Set::extract( $dossier, 'Personne.nom' ).' '.Set::extract( $dossier, 'Personne.prenom'),
            Set::extract( $dossier, 'Adresse.locaadr' )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'dossiers-'.date( 'Ymd-Hhm' ).'.csv' );
?>