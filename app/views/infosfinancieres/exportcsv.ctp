<?php
    $csv->preserveLeadingZerosInExcel = true;
    $csv->addRow( array( 'N° Dossier', 'N° CAF', 'Nom/prénom du bénéficiaire', 'Date de naissance du bénéficiaire', 'Type d\'allocation', 'Montant de l\'allocation', 'Date du mouvement comptable' ) );

    foreach( $infos as $info ) {
        $row = array(
            Set::extract( $info, 'Dossier.numdemrsa' ),
            Set::extract( $info, 'Dossier.matricule' ),
            implode(
                ' ',
                array(
                    Set::extract( $info, 'Personne.qual' ),
                    Set::extract( $info, 'Personne.nom' ),
                    Set::extract( $info, 'Personne.prenom' )
                )
            ),
            $locale->date( 'Date::short', Set::extract( $info, 'Personne.dtnai' ) ),
            $type_allocation[Set::extract( $info, 'Infofinanciere.type_allocation' )],
            str_replace( '.', ',', Set::extract( $info, 'Infofinanciere.mtmoucompta' ) ),
            $locale->date( 'Date::miniLettre', Set::extract( $info, 'Infofinanciere.moismoucompta' ) )
        );
        $csv->addRow( $row );
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'infosfinancieres-'.date( 'Ymd-Hhm' ).'.csv' );
?>