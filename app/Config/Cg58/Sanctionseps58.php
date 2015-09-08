<?php
	/**
	 * Filtres de recerche par défaut pour la "Sélection des allocataires radiés
	 * de Pôle Emploi (nouveau)".
	 */
	Configure::write(
		'Filtresdefaut.Sanctionseps58_cohorte_radiespe',
		array(
		)
	);

	/**
	 * Filtres de recerche par défaut pour la "Sélection des allocataires non
	 * inscrits à Pôle Emploi (nouveau)".
	 */
	Configure::write(
		'Filtresdefaut.Sanctionseps58_cohorte_noninscritspe',
		array(
		)
	);

	Configure::write(
		'ConfigurableQuerySanctionseps58',
		array(
			/**
			 * Champs à utiliser dans les résultats de la "Sélection des
			 * allocataires radiés de Pôle Emploi (nouveau)"
			 */
			'cohorte_radiespe' => array(
				'fields' => array (
					'Dossier.matricule',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.nomcom',
					'Historiqueetatpe.etat',
					'Historiqueetatpe.code',
					'Historiqueetatpe.motif',
					'Historiqueetatpe.date',
					'Structureorientante.lib_struc',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
				),
				'innerTable' => array(
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet',
				),
				// FIXME: tri / tri original si on utilise la pagination normale (l'autre OK)
				'order' => array()
			),
			/**
			 * Champs à utiliser dans l'export CSV des résultats de la "Sélection
			 * des allocataires radiés de Pôle Emploi (nouveau)".
			 *
			 * @see app/View/Sanctionseps58/exportcsv.ctp, les champs ne sont
			 * pas les mêmes si la configuration de Selectionradies.conditions
			 * n'est pas vide ?
			 */
			'exportcsv_radiespe' => array(
				'Personne.nom',
				'Personne.prenom',
				'Personne.dtnai',
				'Adresse.nomcom',
				'Historiqueetatpe.etat',
				'Historiqueetatpe.code',
				'Historiqueetatpe.motif',
				'Historiqueetatpe.date',
				'Serviceinstructeur.lib_service',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet'
			),
			/**
			 * Champs à utiliser dans les résultats de la "Sélection des
			 * allocataires non inscrits à Pôle Emploi (nouveau)"
			 */
			'cohorte_noninscritspe' => array(
				'fields' => array (
					'Dossier.matricule',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.nomcom',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Structureorientante.lib_struc',
					'Orientstruct.date_valid',
				),
				'innerTable' => array(
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet',
				),
				// FIXME: tri / tri original si on utilise la pagination normale (l'autre OK)
				'order' => array()
			),
			/**
			 * Champs à utiliser dans l'export CSV des résultats de la "Sélection
			 * des allocataires radiés de Pôle Emploi (nouveau)".
			 */
			'exportcsv_noninscritspe' => array(
				'Personne.nom',
				'Personne.prenom',
				'Personne.dtnai',
				'Adresse.nomcom',
				'Typeorient.lib_type_orient',
				'Structurereferente.lib_struc',
				'Orientstruct.date_valid',
				'Serviceinstructeur.lib_service',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
			)
		)
	);
