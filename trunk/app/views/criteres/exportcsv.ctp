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

    $csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire', 'NIR', 'Date de naissance', 'N° CAF',  'N° Téléphone', 'Adresse allocataire', 'Complément adresse', 'Code postal', 'Commune de l\'allocataire', 'Date d\'ouverture de droit', 'Etat du droit', 'Date de l\'orientation', 'Structure référente', 'Statut de l\'orientation', 'Soumis à droits et devoirs', 'Nature de la prestation' ) );
// debug($orients);
// die();
    foreach( $orients as $orient ) {
        $row = array(
            Set::classicExtract( $orient, 'Dossier.numdemrsa' ),
            Set::classicExtract( $orient, 'Personne.nom' ).' '.Set::classicExtract( $orient, 'Personne.prenom'),
            Set::classicExtract( $orient, 'Personne.nir' ),
            date_short( Set::classicExtract( $orient, 'Personne.dtnai' ) ),
            Set::classicExtract( $orient, 'Dossier.matricule' ),
            Set::classicExtract( $orient, 'Modecontact.numtel' ),
            Set::classicExtract( $orient, 'Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $orient, 'Adresse.typevoie' ), $typevoie ).' '.Set::classicExtract( $orient, 'Adresse.nomvoie' ),
            Set::classicExtract( $orient, 'Adresse.complideadr' ).' '.Set::classicExtract( $orient, 'Adresse.compladr' ),
            Set::classicExtract( $orient, 'Adresse.codepos' ),
            Set::classicExtract( $orient, 'Adresse.locaadr' ),
            date_short( Set::classicExtract( $orient, 'Dossier.dtdemrsa' ) ),
            value( $etatdosrsa, Set::classicExtract( $orient, 'Situationdossierrsa.etatdosrsa' ) ),
            date_short( Set::classicExtract( $orient, 'Orientstruct.date_valid' ) ),
            Set::enum( Set::classicExtract( $orient, 'Orientstruct.structurereferente_id' ), $sr ),
            Set::classicExtract( $orient, 'Orientstruct.statut_orient' ),
            ( Set::classicExtract( $orient, 'Prestation.toppersdrodevorsa' ) ? 'Oui' : 'Non' ),
            Set::enum( Set::classicExtract( $orient, 'Detailcalculdroitrsa.natpf' ), $natpf )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'orientstructs-'.date( 'Ymd-Hhm' ).'.csv' );
?>