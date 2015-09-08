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
				'dernier' => '1'
			)
		)
	);

	/**
	 * Valeurs des filtres de recherche par défaut pour la "Recherche par PDOs" >
	 * "Nouvelles PDOs (nouveau)"
	 *
	 * @var array
	 */
	Configure::write(
		'Filtresdefaut.Propospdos_search_possibles',
		array(
			'Dossier' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'dernier' => '1'
			)
		)
	);

	/**
	 * Les champs à faire apparaître dans les résultats de:
	 *	1°) la "Recherche par PDOs" > "Liste des PDOs (nouveau)"
	 *	2°) la "Recherche par PDOs" > "Nouvelles PDOs (nouveau)"
	 *
	 * @todo pour tous CG <> 66
	 *
	 * @var array
	 */
	Configure::write(
		'ConfigurableQueryPropospdos',
		array(
			/**
			 *	1°) la "Recherche par PDOs" > "Liste des PDOs (nouveau)"
			 *	 - lignes du tableau: ConfigurableQueryPropospdos.search.fields
			 *	 - info-bulle du tableau: ConfigurableQueryPropospdos.search.innerTable
			 *	 - export CSV: ConfigurableQueryPropospdos.exportcsv
			 */
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
			),
			/**
			 *	2°) la "Recherche par PDOs" > "Nouvelles PDOs (nouveau)"
			 *	 - lignes du tableau: ConfigurableQueryPropospdos.search_possible.fields
			 *	 - info-bulle du tableau: ConfigurableQueryPropospdos.search_possible.innerTable
			 *	 - export CSV: ConfigurableQueryPropospdos.exportcsv_possible
			 */
			'search_possibles' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Situationdossierrsa.etatdosrsa',
					'/Propospdos/index/#Personne.id#' => array(
						'class' => 'view'
					)
				),
				'innerTable' => array(
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
			'exportcsv_possibles' => array(
				'Dossier.numdemrsa',
				'Personne.nom_complet',
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
			)
		)
	);
?>