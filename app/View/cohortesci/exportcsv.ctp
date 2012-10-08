<?php
	$csv->preserveLeadingZerosInExcel = true;

	$csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire', 'Commune de l\'allocataire', 'Référent', 'Service référent', 'Type de contrat', 'Date début contrat', 'Durée', 'Date fin contrat', 'Décision et date validation', 'Action prévue'  ) );

	foreach( $contrats as $contrat ) {

		$row = array(
			Set::classicExtract( $contrat, 'Dossier.numdemrsa' ),
			Set::classicExtract( $contrat, 'Personne.nom' ).' '.Set::classicExtract( $contrat, 'Personne.prenom'),
			Set::classicExtract( $contrat, 'Adresse.locaadr' ),
			value( $referents, Set::classicExtract( $contrat, 'PersonneReferent.referent_id' ) ),
			value( $struct, Set::classicExtract( $contrat, 'Contratinsertion.structurereferente_id' ) ),
			Set::enum( Set::classicExtract( $contrat, 'Contratinsertion.num_contrat' ), $numcontrat['num_contrat'] ),
			$locale->date( 'Date::short', Set::classicExtract( $contrat, 'Contratinsertion.dd_ci' ) ),
			Set::enum( Set::extract( $contrat, 'Contratinsertion.duree_engag' ), $duree_engag_cg93 ),
			$locale->date( 'Date::short', Set::classicExtract( $contrat, 'Contratinsertion.df_ci' ) ),
			Set::classicExtract( $decision_ci, Set::classicExtract( $contrat, 'Contratinsertion.decision_ci' ) ).' '.$locale->date( 'Date::short', Set::classicExtract( $contrat, 'Contratinsertion.datevalidation_ci' ) ),
			Set::enum( Set::classicExtract( $contrat, 'Contratinsertion.actions_prev' ), $action ),
		);
		$csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $csv->render( 'contrats_valides-'.date( 'Ymd-Hhm' ).'.csv' );
?>