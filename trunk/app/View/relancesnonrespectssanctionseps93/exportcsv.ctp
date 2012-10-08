<?php
	$csv->preserveLeadingZerosInExcel = true;

	$csv->addRow(
		array(
			__d( 'dossier', 'Dossier.matricule', true ),
			'Nom / prénom bénéficiaire',
			__d( 'personne', 'Personne.nir', true ),
			'Ville',
			 __d( 'foyer', 'Foyer.enerreur', true ),
			'Présence contrat ?',
			'Date de fin du dernier contrat',
			'Nbre jours depuis la fin du dernier contrat',
			'Date d\'orientation',
			'Nbre jours depuis orientation',
			'Statut EP',
			'Date de relance',
			'Rang de relance'
		)
	);

	foreach( $relances as $relance ) {
		$etatdossierep = $relance['Passagecommissionep']['etatdossierep'];
		if( empty( $etatdossierep ) && !empty( $relance['Dossierep']['id'] ) ) {
			$etatdossierep = 'En attente';
		}
		else {
			$etatdossierep = Set::enum( $relance['Passagecommissionep']['etatdossierep'], $options['Passagecommissionep']['etatdossierep'] );
		}

		$row = array(
			h( $relance['Dossier']['matricule'] ),
			h( "{$relance['Personne']['nom']} {$relance['Personne']['prenom']}" ),
			h( $relance['Personne']['nir'] ),
			h( $relance['Adresse']['locaadr'] ),
			h( $relance['Foyer']['enerreur'] ),
			h( empty( $relance['Contratinsertion']['id'] ) ? 'Non' : 'Oui' ),
			$locale->date( 'Locale->date', $relance['Contratinsertion']['df_ci'] ),
			h( $relance['Contratinsertion']['nbjours'] ),
			$locale->date( 'Locale->date', $relance['Orientstruct']['date_impression'] ),
			h( $relance['Orientstruct']['nbjours'] ),
			h( $etatdossierep ),
			$locale->date( 'Locale->date', $relance['Relancenonrespectsanctionep93']['daterelance'] ),
			( ( $relance['Relancenonrespectsanctionep93']['numrelance'] < 2 ) ? '1ère relance' : "{$relance['Relancenonrespectsanctionep93']['numrelance']}ème relance" )
		);
		$csv->addRow( $row );
	}

	Configure::write( 'debug', 0 );
	echo $csv->render( 'relances-'.date( 'Ymd-Hhm' ).'.csv' );
?>