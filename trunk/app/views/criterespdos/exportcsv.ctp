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

    $csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire', 'N° CAF', 'Commune de l\'allocataire', 'Proposition de décision', 'Motif PDO', 'Date de proposition de décision', 'Gestionnaire' ) );

    foreach( $pdos as $pdo ) {

        $row = array(
            Set::classicExtract( $pdo, 'Dossier.numdemrsa' ),
            Set::classicExtract( $pdo, 'Personne.nom' ).' '.Set::classicExtract( $pdo, 'Personne.prenom'),
            Set::classicExtract( $pdo, 'Dossier.matricule' ),
            Set::classicExtract( $pdo, 'Adresse.locaadr' ),
//             value( $referents, Set::classicExtract( $pdo, 'PersonneReferent.referent_id' ) ),
//             value( $struct, Set::classicExtract( $pdo, 'Propopdo.structurereferente_id' ) ),
            Set::enum( Set::classicExtract( $pdo, 'Propopdo.decisionpdo_id' ), $decisionpdo ),
            Set::enum( Set::classicExtract( $pdo, 'Propopdo.motifpdo' ), $motifpdo ),
            $locale->date( 'Date::short', Set::classicExtract( $pdo, 'Propopdo.datedecisionpdo' ) ),
            Set::classicExtract( $gestionnaire, Set::classicExtract( $pdo, 'Propopdo.user_id' ) )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'pdos-'.date( 'Ymd-Hhm' ).'.csv' );
?>