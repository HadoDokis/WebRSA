<?php
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
					// Nom de l'allocataire
					'Personne.nom_complet_court',
					// Commune de l'allocataire
					'Adresse.nomcom',
					// N° CAF
					'Dossier.matricule',
					// Difficultés sociales
					'Donnees.difsoc' => array(
						'type' => 'list'
					),
					// Domaine d'accompagnement individuel
					'Donnees.nataccosocindi' => array(
						'type' => 'list'
					),
					// Obstacles à la recherche d'emploi
					'Donnees.difdisp' => array(
						'type' => 'list'
					),
					// domaine de la dernière activité
					'Deractromev3.domaineromev3',
					// métier de la dernière activité
					'Deractromev3.metierromev3',
					// domaine de l'emploi recherché
					'Actrechromev3.domaineromev3',
					// métier de l'emploi recherché
					'Actrechromev3.metierromev3',
					// Secteur dernière activité
					'Donnees.libsecactderact',
					// Dernière activité
					'Donnees.libderact',
					// Secteur activité recherchée
					'Donnees.libsecactrech',
					// Activité recherchée
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
					'Structurereferenteparcours.lib_struc' => array(
						'label' => __d( 'search_plugin_93', 'Structurereferenteparcours.lib_struc' )
					),
					'Referentparcours.nom_complet' => array(
						'label' => __d( 'search_plugin_93', 'Referentparcours.nom_complet' )
					),
					'Deractromev3.familleromev3',
					'Deractromev3.appellationromev3',
					'Actrechromev3.familleromev3',
					'Actrechromev3.appellationromev3'
				),
				'header' => array(
					array( 'Dossier' => array( 'colspan' => 3 ) ),
					array( 'Accompagnement et difficultés' => array( 'colspan' => 3 ) ),
					array( 'Code ROME' => array( 'colspan' => 4 ) ),
					array( 'Hors code ROME' => array( 'colspan' => 4 ) ),
					array( ' ' => array( 'class' => 'action noprint' ) ),
					array( ' ' => array( 'style' => 'display: none' ) ),
				)
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Dossier.matricule',
				'Situationdossierrsa.etatdosrsa',
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Dossier.matricule',
				'Detaildroitrsa.natpf',
				'Calculdroitrsa.toppersdrodevorsa',
				'Memo.name',
				'Personne.numfixe',
				'Modecontact.numtel',
				'Personne.age',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Donnees.libsecactrech',
				'Donnees.libemploirech',
				'Structurereferenteparcours.lib_struc' => array(
					'label' => __d( 'search_plugin_93', 'Structurereferenteparcours.lib_struc' )
				),
				'Referentparcours.nom_complet' => array(
					'label' => __d( 'search_plugin_93', 'Referentparcours.nom_complet' )
				),
				'Donnees.difsoc' => array(
					'type' => 'list'
				),
				'Donnees.nataccosocindi' => array(
					'type' => 'list'
				),
				'Donnees.difdisp' => array(
					'type' => 'list'
				),
				'Deractromev3.familleromev3',
				'Deractromev3.domaineromev3',
				'Deractromev3.metierromev3',
				'Deractromev3.appellationromev3',
				'Actrechromev3.familleromev3',
				'Actrechromev3.domaineromev3',
				'Actrechromev3.metierromev3',
				'Actrechromev3.appellationromev3'
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
					// Nom de l'allocataire
					'Personne.nom_complet_court',
					// Commune de l'allocataire
					'Adresse.nomcom',
					// N° CAF
					'Dossier.matricule',
					// Difficultés sociales
					'Donnees.difsoc',
					// Domaine d'accompagnement individuel
					'Donnees.nataccosocindi',
					// Obstacles à la recherche d'emploi
					'Donnees.difdisp',
					// domaine de la dernière activité
					'Deractromev3.domaineromev3',
					// métier de la dernière activité
					'Deractromev3.metierromev3',
					// domaine de l'emploi recherché
					'Actrechromev3.domaineromev3',
					// métier de l'emploi recherché
					'Actrechromev3.metierromev3',
					// Secteur dernière activité
					'Donnees.libsecactderact',
					// Dernière activité
					'Donnees.libderact',
					// Secteur activité recherchée
					'Donnees.libsecactrech',
					// Activité recherchée
					'Donnees.libemploirech'
				),
				'innerTable' => array(
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Situationdossierrsa.etatdosrsa',
					'Donnees.nivetu',
					'Donnees.hispro',
					'Structurereferenteparcours.lib_struc' => array(
						'label' => __d( 'search_plugin_93', 'Structurereferenteparcours.lib_struc' )
					),
					'Referentparcours.nom_complet' => array(
						'label' => __d( 'search_plugin_93', 'Referentparcours.nom_complet' )
					),
					'Deractromev3.familleromev3',
					'Deractromev3.appellationromev3',
					'Actrechromev3.familleromev3',
					'Actrechromev3.appellationromev3'
				),
				'header' => array(
					array( 'Dossier' => array( 'colspan' => 3 ) ),
					array( 'Accompagnement et difficultés' => array( 'colspan' => 3 ) ),
					array( 'Code ROME' => array( 'colspan' => 4 ) ),
					array( 'Hors code ROME' => array( 'colspan' => 4 ) ),
					array( ' ' => array( 'class' => 'action noprint' ) ),
					array( ' ' => array( 'style' => 'display: none' ) ),
				)
			),
			'exportcsv1' => array(
				'Dossier.numdemrsa',
				'Dossier.matricule',
				'Situationdossierrsa.etatdosrsa',
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Dossier.matricule',
				'Detaildroitrsa.natpf',
				'Calculdroitrsa.toppersdrodevorsa',
				'Memo.name',
				'Personne.numfixe',
				'Modecontact.numtel',
				'Personne.age',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Donnees.libsecactrech',
				'Donnees.libemploirech',
				'Structurereferenteparcours.lib_struc' => array(
					'label' => __d( 'search_plugin_93', 'Structurereferenteparcours.lib_struc' )
				),
				'Referentparcours.nom_complet' => array(
					'label' => __d( 'search_plugin_93', 'Referentparcours.nom_complet' )
				),
				'Donnees.difsoc',
				'Donnees.nataccosocindi',
				'Donnees.difdisp',
				'Deractromev3.familleromev3',
				'Deractromev3.domaineromev3',
				'Deractromev3.metierromev3',
				'Deractromev3.appellationromev3',
				'Actrechromev3.familleromev3',
				'Actrechromev3.domaineromev3',
				'Actrechromev3.metierromev3',
				'Actrechromev3.appellationromev3'
			)
		)
	);
?>