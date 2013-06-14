<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
		array(
			'N° Dossier',
			'N° CAF',
			'Etat du droit',
			'Qualité',
			'Nom',
			'Prénom',
			'N° CAF',
			'Numéro de voie',
			'Type de voie',
			'Nom de voie',
			'Complément adresse 1',
			'Complément adresse 2',
			'Code postal',
			'Commune',
			'Code secteur d\'activité',
			'Code métier',
			'Secteur dernière activité dominante',
			'Dernière activité dominante',
			'Code secteur recherché',
			'Code métier recherché',
			'Secteur activité recherché',
			'Activité recherchée'
		)
	);

	foreach( $dsps as $dsp ) {
            $key = $dsp['Donnees']['libsecactdomi66_secteur_id'] . '_' . $dsp['Donnees']['libactdomi66_metier_id'];
            $key2 = $dsp['Donnees']['libsecactrech66_secteur_id'] . '_' . $dsp['Donnees']['libemploirech66_metier_id'];

            $row = array(
                Hash::get( $dsp, 'Dossier.numdemrsa' ),
                Hash::get( $dsp, 'Dossier.matricule' ),
				value( $etatdosrsa, Hash::get( $dsp, 'Situationdossierrsa.etatdosrsa' ) ),
				value( $qual, Hash::get( $dsp, 'Personne.qual' ) ),
				Hash::get( $dsp, 'Personne.nom' ),
				Hash::get( $dsp, 'Personne.prenom' ),
				Hash::get( $dsp, 'Dossier.matricule'  ),
				Hash::get( $dsp, 'Adresse.numvoie' ),
				value( $typevoie, Hash::get( $dsp, 'Adresse.typevoie' ) ),
				Hash::get( $dsp, 'Adresse.nomvoie' ),
				Hash::get( $dsp, 'Adresse.complideadr' ),
				Hash::get( $dsp, 'Adresse.compladr' ),
				Hash::get( $dsp, 'Adresse.codepos' ),
				Hash::get( $dsp, 'Adresse.locaadr' ),
                Set::enum( $dsp['Donnees']['libsecactdomi66_secteur_id'], $options['Coderomesecteurdsp66'] ),
                @$options['Coderomemetierdsp66'][$key],
                $dsp['Donnees']['libsecactdomi'],
                $dsp['Donnees']['libactdomi'],
                Set::enum( $dsp['Donnees']['libsecactrech66_secteur_id'], $options['Coderomesecteurdsp66'] ),
                @$options['Coderomemetierdsp66'][$key2],
                $dsp['Donnees']['libsecactrech'],
                $dsp['Donnees']['libemploirech']
            );
            $this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'dsps-'.date( 'Ymd-His' ).'.csv' );
?>