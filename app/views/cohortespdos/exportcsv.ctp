<?php
    $csv->preserveLeadingZerosInExcel = true;

    $csv->addRow( array( 'N° demande RSA', 'Date demande RSA', 'Nom/Prénom allocataire', 'Date de naissance', 'Commune', 'Type de PDO', 'Date de soumission PDO', 'Décision PDO', 'Motif PDO', 'Commentaires PDO', 'Gestionnaire' ) );

    foreach( $pdos as $pdo ) {
        $row = array(
            Set::classicExtract( $pdo, 'Dossier.numdemrsa' ),
            $locale->date( 'Date::short', Set::classicExtract( $pdo, 'Dossier.dtdemrsa' ) ),
            Set::classicExtract( $pdo, 'Personne.nom' ).' '.Set::classicExtract( $pdo, 'Personne.prenom'),
            $locale->date( 'Date::short', Set::classicExtract( $pdo, 'Personne.dtnai' ) ),
            Set::classicExtract( $pdo, 'Adresse.locaadr' ),
            Set::enum( Set::classicExtract( $pdo, 'Propopdo.typepdo_id' ), $typepdo ),
            $locale->date( 'Date::short', Set::classicExtract( $pdo, 'Propopdo.datedecisionpdo' ) ),
            Set::enum( Set::classicExtract( $pdo, 'Propopdo.decisionpdo_id' ), $decisionpdo ),
            Set::enum( Set::classicExtract( $pdo, 'Propopdo.motifpdo' ), $motifpdo ),
            Set::classicExtract( $pdo, 'Propopdo.commentairepdo' ),
            Set::classicExtract( $gestionnaire, Set::classicExtract( $pdo, 'Propopdo.user_id' ) )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'pdos-'.date( 'Ymd-Hhm' ).'.csv' );
?>