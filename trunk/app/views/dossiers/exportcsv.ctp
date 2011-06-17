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

    $csv->addRow( array( 'N° Dossier', 'Date de demande', 'NIR', 'Etat du droit', 'Nom/Prénom allocataire', 'Date de naissance', 'Commune de l\'allocataire', 'Type d\'orientation', 'Identifiant Pôle Emploi', 'N° CAF' ) );

    foreach( $dossiers as $dossier ) {
        $row = array(
            Set::extract( $dossier, 'Dossier.numdemrsa' ),
            date_short( Set::extract( $dossier, 'Dossier.dtdemrsa' ) ),
            Set::extract( $dossier, 'Personne.nir' ),
            value( $etatdosrsa, Set::extract( $dossier, 'Situationdossierrsa.etatdosrsa' ) ),
            Set::extract( $dossier, 'Personne.nom' ).' '.Set::extract( $dossier, 'Personne.prenom'),
            date_short( Set::extract( $dossier, 'Personne.dtnai' ) ),
            Set::extract( $dossier, 'Adresse.locaadr' ),
            Set::enum( Set::classicExtract( $dossier, 'Orientstruct.typeorient_id' ), $typesorient ),
            Set::extract( $dossier, 'Personne.idassedic' ),
            Set::extract( $dossier, 'Dossier.matricule' )
        );
        $csv->addRow($row);
    }
    Configure::write( 'debug', 0 );
    echo $csv->render( 'dossiers-'.date( 'Ymd-Hhm' ).'.csv' );
?>