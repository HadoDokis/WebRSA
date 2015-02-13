<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
        array(
            'N° Dossier',
            'Nom/Prénom allocataire',
            __d( 'dossier', 'Dossier.matricule' ),
            'Numéro de voie',
            'Type de voie',
            'Nom de voie',
            'Complément adresse 1',
            'Complément adresse 2',
            'Code postal',
            'Commune',
            'Secteur',
            'Hors ACI/ ACI',
            'Date début contrat',
            'Position du CUI',
			__d( 'search_plugin', 'Structurereferenteparcours.lib_struc' ),
			__d( 'search_plugin', 'Referentparcours.nom_complet' ),
        )
    );

	foreach( $cuis as $cui ) {

		$row = array(
			Hash::get( $cui, 'Dossier.numdemrsa' ),
			value( $qual, Hash::get( $cui, 'Personne.qual' ) ).' '.Hash::get( $cui, 'Personne.nom' ).' '.Hash::get( $cui, 'Personne.prenom'),
			Hash::get( $cui, 'Dossier.matricule' ),
			Hash::get( $cui, 'Adresse.numvoie' ),
			Hash::get( $cui, 'Adresse.libtypevoie' ),
			Hash::get( $cui, 'Adresse.nomvoie' ),
			Hash::get( $cui, 'Adresse.complideadr' ),
			Hash::get( $cui, 'Adresse.compladr' ),
			Hash::get( $cui, 'Adresse.codepos' ),
			Hash::get( $cui, 'Adresse.nomcom' ),
			Set::enum( Hash::get( $cui, 'Cui.secteurcui_id' ), $secteurscuis ),
            Set::enum( Hash::get( $cui, 'Cui.isaci' ), $options['Cui']['isaci'] ),
			$this->Locale->date( 'Date::short', Hash::get( $cui, 'Cui.datecontrat' ) ),
            Set::enum( Hash::get( $cui, 'Cui.positioncui66' ), $options['Cui']['positioncui66'] ),
			Hash::get( $cui, 'Structurereferenteparcours.lib_struc' ),
			Hash::get( $cui, 'Referentparcours.nom_complet' ),
		);
		$this->Csv->addRow($row);
	}

    $this->Csv->addRow(array( 'Nombre total de résultats = '.count( $cuis ) ) );

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'contrats_unique_insertion-'.date( 'Ymd-His' ).'.csv' );
?>