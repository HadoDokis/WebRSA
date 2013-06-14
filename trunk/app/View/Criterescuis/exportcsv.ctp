<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire', 'N° CAF', 'Commune de l\'allocataire', 'Secteur', 'Hors ACI/ ACI','Date début contrat', 'Position du CUI' ) );

	foreach( $cuis as $cui ) {

		$row = array(
			Set::classicExtract( $cui, 'Dossier.numdemrsa' ),
			Set::classicExtract( $cui, 'Personne.nom' ).' '.Set::classicExtract( $cui, 'Personne.prenom'),
			Set::classicExtract( $cui, 'Dossier.matricule' ),
			Set::classicExtract( $cui, 'Adresse.locaadr' ),
			Set::enum( Set::classicExtract( $cui, 'Cui.secteurcui_id' ), $secteurscuis ),
            Set::enum( Set::classicExtract( $cui, 'Cui.isaci' ), $options['Cui']['isaci'] ),
			$this->Locale->date( 'Date::short', Set::classicExtract( $cui, 'Cui.datecontrat' ) ),
            Set::enum( Set::classicExtract( $cui, 'Cui.positioncui66' ), $options['Cui']['positioncui66'] )
		);
		$this->Csv->addRow($row);
	}
    
    $this->Csv->addRow(array( 'Nombre total de résultats = '.count( $cuis ) ) );

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'contrats_unique_insertion-'.date( 'Ymd-His' ).'.csv' );
?>