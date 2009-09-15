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

    $csv->addRow( array( 'Commune', 'Nom prenom', 'Date demande', 'Date ouverture de droit', 'Service instructeur', 'PréOrientation', 'Orientation', 'Structure', 'Décision', 'Date proposition', 'Date dernier CI' ) );

    foreach( $cohortes as $cohorte ) {
        $row = array(
            Set::extract( $cohorte, 'Adresse.locaadr' ),
            Set::extract( $cohorte, 'Personne.nom' ).' '.Set::extract( $cohorte, 'Personne.prenom'),
            Set::extract( $cohorte, 'Dossier.dtdemrsa' ),
            Set::extract( $cohorte, 'Dossier.dtdemrsa' ), ///FIXME
            value( $typeserins, Set::extract( $cohorte, 'Suiviinstruction.typeserins' ) ),
            value( $typesOrient, Set::extract( $cohorte, 'Orientstruct.propo_algo' ) ),
            value( $typesOrient, Set::extract( $cohorte, 'Orientstruct.typeorient_id' ) ),
            Set::extract( $cohorte, 'Structurereferente.lib_struc' ),
            Set::extract( $cohorte, 'Orientstruct.statut_orient' ),
            date_short( Set::extract( $cohorte, 'Orientstruct.date_propo' ) ),
            date_short( Set::extract( $cohorte, 'Contratinsertion.dd_ci' ) )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'cohortes-'.date( 'Ymd-Hhm' ).'.csv' );
?>