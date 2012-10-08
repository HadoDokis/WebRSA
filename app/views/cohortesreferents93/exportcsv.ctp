<?php
	$csv->preserveLeadingZerosInExcel = true;

	$csv->addRow(
		array(
			'Commune',
			'Date de demande',
			'Date d\'orientation',
			'Date de naissance',
			'Soumis à droits et devoirs',
			'Présence d\'une DSP',
			'Rang CER',
			'Nom, prénom',
			'Date d\'affectation',
			'Affectation',
		)
	);

	foreach( $personnes_referents as $personne_referent ) {
		$row = array(
			$personne_referent['Adresse']['locaadr'],
			date_short( $personne_referent['Dossier']['dtdemrsa'] ),
			date_short( $personne_referent['Orientstruct']['date_valid'] ),
			date_short( $personne_referent['Personne']['dtnai'] ),
			$xhtml->boolean( $personne_referent['Calculdroitrsa']['toppersdrodevorsa'], false ),
			$xhtml->boolean( $personne_referent['Dsp']['exists'], false ),
			$personne_referent['Contratinsertion']['rg_ci'],
			$personne_referent['Personne']['nom_complet_court'],
			date_short( $personne_referent['PersonneReferent']['dddesignation'] ),
			$personne_referent['Referent']['nom_complet'],
		);
		$csv->addRow( $row );
	}

	Configure::write( 'debug', 0 );
	echo $csv->render( 'personnes_referents-'.date( 'Ymd-Hhm' ).'.csv' );
?>