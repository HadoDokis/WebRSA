<?php
	/**
	 * Menu "Recherches" > "Par allocataires sortants" > "Intra-département (nouveau)"
	 */
	Configure::write(
		'ConfigurableQuery.Transfertspdvs93.search',
		array(
			// 1. Filtres de recherche
			'filters' => array(
				// 1.1 Valeurs par défaut des filtres de recherche
				'defaults' => array(
				),
				// 1.2 Restriction des valeurs qui apparaissent dans les filtres de recherche
				'accepted' => array(),
				// 1.3 Ne pas afficher ni traiter certains filtres de recherche
				'skip' => array()
			),
			// 2. Recherche
			'query' => array(
				// 2.1 Restreindre ou forcer les valeurs renvoyées par le filtre de recherche
				'restrict' => array(),
				// 2.2 Conditions supplémentaires optionnelles
				'conditions' => array(),
				// 2.3 Tri par défaut
				'order' => array()
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
				'fields' => array(
					'Dossier.numdemrsa' => array( 'sort' => false ),
					'Dossier.matricule' => array( 'sort' => false ),
					'Adresse.localite' => array( 'sort' => false ),
					'Personne.nom_complet' => array( 'sort' => false ),
					'Prestation.rolepers' => array( 'sort' => false ),
					'Transfertpdv93.created' => array(
						'sort' => false,
						'format' => '%d/%m/%Y'
					),
					'VxStructurereferente.lib_struc' => array( 'sort' => false ),
					'NvStructurereferente.lib_struc' => array( 'sort' => false ),
					'/Dossiers/view/#Dossier.id#' => array(
						'class' => 'external'
					)
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
	 * Export CSV,  menu "Recherches" > "Par allocataires sortants" > "Intra-département (nouveau)"
	 */
	Configure::write(
		'ConfigurableQuery.Transfertspdvs93.exportcsv',
		array(
			// 1. Filtres de recherche, on reprend la configuration de la recherche
			'filters' => Configure::read( 'ConfigurableQuery.Transfertspdvs93.search.filters' ),
			// 2. Recherche, on reprend la configuration de la recherche
			'query' => Configure::read( 'ConfigurableQuery.Transfertspdvs93.search.query' ),
			// 3. Résultats de la recherche
			'results' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Dossier.matricule',
					'Adresse.codepos',
					'Adresse.nomcom',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Prestation.rolepers',
					'Transfertpdv93.created' => array( 'type' => 'date' ),
					'VxStructurereferente.lib_struc',
					'NvStructurereferente.lib_struc',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				)
			),
			// 4. Temps d'exécution, mémoire maximum, ...
			'ini_set' => Configure::read( 'ConfigurableQuery.Transfertspdvs93.search.ini_set' ),
		)
	);
?>