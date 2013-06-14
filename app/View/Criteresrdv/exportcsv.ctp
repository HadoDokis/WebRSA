<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
		array(
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
			'Structure référente',
			'Adresse de la structure',
			'Référent',
			'Objet du RDV',
			'Statut du RDV',
			'Date du RDV',
			'Heure du RDV',
			'Objectif du RDV',
			'Commentaire suite RDV',
			'Etat du droit',
		)
	);

	foreach( $rdvs as $rdv ) {
		$row = array(
			value( $qual, Hash::get( $rdv, 'Personne.qual' ) ),
			Hash::get( $rdv, 'Personne.nom' ),
			Hash::get( $rdv, 'Personne.prenom' ),
			Hash::get( $rdv, 'Dossier.matricule'  ),
			Hash::get( $rdv, 'Adresse.numvoie' ),
			value( $typevoie, Hash::get( $rdv, 'Adresse.typevoie' ) ),
			Hash::get( $rdv, 'Adresse.nomvoie' ),
			Hash::get( $rdv, 'Adresse.complideadr' ),
			Hash::get( $rdv, 'Adresse.compladr' ),
			Hash::get( $rdv, 'Adresse.codepos' ),
			Hash::get( $rdv, 'Adresse.locaadr' ),
			Hash::get( $rdv, 'Structurereferente.lib_struc' ),
			Hash::get( $rdv, 'Structurereferente.num_voie' ).' '.Set::enum( Hash::get( $rdv, 'Structurereferente.type_voie' ), $typevoie ).' '.Hash::get( $rdv, 'Structurereferente.nom_voie' ).' '.Hash::get( $rdv, 'Structurereferente.code_postal' ).' '.Hash::get( $rdv, 'Structurereferente.ville' ),
			value( $qual, Hash::get( $rdv, 'Referent.qual' ) ).' '.Hash::get( $rdv, 'Referent.nom' ).' '.Hash::get( $rdv, 'Referent.prenom' ),
			value( $typerdv, Hash::get( $rdv, 'Rendezvous.typerdv_id' ) ),
			value( $statutrdv, Hash::get( $rdv, 'Rendezvous.statutrdv_id' ) ),
			date_short( $rdv['Rendezvous']['daterdv'] ),
			$rdv['Rendezvous']['heurerdv'],
			Hash::get( $rdv, 'Rendezvous.objetrdv' ),
			Hash::get( $rdv, 'Rendezvous.commentairerdv' ),
			value( $etatdosrsa, Hash::get( $rdv, 'Situationdossierrsa.etatdosrsa' ) ),
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'rendezvous-'.date( 'Ymd-His' ).'.csv' );
?>