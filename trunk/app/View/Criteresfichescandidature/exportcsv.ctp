<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
        array(
            'Date de signature de la fiche',
            'Nom de la personne',
            'N° CAF',
            'Nom du prescripteur',
            'Action engagée',
            'Nom de la formation',
            'Programme(s)',
            'Nom du partenaire',
            'Position de la fiche de candidature',
            'Date de sortie',
            'Motif de sortie',
            'Code INSEE',
            'Localité'
        )
    );
    
    

//debug( $actionscandidats_personnes );
//die();
	foreach( $actionscandidats_personnes as $actioncandidat_personne ) {
        
		$row = array(
			$this->Locale->date( 'Date::short', Hash::get( $actioncandidat_personne, 'ActioncandidatPersonne.datesignature' ) ),
			Hash::get( $actioncandidat_personne, 'Personne.nom_complet' ),
			Hash::get( $actioncandidat_personne, 'Dossier.matricule' ),
			Hash::get( $actioncandidat_personne, 'Referent.nom_complet' ),
			Hash::get( $actioncandidat_personne, 'Actioncandidat.name' ),
			Hash::get( $actioncandidat_personne, 'ActioncandidatPersonne.formationregion' ),
			$actioncandidat_personne['Progfichecandidature66']['listenoms'],
			Hash::get( $actioncandidat_personne, 'Partenaire.libstruc' ),
			Set::enum( Hash::get( $actioncandidat_personne, 'ActioncandidatPersonne.positionfiche' ), $options['positionfiche'] ),
			$this->Locale->date( 'Date::short', Hash::get( $actioncandidat_personne, 'ActioncandidatPersonne.sortiele' ) ),
			Set::enum( Hash::get( $actioncandidat_personne, 'ActioncandidatPersonne.motifsortie_id' ), $motifssortie ),
			$actioncandidat_personne['Adresse']['numcomptt'],
			$actioncandidat_personne['Adresse']['locaadr'],
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'fiches-candidature'.date( 'Ymd-His' ).'.csv' );
?>