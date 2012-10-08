<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'Date de signature de la fiche', 'Nom de la personne', 'N° CAF', 'Nom du prescripteur', 'Action engagée', 'Nom du partenaire', 'Position de la fiche de candidature', 'Date de sortie', 'Motif de sortie', 'Code INSEE', 'Localité' ) );

	foreach( $actionscandidatsPersonnes as $actioncandidat_personne ) {
		$row = array(
			$this->Locale->date( 'Date::short', Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.datesignature' ) ),
			Set::classicExtract( $actioncandidat_personne, 'Personne.nom_complet' ),
			Set::classicExtract( $actioncandidat_personne, 'Dossier.matricule' ),
			Set::classicExtract( $actioncandidat_personne, 'Referent.nom_complet' ),
			Set::classicExtract( $actioncandidat_personne, 'Actioncandidat.name' ),
			Set::classicExtract( $actioncandidat_personne, 'Partenaire.libstruc' ),
			Set::enum( Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.positionfiche' ), $options['positionfiche'] ),
			$this->Locale->date( 'Date::short', Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.sortiele' ) ),
			Set::enum( Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.motifsortie_id' ), $motifssortie ),
			$actioncandidat_personne['Adresse']['numcomptt'],
			$actioncandidat_personne['Adresse']['locaadr'],
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'fiches-candidature'.date( 'Ymd-Hhm' ).'.csv' );
?>