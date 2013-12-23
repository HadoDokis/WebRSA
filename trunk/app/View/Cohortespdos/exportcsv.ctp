<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
		array(
			'N° demande RSA',
			'Date demande RSA',
			'Nom/Prénom allocataire',
			'Date de naissance',
			'Commune',
			'Type de PDO',
			'Date de soumission PDO',
			'Décision PDO',
			'Motif PDO',
			'Commentaires PDO',
			'Gestionnaire',
			__d( 'search_plugin', 'Structurereferenteparcours.lib_struc' ),
			__d( 'search_plugin', 'Referentparcours.nom_complet' ),
		)
	);

	foreach( $pdos as $pdo ) {
		$row = array(
			Set::classicExtract( $pdo, 'Dossier.numdemrsa' ),
			$this->Locale->date( 'Date::short', Set::classicExtract( $pdo, 'Dossier.dtdemrsa' ) ),
			Set::classicExtract( $pdo, 'Personne.nom' ).' '.Set::classicExtract( $pdo, 'Personne.prenom'),
			$this->Locale->date( 'Date::short', Set::classicExtract( $pdo, 'Personne.dtnai' ) ),
			Set::classicExtract( $pdo, 'Adresse.locaadr' ),
			Set::enum( Set::classicExtract( $pdo, 'Propopdo.typepdo_id' ), $typepdo ),
			$this->Locale->date( 'Date::short', Set::classicExtract( $pdo, 'Propopdo.datedecisionpdo' ) ),
			Set::enum( Set::classicExtract( $pdo, 'Propopdo.decisionpdo_id' ), $decisionpdo ),
			Set::enum( Set::classicExtract( $pdo, 'Propopdo.motifpdo' ), $motifpdo ),
			Set::classicExtract( $pdo, 'Propopdo.commentairepdo' ),
			Set::classicExtract( $gestionnaire, Set::classicExtract( $pdo, 'Propopdo.user_id' ) ),
			Hash::get( $pdo, 'Structurereferenteparcours.lib_struc' ),
			Hash::get( $pdo, 'Referentparcours.nom_complet' ),
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'pdos-'.date( 'Ymd-His' ).'.csv' );
?>