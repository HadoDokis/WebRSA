<?php
	$csv->preserveLeadingZerosInExcel = true;

	$csv->addRow( array( 'N° Demande APRE', 'Nom/Prénom allocataire', 'Commune de l\'allocataire', 'Date de demande APRE', 'Etat du dossier', 'Décision', 'Montant accordé', 'Motif du rejet', 'Date de la décision'  ) );

	foreach( $apres as $apre ) {

		$row = array(
			Set::classicExtract( $apre, 'Apre66.numeroapre' ),
			Set::enum( Set::classicExtract( $apre, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom'),
			Set::classicExtract( $apre, 'Adresse.locaadr' ),
			$locale->date( 'Date::short', Set::classicExtract( $apre, 'Aideapre66.datedemande' ) ),
			Set::enum( Set::classicExtract( $apre, 'Apre.etatdossierapre' ), $options['etatdossierapre'] ),
			Set::enum( Set::classicExtract( $apre, 'Aideapre66.decisionapre' ), $optionsaideapre66['decisionapre'] ),
			Set::classicExtract( $apre, 'Aideapre66.montantaccorde' ),
			Set::classicExtract( $apre, 'Aideapre66.motifrejetequipe' ),
			$locale->date( 'Date::short', Set::classicExtract( $apre, 'Aideapre66.datemontantaccorde' ) )
		);
		$csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $csv->render( 'apres_valides-'.date( 'Ymd-Hhm' ).'.csv' );
?>