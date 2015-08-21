<?php
	/**
	 * Valeurs des filtres de recherche par défaut pour la "Recherche par DSPs
	 * (nouveau)"
	 *
	 * @var array
	 */
	Configure::write(
			'Filtresdefaut.Dsps_search',
		array(
			'Dossier' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'dernier' => true
			)
		)
	);

	/**
	 * Liste des champs devant apparaître dans les résultats de la recherche par DSP:
	 *	- Dsps.search.fields contient les champs de chaque ligne du tableau de résultats
	 *	- Dsps.search.innerTable contient les champs de l'infobulle de chaque ligne du tableau de résultats
	 *	- Dsps.exportcsv contient les champs de chaque ligne du tableau à télécharger au format CSV
	 *
	 * Voir l'onglet "Environnement logiciel" > "WebRSA" > "Champs spécifiés dans
	 * le webrsa.inc" de la vérification de l'application.
	 */
	Configure::write(
		'Dsps',
		array(
			'search' => array(
				'fields' => array(
					'Personne.nom_complet_court',
					'Adresse.nomcom',
					'Dossier.matricule',
					'Donnees.libsecactdomi',
					'Donnees.libactdomi',
					'Donnees.libsecactrech',
					'Donnees.libemploirech',
					'/Dsps/view_revs/#DspRev.id#' => array(
						'class' => 'view',
						'condition' => '!empty("#DspRev.id#")'
					),
					'/Dsps/view/#Personne.id#' => array(
						'class' => 'view',
						'condition' => 'empty("#DspRev.id#")'
					)
				),
				'innerTable' => array(
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Situationdossierrsa.etatdosrsa',
					'Donnees.nivetu',
					'Donnees.hispro',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array()
			),
			'exportcsv' => array(
				'Dossier.numdemrsa', // N° Dossier
				'Dossier.matricule', // N° CAF
				'Situationdossierrsa.etatdosrsa', // Etat du droit
				'Personne.qual', // Qualité
				'Personne.nom', // Nom
				'Personne.prenom', // Prénom
				'Dossier.matricule', // N° CAF
				'Adresse.numvoie', // Numéro de voie
				'Adresse.libtypevoie', // Type de voie
				'Adresse.nomvoie', // Nom de voie
				'Adresse.complideadr', // Complément adresse 1
				'Adresse.compladr', // Complément adresse 2
				'Adresse.codepos', // Code postal
				'Adresse.nomcom', // Commune
				'Donnees.libsecactderact', // Secteur dernière activité
				'Donnees.libderact', // Dernière activité
				'Donnees.libsecactdomi', // Secteur dernière activité dominante
				'Donnees.libactdomi', // Dernière activité dominante
				'Donnees.libsecactrech', // Secteur activité recherché
				'Donnees.libemploirech', // Activité recherchée
				'Structurereferenteparcours.lib_struc', // Structure du parcours
				'Referentparcours.nom_complet', // Référent du parcours
			),
			/**
			 * Liste des champs devant apparaître dans les résultats de la recherche par DSP:
			 *	- Dsps.index.fields contient les champs de chaque ligne du tableau de résultats
			 *	- Dsps.index.innerTable contient les champs de l'infobulle de chaque ligne du tableau de résultats
			 *	- Dsps.exportcsv contient les champs de chaque ligne du tableau à télécharger au format CSV
			 *
			 * Voir l'onglet "Environnement logiciel" > "WebRSA" > "Champs spécifiés dans
			 * le webrsa.inc" de la vérification de l'application.
			 *
			 * @deprecated
			 */
			'index' => array(
				'fields' => array(
					'Personne.nom_complet_court',
					'Adresse.nomcom',
					'Dossier.matricule',
					'Donnees.libsecactdomi',
					'Donnees.libactdomi',
					'Donnees.libsecactrech',
					'Donnees.libemploirech'
				),
				'innerTable' => array(
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Situationdossierrsa.etatdosrsa',
					'Donnees.nivetu',
					'Donnees.hispro',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				)
			),
			'exportcsv1' => array(
				'Dossier.numdemrsa', // N° Dossier
				'Dossier.matricule', // N° CAF
				'Situationdossierrsa.etatdosrsa', // Etat du droit
				'Personne.qual', // Qualité
				'Personne.nom', // Nom
				'Personne.prenom', // Prénom
				'Dossier.matricule', // N° CAF
				'Adresse.numvoie', // Numéro de voie
				'Adresse.libtypevoie', // Type de voie
				'Adresse.nomvoie', // Nom de voie
				'Adresse.complideadr', // Complément adresse 1
				'Adresse.compladr', // Complément adresse 2
				'Adresse.codepos', // Code postal
				'Adresse.nomcom', // Commune
				'Donnees.libsecactderact', // Secteur dernière activité
				'Donnees.libderact', // Dernière activité
				'Donnees.libsecactdomi', // Secteur dernière activité dominante
				'Donnees.libactdomi', // Dernière activité dominante
				'Donnees.libsecactrech', // Secteur activité recherché
				'Donnees.libemploirech', // Activité recherchée
				'Structurereferenteparcours.lib_struc', // Structure du parcours
				'Referentparcours.nom_complet', // Référent du parcours
			)
		)
	);
?>