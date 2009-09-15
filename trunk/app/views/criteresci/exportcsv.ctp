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

    $csv->addRow( array( 'Nom/Prénom allocataire', 'Commune de l\'allocataire', 'Contrat envoyé par', 'N° CAF', 'Date de saisie du contrat', 'Rang contrat', 'Décision' ) );

    foreach( $contrats as $contrat ) {
        $row = array(
            Set::extract( $contrat, 'Personne.nom' ).' '.Set::extract( $contrat, 'Personne.prenom'),
            Set::extract( $contrat, 'Adresse.locaadr' ),
            Set::extract( $contrat, 'Contratinsertion.pers_charg_suivi' ),
            Set::extract( $contrat, 'Dossier.matricule' ),
            date_short( Set::extract( $contrat, 'Contratinsertion.date_saisi_ci' ) ),
            Set::extract( $contrat, 'Contratinsertion.rg_ci' ),
            value( $decision_ci, Set::extract( $contrat, 'Contratinsertion.decision_ci' ) )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'contrats_insertion-'.date( 'Ymd-Hhm' ).'.csv' );
?>