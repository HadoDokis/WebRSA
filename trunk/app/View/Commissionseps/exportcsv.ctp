<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'Nom/Prénom allocataire', 'N° CAF', 'Date de naissance', 'Commune de l\'allocataire', 'Structure référente', 'Date de création du dossier', 'Thème du dossier', 'Etat du dossier d\'EP', 'Date de proposition validée par la COV' ) );

	foreach( $dossierseps as $dossierep ) {

		$row = array(
			Set::classicExtract( $dossierep, 'Dossierep.Personne.qual' ).' '.Set::classicExtract( $dossierep, 'Dossierep.Personne.nom' ).' '.Set::classicExtract( $dossierep, 'Dossierep.Personne.prenom' ),
			Set::classicExtract( $dossierep, 'Dossierep.Personne.Foyer.Dossier.matricule'),
			$this->Locale->date( 'Date::short', Set::classicExtract( $dossierep, 'Dossierep.Personne.dtnai' ) ),
			Set::classicExtract( $dossierep, 'Dossierep.Personne.Foyer.Adressefoyer.0.Adresse.locaadr' ),
			Set::enum( Set::classicExtract( $dossierep, 'Dossierep.Personne.Orientstruct.0.structurereferente_id' ), $options['Orientstruct']['structurereferente_id'] ),
			$this->Locale->date( 'Date::short', Set::classicExtract( $dossierep, 'Dossierep.created') ),
			Set::enum( Set::classicExtract( $dossierep, 'Dossierep.themeep'), $options['Dossierep']['themeep'] ),
			Set::enum( Set::classicExtract( $dossierep, 'Passagecommissionep.etatdossierep'), $options['Passagecommissionep']['etatdossierep'] ),
			$this->Locale->date( 'Date::short', Set::classicExtract( $dossierep, 'Dossierep.Nonorientationproep58.Decisionpropononorientationprocov58.Passagecov58.Cov58.datecommission') )
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'listes_demande_maintien_social'.date( 'Ymd-His' ).'.csv' );
?>