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
            Set::classicExtract( $orient, 'Dossier.numdemrsa' ),
            Set::classicExtract( $orient, 'Personne.nom' ).' '.Set::classicExtract( $orient, 'Personne.prenom'),
            Set::classicExtract( $orient, 'Modecontact.numtel' ),
            Set::classicExtract( $orient, 'Adresse.locaadr' ),
            date_short( Set::classicExtract( $orient, 'Dossier.dtdemrsa' ) ),
            Set::classicExtract( $etatdosrsa, Set::classicExtract( $orient, 'Situationdossierrsa.etatdosrsa' ) ),
            date_short( Set::classicExtract( $orient, 'Orientstruct.date_valid' ) ),
            value( $sr, Set::classicExtract( $orient, 'Orientstruct.structurereferente_id' ) ),
            Set::classicExtract( $orient, 'Orientstruct.statut_orient' ),
            ( Set::classicExtract( $orient, 'Prestation.toppersdrodevorsa' ) ? 'Oui' : 'Non' ),
            value( $natpf, Set::classicExtract( $orient, 'Detailcalculdroitrsa.natpf' ) )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'orientstructs-'.date( 'Ymd-Hhm' ).'.csv' );
?>