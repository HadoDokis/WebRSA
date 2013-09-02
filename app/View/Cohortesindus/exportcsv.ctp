<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
        array(
            'N° Dossier',
            'N° CAF',
            'Nom/Prénom allocataire',
            'Numéro de voie',
            'Type de voie',
            'Nom de voie',
            'Complément adresse 1',
            'Complément adresse 2',
            'Code postal',
            'Commune',
            'Suivi',
            'Situation des droits',
            'Date indus',
            'Montant initial de l\'indu',
            'Montant transféré CG',
            'Remise CG'
        )
    );

	foreach( $indus as $indu ) {
		$row = array(
			Hash::get( $indu, 'Dossier.numdemrsa' ),
			Hash::get( $indu, 'Dossier.matricule' ),
			value( $qual, Hash::get( $indu, 'Personne.qual' ) ).' '.Hash::get( $indu, 'Personne.nom' ).' '.Hash::get( $indu, 'Personne.prenom'),
            Hash::get( $indu, 'Adresse.numvoie' ),
			value( $typevoie, Hash::get( $indu, 'Adresse.typevoie' ) ),
			Hash::get( $indu, 'Adresse.nomvoie' ),
			Hash::get( $indu, 'Adresse.complideadr' ),
			Hash::get( $indu, 'Adresse.compladr' ),
			Hash::get( $indu, 'Adresse.codepos' ),
			Hash::get( $indu, 'Adresse.locaadr' ),
			Hash::get( $indu, 'Dossier.typeparte' ),
			value( $etatdosrsa, Hash::get( $indu, 'Situationdossierrsa.etatdosrsa' ) ),
			$this->Locale->date( 'Date::miniLettre', $indu[0]['moismoucompta'] ),
			$this->Locale->money( $indu[0]['mt_indus_constate'] ),
			$this->Locale->money( $indu[0]['mt_indus_transferes_c_g'] ),
			$this->Locale->money( $indu[0]['mt_remises_indus'] ),
		);
		$this->Csv->addRow($row);
	}
	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'indus-'.date( 'Ymd-His' ).'.csv' );
?>