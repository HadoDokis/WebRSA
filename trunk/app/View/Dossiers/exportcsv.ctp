<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
        array(
            'N° Dossier',
            'Date de demande',
            'NIR',
            'Etat du droit',
            'Nom/Prénom allocataire',
            'Date de naissance',
            'Numéro de voie',
            'Type de voie',
            'Nom de voie',
            'Complément adresse 1',
            'Complément adresse 2',
            'Code postal',
            'Commune',
			'Type d\'orientation',
            'Identifiant Pôle Emploi',
            'N° CAF',
			__d( 'search_plugin', 'Structurereferenteparcours.lib_struc' ),
			__d( 'search_plugin', 'Referentparcours.nom_complet' ),
			'Sexe',
			'Conditions de logement',
        )
    );

	foreach( $dossiers as $dossier ) {
		$dspnatlog = Hash::get( $dossier, 'DspRev.natlog' );
		if( empty( $natlog ) ) {
			$dspnatlog = Hash::get( $dossier, 'Dsp.natlog' );
		}

		$row = array(
			Hash::get( $dossier, 'Dossier.numdemrsa' ),
			date_short( Hash::get( $dossier, 'Dossier.dtdemrsa' ) ),
			Hash::get( $dossier, 'Personne.nir' ),
			value( $etatdosrsa, Hash::get( $dossier, 'Situationdossierrsa.etatdosrsa' ) ),
			value( $qual, Hash::get( $dossier, 'Personne.qual' ) ).' '.Hash::get( $dossier, 'Personne.nom' ).' '.Hash::get( $dossier, 'Personne.prenom'),
			date_short( Hash::get( $dossier, 'Personne.dtnai' ) ),
			Hash::get( $dossier, 'Adresse.numvoie' ),
			value( $typevoie, Hash::get( $dossier, 'Adresse.typevoie' ) ),
			Hash::get( $dossier, 'Adresse.nomvoie' ),
			Hash::get( $dossier, 'Adresse.complideadr' ),
			Hash::get( $dossier, 'Adresse.compladr' ),
			Hash::get( $dossier, 'Adresse.codepos' ),
			Hash::get( $dossier, 'Adresse.locaadr' ),
			Hash::get( $dossier, 'Typeorient.lib_type_orient' ),
			Hash::get( $dossier, 'Personne.idassedic' ),
			Hash::get( $dossier, 'Dossier.matricule' ),
			Hash::get( $dossier, 'Structurereferenteparcours.lib_struc' ),
			Hash::get( $dossier, 'Referentparcours.nom_complet' ),
			value( $sexe, Hash::get( $dossier, 'Personne.sexe' ) ),
			value( $natlog, $dspnatlog ),
		);
		$this->Csv->addRow($row);
	}
	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'dossiers-'.date( 'Ymd-His' ).'.csv' );
?>