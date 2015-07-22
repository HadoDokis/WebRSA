<?php
	Configure::write(
		'Filtresdefaut.ActionscandidatsPersonnes_search',
		array(
			'Search' => array(
				'Pagination' => array(
					'nombre_total' => 0
				),
			)
		)
	);
	
	Configure::write(
		'ActionscandidatsPersonnes',
		array(
			'search' => array(
				'fields' => array (
					'Actioncandidat.name',
					'Partenaire.libstruc',
					'Personne.nom_complet',
					'Referent.nom_complet',
					'ActioncandidatPersonne.positionfiche',
					'ActioncandidatPersonne.datesignature',
					'/ActionscandidatsPersonnes/index/#ActioncandidatPersonne.personne_id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Adresse.numcom',
					'Adresse.nomcom',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet',
				)
			),
			'exportcsv' => array(
				'ActioncandidatPersonne.datesignature',
				'Personne.nom_complet',
				'Dossier.matricule',
				'Referent.nom_complet',
				'Actioncandidat.name',
				'ActioncandidatPersonne.formationregion',
				'ActioncandidatPersonne.nomprestataire',
				'Progfichecandidature66.name',
				'Partenaire.libstruc',
				'ActioncandidatPersonne.positionfiche',
				'ActioncandidatPersonne.sortiele',
				'ActioncandidatPersonne.motifsortie_id',
				'Adresse.numcom',
				'Adresse.nomcom',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
			)
		)
	);
?>