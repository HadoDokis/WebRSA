<?php
	Configure::write(
		'Filtresdefaut.Indus_search',
		array(
			'Pagination' => array(
				'nombre_total' => 0
			),
			'Dossier' => array(
				'dernier' => true
			)
		)
	);

	Configure::write(
		'ConfigurableQueryIndus',
		array(
			'search' => array(
				'fields' => array (
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Dossier.typeparte',
					'Situationdossierrsa.etatdosrsa',
					'Indu.moismoucompta' => array( 'type' => 'date', 'format' => '%B %Y' ),
					'IndusConstates.mtmoucompta',
					'IndusTransferesCG.mtmoucompta',
					'RemisesIndus.mtmoucompta',
					'/Indus/view/#Dossier.id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Personne.dtnai',
					'Dossier.matricule',
					'Personne.nir',
					'Adresse.codepos',
					'Adresse.numcom',
					'Prestation.rolepers',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array()
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Dossier.matricule',
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Dossier.typeparte',
				'Situationdossierrsa.etatdosrsa',
				'Indu.moismoucompta' => array( 'type' => 'date', 'format' => '%B %Y' ),
				'IndusConstates.mtmoucompta',
				'IndusTransferesCG.mtmoucompta',
				'RemisesIndus.mtmoucompta',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
			)
		)
	);
?>
