<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
		array(
			null,
			null,
			'Nombre de participants prévisionnel',
			'Report des participants de l\'année précédente, le cas échéant',
			null,
			null,
			'Entrées enregistrées, au titre de la période d\'exécution considérée',
			null,
			null,
			'Sorties enregistrées, au titre de la période d\'exécution considérée',
			null,
			null,
			sprintf( "Nombre de participants à l'action au 31/12/%d", $tableausuivipdv93['Tableausuivipdv93']['annee'] ),
			null,
			null,
		)
	);

	$this->Csv->addRow(
		array(
			null,
			null,
			'Total',
			'Total',
			'Hommes',
			'Femmes',
			'Total',
			'Hommes',
			'Femmes',
			'Total',
			'Hommes',
			'Femmes',
			'Total',
			'Hommes',
			'Femmes',
		)
	);

	foreach( $categories as $categorie => $foos ) {
		if( !in_array( $categorie, array( 'non_scolarise', 'diplomes_etrangers' ) ) ) {
			$this->Csv->addRow( array( __d( 'tableauxsuivispdvs93', "/Tableauxsuivispdvs93/tableaud1/{$categorie}" ) ) );
		}

		foreach( $foos as $key => $label ) {
			$row = array();

			// Présentation des lignes "Non scolarisé" et "Diplômes étrangers non reconnus en France"
			$lineTotal = array( 'total' => 0, 'homme' => 0, 'femme' => 0 );
			if( !in_array( $categorie, array( 'non_scolarise', 'diplomes_etrangers' ) ) ) {
				$row[] = __d( 'tableauxsuivispdvs93',  $label );
			}
			else {
				$row[] = __d( 'tableauxsuivispdvs93', "/Tableauxsuivispdvs93/tableaud1/{$categorie}" );
			}

			$hasResults = isset( $results[$categorie]['previsionnel'] );
			$total = (int)Hash::get( $results, "{$categorie}.{$key}.previsionnel" );

			$row[] = ( $hasResults ? $this->Locale->number( $total ) : 'N/C' );
			foreach( array( 'report', 'entrees', 'sorties' ) as $colonne ) {
				$bar = Hash::extract( $results, "{$categorie}.{s}.{$colonne}" );
				$baz = Hash::extract( $results, "{$categorie}.{n}.{$colonne}" );
				$hasResults = !empty( $bar ) || !empty( $baz );
				$hommes = (int)Hash::get( $results, "{$categorie}.{$key}.{$colonne}.homme" );
				$femmes = (int)Hash::get( $results, "{$categorie}.{$key}.{$colonne}.femme" );
				$total = $hommes + $femmes;

				if( $colonne != 'sortie' ) {
					$lineTotal['total'] += $total;
					$lineTotal['homme'] += $hommes;
					$lineTotal['femme'] += $femmes;
				}
				else {
					$lineTotal['total'] -= $total;
					$lineTotal['homme'] -= $hommes;
					$lineTotal['femme'] -= $femmes;
				}

				$row[] = ( $hasResults ? $this->Locale->number( $total ) : 'N/C' );
				$row[] = ( $hasResults ? $this->Locale->number( $hommes ) : 'N/C' );
				$row[] = ( $hasResults ? $this->Locale->number( $femmes ) : 'N/C' );
			}
			$row[] = $this->Locale->number( $lineTotal['total'] );
			$row[] = $this->Locale->number( $lineTotal['homme'] );
			$row[] = $this->Locale->number( $lineTotal['femme'] );

			$this->Csv->addRow( $row );
		}
	}

	Configure::write( 'debug', 0 );
	echo $this->Tableaud2->Csv->render(
		sprintf(
			"tableaud1-%s-%d-%s",
			empty( $tableausuivipdv93['Pdv']['lib_struc'] ) ? 'CG' : $tableausuivipdv93['Pdv']['lib_struc'],
			$tableausuivipdv93['Tableausuivipdv93']['annee'],
			date( 'Ymd-His' )
		).'.csv'
	);
?>