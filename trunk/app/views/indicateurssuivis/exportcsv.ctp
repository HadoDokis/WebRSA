<?php
//	$csv->preserveLeadingZerosInExcel = true;

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
		'Nom DEM',
		'Prenom DEM',
		'Date de naissance DEM',
		'Adresse',
		'Nom CJT',
		'Prenom CJT',
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
		$adresse = Set::extract( $indicateur, 'Adresse.numvoie' ).' '.Set::extract( $typevoie, Set::extract( $indicateur, 'Adresse.typevoie' ) ).' '.Set::extract( $indicateur, 'Adresse.nomvoie' ).' '.Set::extract( $indicateur, 'Adresse.compladr' ).' '.Set::extract( $indicateur, 'Adresse.codepos' ).' '.Set::extract( $indicateur, 'Adresse.locaadr' );

		$row = 	array(
			$indicateur['Dossier']['matricule'],
			$indicateur['Personne']['nom'],
			$indicateur['Personne']['prenom'],
			$indicateur['Personne']['dtnai'],
			$adresse,
			$indicateur['Personne']['nomcjt'],
			$indicateur['Personne']['prenomcjt'],
			date_short( $indicateur['Dossier']['dtdemrsa'] ),
			is_null($indicateur['Orientstruct']['referent_id'] ) ? '' : Set::extract( $referents, Set::extract( $indicateur, 'Orientstruct.referent_id' ) ),
			date_short( $indicateur['Cov58']['datecommission']),
			$indicateur['Orientstruct']['rgorient'],
			is_null($indicateur['PersonneReferent']['referent_id'] ) ? '' : Set::extract( $referents, Set::extract( $indicateur, 'PersonneReferent.referent_id' ) ),
			date_short( $indicateur['Contratinsertion']['dd_ci'] ),
			date_short( $indicateur['Contratinsertion']['df_ci'] ),
			$indicateur['Contratinsertion']['rg_ci'],
			'-',	//h( date_short( $indicateur[''][''] ),
			date_short( $indicateur['Commissionep']['dateseance'] ),
			is_null($indicateur['Dossierep']['themeep'] ) ? '' : Set::extract( $options['themeep'], $indicateur['Dossierep']['themeep']),								
		);
		$csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $csv->render( 'indicateurssuivis-'.date( 'Ymd-Hhm' ).'.csv' );
?>