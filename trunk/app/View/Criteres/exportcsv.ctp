<?php
	$this->Csv->preserveLeadingZerosInExcel = true;

	$cells = array(
		'N° Dossier',
		'Nom/Prénom allocataire',
		'NIR',
		'Date de naissance',
		'N° CAF',
		'Identifiant Pôle Emploi',
		'N° Téléphone',
		'Adresse allocataire',
		'Complément adresse',
		'Code postal',
		'Commune de l\'allocataire',
        'Canton de l\'allocataire',
		'Date d\'ouverture de droit',
		'Etat du droit'
	);

	if( Configure::read( 'Cg.departement' ) == 93 ) {
		array_push( $cells, __d( 'orientstruct', 'Orientstruct.origine' ) );
	}

	array_push(
		$cells,
		'Date de l\'orientation',
		'Structure référente',
		'Statut de l\'orientation',
		'Soumis à droits et devoirs',
		'Nature de la prestation'
	);

	$this->Csv->addRow( $cells );

	foreach( $orients as $orient ) {
		$toppersdrodevorsa = Set::classicExtract( $orient, 'Calculdroitrsa.toppersdrodevorsa' );
		switch( $toppersdrodevorsa ) {
			case '0':
				$toppersdrodevorsa = 'Non';
				break;
			case '1':
				$toppersdrodevorsa = 'Oui';
				break;
			default:
				$toppersdrodevorsa = 'Non défini';
				break;
		}

		$row = array(
			Set::classicExtract( $orient, 'Dossier.numdemrsa' ),
			Set::classicExtract( $orient, 'Personne.nom' ).' '.Set::classicExtract( $orient, 'Personne.prenom'),
			Set::classicExtract( $orient, 'Personne.nir' ),
			date_short( Set::classicExtract( $orient, 'Personne.dtnai' ) ),
			Set::classicExtract( $orient, 'Dossier.matricule' ),
			Set::classicExtract( $orient, 'Historiqueetatpe.identifiantpe' ),
			Set::classicExtract( $orient, 'Modecontact.numtel' ),
			Set::classicExtract( $orient, 'Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $orient, 'Adresse.typevoie' ), $typevoie ).' '.Set::classicExtract( $orient, 'Adresse.nomvoie' ),
			Set::classicExtract( $orient, 'Adresse.complideadr' ).' '.Set::classicExtract( $orient, 'Adresse.compladr' ),
			Set::classicExtract( $orient, 'Adresse.codepos' ),
			Set::classicExtract( $orient, 'Adresse.locaadr' ),
            Set::classicExtract( $orient, 'Canton.canton' ),
			date_short( Set::classicExtract( $orient, 'Dossier.dtdemrsa' ) ),
			value( $etatdosrsa, Set::classicExtract( $orient, 'Situationdossierrsa.etatdosrsa' ) )
		);

		if( Configure::read( 'Cg.departement' ) == 93 ) {
			array_push(
				$row,
				value( $options['Orientstruct']['origine'], Set::extract( $orient, 'Orientstruct.origine' ) )
			);
		}

		array_push(
			$row,
			date_short( Set::classicExtract( $orient, 'Orientstruct.date_valid' ) ),
			Set::enum( Set::classicExtract( $orient, 'Orientstruct.structurereferente_id' ), $sr ),
			Set::classicExtract( $orient, 'Orientstruct.statut_orient' ),
			$toppersdrodevorsa,
			Set::enum( Set::classicExtract( $orient, 'Detailcalculdroitrsa.natpf' ), $natpf )
		);
		$this->Csv->addRow($row);
	}

	Configure::write( 'debug', 0 );
	echo $this->Csv->render( 'orientstructs-'.date( 'Ymd-Hhm' ).'.csv' );
?>