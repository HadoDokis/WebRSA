<?php
	/**
	 * Valeurs des filtres de recherche par défaut pour la "Recherche par dossier
	 * / allocataire (nouveau)"
	 *
	 * @var array
	 */
	Configure::write(
			'Filtresdefaut.Dossiers_search',
		array(
			'Dossier' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'dernier' => true,
				// Case à cocher "Filtrer par date de demande RSA"
				'dtdemrsa' => false,
				// Du (inclus)
				'dtdemrsa_from' => date( 'Y-m-d', strtotime( '-1 week' ) ),
				// Au (inclus)
				'dtdemrsa_to' => date( 'Y-m-d', strtotime( 'now' ) ),
			)
		)
	);

	/**
	 * Les champs à faire apparaître dans les résultats de la recherche par
	 * dossier / allocataire:
	 *	- lignes du tableau: ConfigurableQueryDossiers.search.fields
	 *	- info-bulle du tableau: ConfigurableQueryDossiers.search.innerTable
	 *	- export CSV: ConfigurableQueryDossiers.exportcsv
	 *
	 * @var array
	 */
	Configure::write(
		'ConfigurableQueryDossiers',
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
					'Referentparcours.nom_complet'
				),
				'order' => array( 'Personne.nom' )
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
				'Dsp.natlog'
			)
		)
	);
?>