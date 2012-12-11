<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'N° Dossier', 'N° CAF',  'Nom/Prénom allocataire', 'Adresse de l\'allocataire', 'Référent', 'Service référent', 'Type de contrat', 'Date début contrat', 'Durée', 'Date fin contrat', 'Décision et date validation', 'Action prévue' ) );

	foreach( $contrats as $contrat ) {

		$adresse = Set::classicExtract( $contrat, 'Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $contrat, 'Adresse.typevoie' ), $typevoie ).' '.Set::classicExtract( $contrat, 'Adresse.nomvoie' ).' '.Set::classicExtract( $contrat, 'Adresse.compladr' ).' '.Set::classicExtract( $contrat, 'Adresse.codepos' ).' '.Set::classicExtract( $contrat, 'Adresse.locaadr' );

		$row = array(
			Set::classicExtract( $contrat, 'Dossier.numdemrsa' ),
			Set::classicExtract( $contrat, 'Dossier.matricule' ),
			Set::enum( Set::classicExtract( $contrat, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $contrat, 'Personne.nom' ).' '.Set::classicExtract( $contrat, 'Personne.prenom'),
			$adresse,
			value( $referents, Set::classicExtract( $contrat, 'PersonneReferent.referent_id' ) ),
			value( $struct, Set::classicExtract( $contrat, 'Contratinsertion.structurereferente_id' ) ),
			Set::enum( Set::classicExtract( $contrat, 'Contratinsertion.num_contrat' ), $numcontrat['num_contrat'] ),
			$this->Locale->date( 'Date::short', Set::classicExtract( $contrat, 'Contratinsertion.dd_ci' ) ),
			Set::enum( Set::classicExtract( $contrat, 'Contratinsertion.duree_engag' ), $duree_engag_cg93 ),
			$this->Locale->date( 'Date::short', Set::classicExtract( $contrat, 'Contratinsertion.df_ci' ) ),
			value( $decision_ci, Set::classicExtract( $contrat, 'Contratinsertion.decision_ci' ) ).' '.$this->Locale->date( 'Date::short', Set::classicExtract( $contrat, 'Contratinsertion.datevalidation_ci' ) ),
			Set::enum( Set::classicExtract( $contrat, 'Contratinsertion.actions_prev' ), $action ),
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'contrats_engagement-'.date( 'Ymd-His' ).'.csv' );
?>