<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

    if( Configure::read( 'Cg.departement') == 93 ) {
        $this->Csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire',  'Commune de l\'allocataire', 'Date de demande d\'APRE', 'Nature de l\'aide', 'Type de demande APRE', 'Activité du bénéficiaire' ) );
    }
    else {
        $this->Csv->addRow(
            array(
                'Nom/Prénom allocataire',
                'Date de demande d\'APRE',
                'Nature de l\'aide',
                'Type de demande APRE',
                'Structure référente',
                'Référent',
                'Etat du dossier APRE',
                'Décision',
                'Montant accordé',
                'Canton'
            )
        );
    }

	foreach( $apres as $apre ) {

        if( Configure::read( 'Cg.departement') == 93 ) {
            $aidesApre = array();
            $naturesaide = Set::classicExtract( $apre, 'Apre.Natureaide' );
            foreach( $naturesaide as $natureaide => $nombre ) {
                if( $nombre > 0 ) {
                    $aidesApre[] = Set::classicExtract( $natureAidesApres, $natureaide );
                }
            }

        
            $row = array(
                Hash::get( $apre, 'Dossier.numdemrsa' ),
                Hash::get( $apre, 'Personne.nom' ).' '.Hash::get( $apre, 'Personne.prenom'),
                Hash::get( $apre, 'Adresse.locaadr' ),
                $this->Locale->date( 'Locale->date', Hash::get( $apre, 'Apre.datedemandeapre' ) ),
                ( empty( $aidesApre ) ? null : implode( "\n", $aidesApre ) ),
                value( $options['typedemandeapre'], Hash::get( $apre, 'Apre.typedemandeapre' ) ),
                value( $options['activitebeneficiaire'], Hash::get( $apre, 'Apre.activitebeneficiaire' ) ),
            );
        }
        else {
            $row = array(
                Hash::get( $apre, 'Personne.nom' ).' '.Hash::get( $apre, 'Personne.prenom'),
                $this->Locale->date( 'Locale->date', Hash::get( $apre, 'Aideapre66.datedemande' ) ),
                Hash::get( $apre, 'Themeapre66.name' ),
                Hash::get( $apre, 'Typeaideapre66.name' ),
                Hash::get( $apre, 'Structurereferente.lib_struc' ),
                Hash::get( $apre, 'Referent.nom' ).' '.Hash::get( $apre, 'Referent.prenom' ),
                value( $options['etatdossierapre'], Hash::get( $apre, 'Apre.etatdossierapre' ) ),
                value( $options['decisionapre'], Hash::get( $apre, 'Aideapre66.decisionapre' ) ),
                Hash::get( $apre, 'Aideapre66.montantaccorde' ),
                Hash::get( $apre, 'Canton.canton' )
            );
        }
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'apres-'.date( 'Ymd-His' ).'.csv' );
?>