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

    $csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire', 'Suivi', 'Situation des droits', 'Date indus', /*'Allocation comptabilisée',*/ 'Montant initial de l\'indu', 'Montant transféré CG', 'Remise CG'/*, 'Annulation faible montant', 'Autres montants'*/ ) );

    foreach( $indus as $indu ) {
        $row = array(
            Set::extract( $indu, 'Dossier.numdemrsa' ),
            Set::extract( $indu, 'Personne.nom' ).' '.Set::extract( $indu, 'Personne.prenom'),
            Set::extract( $indu, 'Dossier.typeparte' ),
            value( $etatdosrsa, Set::extract( $indu, 'Situationdossierrsa.etatdosrsa' ) ),
            $locale->date( 'Date::miniLettre', $indu[0]['moismoucompta'] ),
//             $locale->money( $indu[0]['mt_allocation_comptabilisee'] ),
            $locale->money( $indu[0]['mt_indus_constate'] ),
            $locale->money( $indu[0]['mt_indus_transferes_c_g'] ),
            $locale->money( $indu[0]['mt_remises_indus'] ),
//             $locale->money( $indu[0]['mt_annulations_faible_montant'] ),
//             $locale->money( $indu[0]['mt_autre_annulation'] ),
        );
        $csv->addRow($row);
    }
// debug($indus);
// die();
    Configure::write( 'debug', 0 );
    echo $csv->render( 'indus-'.date( 'Ymd-Hhm' ).'.csv' );
?>