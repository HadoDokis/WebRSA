<?php
	/**
	 * Fichier de configuration du moteur de recherche "Par Indus (nouveau)" pour
	 * le département 976.
	 *
	 * PHP 5.3
	 *
	 * @package app.Config.Cg976
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Valeurs par défaut du filtre de recherche.
	 *
	 * @var array
	 */
	Configure::write(
		'Filtresdefaut.Indus_search',
		array(
			// FIXME: faut-il inclure le prefix Search dans le component ?
			// FIXME: corriger dans les autres fichiers de configuration ?
//			'Search' => array(
				'Dossier' => array(
					'dernier' => '1'
				),
//			)
		)
	);

	Configure::write(
		'ConfigurableQueryIndus',
		array(
			'search' => array(
				'fields' => array (
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Dossier.typeparte',
					'Situationdossierrsa.etatdosrsa',
					'Indu.moismoucompta' => array( 'type' => 'date', 'format' => '%B %Y' ),
					'IndusConstates.mtmoucompta',
					'IndusTransferesCG.mtmoucompta',
					'RemisesIndus.mtmoucompta',
					'/Indus/view/#Dossier.id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Personne.dtnai',
					'Dossier.matricule',
					'Personne.nir',
					'Adresse.codepos',
					'Adresse.numcom',
					'Prestation.rolepers',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				)
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Dossier.matricule',
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Dossier.typeparte',
				'Situationdossierrsa.etatdosrsa',
				'Indu.moismoucompta' => array( 'type' => 'date', 'format' => '%B %Y' ),
				'IndusConstates.mtmoucompta',
				'IndusTransferesCG.mtmoucompta',
				'RemisesIndus.mtmoucompta',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
			)
		)
	);
?>
