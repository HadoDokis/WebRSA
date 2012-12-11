<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'Nom/Prénom allocataire', 'N° CAF',  'Commune de l\'allocataire', 'Structure référente', 'Référent', 'Objet du RDV', 'Statut du RDV', 'Date du RDV', 'Heure du RDV', 'Objectif du RDV', 'Commentaire suite RDV' ) );

	foreach( $rdvs as $rdv ) {
		$row = array(
			Set::extract( $rdv, 'Personne.nom' ).' '.Set::extract( $rdv, 'Personne.prenom'),
			Set::extract( $rdv, 'Dossier.matricule'  ),
			Set::extract( $rdv, 'Adresse.locaadr'  ),
			value( $struct, Set::extract( $rdv, 'Rendezvous.structurereferente_id' ) ),
			value( $referents, Set::extract( $rdv, 'Rendezvous.referent_id' ) ),
			value( $typerdv, Set::extract( $rdv, 'Rendezvous.typerdv_id' ) ),
			value( $statutrdv, Set::extract( $rdv, 'Rendezvous.statutrdv' ) ),
			date_short( $rdv['Rendezvous']['daterdv'] ),
			$rdv['Rendezvous']['heurerdv'],
			Set::extract( $rdv, 'Rendezvous.objetrdv' ),
			Set::extract( $rdv, 'Rendezvous.commentairerdv' ),
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'rendezvous-'.date( 'Ymd-His' ).'.csv' );
?>