<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$this->Csv->addRow( array( 'Date du bilan de parcours', 'Nom de la personne', 'N° CAF', 'Type de structure', 'Nom du prescripteur', 'Type de commission', 'Position du bilan', 'Choix du parcours', 'Saisine EP', 'Code INSEE', 'Localité' ) );

	foreach( $bilansparcours66 as $bilanparcours66 ) {
		$isSaisine = 'Non';
		if( isset( $bilanparcours66['Dossierep']['themeep'] ) ){
			$isSaisine = 'Oui';
		}

		$motif = null;
		if (empty($bilanparcours66['Bilanparcours66']['choixparcours']) && !empty($bilanparcours66['Bilanparcours66']['examenaudition'])) {
			$motif = Set::classicExtract( $options['examenaudition'], $bilanparcours66['Bilanparcours66']['examenaudition'] );
		}
		elseif (empty($bilanparcours66['Bilanparcours66']['choixparcours']) && empty($bilanparcours66['Bilanparcours66']['examenaudition'])) {
			if ($bilanparcours66['Bilanparcours66']['maintienorientation']=='0') {
				$motif = 'Réorientation';
			}
			else {
				$motif = 'Maintien';
			}
		}
		else {
			$motif = Set::classicExtract( $options['choixparcours'], $bilanparcours66['Bilanparcours66']['choixparcours'] );
		}

		$row = array(
			$this->Locale->date( 'Date::short', Set::classicExtract( $bilanparcours66, 'Bilanparcours66.datebilan' ) ),
			Set::classicExtract( $bilanparcours66, 'Personne.nom_complet' ),
			Set::classicExtract( $bilanparcours66, 'Dossier.matricule' ),
			Set::classicExtract( $bilanparcours66, 'Structurereferente.lib_struc' ),
			Set::classicExtract( $bilanparcours66, 'Referent.nom_complet' ),
			Set::classicExtract( $options['proposition'], $bilanparcours66['Bilanparcours66']['proposition'] ),
			Set::enum( Set::classicExtract( $bilanparcours66, 'Bilanparcours66.positionbilan' ), $options['positionbilan'] ),
			$motif,
			$isSaisine,
			$bilanparcours66['Adresse']['numcomptt'],
			$bilanparcours66['Adresse']['locaadr']
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'bilansparcours66-'.date( 'Ymd-His' ).'.csv' );
?>