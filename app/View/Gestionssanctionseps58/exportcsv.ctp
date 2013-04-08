<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'Nom allocataire', 'Commune allocataire', 'Identifiant EP', 'Identifiant commission', 'Date de la commission', 'Thématique', 'Sanction 1', 'Sanction 2', 'Modification de la sanction', 'Date fin de sanction', 'Commentaire' ) );

	foreach( $gestionssanctionseps58 as $gestionsanctionep58 ) {
		if( $gestionsanctionep58['Dossierep']['themeep'] == 'sanctionseps58' ) {
			// Type de sanction
			$decisionSanction1 = Set::enum( $gestionsanctionep58['Decisionsanctionep58']['decision'], $regularisationlistesanctionseps58['Decisionsanctionep58']['decision'] );
			$decisionSanction2 = Set::enum( $gestionsanctionep58['Decisionsanctionep58']['decision2'], $regularisationlistesanctionseps58['Decisionsanctionep58']['decision'] );
			// Libellé de la sanction
			$libelleSanction1 = Set::enum( $gestionsanctionep58['Decisionsanctionep58']['listesanctionep58_id'], $listesanctionseps58 );
			$libelleSanction2 = Set::enum( $gestionsanctionep58['Decisionsanctionep58']['autrelistesanctionep58_id'], $listesanctionseps58 );

			$fieldDecisionSanction = Set::enum( $gestionsanctionep58['Decisionsanctionep58']['arretsanction'], $options['Decisionsanctionep58']['arretsanction'] );
			$dateFinSanction = date_short( $gestionsanctionep58['Decisionsanctionep58']['datearretsanction'] );
			$commentaireFinSanction = $gestionsanctionep58['Decisionsanctionep58']['commentairearretsanction'];
		}
		else {
			// Type de sanction
			$decisionSanction1 = Set::enum( $gestionsanctionep58['Decisionsanctionrendezvousep58']['decision'], $regularisationlistesanctionseps58['Decisionsanctionrendezvousep58']['decision'] );
			$decisionSanction2 = Set::enum( $gestionsanctionep58['Decisionsanctionrendezvousep58']['decision2'], $regularisationlistesanctionseps58['Decisionsanctionrendezvousep58']['decision'] );

			// Libellé de la sanction
			$libelleSanction1 = Set::enum( $gestionsanctionep58['Decisionsanctionrendezvousep58']['listesanctionep58_id'], $listesanctionseps58 );
			$libelleSanction2 = Set::enum( $gestionsanctionep58['Decisionsanctionrendezvousep58']['autrelistesanctionep58_id'], $listesanctionseps58 );

			//Champ permettant la modification de la sanction
			$fieldDecisionSanction = Set::enum( $gestionsanctionep58['Decisionsanctionrendezvousep58']['arretsanction'], $options['Decisionsanctionep58']['arretsanction'] );
			$dateFinSanction = date_short( $gestionsanctionep58['Decisionsanctionrendezvousep58']['datearretsanction'] );
			$commentaireFinSanction = $gestionsanctionep58['Decisionsanctionrendezvousep58']['commentairearretsanction'];
		}

		$row = array(
			$gestionsanctionep58['Personne']['qual'].' '.$gestionsanctionep58['Personne']['nom'].' '.$gestionsanctionep58['Personne']['prenom'],
			Set::classicExtract(  $gestionsanctionep58, 'Adresse.numvoie' ).' '.value(  $typevoie, Set::classicExtract( $gestionsanctionep58, 'Adresse.typevoie' ) ).' '.Set::classicExtract(  $gestionsanctionep58, 'Adresse.nomvoie' )."\n".Set::classicExtract(  $gestionsanctionep58, 'Adresse.codepos' ).' '.Set::classicExtract(  $gestionsanctionep58, 'Adresse.locaadr' ),
			$gestionsanctionep58['Ep']['identifiant'],
			$gestionsanctionep58['Commissionep']['identifiant'],
			date_short( $gestionsanctionep58['Commissionep']['dateseance'] ),
			Set::classicExtract( $options['Dossierep']['themeep'], ( $gestionsanctionep58['Dossierep']['themeep'] ) ),
			$decisionSanction1."\n".$libelleSanction1,
			$decisionSanction2."\n".$libelleSanction2,
			$fieldDecisionSanction,
			$dateFinSanction,
			$commentaireFinSanction
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'listes_modification_sanctionep'.date( 'Ymd-His' ).'.csv' );