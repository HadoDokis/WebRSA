<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
		array(
			'N° Dossier',
			'N° CAF',
			'Etat du droit',
			'Qualité',
			'Nom',
			'Prénom',
			'N° CAF',
			'Numéro de voie',
			'Type de voie',
			'Nom de voie',
			'Complément adresse 1',
			'Complément adresse 2',
			'Code postal',
			'Commune',
			'Référent',
			'Service référent',
			'Type de contrat',
			'Date début contrat',
			'Durée',
			'Date fin contrat',
			'Décision et date validation',
			'Action prévue'
		)
	);

	foreach( $contrats as $contrat ) {
		$row = array(
			Hash::get( $contrat, 'Dossier.numdemrsa' ),
			Hash::get( $contrat, 'Dossier.matricule' ),
			value( $etatdosrsa, Hash::get( $contrat, 'Situationdossierrsa.etatdosrsa' ) ),
			value( $qual, Hash::get( $contrat, 'Personne.qual' ) ),
			Hash::get( $contrat, 'Personne.nom' ),
			Hash::get( $contrat, 'Personne.prenom' ),
			Hash::get( $contrat, 'Dossier.matricule'  ),
			Hash::get( $contrat, 'Adresse.numvoie' ),
			value( $typevoie, Hash::get( $contrat, 'Adresse.typevoie' ) ),
			Hash::get( $contrat, 'Adresse.nomvoie' ),
			Hash::get( $contrat, 'Adresse.complideadr' ),
			Hash::get( $contrat, 'Adresse.compladr' ),
			Hash::get( $contrat, 'Adresse.codepos' ),
			Hash::get( $contrat, 'Adresse.locaadr' ),
			value( $referents, Hash::get( $contrat, 'PersonneReferent.referent_id' ) ),
			Hash::get( $contrat, 'Structurereferente.lib_struc' ),
			Set::enum( Hash::get( $contrat, 'Contratinsertion.num_contrat' ), $numcontrat['num_contrat'] ),
			date_short( Hash::get( $contrat, 'Contratinsertion.dd_ci' ) ),
			Set::enum( Hash::get( $contrat, 'Contratinsertion.duree_engag' ), $duree_engag_cg93 ),
			date_short( Hash::get( $contrat, 'Contratinsertion.df_ci' ) ),
			value( $decision_ci, Hash::get( $contrat, 'Contratinsertion.decision_ci' ) ).' '.date_short( Hash::get( $contrat, 'Contratinsertion.datevalidation_ci' ) ),
			Set::enum( Hash::get( $contrat, 'Contratinsertion.actions_prev' ), $action ),
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'contrats_engagement-'.date( 'Ymd-His' ).'.csv' );
?>