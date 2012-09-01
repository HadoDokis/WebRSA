<?php
	$csv->preserveLeadingZerosInExcel = true;

	$csv->addRow(
		array(
			'N° CAF',
			'Nom',
			'Prénom',
			'NIR',
			'Origine',
			'Présence contrat (oui/non)',
			'Date de fin de contrat',
			'Date d\'orientation',
			'Date de la commission',
			'Rang passage en EP',
			'Décision',
			'Montant réduction',
			'Durée sursis',
		)
	);

	foreach( $dossiers as $dossier ) {
		$row = array(
			'="'.Set::extract( $dossier, 'Dossier.matricule' ).'"',
			Set::extract( $dossier, 'Personne.nom' ),
			Set::extract( $dossier, 'Personne.prenom' ),
			'="'.Set::extract( $dossier, 'Personne.nir' ).'"',
			Set::enum( Set::extract( $dossier, 'Nonrespectsanctionep93.origine' ), $options['Nonrespectsanctionep93']['origine'] ),
			( Set::extract( $dossier, 'Nonrespectsanctionep93.contratinsertion_id' ) != '' ? 'Oui' : 'Non' ),
			date_short( Set::extract( $dossier, 'Contratinsertion.df_ci' ) ),
			date_short( Set::extract( $dossier, 'Orientstruct.date_valid' ) ),
			date_short( Set::extract( $dossier, 'Commissionep.dateseance' ) ),
			Set::extract( $dossier, 'Nonrespectsanctionep93.rgpassage' ),
			Set::enum( Set::extract( $dossier, 'Decisionnonrespectsanctionep93.decision' ), $options['Decisionnonrespectsanctionep93']['decision'] ),
			Set::extract( $dossier, 'Decisionnonrespectsanctionep93.montantreduction' ),
			Set::extract( $dossier, 'Decisionnonrespectsanctionep93.dureesursis' ),
		);
		$csv->addRow($row);
	}
	Configure::write( 'debug', 0 );
	echo $csv->render( 'dossiers-'.date( 'Ymd-His' ).'.csv', Configure::read( 'App.encoding' ) );
?>