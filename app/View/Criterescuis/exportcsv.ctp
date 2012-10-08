<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire', 'N° CAF', 'Commune de l\'allocataire', 'Secteur', 'Date début contrat' ) );

	foreach( $cuis as $cui ) {

		$row = array(
			Set::classicExtract( $cui, 'Dossier.numdemrsa' ),
			Set::classicExtract( $cui, 'Personne.nom' ).' '.Set::classicExtract( $cui, 'Personne.prenom'),
			Set::classicExtract( $cui, 'Dossier.matricule' ),
			Set::classicExtract( $cui, 'Adresse.locaadr' ),
			Set::enum( Set::classicExtract( $cui, 'Cui.secteur' ), $options['secteur'] ),
			$this->Locale->date( 'Date::short', Set::classicExtract( $cui, 'Cui.datecontrat' ) )
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'contrats_unique_insertion-'.date( 'Ymd-Hhm' ).'.csv' );
?>