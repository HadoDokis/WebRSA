<?php
    $csv->preserveLeadingZerosInExcel = true;

//     function value( $array, $index ) {
//         $keys = array_keys( $array );
//         $index = ( ( $index == null ) ? '' : $index );
//         if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
//             return $array[$index];
//         }
//         else {
//             return null;
//         }
//     }

    $csv->addRow( array( 'Date du bilan de parcours', 'Nom de la personne', 'Type de structure', 'Nom du prescripteur', 'Type de commission', 'Position du bilan', 'Choix du parcours', 'Saisine EP' ) );

    foreach( $bilansparcours66 as $bilanparcours66 ) {
        $isSaisine = 'Non';
        if( isset( $bilanparcours66['Dossierep']['etapedossierep'] ) ){
            $isSaisine = 'Oui';
        }

        $motif = null;
        if (empty($bilanparcours66['Bilanparcours66']['choixparcours']) && !empty($bilanparcours66['Bilanparcours66']['examenaudition'])) {
            $motif = Set::classicExtract( $options['examenaudition'], $bilanparcours66['Bilanparcours66']['examenaudition'] );
        }
        elseif (empty($bilanparcours66['Bilanparcours66']['choixparcours']) && empty($bilanparcours66['Bilanparcours66']['examenaudition'])) {
            if ($bilanparcours66['Bilanparcours66']['maintienorientation']=='0') {
                $motif = 'Réorientation';
            }
            else {
                $motif = 'Maintien';
            }
        }
        else {
            $motif = Set::classicExtract( $options['choixparcours'], $bilanparcours66['Bilanparcours66']['choixparcours'] );
        }



        $row = array(
            $locale->date( 'Date::short', Set::classicExtract( $bilanparcours66, 'Bilanparcours66.datebilan' ) ),
            Set::classicExtract( $bilanparcours66, 'Personne.nom_complet' ),
            Set::classicExtract( $bilanparcours66, 'Structurereferente.lib_struc' ),
            Set::classicExtract( $bilanparcours66, 'Referent.nom_complet' ),
            Set::classicExtract( $options['proposition'], $bilanparcours66['Bilanparcours66']['proposition'] ),
            Set::enum( Set::classicExtract( $bilanparcours66, 'Bilanparcours66.positionbilan' ), $options['positionbilan'] ),
            $motif,
            $isSaisine
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'bilansparcours66-'.date( 'Ymd-Hhm' ).'.csv' );
?>