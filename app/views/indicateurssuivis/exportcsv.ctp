<?php
	$csv->preserveLeadingZerosInExcel = true;

// 	function value( $array, $index ) {
// 		$keys = array_keys( $array );
// 		$index = ( ( $index == null ) ? '' : $index );
// 		if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
// 			return $array[$index];
// 		}
// 		else {
// 			return null;
// 		}
// 	}

	$csv->addRow( array(
		'Numero CAF/MSA',
		'Nom / Prénom du demandeur',
		'Date de naissance du demandeur',
		'Adresse',
		'Nom / Prénom du conjoint',
		'Date ouverture de droits',
		'Ref. charge de l\'evaluation',
		'Date orientation (COV)',
		'Rang orientation (COV)',
		'Referent unique',
		'Date debut (CER)',
		'Date fin (CER)',
		'Rang (CER)',
		'Date inscription Pole Emploi',
		'Date (EP)',
		'Motif (EP)'
	) );

	foreach( $indicateurs as $indicateur ) {
		$adresse = Set::classicExtract( $indicateur, 'Adresse.numvoie' ).' '.Set::classicExtract( $typevoie, Set::classicExtract( $indicateur, 'Adresse.typevoie' ) ).' '.Set::classicExtract( $indicateur, 'Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $indicateur, 'Adresse.compladr' ).'<br /> '.Set::classicExtract( $indicateur, 'Adresse.codepos' ).' '.Set::classicExtract( $indicateur, 'Adresse.locaadr' );
		
		$conjoint = $indicateur['Personne']['qualcjt'].' '.$indicateur['Personne']['nomcjt'].' '.$indicateur['Personne']['prenomcjt'];

		$row = array(
			$indicateur['Dossier']['matricule'],
			$indicateur['Personne']['nom_complet'],
			date_short( $indicateur['Personne']['dtnai'] ),
			$adresse,
			$conjoint,
			date_short( $indicateur['Dossier']['dtdemrsa'] ),
			$indicateur['Referentorient']['nom_complet'],
			date_short( $indicateur['Orientstruct']['date_valid']),
			$indicateur['Orientstruct']['rgorient'],
			$indicateur['Referentunique']['nom_complet'],
			date_short( $indicateur['Contratinsertion']['dd_ci'] ),
			date_short( $indicateur['Contratinsertion']['df_ci'] ),
			$indicateur['Contratinsertion']['rg_ci'],
			Set::enum( $indicateur['Historiqueetatpe']['etat'], $etatpe['etat'] ).' '.date_short( $indicateur['Historiqueetatpe']['date'] ),
			date_short( $indicateur['Commissionep']['dateseance'] ),
			!empty( $indicateur['Dossierep']['themeep'] ) ? Set::classicExtract( $options['themeep'], $indicateur['Dossierep']['themeep'] ) : null
		);
		$csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $csv->render( 'indicateurssuivis-'.date( 'Ymd-Hhm' ).'.csv' );
?>