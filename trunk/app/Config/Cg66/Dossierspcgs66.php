<?php
	/**
	 * Menu Recherche de dossiers PCGs (nouveau)
	 */
	Configure::write(
		'ConfigurableQuery.Dossierspcgs66.search',
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
				'restrict' => array(),
				// 2.2 Conditions supplémentaires optionnelles
				'conditions' => array(),
				// 2.3 Tri par défaut
				//'order' => array( 'Personne.nom', 'Personne.prenom' )
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
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Dossierpcg66.originepdo_id',
					'Dossierpcg66.typepdo_id',
					'Dossierpcg66.datereceptionpdo',
					'Poledossierpcg66.name',
					'User.nom_complet',
					'Dossierpcg66.nbpropositions',
					'Dossierpcg66.etatdossierpcg',
					'Decisiondossierpcg66.org_id',
					'Decisiondossierpcg66.datetransmissionop',
					'Traitementpcg66.datereception',
					'Decisionpdo.libelle',
					'Traitementpcg66.situationpdo_id',
					'Traitementpcg66.statutpdo_id',
					'Fichiermodule.nb_fichiers_lies',
					'/Dossierspcgs66/index/#Dossierpcg66.foyer_id#' => array( 'class' => 'view' ),
					'/Dossierspcgs66/edit/#Dossierpcg66.id#' => array( 'class' => 'edit' ),
				),
				// 5.3 Infobulle optionnelle du tableau de résultats
				'innerTable' => array(
					'Situationdossierrsa.etatdosrsa',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Dossier.matricule',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				)
			),
			// 6. Temps d'exécution, mémoire maximum, ...
			'ini_set' => array()
		)
	);

	/**
	 * Export CSV,  menu "Recherches" > "Par entretiens (nouveau)"
	 */
	Configure::write(
		'ConfigurableQuery.Dossierspcgs66.exportcsv',
		array(
			// 1. Filtres de recherche, on reprend la configuration de la recherche
			'filters' => Configure::read( 'ConfigurableQuery.Dossierspcgs66.search.filters' ),
			// 2. Recherche, on reprend la configuration de la recherche
			'query' => Configure::read( 'ConfigurableQuery.Dossierspcgs66.search.query' ),
			// 3. Résultats de la recherche
			'results' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Dossierpcg66.originepdo_id',
					'Dossierpcg66.typepdo_id',
					'Dossierpcg66.datereceptionpdo',
					'Dossierpcg66.poledossierpcg66_id',
					'User.nom_complet',
					'Decisionpdo.libelle',
					'Dossierpcg66.nbpropositions',
					'Dossierpcg66.etatdossierpcg',
					'Decisiondossierpcg66.org_id',
					'Decisiondossierpcg66.datetransmissionop',
					'Traitementpcg66.datereception',
					'Traitementpcg66.situationpdo_id',
					'Traitementpcg66.statutpdo_id',
					'Fichiermodule.nb_fichiers_lies',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet',
					'Familleromev3.name',
					'Domaineromev3.name',
					'Metierromev3.name',
					'Appellationromev3.name',
					'Categoriemetierromev2.code',
					'Categoriemetierromev2.name'
				)
			),
			// 4. Temps d'exécution, mémoire maximum, ...
			'ini_set' => Configure::read( 'ConfigurableQuery.Dossierspcgs66.search.ini_set' ),
		)
	);
	
	/**
	 * Menu Gestionnaire de dossiers PCG (nouveau)
	 */
	Configure::write(
		'ConfigurableQuery.Dossierspcgs66.search_gestionnaire',
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
				'fields' => array (
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Dossierpcg66.originepdo_id',
					'Dossierpcg66.typepdo_id',
					'Traitementpcg66.dateecheance',
					'Dossierpcg66.user_id',
					'Dossierpcg66.nbpropositions',
					'Personnepcg66.nbtraitements',
					'Dossierpcg66.listetraitements',
					'Dossierpcg66.etatdossierpcg',
					'Decisiondossierpcg66.decisionpdo_id',
					'Traitementpcg66.situationpdo_id',
					'Traitementpcg66.statutpdo_id',
					'Fichiermodule.nb_fichiers_lies',
					'Dossier.locked',
					'/Dossierspcgs66/index/#Dossierpcg66.foyer_id#' => array( 'class' => 'view' ),
					'/Dossierspcgs66/edit/#Dossierpcg66.id#' => array( 'class' => 'edit' ),
				),
				// 5.3 Infobulle optionnelle du tableau de résultats
				'innerTable' => array(
					'Situationdossierrsa.etatdosrsa',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Dossier.matricule',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				)
			),
			// 6. Temps d'exécution, mémoire maximum, ...
			'ini_set' => array()
		)
	);

	/**
	 * Export CSV,  menu "Recherches" > "Par entretiens (nouveau)"
	 */
	Configure::write(
		'ConfigurableQuery.Dossierspcgs66.exportcsv_gestionnaire',
		array(
			// 1. Filtres de recherche, on reprend la configuration de la recherche
			'filters' => Configure::read( 'ConfigurableQuery.Dossierspcgs66.search.filters' ),
			// 2. Recherche, on reprend la configuration de la recherche
			'query' => Configure::read( 'ConfigurableQuery.Dossierspcgs66.search.query' ),
			// 3. Résultats de la recherche
			'results' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Dossierpcg66.originepdo_id',
					'Dossierpcg66.typepdo_id',
					'Dossierpcg66.datereceptionpdo',
					'User.nom_complet',
					'Dossierpcg66.nbpropositions',
					'Personnepcg66.nbtraitements',
					'Dossierpcg66.listetraitements',
					'Dossierpcg66.etatdossierpcg',
					'Fichiermodule.nb_fichiers_lies',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				)
			),
			// 4. Temps d'exécution, mémoire maximum, ...
			'ini_set' => Configure::read( 'ConfigurableQuery.Dossierspcgs66.search.ini_set' ),
		)
	);
	
	/**
	 * Menu Dossiers PCGs en attente d'affectation
	 */
	Configure::write(
		'ConfigurableQuery.Dossierspcgs66.cohorte_enattenteaffectation',
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
				'fields' => array (
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Dossierpcg66.datereceptionpdo',
					'Typepdo.libelle',
					'Originepdo.libelle',
					'Dossierpcg66.orgpayeur',
					'Serviceinstructeur.lib_service',
					'/Dossierspcgs66/index/#Dossierpcg66.foyer_id#' => array( 'class' => 'view' ),
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
	 * Menu Dossiers PCGs en attente d'affectation
	 */
	Configure::write(
		'ConfigurableQuery.Dossierspcgs66.cohorte_atransmettre',
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
				'fields' => array (
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Dossierpcg66.datereceptionpdo',
					'Typepdo.libelle',
					'Originepdo.libelle',
					'Dossierpcg66.orgpayeur',
					'Serviceinstructeur.lib_service',
					'/Dossierspcgs66/index/#Dossierpcg66.foyer_id#' => array( 'class' => 'view' ),
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
?>