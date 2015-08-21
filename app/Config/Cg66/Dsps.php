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
					'Dossier.matricule',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.nomcom',
					'Canton.canton',
					'Donnees.toppermicondub', // Permis de conduire Cat B
					'Donnees.topmoyloco', // Moyen de transport Coll. Ou IndiV.
					'Donnees.difdisp' => array( // Obstacles à une recherche d'emploi
						'type' => 'list'
					),
					'Donnees.nivetu', // Niveau d'étude
					'Donnees.nivdipmaxobt', // Diplomes le plus élevé
					'Donnees.topengdemarechemploi', // Disponibilité à la recherche d'emploi
					'Actrechromev3.familleromev3', // Code Famille de l'emploi recherché
					'Actrechromev3.domaineromev3', // Code Domaine de l'emploi recherché
					'Actrechromev3.metierromev3', // Code Emploi de l'emploi recherché
					'Actrechromev3.appellationromev3', // Appellattion de l'emploi recherché (rome V3)
					'Libemploirech66Metier.name', // Emploi recherché (rome V2)
					'Deractromev3.appellationromev3', // Appellattion de la derniere activité (rome V3)
					'Libsecactrech66Secteur.name', // Le secteur d'activité recherché (rome v2)
					'Libderact66Metier.name', // La derniere activité (rome V2)
					'Donnees.libautrqualipro', // Qualification ou certificats professionnels
					'Donnees.nb_fichiers_lies', // Nb Fichiers Liés des dsp
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
					'Situationdossierrsa.etatdosrsa', // Position du droit
					'Calculdroitrsa.toppersdrodevorsa', // Soumis à Droit et Devoir
					'Foyer.sitfam', // Situation de famille
					'Foyer.nbenfants', // Nbre d'enfants
					'Personne.numfixe', // N° téléphone fixe
					'Personne.numport', // N° téléphone portable
					'Referentparcours.nom_complet',// Nom du référent
				),
				'order' => array()
			),
			'exportcsv' => array(
				'Dossier.matricule',
				'Personne.nom',
				'Personne.prenom',
				'Personne.dtnai',
				'Situationdossierrsa.etatdosrsa', // Position du droit
				'Calculdroitrsa.toppersdrodevorsa', // Soumis à Droit et Devoir
				'Foyer.sitfam', // Situation de famille
				'Foyer.nbenfants', // Nbre d'enfants
				'Personne.numfixe', // N° téléphone fixe
				'Personne.numport', // N° téléphone portable
				'Referentparcours.nom_complet',// Nom du référent
				'Adresse.nomcom',
				'Canton.canton',
				'Donnees.toppermicondub', // Permis de conduire Cat B
				'Donnees.topmoyloco', // Moyen de transport Coll. Ou IndiV.
				'Donnees.difdisp' => array( // Obstacles à une recherche d'emploi
					'type' => 'list'
				),
				'Donnees.nivetu', // Niveau d'étude
				'Donnees.nivdipmaxobt', // Diplomes le plus élevé
				'Donnees.topengdemarechemploi', // Disponibilité à la recherche d'emploi
				'Actrechromev3.familleromev3', // Code Famille de l'emploi recherché
				'Actrechromev3.domaineromev3', // Code Domaine de l'emploi recherché
				'Actrechromev3.metierromev3', // Code Emploi de l'emploi recherché
				'Actrechromev3.appellationromev3', // Appellattion de l'emploi recherché (rome V3)
				'Libemploirech66Metier.name', // Emploi recherché (rome V2)
				'Deractromev3.appellationromev3', // Appellattion de la derniere activité (rome V3)
				'Libsecactrech66Secteur.name', // Le secteur d'activité recherché (rome v2)
				'Libderact66Metier.name', // La derniere activité (rome V2)
				'Donnees.libautrqualipro', // Qualification ou certificats professionnels
				'Donnees.nb_fichiers_lies', // Nb Fichiers Liés des dsp
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
					'Dossier.matricule',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.nomcom',
					'Canton.canton',
					'Donnees.toppermicondub', // Permis de conduire Cat B
					'Donnees.topmoyloco', // Moyen de transport Coll. Ou IndiV.
					'Donnees.difdisp', // Obstacles à une recherche d'emploi
					'Donnees.nivetu', // Niveau d'étude
					'Donnees.nivdipmaxobt', // Diplomes le plus élevé
					'Donnees.topengdemarechemploi', // Disponibilité à la recherche d'emploi
					'Actrechromev3.familleromev3', // Code Famille de l'emploi recherché
					'Actrechromev3.domaineromev3', // Code Domaine de l'emploi recherché
					'Actrechromev3.metierromev3', // Code Emploi de l'emploi recherché
					'Actrechromev3.appellationromev3', // Appellattion de l'emploi recherché (rome V3)
					'Libemploirech66Metier.name', // Emploi recherché (rome V2)
					'Deractromev3.appellationromev3', // Appellattion de la derniere activité (rome V3)
					'Libsecactrech66Secteur.name', // Le secteur d'activité recherché (rome v2)
					'Libderact66Metier.name', // La derniere activité (rome V2)
					'Donnees.libautrqualipro', // Qualification ou certificats professionnels
					'Donnees.nb_fichiers_lies', // Nb Fichiers Liés des dsp
				),
				'innerTable' => array(
					'Situationdossierrsa.etatdosrsa', // Position du droit
					'Calculdroitrsa.toppersdrodevorsa', // Soumis à Droit et Devoir
					'Foyer.sitfam', // Situation de famille
					'Foyer.nbenfants', // Nbre d'enfants
					'Personne.numfixe', // N° téléphone fixe
					'Personne.numport', // N° téléphone portable
					'Referentparcours.nom_complet',// Nom du référent
				)
			),
			'exportcsv1' => array(
				'Dossier.matricule',
				'Personne.nom',
				'Personne.prenom',
				'Personne.dtnai',
				'Situationdossierrsa.etatdosrsa', // Position du droit
				'Calculdroitrsa.toppersdrodevorsa', // Soumis à Droit et Devoir
				'Foyer.sitfam', // Situation de famille
				'Foyer.nbenfants', // Nbre d'enfants
				'Personne.numfixe', // N° téléphone fixe
				'Personne.numport', // N° téléphone portable
				'Referentparcours.nom_complet',// Nom du référent
				'Adresse.nomcom',
				'Canton.canton',
				'Donnees.toppermicondub', // Permis de conduire Cat B
				'Donnees.topmoyloco', // Moyen de transport Coll. Ou IndiV.
				'Donnees.difdisp', // Obstacles à une recherche d'emploi
				'Donnees.nivetu', // Niveau d'étude
				'Donnees.nivdipmaxobt', // Diplomes le plus élevé
				'Donnees.topengdemarechemploi', // Disponibilité à la recherche d'emploi
				'Actrechromev3.familleromev3', // Code Famille de l'emploi recherché
				'Actrechromev3.domaineromev3', // Code Domaine de l'emploi recherché
				'Actrechromev3.metierromev3', // Code Emploi de l'emploi recherché
				'Actrechromev3.appellationromev3', // Appellattion de l'emploi recherché (rome V3)
				'Libemploirech66Metier.name', // Emploi recherché (rome V2)
				'Deractromev3.appellationromev3', // Appellattion de la derniere activité (rome V3)
				'Libsecactrech66Secteur.name', // Le secteur d'activité recherché (rome v2)
				'Libderact66Metier.name', // La derniere activité (rome V2)
				'Donnees.libautrqualipro', // Qualification ou certificats professionnels
				'Donnees.nb_fichiers_lies', // Nb Fichiers Liés des dsp
			)
		)
	);
?>