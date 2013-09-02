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
            'Identifiant Pôle Emploi',
            'N° CAF'
        )
    );

	foreach( $dossiers as $dossier ) {
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
			Hash::get( $dossier, 'Personne.idassedic' ),
			Hash::get( $dossier, 'Dossier.matricule' )
		);
		$this->Csv->addRow($row);
	}
	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'dossiers-'.date( 'Ymd-His' ).'.csv' );
?>