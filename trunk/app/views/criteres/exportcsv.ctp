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

    $csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire',  'N° Téléphone', 'Commune de l\'allocataire', 'Date d\'ouverture de droit', 'Etat du droit', 'Date de l\'orientation', 'Structure référente', 'Statut de l\'orientation', 'Soumis à droits et devoirs', 'Nature de la prestation' ) );
// debug($orients);
// die();
    foreach( $orients as $orient ) {
        $row = array(
            Set::extract( $orient, 'Dossier.numdemrsa' ),
            Set::extract( $orient, 'Personne.nom' ).' '.Set::extract( $orient, 'Personne.prenom'),
            Set::extract( $orient, 'Modecontact.numtel' ),
            Set::extract( $orient, 'Adresse.locaadr' ),
            date_short( Set::extract( $orient, 'Dossier.dtdemrsa' ) ),
            Set::extract( $etatdosrsa, Set::extract( $orient, 'Situationdossierrsa.etatdosrsa' ) ),
            date_short( Set::extract( $orient, 'Orientstruct.date_valid' ) ),
            value( $sr, Set::extract( $orient, 'Orientstruct.structurereferente_id' ) ),
            Set::extract( $orient, 'Orientstruct.statut_orient' ),
            ( Set::extract( $orient, 'Prestation.toppersdrodevorsa' ) ? 'Oui' : 'Non' ),
            Set::extract( $natpf, Set::extract( $orient, 'Detailcalculdroitrsa.natpf' ) )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'orientstructs-'.date( 'Ymd-Hhm' ).'.csv' );
?>