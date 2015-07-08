<?php
	/**
	 * Les champs à faire apparaître dans les résultats de la recherche par
	 * entretiens:
	 *	- lignes du tableau: Criteresentretiens.index.fields
	 *	- info-bulle du tableau: Criteresentretiens.index.innerTable
	 *	- export CSV: Criteresentretiens.exportcsv
	 *
	 * @var array
	 */
	Configure::write(
		'Entretiens',
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

	// FIXME: nettoyer, les autres CG
	// FIXME: traductions dans entretiens_search à présent
?>