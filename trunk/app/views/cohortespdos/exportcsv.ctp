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

    $csv->addRow( array( 'Nom/Prénom allocataire', 'Suivi', 'Situation des droits', 'Type de PDO', 'Date de soumission PDO', 'Décision PDO', 'Commentaires PDO' ) );

    foreach( $pdos as $pdo ) {
        $row = array(

            Set::extract( $pdo, 'Personne.nom' ).' '.Set::extract( $pdo, 'Personne.prenom'),
            Set::extract( $pdo, 'Dossier.typeparte' ),
            value( $etatdosrsa, Set::extract( $pdo, 'Situationdossierrsa.etatdosrsa' ) ),
            value( $typepdo, Set::extract( $pdo, 'Propopdo.typepdo' ) ),
            date_short( Set::extract( $pdo, 'Propopdo.datedecisionpdo' ) ),
            value( $decisionpdo, Set::extract( $pdo, 'Propopdo.decisionpdo' ) ),
            Set::extract( $pdo, 'Propopdo.commentairepdo' )
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'pdos-'.date( 'Ymd-Hhm' ).'.csv' );
?>