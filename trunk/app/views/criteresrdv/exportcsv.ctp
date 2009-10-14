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

    $csv->addRow( array( 'Nom/Prénom allocataire', 'Commune de l\'allocataire', 'Structure référente', 'Référent', 'Type de RDV', 'Statut du RDV', 'Date du RDV', 'Heure du RDV', 'Objet du RDV', 'Commentaire suite RDV' ) );

    foreach( $rdvs as $rdv ) {
        $row = array(
            Set::extract( $rdv, 'Personne.nom' ).' '.Set::extract( $rdv, 'Personne.prenom'),
            Set::extract( $rdv, 'Adresse.locaadr'  ),
            value( $struct, Set::extract( $rdv, 'Rendezvous.structurereferente_id' ) ),
            value( $referents, Set::extract( $rdv, 'Rendezvous.referent_id' ) ),
            value( $typerdv, Set::extract( $rdv, 'Rendezvous.typerdv_id' ) ),
            value( $statutrdv, Set::extract( $rdv, 'Rendezvous.statutrdv' ) ),
            date_short( $rdv['Rendezvous']['daterdv'] ),
            $rdv['Rendezvous']['heurerdv'],
            Set::extract( $rdv, 'Rendezvous.objetrdv' ),
            Set::extract( $rdv, 'Rendezvous.commentairerdv' ),
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'rendezvous-'.date( 'Ymd-Hhm' ).'.csv' );
?>