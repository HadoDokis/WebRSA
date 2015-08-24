<?php
	/**
	 * Valeurs des filtres de recherche par défaut pour la "Recherche par
	 * orientation (nouveau)"
	 *
	 * @var array
	 */
	Configure::write(
			'Filtresdefaut.Orientsstructs_search',
		array(
			'Dossier' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'dernier' => true
			)
		)
	);

	/**
	 * Les champs à faire apparaître dans les résultats de la "Recherche par
	 * orientation (nouveau)"
	 *	- lignes du tableau: ConfigurableQueryOrientsstructs.search.fields
	 *	- info-bulle du tableau: ConfigurableQueryOrientsstructs.search.innerTable
	 *	- export CSV: ConfigurableQueryOrientsstructs.exportcsv
	 *
	 * @var array
	 */
	Configure::write(
		'ConfigurableQueryOrientsstructs',
		array(
			'search' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Dossier.dtdemrsa',
					'Orientstruct.date_valid',
					'Orientstruct.propo_algo',
					'Orientstruct.origine',
					'Typeorient.lib_type_orient',
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
				),
				'order' => array()
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
				'Dossier.dtdemrsa',
				'Situationdossierrsa.etatdosrsa',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
				'Orientstruct.origine',
				'Orientstruct.date_valid',
				'Structurereferente.lib_struc',
				'Orientstruct.statut_orient',
				'Calculdroitrsa.toppersdrodevorsa',
			)
		)
	);
?>