<?php
/**
	 * Valeurs par défaut des filtres pour le moteur de recherche par CUI.
	 */
	Configure::write(
		'Filtresdefaut.Cuis_search',
		array(
			'Pagination' => array(
				'nombre_total' => 0
			),
			'Dossier' => array(
				'dernier' => true
			)
		)
	);

	/**
	 * Liste des champs devant apparaître dans les résultats de la recherche par CUI:
	 *	- Cuis.search.fields contient les champs de chaque ligne du tableau de résultats
	 *	- Cuis.exportcsv contient les champs de chaque ligne du tableau à télécharger au format CSV
	 *
	 * Voir l'onglet "Environnement logiciel" > "WebRSA" > "Champs spécifiés dans
	 * le webrsa.inc" de la vérification de l'application.
	 */
	Configure::write(
		'Cuis',
		array(
			'search' => array(
				'fields' => array(
					'Dossier.matricule',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Cui66.etatdossiercui66',
					'Historiquepositioncui66.created' => array( 'type' => 'date' ), // Type datetime
					'Partenairecui.raisonsociale',
					'Cui.effetpriseencharge',
					'Cui.finpriseencharge',
					'Decisioncui66.decision',
					'Decisioncui66.datedecision' => array( 'type' => 'date' ), // Type datetime
					'Emailcui.textmailcui66_id' => array( 'type' => 'varchar' ), // Type integer
					'Emailcui.dateenvoi' => array( 'type' => 'date' ), // Type datetime
					'/Cuis66/index/#Cui.personne_id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array()
			),
			'exportcsv' => array(
				'Dossier.matricule',
				'Personne.nom_complet',
				'Adresse.nomcom',
				'Cui66.etatdossiercui66',
				'Historiquepositioncui66.created' => array( 'type' => 'date' ),
				'Partenairecui.raisonsociale',
				'Cui.effetpriseencharge',
				'Cui.finpriseencharge',
				'Decisioncui66.decision',
				'Decisioncui66.datedecision' => array( 'type' => 'date' ),
				'Emailcui.textmailcui66_id' => array( 'type' => 'varchar' ),
				'Emailcui.dateenvoi' => array( 'type' => 'date' ),
			)
		)
	);
?>