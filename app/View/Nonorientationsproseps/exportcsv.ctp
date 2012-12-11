<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'N° dossier RSA', 'Nom allocataire', 'Prénom allocataire', 'Date de naissance', 'Commune de l\'allocataire', 'Date de validation de l\'orientation', 'Nb de jours depuis la fin du contrat lié', 'Type d\'orientation', 'Structure référente', 'Référent' ) );

	foreach( $orientsstructs as $orientstruct ) {

		$row = array(
			Set::classicExtract( $orientstruct, 'Dossier.numdemrsa' ),
			Set::classicExtract( $orientstruct, 'Personne.nom'),
			Set::classicExtract( $orientstruct, 'Personne.prenom'),
			date_short( Set::classicExtract( $orientstruct, 'Personne.dtnai' ) ),
			Set::classicExtract( $orientstruct, 'Adresse.locaadr' ),
			$this->Locale->date( 'Date::short', Set::classicExtract( $orientstruct, 'Orientstruct.date_valid' ) ),
			Set::classicExtract( $orientstruct, 'Contratinsertion.nbjours'),
			Set::classicExtract( $orientstruct, 'Typeorient.lib_type_orient'),
			Set::classicExtract( $orientstruct, 'Structurereferente.lib_struc'),
			Set::classicExtract( $orientstruct, 'Referent.nom').' '.Set::classicExtract( $orientstruct, 'Referent.prenom')
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'listes_demande_maintien_social'.date( 'Ymd-His' ).'.csv' );
?>