<?php
	/**
	 * Liste des champs pris en compte dans l'export CSV des corpus des tableaux
	 * de suivi 1B3, 1B4, 1B5 et 1B6.
	 *
	 * La liste complète des champs utilisables pour chacun des tableaux se
	 * trouvera dans le répertoire /app/tmp/logs après le lancement du shell de
	 * Prechargement, lorsque la valeur de "production" sera à true dans le fichier
	 * app/Config/core.php.
	 *
	 * Les fichiers concernés sont: Tableausuivipdv93__tableau1b3.csv, Tableausuivipdv93__tableau1b4.csv,
	 * Tableausuivipdv93__tableau1b5.csv et Tableausuivipdv93__tableau1b6.csv.
	 *
	 * Après avoir configuré ces champs, vérifiez qu'il n'y ait pas d'erreur en
	 * vous rendant dans le partie "Vérification de l'application", onglet "Environnement logiciel"
	 * > "WebRSA" > "Champs spécifiés dans le webrsa.inc" (ceux qui commencent par "Tableauxsuivispdvs93").
	 *
	 * @var array
	 */
	Configure::write(
		'Tableauxsuivispdvs93',
		array(
			'tableau1b3' => array(
				'exportcsvcorpus' => array(
					// Rendez-vous
					'Rendezvous.daterdv',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					// Allocataire
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.sexe',
					'Prestation.rolepers',
					'Adresse.codepos',
					'Adresse.nomcom',
					'Foyer.sitfam',
					'Dossier.matricule',
					// Difficultés exprimées
					'Difficulte.sante',
					'Difficulte.logement',
					'Difficulte.familiales',
					'Difficulte.modes_gardes',
					'Difficulte.surendettement',
					'Difficulte.administratives',
					'Difficulte.linguistiques',
					'Difficulte.qualification_professionnelle',
					'Difficulte.acces_emploi',
					'Difficulte.autres'
				)
			),
			'tableau1b4' => array(
				'exportcsvcorpus' => array(
					// Fiche de prescription
					'Ficheprescription93.date_signature',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					// Allocataire
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.sexe',
					'Prestation.rolepers',
					'Adresse.codepos',
					'Adresse.nomcom',
					'Foyer.sitfam',
					'Dossier.matricule',
					// Fiche de prescription
					'Thematiquefp93.type',
					'Thematiquefp93.name',
					'Categoriefp93.name',
				)
			),
			'tableau1b5' => array(
				'exportcsvcorpus' => array(
					// Fiche de prescription
					'Ficheprescription93.date_signature',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					'Ficheprescription93.personne_a_integre',
					'Ficheprescription93.personne_pas_deplace',
					'Ficheprescription93.en_attente',
					// Allocataire
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.sexe',
					'Prestation.rolepers',
					'Adresse.codepos',
					'Adresse.nomcom',
					'Foyer.sitfam',
					'Dossier.matricule',
					// Fiche de prescription
					'Thematiquefp93.type',
					'Thematiquefp93.name',
					'Categoriefp93.name',
				)
			),
			'tableau1b6' => array(
				'exportcsvcorpus' => array(
					// Rendez-vous
					'Rendezvous.daterdv',
					'Thematiquerdv.name',
					'Statutrdv.libelle',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					// Allocataire
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.sexe',
					'Prestation.rolepers',
					'Adresse.codepos',
					'Adresse.nomcom',
					'Foyer.sitfam',
					'Dossier.matricule',
				)
			),
		)
	);
?>