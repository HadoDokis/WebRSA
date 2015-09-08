<?php
	Configure::write(
		'Filtresdefaut.Apres_search',
		array(
			'Pagination' => array(
				'nombre_total' => 0
			),
			'Dossier' => array(
				'dernier' => '1'
			)
		)
	);

	Configure::write(
		'ConfigurableQueryApres',
		array(
			'search' => array(
				'fields' => array (
					'Dossier.numdemrsa',
					'Apre.numeroapre',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Aideapre66.datedemande',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					'Apre.activitebeneficiaire',
					'Apre.etatdossierapre',
					'Apre.isdecision',
					'Aideapre66.decisionapre',
					'/Apres'.Configure::read( 'Apre.suffixe' ).'/index/#Apre.personne_id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Dossier.matricule',
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array()
			),
			'exportcsv' => array(
				'Personne.nom_complet',
				'Aideapre66.datedemande' => array( 'type' => 'date' ),
				'Themeapre66.name',
				'Typeaideapre66.name',
				'Structurereferente.lib_struc',
				'Referent.nom_complet',
				'Apre.etatdossierapre',
				'Aideapre66.decisionapre',
				'Aideapre66.montantaccorde',
				'Canton.canton',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet'
			)
		)
	);
?>