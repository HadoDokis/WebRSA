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

    $csv->addRow( array( 'N° Dossier', 'N° CAF', 'Nom/Prénom allocataire', 'Date d\'orientation', 'Date de relance', 'Statut de relance' ) );

    foreach( $orients as $orient ) {
        $row = array(
            Set::extract( $orient, 'Dossier.numdemrsa' ),
            Set::extract( $orient, 'Dossier.matricule' ),
            Set::extract( $orient, 'Personne.nom' ).' '.Set::extract( $orient, 'Personne.prenom'),
            $locale->date( 'Date::short', Set::extract( $orient, 'Orientstruct.date_valid' ) ),
            $locale->date( 'Date::short', Set::extract( $orient, 'Orientstruct.daterelance' ) ),
            value( $statutrelance, Set::extract( $orient, 'Orientstruct.statutrelance' ) )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'relances-'.date( 'Ymd-Hhm' ).'.csv' );
?>