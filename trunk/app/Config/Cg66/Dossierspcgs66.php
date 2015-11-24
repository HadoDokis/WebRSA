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
	
	Configure::write(
		'ConfigurableQuery.Dossierspcgs66.cohorte_heberge',
		array(
			// 1. Filtres de recherche, on reprend la configuration de la recherche
			'filters' => array(
				// 1.1 Valeurs par défaut des filtres de recherche
				'defaults' => array(
					'Dossier' => array(
						// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
						'dernier' => '1'
					),
					'Situationdossierrsa' => array(
						'etatdosrsa_choice' => '1',
						'etatdosrsa' => array(
							'2' // Droit ouvert et versable
						)
					),
					'Calculdroitrsa' => array(
						'toppersdrodevorsa' => array( 
							'1' // Oui
						)
					),
					'Detailcalculdroitrsa' => array(
						'natpf_choice' => '1',
						'natpf' => array(
							'RSD', // RSA Socle (Financement sur fonds Conseil général)
							'RSI', // RSA Socle majoré (Financement sur fonds Conseil général)
						)
					),
					'Adresse' => array(
						'heberge' => '1'
					),
					'Tag' => array(
						'valeurtag_id' => array(
							'2', // Valeur du tag pour la cohorte hebergé
						)
					),
					'Prestation' => array(
						'rolepers' => 'DEM', // Demandeur du RSA
					)
				),
				// 1.2 Restriction des valeurs qui apparaissent dans les filtres de recherche
				'accepted' => array(),
				// 1.3 Ne pas afficher ni traiter certains filtres de recherche
				'skip' => array(),
				// 1.4 Filtres additionnels : La personne possède un(e)...
				'has' => array(
					'Cui',
					'Orientstruct' => array(
						'Orientstruct.statut_orient' => 'Orienté',
						// Orientstruct possède des conditions supplémentaire dans le modèle WebrsaRechercheDossier pour le CD66
					),
					'Contratinsertion' => array(
						'Contratinsertion.decision_ci' => 'V'
					),
					'Personnepcg66'
				)
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
			'limit' => 1,
			// 4. Lancer la recherche au premier accès à la page ?
			'auto' => false,
			// 5. Résultats de la recherche
			'results' => array(
				// 5.1 Ligne optionnelle supplémentaire d'en-tête du tableau de résultats
				'header' => array(),
				// 5.2 Colonnes du tableau de résultats
				'fields' => array(
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Personne.nir',
					'Situationdossierrsa.etatdosrsa',
					'Personne.nom_complet_prenoms',
					'Adresse.complete',
					'Adresse.numvoie',
					'Adresse.nomvoie',
					'Adresse.complideadr',
					'Adresse.compladr',
					'Adresse.lieudist',
					'Adresse.codepos',
					'Adresse.pays',
					'Canton.canton',
					'Adresse.libtypevoie',
					'Adresse.numcom',
					'Adresse.nomcom',
					'Adressefoyer.dtemm',
					'DspRev.natlog',
					'/Dossiers/view/#Dossier.id#' => array( 'class' => 'external' ),
				),
				// 5.3 Infobulle optionnelle du tableau de résultats
				'innerTable' => array(
					'Dossier.matricule',
					'Personne.dtnai',
					'Adresse.numcom' => array(
						'options' => array()
					),
					'Prestation.rolepers',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				)
			),
			// 6. Temps d'exécution, mémoire maximum, ...
			'ini_set' => array(),
			// 7. Affichage vertical des résultats
			'view' => true,
		)
	);
	
	/**
	 * Catégories des requetes obtenus par le request manager affiché par actions
	 */
	Configure::write('Dossierspcgs66.cohorte_heberge.allowed.Requestgroup.id',
		array(
			7, // Noter nom de catégorie - Cohorte de tag
		)
	);
	
	/**
	 * Choix possible pour le préremplissage de la date butoir
	 */
	Configure::write('Dossierspcgs66.cohorte_heberge.range_date_butoir',
		array(
			'1' => '1 mois',
			'1.5' => '1 mois et demi', // Supporte les nombres de type float
			2 => '2 mois',
			3 => '3 mois',
			6 => '6 mois',
			12 => '1 an',
			24 => '2 ans',
			36 => '3 ans',
		)
	);
	
	/**
	 * Valeurs de Foyer.sitfam consideré comme une situation d'isolement
	 * Clef en dur dans le modele, nécéssaire pour l'utilisation du filtre "Composition familiale"
	 */
	Configure::write('Tags.cohorte.Foyer.sitfam.isolement',
		array(
			'CEL', // Célibataire
			'DIV', // Divorcé(e)
			'ISO', // Isolement après vie maritale ou PACS
			'SEF', // Séparation de fait
			'SEL', // Séparation légale
			'VEU', // Veuvage
		)
	);
	
	/**
	 * Choisi une valeur spécifique et cache le champ
	 */
	Configure::write('Dossierspcgs66.cohorte_heberge.options.choose_and_hide',
		array(
			'Dossierpcg66.originepdo_id' => 21, // PDU - MMR Cible Imposition
			'Dossierpcg66.poledossierpcg66_id' => 1, // PDU
			'Traitementpcg66.typecourrierpcg66_id' => 9, // PDU - Cibles
			'Traitementpcg66.descriptionpdo_id' => 1, // Courrier à l'allocataire
			'Traitementpcg66.datereception' => null, // Date de reception
		)
	);
	
	/**
	 * Retire toutes les valeurs ne correspondent pas dans les options
	 */
	Configure::write('Dossierspcgs66.cohorte_heberge.options.allowed',
		array(
			'Dossierpcg66.user_id' => array( // PDU's Users
				405,
				442,
				534,
				528
			), 
			'Traitementpcg66.typetraitement' => array(
				'courrier',
				'dossierarevoir'
			)
		)
	);
?>