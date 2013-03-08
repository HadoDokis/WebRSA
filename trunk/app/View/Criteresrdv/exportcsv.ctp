<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'Nom/Prénom allocataire', 'N° CAF', 'Adresse de l\'allocataire', 'Commune de l\'allocataire', 'Structure référente', 'Adresse de la structure', 'Référent', 'Objet du RDV', 'Statut du RDV', 'Date du RDV', 'Heure du RDV', 'Objectif du RDV', 'Commentaire suite RDV' ) );

	foreach( $rdvs as $rdv ) {
		$row = array(
			Hash::get( $rdv, 'Personne.nom' ).' '.Hash::get( $rdv, 'Personne.prenom'),
			Hash::get( $rdv, 'Dossier.matricule'  ),
			Hash::get( $rdv, 'Adresse.numvoie' ).' '.Set::enum( Hash::get( $rdv, 'Adresse.typevoie' ),  $typevoie).' '.Hash::get( $rdv, 'Adresse.nomvoie' ).' '.Hash::get( $rdv, 'Adresse.compladr' ).' '.Hash::get( $rdv, 'Adresse.complideadr' ).' '.Hash::get( $rdv, 'Adresse.codepos' ).' '.Hash::get( $rdv, 'Adresse.locaadr' ),
			Hash::get( $rdv, 'Adresse.locaadr'  ),
			Hash::get( $rdv, 'Structurereferente.lib_struc' ),
			Hash::get( $rdv, 'Structurereferente.num_voie' ).' '.Set::enum( Hash::get( $rdv, 'Structurereferente.type_voie' ), $typevoie ).' '.Hash::get( $rdv, 'Structurereferente.nom_voie' ).' '.Hash::get( $rdv, 'Structurereferente.code_postal' ).' '.Hash::get( $rdv, 'Structurereferente.ville' ),
			value( $referents, Hash::get( $rdv, 'Rendezvous.referent_id' ) ),
			value( $typerdv, Hash::get( $rdv, 'Rendezvous.typerdv_id' ) ),
			value( $statutrdv, Hash::get( $rdv, 'Rendezvous.statutrdv' ) ),
			date_short( $rdv['Rendezvous']['daterdv'] ),
			$rdv['Rendezvous']['heurerdv'],
			Hash::get( $rdv, 'Rendezvous.objetrdv' ),
			Hash::get( $rdv, 'Rendezvous.commentairerdv' ),
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'rendezvous-'.date( 'Ymd-His' ).'.csv' );
?>