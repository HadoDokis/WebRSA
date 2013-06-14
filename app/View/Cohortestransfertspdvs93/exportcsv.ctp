<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
		array(
			__d( 'dossier', 'Dossier.numdemrsa' ),
			__d( 'dossier', 'Dossier.matricule' ),
			'Adresse actuelle',
			'Allocataire',
			__d( 'prestation', 'Prestation.rolepers' ),
			'Date de transfert',
			'Structure référente source',
			'Structure référente cible',
		)
	);

	foreach( $results as $result ) {
		$row = array(
			h( $result['Dossier']['numdemrsa'] ),
			h( $result['Dossier']['matricule'] ),
			h( "{$result['Adresse']['codepos']} {$result['Adresse']['locaadr']}" ),
			h( "{$options['qual'][$result['Personne']['qual']]} {$result['Personne']['nom']} {$result['Personne']['prenom']}" ),
			$options['rolepers'][$result['Prestation']['rolepers']],
			$this->Locale->date( __( 'Date::short' ), $result['Transfertpdv93']['created'] ),
			$result['VxStructurereferente']['lib_struc'],
			$result['Structurereferente']['lib_struc'],
		);
		$this->Csv->addRow( $row );
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( "{$this->request->params['controller']}_{$this->request->params['action']}_".date( 'Ymd-His' ).'.csv' );
?>