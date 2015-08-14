<?php
	Configure::write(
		'Filtresdefaut.Orientsstructs_search',
		array(
			'Search' => array(
				'Pagination' => array(
					'nombre_total' => 0
				),
			)
		)
	);

	Configure::write(
		'Orientsstructs',
		array(
			'search' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Dossier.dtdemrsa',
					'Orientstruct.date_valid',
					'Orientstruct.typeorient_id',
					'Structurereferente.lib_struc',
					'Orientstruct.statut_orient',
					'Calculdroitrsa.toppersdrodevorsa' => array( 'type' => 'boolean' ),
					'/Orientsstructs/index/#Orientstruct.personne_id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Situationdossierrsa.etatdosrsa',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Historiqueetatpe.identifiantpe',
					'Modecontact.numtel',
					'Prestation.rolepers',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet',
				)
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Personne.nir',
				'Personne.dtnai',
				'Dossier.matricule',
				'Historiqueetatpe.identifiantpe',
				'Modecontact.numtel',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Canton.canton',
				'Dossier.dtdemrsa',
				'Situationdossierrsa.etatdosrsa',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
				'Orientstruct.date_valid',
				'Structurereferente.lib_struc',
				'Orientstruct.statut_orient',
				'Calculdroitrsa.toppersdrodevorsa',
			)
		)
	);
?>