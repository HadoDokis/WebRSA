<?php
//     if( !empty( $typepdo ) ) {
//         echo 'Etat du dossier : <strong>En attente instruction</strong>';
//     }
//     else {
//         echo '';
//     }
// 
//     if( $decision == '1' ) {
//         echo 'Etat du dossier : <strong>En attente de validation</strong>';
//     }
//     else {
//         echo '';
//     }
// 
//     if( $suivi == '1' ) {
//         echo 'Etat du dossier : <strong>Décision validée</strong>';
//     }
//     else {
//         echo '';
//     }
// 
//     if( $autres == '1' ) {
//         echo 'Etat du dossier : <strong>Dossier traité ou En attente de pièce</strong>';
//     }
//     else {
//         echo '';
//     }

    if( !empty( $typepdo_id ) && empty( $iscomplet ) && empty( $decisionpdo_id ) && empty( $isvalidation ) && empty( $isdecisionop ) ){
        echo 'Etat du dossier : <strong>En attente instruction</strong>';
    }
    else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && empty( $decisionpdo_id ) && empty( $isvalidation ) && empty( $isdecisionop ) ){
        echo 'Etat du dossier : <strong>Instruction en cours</strong>';
    }
    else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && !empty( $decisionpdo_id ) && empty( $isvalidation ) && empty( $isdecisionop ) ){
        echo 'Etat du dossier : <strong>En attente de validation</strong>';
    }
    else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && empty( $decisionpdo_id ) && !empty( $isvalidation ) && empty( $isdecisionop ) ){
        echo 'Etat du dossier : <strong>Décision validée</strong>';
    }
    else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && !empty( $decisionpdo_id ) && !empty( $isvalidation ) && !empty( $isdecisionop ) ){
        echo 'Etat du dossier : <strong>Dossier traité ou En attente de pièce</strong>';
    }
?>