<?php
	/**
	 * Valeurs par défaut des filtres pour le moteur de recherche "Recherches" >
	 * "Par dossiers COV" > "Demande de maintien dans le social (nouveau)"
	 */
	Configure::write(
		'Filtresdefaut.Nonorientationsproscovs58_cohorte',
		array(
			'Search' => array(
				'Contratinsertion' => array(
					'df_ci_from' => strtotime( '-1 week' ),
					'df_ci_to' => strtotime( 'now' )
				),
				'Pagination' => array(
					'nombre_total' => 0
				)
			)
		)
	);

	/**
	 * Valeurs par défaut des filtres pour le moteur de recherche "Recherches" >
	 * "Par dossiers COV" > "Demande de maintien dans le social"
	 */
	Configure::write(
		'Filtresdefaut.Nonorientationsproscovs58_cohorte1',
		array(
			'Search' => array(
				'Contratinsertion' => array(
					'df_ci_from' => strtotime( '-1 week' ),
					'df_ci_to' => strtotime( 'now' )
				),
				'Pagination' => array(
					'nombre_total' => 0
				)
			)
		)
	);

	Configure::write(
		'ConfigurableQueryNonorientationsproscovs58',
		array(
			/**
			 * Champs à utiliser dans les résultats de la "Demandes de maintien
			 * dans le social (nouveau)"
			 */
			'cohorte' => array(
					'fields' => array (
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Personne.dtnai',
					'Adresse.codepos',
					'Foyer.enerreur' => array( 'sort' => false ),
					'Orientstruct.date_valid',
					'Contratinsertion.nbjours',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					'/Orientsstructs/index/#Personne.id#'
				),
				'innerTable' => array(
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet',
				),
				'order' => array()
			),
			/**
			 * Champs à utiliser dans l'export CSV des résultats de la "Demandes
			 * de maintien dans le social (nouveau)".
			 */
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Personne.nom',
				'Personne.prenom',
				'Personne.dtnai',
				'Adresse.nomcom',
				'Orientstruct.date_valid',
				'Contratinsertion.nbjours',
				'Typeorient.lib_type_orient',
				'Structurereferente.lib_struc',
				'Referent.nom_complet',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet'
			)
		)
	);
