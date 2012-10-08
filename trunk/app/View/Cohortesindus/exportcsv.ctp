<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'N° Dossier', 'N° CAF', 'Nom/Prénom allocataire', 'Suivi', 'Situation des droits', 'Date indus', 'Montant initial de l\'indu', 'Montant transféré CG', 'Remise CG' ) );

	foreach( $indus as $indu ) {
		$row = array(
			Set::extract( $indu, 'Dossier.numdemrsa' ),
			Set::extract( $indu, 'Dossier.matricule' ),
			Set::extract( $indu, 'Personne.nom' ).' '.Set::extract( $indu, 'Personne.prenom'),
			Set::extract( $indu, 'Dossier.typeparte' ),
			value( $etatdosrsa, Set::extract( $indu, 'Situationdossierrsa.etatdosrsa' ) ),
			$this->Locale->date( 'Date::miniLettre', $indu[0]['moismoucompta'] ),
			$this->Locale->money( $indu[0]['mt_indus_constate'] ),
			$this->Locale->money( $indu[0]['mt_indus_transferes_c_g'] ),
			$this->Locale->money( $indu[0]['mt_remises_indus'] ),
		);
		$this->Csv->addRow($row);
	}
	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'indus-'.date( 'Ymd-Hhm' ).'.csv' );
?>