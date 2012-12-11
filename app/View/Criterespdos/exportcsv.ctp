<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'N° Dossier', 'Nom/Prénom allocataire', 'N° CAF', 'Commune de l\'allocataire', 'Proposition de décision', 'Motif PDO', 'Date de proposition de décision', 'Gestionnaire' ) );
// debug($pdos);
// die();
	foreach( $pdos as $pdo ) {
		$row = array(
			Set::classicExtract( $pdo, 'Dossier.numdemrsa' ),
			Set::classicExtract( $pdo, 'Personne.nom' ).' '.Set::classicExtract( $pdo, 'Personne.prenom'),
			Set::classicExtract( $pdo, 'Dossier.matricule' ),
			Set::classicExtract( $pdo, 'Adresse.locaadr' ),
			Set::enum( Set::classicExtract( $pdo, 'Decisionpropopdo.decisionpdo_id' ), $decisionpdo ),
			Set::enum( Set::classicExtract( $pdo, 'Propopdo.motifpdo' ), $motifpdo ),
			$this->Locale->date( 'Date::short', Set::classicExtract( $pdo, 'Decisionpropopdo.datedecisionpdo' ) ),
			Set::enum( Set::classicExtract( $pdo, 'Propopdo.user_id' ), $gestionnaire )
		);

		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'pdos-'.date( 'Ymd-His' ).'.csv' );
?>