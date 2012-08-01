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

	$csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire', 'N° CAF', 'Commune de l\'allocataire', 'Secteur', 'Date début contrat' ) );

	foreach( $cuis as $cui ) {

		$row = array(
			Set::classicExtract( $cui, 'Dossier.numdemrsa' ),
			Set::classicExtract( $cui, 'Personne.nom' ).' '.Set::classicExtract( $cui, 'Personne.prenom'),
			Set::classicExtract( $cui, 'Dossier.matricule' ),
			Set::classicExtract( $cui, 'Adresse.locaadr' ),
			Set::enum( Set::classicExtract( $cui, 'Cui.secteur' ), $options['secteur'] ),
			$locale->date( 'Date::short', Set::classicExtract( $cui, 'Cui.datecontrat' ) )
		);
		$csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $csv->render( 'contrats_unique_insertion-'.date( 'Ymd-Hhm' ).'.csv' );
?>