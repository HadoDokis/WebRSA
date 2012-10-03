<?php
	$csv->preserveLeadingZerosInExcel = true;

	if( $nameTableauCsv == 'noninscrits' ){

		$csv->addRow( array( 'Nom allocataire', 'Prénom allocataire',  'Date de naissance', 'Commune de l\'allocataire', 'Type d\'orientation', 'Type de structure', 'Date d\'orientation', 'Service instructeur' ) );

		foreach( $personnes as $personne ) {

			$row = array(
				Set::classicExtract( $personne, 'Personne.nom' ),
				Set::classicExtract( $personne, 'Personne.prenom'),
				date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) ),
				Set::classicExtract( $personne, 'Adresse.locaadr' ),
				Set::classicExtract( $personne, 'Typeorient.lib_type_orient' ),
				Set::classicExtract( $personne, 'Structurereferente.lib_struc' ),
				$locale->date( 'Date::short', Set::classicExtract( $personne, 'Orientstruct.date_valid' ) ),
				Set::classicExtract( $personne, 'Serviceinstructeur.lib_service' )
			);
			$csv->addRow($row);
		}
	}
	else if( $nameTableauCsv == 'radies' ) {
		$configureConditions = Configure::read( 'Selectionradies.conditions' );

		if( !empty( $configureConditions ) ) {
			$csv->addRow( array( 'Nom allocataire', 'Prénom allocataire', 'Date de naissance', 'Commune de l\'allocataire', __d( 'sanctionep58', 'Historiqueetatpe.etat', true ), __d( 'sanctionep58', 'Historiqueetatpe.code', true ), 'Motif de radiation Pôle Emploi', 'Date de radiation Pôle Emploi', 'Service instructeur' ) );

			foreach( $personnes as $personne ) {
				$row = array(
					Set::classicExtract( $personne, 'Personne.nom' ),
					Set::classicExtract( $personne, 'Personne.prenom'),
					date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) ),
					Set::classicExtract( $personne, 'Adresse.locaadr' ),
					Set::classicExtract( $personne, 'Historiqueetatpe.etat' ),
					Set::classicExtract( $personne, 'Historiqueetatpe.code' ),
					Set::classicExtract( $personne, 'Historiqueetatpe.motif' ),
					$locale->date( 'Date::short', Set::classicExtract( $personne, 'Historiqueetatpe.date' ) ),
					Set::classicExtract( $personne, 'Serviceinstructeur.lib_service' )
				);
				$csv->addRow($row);
			}

		}
		else {
			$csv->addRow( array( 'Nom allocataire', 'Prénom allocataire', 'Date de naissance', 'Commune de l\'allocataire', 'Motif de radiation', 'Date de radiation', 'Service instructeur' ) );

			foreach( $personnes as $personne ) {
				$row = array(
					Set::classicExtract( $personne, 'Personne.nom' ),
					Set::classicExtract( $personne, 'Personne.prenom'),
					date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) ),
					Set::classicExtract( $personne, 'Adresse.locaadr' ),
					Set::classicExtract( $personne, 'Historiqueetatpe.motif' ),
					$locale->date( 'Date::short', Set::classicExtract( $personne, 'Historiqueetatpe.date' ) ),
					Set::classicExtract( $personne, 'Serviceinstructeur.lib_service' )
				);
				$csv->addRow($row);
			}

		}
	}

	Configure::write( 'debug', 0 );
	echo $csv->render( 'listes_pe-'.$nameTableauCsv.''.date( 'Ymd-Hhm' ).'.csv' );
?>