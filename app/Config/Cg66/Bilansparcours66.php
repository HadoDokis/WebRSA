<?php
	Configure::write(
		'Filtresdefaut.Bilansparcours66_search',
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
		'Bilansparcours66',
		array(
			'search' => array(
				'fields' => array (
					'Dossier.numdemrsa',
					'Bilanparcours66.datebilan',
					'Personne.nom_complet',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					'Bilanparcours66.proposition',
					'Bilanparcours66.positionbilan',
					'Bilanparcours66.choixparcours',
					'Bilanparcours66.examenaudition',
					'Bilanparcours66.examenauditionpe',
					'Dossierep.themeep',
					'/Bilansparcours66/index/#Bilanparcours66.personne_id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Adresse.numcom',
					'Adresse.nomcom',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				)
			),
			'exportcsv' => array(
				'Bilanparcours66.datebilan',
				'Personne.nom_complet',
				'Dossier.matricule',
				'Structurereferente.lib_struc',
				'Referent.nom_complet',
				'Bilanparcours66.proposition',
				'Bilanparcours66.positionbilan',
				'Bilanparcours66.choixparcours',
				'Bilanparcours66.examenaudition',
				'Bilanparcours66.examenauditionpe',
				'Adresse.numcom',
				'Adresse.nomcom',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet'
			)
		)
	);
?>