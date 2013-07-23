<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire',  'Commune de l\'allocataire', 'Date de demande d\'APRE', 'Nature de l\'aide', 'Type de demande APRE', 'Activité du bénéficiaire' ) );

	foreach( $apres as $apre ) {

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
			$this->Locale->date( Hash::get( $apre, 'Apre.datedemandeapre' ) ),
			( empty( $aidesApre ) ? null : implode( "\n", $aidesApre ) ),
			value( $options['typedemandeapre'], Hash::get( $apre, 'Apre.typedemandeapre' ) ),
			value( $options['activitebeneficiaire'], Hash::get( $apre, 'Apre.activitebeneficiaire' ) ),
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'apres-'.date( 'Ymd-His' ).'.csv' );
?>