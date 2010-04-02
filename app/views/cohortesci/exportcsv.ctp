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

    $csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire', 'Commune de l\'allocataire', 'Référent', 'Service référent', 'Type de contrat', 'Date début contrat', 'Durée', 'Date fin contrat', 'Décision et date validation', 'Action prévue'  ) );

    foreach( $contrats as $contrat ) {

        $row = array(
            Set::extract( $contrat, 'Dossier.numdemrsa' ),
            Set::extract( $contrat, 'Personne.nom' ).' '.Set::extract( $contrat, 'Personne.prenom'),
            Set::extract( $contrat, 'Adresse.locaadr' ),
            value( $referents, Set::extract( $contrat, 'PersonneReferent.referent_id' ) ),
            value( $struct, Set::extract( $contrat, 'Contratinsertion.structurereferente_id' ) ),
            value( $tc, Set::extract( $contrat, 'Contratinsertion.typocontrat_id' ) ),
            $locale->date( 'Date::short', Set::extract( $contrat, 'Contratinsertion.dd_ci' ) ),
            Set::enum( Set::extract( $contrat, 'Contratinsertion.duree_engag' ), $duree_engag_cg93 ),
            $locale->date( 'Date::short', Set::extract( $contrat, 'Contratinsertion.df_ci' ) ),
            Set::extract( $decision_ci, Set::extract( $contrat, 'Contratinsertion.decision_ci' ) ).' '.$locale->date( 'Date::short', Set::extract( $contrat, 'Contratinsertion.datevalidation_ci' ) ),
            Set::enum( Set::classicExtract( $contrat, 'Contratinsertion.actions_prev' ), $action ),
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'contrats_valides-'.date( 'Ymd-Hhm' ).'.csv' );
?>