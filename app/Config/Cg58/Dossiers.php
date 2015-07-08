<?php
	/**
	 * Les conditions par défaut du moteur de recherche par dossiers /
	 * allocataires.
	 *
	 * @deprecated
	 *
	 * @var array
	 */
	Configure::write(
		'Filtresdefaut.Dossiers_index', array(
			'Calculdroitrsa' => array(
				'toppersdrodevorsa' => '1'
			)
		)
	);

	/**
	 * Les conditions par défaut du moteur de recherche par dossiers /
	 * allocataires.
	 *
	 * @var array
	 */
	Configure::write(
		'Filtresdefaut.Dossiers_search', array(
			'Calculdroitrsa' => array(
				'toppersdrodevorsa' => '1'
			)
		)
	);

	/**
	 * Les champs à faire apparaître dans les résultats de la recherche par
	 * dossier / allocataire:
	 *	- lignes du tableau: Dossiers.search.fields
	 *	- info-bulle du tableau: Dossiers.search.innerTable
	 *	- export CSV: Dossiers.exportcsv
	 *
	 * @var array
	 */
	Configure::write(
		'Dossiers',
		array(
			'search' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Personne.nir',
					'Situationdossierrsa.etatdosrsa',
					'Personne.nom_complet_prenoms',
					'Adresse.nomcom',
					'Dossier.locked' => array(
						'type' => 'boolean',
						'class' => 'dossier_locked'
					),
					'/Dossiers/view/#Dossier.id#'
				),
				'innerTable' => array(
					'Dossier.matricule',
					'Personne.dtnai',
					'Adresse.numcom' => array(
						'options' => array()
					),
					'Prestation.rolepers',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet',
					'Activite.act', // CG 58
					'Personne.etat_dossier_orientation', // CG 58
				)
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Dossier.dtdemrsa',
				'Personne.nir',
				'Situationdossierrsa.etatdosrsa',
				'Personne.nom_complet_prenoms',
				'Personne.dtnai',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Typeorient.lib_type_orient',
				'Personne.idassedic',
				'Dossier.matricule',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
				'Personne.sexe',
				'Dsp.natlog',
				'Activite.act', // CG 58
				'Personne.etat_dossier_orientation' // CG 58
			)
		)
	);
?>