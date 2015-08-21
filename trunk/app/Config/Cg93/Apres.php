<?php
	/**
	 * Valeurs des filtres de recherche par défaut pour la recherche par APRE
	 *
	 * @var array
	 */
	Configure::write(
			'Filtresdefaut.Apres_search',
		array(
			'Apre' => array(
				'statutapre' => 'C' // TODO: à ajouter dans les filtres
			),
			'Dossier' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'dernier' => true
			)
		)
	);


	/**
	 * Valeurs des filtres de recherche par défaut pour la recherche par
	 * éligibilité des APRE.
	 *
	 * @var array
	 */
	Configure::write(
		'Filtresdefaut.Apres_search_eligibilite',
		array(
			'Apre' => array(
				'statutapre' => 'C'
			),
			'Dossier' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'dernier' => true
			)
		)
	);

	/**
	 * Les champs à faire apparaître dans les résultats de la recherche par
	 * apres:
	 *	- Recherche de demande APRE
	 *		* lignes du tableau: Apres.search.fields
	 *		* info-bulle du tableau: Apres.search.innerTable
	 *		* export CSV: Apres.exportcsv
	 *	- Etat des demandes d'APRE
	 *		* lignes du tableau: Apres.search_eligibilite.fields
	 *		* info-bulle du tableau: Apres.search_eligibilite.innerTable
	 *		* export CSV: Apres.exportcsv_eligibilite
	 * @var array
	 */
	Configure::write(
		'Apres',
		array(
			'search' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Apre.numeroapre',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Apre.datedemandeapre',
					'Apre.natureaide' => array(
						'type' => 'list'
					),
					'Apre.typedemandeapre',
					'Apre.activitebeneficiaire',
					'Apre.etatdossierapre',
					'/Apres/index/#Apre.personne_id#' => array(
						'class' => 'view'
					),
				),
				'innerTable' => array(
					'Dossier.matricule',
					'Personne.dtnai',
					'Adresse.numcom' => array(
						'options' => array()
					),
					'Personne.nir',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				)
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Personne.nom_complet',
				'Adresse.nomcom',
				'Apre.datedemandeapre',
				'Apre.natureaide' => array(
					'type' => 'list'
				),
				'Apre.typedemandeapre',
				'Apre.activitebeneficiaire',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet'
			),
			// -----------------------------------------------------------------
			'search_eligibilite' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Apre.numeroapre',
					'Personne.nom_complet',
					'Apre.datedemandeapre',
					'Apre.eligibiliteapre',
					'Apre.etatdossierapre',
					'Relanceapre.daterelance',
					'Comiteapre.datecomite',
					'/Apres/index/#Apre.personne_id#' => array(
						'class' => 'view'
					),
				),
				'innerTable' => array(
					'Dossier.matricule',
					'Personne.dtnai',
					'Adresse.numcom' => array(
						'options' => array()
					),
					'Personne.nir',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				)
			),
			'exportcsv_eligibilite' => array(
				'Dossier.numdemrsa',
				'Personne.nom_complet',
				'Adresse.nomcom',
				'Apre.datedemandeapre',
				'Apre.eligibiliteapre',
				'Apre.etatdossierapre',
				'Relanceapre.daterelance',
				'Comiteapre.datecomite',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet'
			)
		)
	);
?>