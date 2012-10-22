<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
		array(
			'Commune',
			'Date de demande',
			'Nom/Prénom',
			'Date de naissance',
			'Date d\'orientation',
			'Soumis à droits et devoirs',
			'Présence d\'une DSP',
			'Rang CER',
			'Statut CER',
			'Forme CER',
			'Date d\'affectation',
			'Affectation',
		)
	);

	foreach( $cers93 as $cer93 ) {
		$row = array(
			$cer93['Adresse']['locaadr'],
			date_short( $cer93['Dossier']['dtdemrsa'] ),
			$cer93['Personne']['nom_complet_court'],
			date_short( $cer93['Personne']['dtnai'] ),
			date_short( $cer93['Orientstruct']['date_valid'] ),
			$this->Xhtml->boolean( $cer93['Calculdroitrsa']['toppersdrodevorsa'], false ),
			$this->Xhtml->boolean( $cer93['Dsp']['exists'], false ),
			$cer93['Contratinsertion']['rg_ci'],
			$cer93['Cer93']['positioncer'],
			$cer93['Cer93']['formeci'],
			date_short( $cer93['PersonneReferent']['dddesignation'] ),
			$cer93['Referent']['nom_complet'],
		);
		$this->Csv->addRow( $row );
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'cers93-'.date( 'Ymd-Hhm' ).'.csv' );
?>