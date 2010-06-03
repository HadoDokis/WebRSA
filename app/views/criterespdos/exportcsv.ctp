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

    $csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire', 'N° CAF', 'Commune de l\'allocataire', 'Proposition de décision', 'Secteur', 'Date de proposition de décision' ) );

    foreach( $pdos as $pdo ) {

        $row = array(
            Set::classicExtract( $pdo, 'Dossier.numdemrsa' ),
            Set::classicExtract( $pdo, 'Personne.nom' ).' '.Set::classicExtract( $pdo, 'Personne.prenom'),
            Set::classicExtract( $pdo, 'Dossier.matricule' ),
            Set::classicExtract( $pdo, 'Adresse.locaadr' ),
//             value( $referents, Set::classicExtract( $pdo, 'PersonneReferent.referent_id' ) ),
//             value( $struct, Set::classicExtract( $pdo, 'Propopdo.structurereferente_id' ) ),
            Set::enum( Set::classicExtract( $pdo, 'Propopdo.decisionpdo_id' ), $decisionpdo ),
            Set::enum( Set::classicExtract( $pdo, 'Propopdo.secteur' ), $options['secteur'] ),
            $locale->date( 'Date::short', Set::classicExtract( $pdo, 'Propopdo.datedecisionpdo' ) )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'pdos-'.date( 'Ymd-Hhm' ).'.csv' );
?>