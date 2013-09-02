<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow(
        array(
            'N° Dossier',
            'Nom/Prénom allocataire',
            'N° CAF',
            'Numéro de voie',
            'Type de voie',
            'Nom de voie',
            'Complément adresse 1',
            'Complément adresse 2',
            'Code postal',
            'Commune',
            'Proposition de décision',
            'Motif PDO',
            'Date de proposition de décision',
            'Gestionnaire'
        )
    );
// debug($pdos);
// die();
	foreach( $pdos as $pdo ) {
		$row = array(
			Hash::get( $pdo, 'Dossier.numdemrsa' ),
			value( $qual, Hash::get( $pdo, 'Personne.qual' ) ).' '.Hash::get( $pdo, 'Personne.nom' ).' '.Hash::get( $pdo, 'Personne.prenom'),
			Hash::get( $pdo, 'Dossier.matricule' ),
			Hash::get( $pdo, 'Adresse.numvoie' ),
			value( $typevoie, Hash::get( $pdo, 'Adresse.typevoie' ) ),
			Hash::get( $pdo, 'Adresse.nomvoie' ),
			Hash::get( $pdo, 'Adresse.complideadr' ),
			Hash::get( $pdo, 'Adresse.compladr' ),
			Hash::get( $pdo, 'Adresse.codepos' ),
			Hash::get( $pdo, 'Adresse.locaadr' ),
			Set::enum( Hash::get( $pdo, 'Decisionpropopdo.decisionpdo_id' ), $decisionpdo ),
			Set::enum( Hash::get( $pdo, 'Propopdo.motifpdo' ), $motifpdo ),
			$this->Locale->date( 'Date::short', Hash::get( $pdo, 'Decisionpropopdo.datedecisionpdo' ) ),
			Set::enum( Hash::get( $pdo, 'Propopdo.user_id' ), $gestionnaire )
		);

		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'pdos-'.date( 'Ymd-His' ).'.csv' );
?>