<?php
	/**
	 * Cohorte
	 */
	Configure::write(
		'ConfigurableQuery.Apres66.cohorte_validation',
		array(
			// 1. Filtres de recherche
			'filters' => array(
				// 1.1 Valeurs par défaut des filtres de recherche
				'defaults' => array(
					'Dossier' => array(
						// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
						'dernier' => '1'
					)
				),
				// 1.2 Restriction des valeurs qui apparaissent dans les filtres de recherche
				'accepted' => array(),
				// 1.3 Ne pas afficher ni traiter certains filtres de recherche
				'skip' => array()
			),
			// 2. Recherche
			'query' => array(
				// 2.1 Restreindre ou forcer les valeurs renvoyées par le filtre de recherche
				'restrict' => array(
					'Apre66.etatdossierapre' => 'COM',
					'Apre66.isdecision' => 'N',
				),
				// 2.2 Conditions supplémentaires optionnelles
				'conditions' => array(),
				// 2.3 Tri par défaut
				'order' => array(
					'Personne.nom',
					'Personne.prenom'
				)
			),
			// 3. Nombre d'enregistrements par page
			'limit' => 10,
			// 4. Lancer la recherche au premier accès à la page ?
			'auto' => false,
			// 5. Résultats de la recherche
			'results' => array(
				// 5.1 Ligne optionnelle supplémentaire d'en-tête du tableau de résultats
				'header' => array(),
				// 5.2 Colonnes du tableau de résultats
				'fields' => array (
					'Apre66.numeroapre',
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Referentapre.nom_complet',
					'Aideapre66.datedemande',
					'Aideapre66.montantpropose',
					'/Apres66/index/#Personne.id#' => array( 'class' => 'view external' ),
				),
				// 5.3 Infobulle optionnelle du tableau de résultats
				'innerTable' => array(
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				)
			),
			// 6. Temps d'exécution, mémoire maximum, ...
			'ini_set' => array()
		)
	);

	/**
	 * Export CSV
	 */
	Configure::write(
		'ConfigurableQuery.Apres66.exportcsv_validation',
		array(
			// 1. Filtres de recherche, on reprend la configuration de la recherche
			'filters' => Configure::read( 'ConfigurableQuery.Apres66.cohorte_validation.filters' ),
			// 2. Recherche, on reprend la configuration de la recherche
			'query' => Configure::read( 'ConfigurableQuery.Apres66.cohorte_validation.query' ),
			// 3. Résultats de la recherche
			'results' => array(
				'fields' => array(
					'Apre66.numeroapre',
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Referentapre.nom_complet',
					'Apre66.datedemandeapre',
					'Aideapre66.montantpropose',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				)
			),
			// 4. Temps d'exécution, mémoire maximum, ...
			'ini_set' => Configure::read( 'ConfigurableQuery.Apres66.cohorte_validation.ini_set' ),
		)
	);
?>