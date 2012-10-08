<?php
	$csv->preserveLeadingZerosInExcel = true;

	$csv->addRow( array( 'N° Dossier', 'N° CAF', 'Nom/Prénom allocataire', 'Suivi', 'Situation des droits', 'Date indus', 'Montant initial de l\'indu', 'Montant transféré CG', 'Remise CG' ) );

	foreach( $indus as $indu ) {
		$row = array(
			Set::extract( $indu, 'Dossier.numdemrsa' ),
			Set::extract( $indu, 'Dossier.matricule' ),
			Set::extract( $indu, 'Personne.nom' ).' '.Set::extract( $indu, 'Personne.prenom'),
			Set::extract( $indu, 'Dossier.typeparte' ),
			value( $etatdosrsa, Set::extract( $indu, 'Situationdossierrsa.etatdosrsa' ) ),
			$locale->date( 'Date::miniLettre', $indu[0]['moismoucompta'] ),
			$locale->money( $indu[0]['mt_indus_constate'] ),
			$locale->money( $indu[0]['mt_indus_transferes_c_g'] ),
			$locale->money( $indu[0]['mt_remises_indus'] ),
		);
		$csv->addRow($row);
	}
	Configure::write( 'debug', 0 );
	echo $csv->render( 'indus-'.date( 'Ymd-Hhm' ).'.csv' );
?>