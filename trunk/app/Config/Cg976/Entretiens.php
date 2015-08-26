<?php
	/**
	 * Valeurs des filtres de recherche par défaut pour la "Recherche par entretiens
	 * (nouveau)"
	 *
	 * @var array
	 */
	Configure::write(
			'Filtresdefaut.Entretiens_search',
		array(
			'Dossier' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'dernier' => true
			)
		)
	);

	/**
	 * Les champs à faire apparaître dans les résultats de la recherche par
	 * entretiens:
	 *	- lignes du tableau: ConfigurableQueryEntretiens.search.fields
	 *	- info-bulle du tableau: ConfigurableQueryEntretiens.search.innerTable
	 *	- export CSV: ConfigurableQueryEntretiens.exportcsv
	 *
	 * @var array
	 */
	Configure::write(
		'ConfigurableQueryEntretiens',
		array(
			'search' => array(
				'fields' => array(
					'Entretien.dateentretien',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					'Entretien.typeentretien',
					'Objetentretien.name',
					'Entretien.arevoirle' => array(
						'format' => '%B %Y'
					),
					'/Entretiens/index/#Entretien.personne_id#'
				),
				'innerTable' => array(
					'Personne.dtnai',
					'Dossier.matricule',
					'Personne.nir',
					'Adresse.codepos',
					'Adresse.numcom',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet',
				)
			),
			'exportcsv' => array(
				'Entretien.dateentretien',
				'Personne.nom_complet',
				'Dossier.matricule',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Structurereferente.lib_struc',
				'Referent.nom_complet',
				'Entretien.typeentretien',
				'Objetentretien.name',
				'Entretien.arevoirle' => array(
					'format' => '%B %Y'
				),
				'Referentparcours.nom_complet',
				'Structurereferenteparcours.lib_struc'
			)
		)
	);
?>