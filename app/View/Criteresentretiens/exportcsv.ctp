<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
        array(
            'Date de l\'entretien',
            'Nom/Prénom allocataire',
            'N° CAF',
            'Numéro de voie',
            'Type de voie',
            'Nom de voie',
            'Complément adresse 1',
            'Complément adresse 2',
            'Code postal',
            'Commune',
            'Structure référente',
            'Référent',
            'Type d\'entretien',
            'Objet de l\'entretien',
            'A revoir le',
			__d( 'search_plugin', 'Structurereferenteparcours.lib_struc' ),
			__d( 'search_plugin', 'Referentparcours.nom_complet' ),
        )
    );

	foreach( $entretiens as $entretien ) {
		$row = array(
			$this->Locale->date( 'Date::short', Set::classicExtract( $entretien, 'Entretien.dateentretien' ) ),
			Set::classicExtract( $entretien, 'Personne.nom' ).' '.Set::classicExtract( $entretien, 'Personne.prenom'),
			Set::classicExtract( $entretien, 'Dossier.matricule' ),
			Hash::get( $entretien, 'Adresse.numvoie' ),
			value( $typevoie, Hash::get( $entretien, 'Adresse.typevoie' ) ),
			Hash::get( $entretien, 'Adresse.nomvoie' ),
			Hash::get( $entretien, 'Adresse.complideadr' ),
			Hash::get( $entretien, 'Adresse.compladr' ),
			Hash::get( $entretien, 'Adresse.codepos' ),
			Hash::get( $entretien, 'Adresse.locaadr' ),
			Set::classicExtract( $entretien, 'Structurereferente.lib_struc' ),
			Set::classicExtract( $entretien, 'Referent.qual' ).' '.Set::classicExtract( $entretien, 'Referent.nom').' '.Set::classicExtract( $entretien, 'Referent.prenom'),
			Set::enum( Set::classicExtract( $entretien, 'Entretien.typeentretien' ), $options['typeentretien'] ),
			Set::classicExtract( $entretien, 'Objetentretien.name' ),
			$this->Locale->date( "Date::miniLettre", Set::classicExtract( $entretien, 'Entretien.arevoirle' ) ),
			Hash::get( $entretien, 'Structurereferenteparcours.lib_struc' ),
			Hash::get( $entretien, 'Referentparcours.nom_complet' ),
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'entretiens-'.date( 'Ymd-His' ).'.csv' );
?>