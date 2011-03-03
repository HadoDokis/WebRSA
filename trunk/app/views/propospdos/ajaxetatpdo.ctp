<?php

	if ( !empty($typepdo_id) && empty($user_id) )
		echo 'Etat du dossier : <strong>En attente d\'affectation</strong>';
		
	elseif ( !empty($typepdo_id) && !empty($user_id) && empty($iscomplet) )
		echo 'Etat du dossier : <strong>En attente d\'instruction</strong>';
		
	elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($iscomplet) && empty($decisionpdo_id)  )
		echo 'Etat du dossier : <strong>Instruction en cours</strong>';

    elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($iscomplet) && !empty($decisionpdo_id) && empty($avistechnique) )
        echo 'Etat du dossier : <strong>En attente d\'avis technique</strong>';

    elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($iscomplet) && !empty($decisionpdo_id) && !empty($avistechnique) && empty( $validationavis ) )
        echo 'Etat du dossier : <strong>En attente de validation de la proposition</strong>';

	elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($iscomplet) && !empty($decisionpdo_id) && !empty($avistechnique) && !empty($validationavis) && $iscomplet=='COM' )
		echo 'Etat du dossier : <strong>Dossier traité</strong>';
		
	elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($iscomplet) && !empty($decisionpdo_id) && !empty($avistechnique)  && !empty($validationavis)&& $iscomplet=='INC'  )
		echo 'Etat du dossier : <strong>Dossier traité mais en attente de pièce</strong>';
		
	else
		echo '';
// 		debug(  !empty($typepdo_id) && !empty($user_id) && empty($iscomplet) );
// 		debug( !empty($typepdo_id) && !empty($user_id) && !empty($iscomplet) && empty($decisionpdo_id) );
// debug(!empty($typepdo_id) && !empty($user_id) && !empty($iscomplet) && !empty($decisionpdo_id) && empty($isvalidation) );
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
