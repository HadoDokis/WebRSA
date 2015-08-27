<?php
	/**
	 * Valeurs des filtres de recherche par défaut pour la "Recherche par PDOs" >
	 * "Liste des PDOs (nouveau)"
	 *
	 * @var array
	 */
	Configure::write(
			'Filtresdefaut.Propospdos_search',
		array(
			'Dossier' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'dernier' => true
			)
		)
	);

	/**
	 * Les champs à faire apparaître dans les résultats de la "Recherche par PDOs" >
	 * "Liste des PDOs (nouveau)"
	 *	- lignes du tableau: ConfigurableQueryPropospdos.search.fields
	 *	- info-bulle du tableau: ConfigurableQueryPropospdos.search.innerTable
	 *	- export CSV: ConfigurableQueryPropospdos.exportcsv
	 *
	 * @var array
	 */
	Configure::write(
		'ConfigurableQueryPropospdos',
		array(
			'search' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Decisionpdo.libelle',
					'Originepdo.libelle',
					'Propopdo.motifpdo',
					'Propopdo.datereceptionpdo',
					'User.nom_complet',
					'Propopdo.etatdossierpdo',
					'/Propospdos/index/#Propopdo.personne_id#'
				),
				'innerTable' => array(
					'Situationdossierrsa.etatdosrsa',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Adresse.numcom' => array(
						'options' => array()
					),
					'Personne.nir',
					'Dossier.matricule',
					'Prestation.rolepers',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array()
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Personne.nom_complet',
				'Dossier.matricule',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Decisionpdo.libelle',
				'Propopdo.motifpdo',
				'Decisionpropopdo.datedecisionpdo',
				'User.nom_complet',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet'
			)
		)
	);
?>