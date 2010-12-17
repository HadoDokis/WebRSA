<?php
	$csv->preserveLeadingZerosInExcel = true;

	function value( $array, $index ) {
		$keys = array_keys( $array );
		$index = ( ( $index == null ) ? '' : $index );
		if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
			return $array[$index];
		}
		else {
			return null;
		}
	}

    $csv->addRow(
		array(
			'N° CAF',
			'Nom',
			'Prénom',
			'NIR',
			'Présence contrat (oui/non)',
			'Date de fin de contrat',
			'Date d\'orientation',
			'Rang passage en EP',
			'Décision',
			'Montant réduction',
			'Durée sursis',
		)
	);

	foreach( $dossiers as $dossier ) {
		$row = array(
			'="'.Set::extract( $dossier, 'Dossierep.Personne.Foyer.Dossier.matricule' ).'"',
			'"'.Set::extract( $dossier, 'Dossierep.Personne.nom' ).'"',
			'"'.Set::extract( $dossier, 'Dossierep.Personne.prenom' ).'"',
			'="'.Set::extract( $dossier, 'Dossierep.Personne.nir' ).'"',
			'"'.( Set::extract( $dossier, 'Nonrespectsanctionep93.contratinsertion_id' ) != '' ? 'Oui' : 'Non' ).'"',
			'"'.date_short( Set::extract( $dossier, 'Contratinsertion.df_ci' ) ).'"',
			'"'.date_short( Set::extract( $dossier, 'Orientstruct.date_valid' ) ).'"',
			'"'.Set::extract( $dossier, 'Nonrespectsanctionep93.rgpassage' ).'"',
			'"'.Set::extract( $dossier, 'Nonrespectsanctionep93.decision' ).'"',
			'"'.Set::extract( $dossier, 'Nonrespectsanctionep93.montantreduction' ).'"',
			'"'.Set::extract( $dossier, 'Nonrespectsanctionep93.dureesursis' ).'"',
		);
		$csv->addRow($row);
	}
	Configure::write( 'debug', 0 );
	echo $csv->render( 'dossiers-'.date( 'Ymd-Hhm' ).'.csv' );
?>