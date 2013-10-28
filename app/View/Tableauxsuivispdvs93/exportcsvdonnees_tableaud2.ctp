<?php
	$this->Tableaud2->Csv->preserveLeadingZerosInExcel = true;

	$this->Tableaud2->Csv->addRow(
		array(
			null,
			null,
			null,
			'Nombre de personnes',
			'En %',
			'Dont hommes',
			'En %',
			'Dont femmes',
			'En %',
			'Dont couvert par un CER = Objectif "SORTIE"',
			'En %',
		)
	);

	$this->Tableaud2->line1CategorieCsv( 'maintien', $results );
	$this->Tableaud2->line3CategorieCsv( 'sortie_obligation', $results, $categories );
	$this->Tableaud2->line1CategorieCsv( 'abandon', $results );
	$this->Tableaud2->line1CategorieCsv( 'reorientation', $results );
	$this->Tableaud2->line2CategorieCsv( 'changement_situation', $results, $categories );

	Configure::write( 'debug', 0 );
	echo $this->Tableaud2->Csv->render(
		sprintf(
			"tableaud2-%s-%d-%s",
			empty( $tableausuivipdv93['Pdv']['lib_struc'] ) ? 'CG' : $tableausuivipdv93['Pdv']['lib_struc'],
			$tableausuivipdv93['Tableausuivipdv93']['annee'],
			date( 'Ymd-His' )
		).'.csv'
	);
?>