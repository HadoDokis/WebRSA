<?php

	if ( !empty($typepdo_id) && empty($user_id) )
		echo 'Etat du dossier : <strong>En attente d\'affectation</strong>';
		
	elseif ( !empty($typepdo_id) && !empty($user_id) && empty($iscomplet) )
		echo 'Etat du dossier : <strong>En attente d\'instruction</strong>';
		
	elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($iscomplet) && ( !isset($decisionpdo_id) || empty($decisionpdo_id) ) || ( !isset($isvalidation) || empty($isvalidation) ) )
		echo 'Etat du dossier : <strong>Instruction en cours</strong>';
		
	elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($iscomplet) && $iscomplet=='COM' && isset($decisionpdo_id) && !empty($decisionpdo_id) && isset($isvalidation) && !empty($isvalidation) )
		echo 'Etat du dossier : <strong>Dossier traité</strong>';
		
	elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($iscomplet) && $iscomplet=='INC' && isset($decisionpdo_id) && !empty($decisionpdo_id) && isset($isvalidation) && !empty($isvalidation) )
		echo 'Etat du dossier : <strong>En attente de pièce</strong>';
		
	else
		echo '';

	/*if ( !empty($typepdo_id) && empty($user_id) )
		echo 'Etat du dossier : <strong>En attente d\'affectation</strong>';
	elseif ( !empty($typepdo_id) && !empty($user_id) && empty($complet) && empty($incomplet) )
		echo 'Etat du dossier : <strong>En attente d\'instruction</strong>';
	elseif ( !empty($typepdo_id) && !empty($user_id) && ( !empty($complet) || !empty($incomplet) ) && empty($decisionpdo_id) )
		echo 'Etat du dossier : <strong>En attente de validation</strong>';
	elseif ( !empty($typepdo_id) && !empty($user_id) && ( !empty($complet) || !empty($incomplet) ) && !empty($decisionpdo_id) && empty($isvalidation) )
		echo 'Etat du dossier : <strong>Décision validée</strong>';
	elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($complet) && empty($incomplet) && !empty($decisionpdo_id) && !empty($isvalidation) )
		echo 'Etat du dossier : <strong>Dossier traité</strong>';
	elseif ( !empty($typepdo_id) && !empty($user_id) && empty($complet) && !empty($incomplet) && !empty($decisionpdo_id) && !empty($isvalidation) )
		echo 'Etat du dossier : <strong>En attente de pièce</strong>';
	else
		echo '';*/
	
	
    /*if( !empty( $typepdo_id ) && empty( $iscomplet ) && empty( $decisionpdo_id ) && empty( $isvalidation ) && empty( $isdecisionop ) ){
        echo 'Etat du dossier : <strong>En attente instruction</strong>';
    }
    elseif ( !empty( $typepdo_id ) && !empty( $iscomplet ) && empty( $decisionpdo_id ) && empty( $isvalidation ) && empty( $isdecisionop ) ){
        echo 'Etat du dossier : <strong>Instruction en cours</strong>';
    }
    elseif ( !empty( $typepdo_id ) && !empty( $iscomplet ) && !empty( $decisionpdo_id ) && empty( $isvalidation ) && empty( $isdecisionop ) ){
        echo 'Etat du dossier : <strong>En attente de validation</strong>';
    }
    elseif ( !empty( $typepdo_id ) && !empty( $iscomplet ) && !empty( $decisionpdo_id ) && ( $isvalidation == 'O' ) && empty( $isdecisionop ) ){
        echo 'Etat du dossier : <strong>Décision validée</strong>';
    }
    elseif ( !empty( $typepdo_id ) && ( $iscomplet == 'COM' ) && !empty( $decisionpdo_id ) && !empty( $isvalidation ) && !empty( $isdecisionop ) ){
        echo 'Etat du dossier : <strong>Dossier traité</strong>';
    }
    elseif ( !empty( $typepdo_id ) && ( $iscomplet == 'INC' ) && !empty( $decisionpdo_id ) && !empty( $isvalidation ) && !empty( $isdecisionop ) ){
        echo 'Etat du dossier : <strong>En attente de pièce</strong>';
    }*/

?>
