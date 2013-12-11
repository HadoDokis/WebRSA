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
			'Type d\'orientation',
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
		$lib_type_orient = Hash::get( $contrat, 'Typeorient.lib_type_orient' );

		$duree = Hash::get( $contrat, 'Cer93.duree' );
		if( empty( $duree ) ) {
			$duree = Set::enum( Hash::get( $contrat, 'Contratinsertion.duree_engag' ), $duree_engag );
		}
		else {
			$duree = "{$duree} mois";
		}

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
			( empty( $lib_type_orient ) ? 'Non orienté' : $lib_type_orient ),
			@$contrat['Referent']['nom_complet'],
			Hash::get( $contrat, 'Structurereferente.lib_struc' ),
			Set::enum( Hash::get( $contrat, 'Contratinsertion.num_contrat' ), $numcontrat['num_contrat'] ),
			date_short( Hash::get( $contrat, 'Contratinsertion.dd_ci' ) ),
			$duree,
			date_short( Hash::get( $contrat, 'Contratinsertion.df_ci' ) ),
			value( $decision_ci, Hash::get( $contrat, 'Contratinsertion.decision_ci' ) ).' '.date_short( Hash::get( $contrat, 'Contratinsertion.datevalidation_ci' ) ),
			Set::enum( Hash::get( $contrat, 'Contratinsertion.actions_prev' ), $action ),
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'contrats_engagement-'.date( 'Ymd-His' ).'.csv' );
?>