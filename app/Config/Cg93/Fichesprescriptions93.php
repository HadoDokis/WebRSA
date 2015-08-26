<?php
	/**
	 * Filtres par défaut de la recherche par fiche de prescription.
	 */
	Configure::write(
		'Filtresdefaut.Fichesprescriptions93_search',
		array(
			'Calculdroitrsa' => array(
				'toppersdrodevorsa' => '1'
			),
			'Dossier' => array(
				'dernier' => '1',
			),
			'Ficheprescription93' => array(
				'exists' => '1'
			),
			'Pagination' => array(
				'nombre_total' => false
			),
			'Situationdossierrsa' => array(
				'etatdosrsa_choice' => '1',
				'etatdosrsa' => array( '2', '3', '4' )
			)
		)
	);

	/**
	 * Filtres par défaut de la recherche par fiche de prescription.
	 * @deprecated
	 */
	Configure::write(
		'Filtresdefaut.Fichesprescriptions93_search1',
		array(
			'Search' => array(
				'Calculdroitrsa' => array(
					'toppersdrodevorsa' => '1'
				),
				'Dossier' => array(
					'dernier' => '1',
				),
				'Ficheprescription93' => array(
					'exists' => '1'
				),
				'Pagination' => array(
					'nombre_total' => false
				),
				'Situationdossierrsa' => array(
					'etatdosrsa_choice' => '1',
					'etatdosrsa' => array( '2', '3', '4' )
				)
			)
		)
	);

	Configure::write(
		'ConfigurableQueryFichesprescriptions93',
		array(
			'search' => array(
				'fields' => array (
					'Dossier.matricule',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Ficheprescription93.statut',
					'Actionfp93.name',
					'Dossier.locked' => array(
						'type' => 'boolean',
						'class' => 'dossier_locked'
					),
					// Début: données nécessaires pour les permissions sur les liens, sans affichage
					'Referent.horszone' => array( 'hidden' => true ),
					'Ficheprescription93.id' => array( 'hidden' => true ),
					// Fin: données nécessaires pour les permissions sur les liens, sans affichage
					'/Fichesprescriptions93/edit/#Ficheprescription93.id#' => array(
						'disabled' => "( '#Referent.horszone#' == true || '#Ficheprescription93.id#' == '' )",
						'class' => 'external'
					),
					'/Fichesprescriptions93/index/#Personne.id#' => array(
						'title' => 'Voir les fiches de prescription de #Personne.nom_complet#',
						'disabled' => "( '#Referent.horszone#' == true )",
						'class' => 'view external'
					),
				),
				'innerTable' => array(
					'Personne.dtnai',
					'Dossier.numdemrsa',
					'Personne.nir',
					'Adresse.codepos',
					'Adresse.numcom',
					'Prestation.rolepers',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array()
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Dossier.dtdemrsa',
				'Dossier.matricule',
				'Personne.nom_complet',
				'Prestation.rolepers',
				'Ficheprescription93.statut',
				'Referent.nom_complet',
				'Adresse.numvoie' => array( 'domain' => 'adresse' ),
				'Adresse.libtypevoie' => array( 'domain' => 'adresse' ),
				'Adresse.nomvoie' => array( 'domain' => 'adresse' ),
				'Adresse.complideadr' => array( 'domain' => 'adresse' ),
				'Adresse.compladr' => array( 'domain' => 'adresse' ),
				'Adresse.lieudist' => array( 'domain' => 'adresse' ),
				'Adresse.numcom' => array( 'domain' => 'adresse' ),
				'Adresse.numcom' => array( 'domain' => 'adresse' ),
				'Adresse.codepos' => array( 'domain' => 'adresse' ),
				'Adresse.nomcom' => array( 'domain' => 'adresse' ),
				'Ficheprescription93.rdvprestataire_date',
				'Actionfp93.numconvention' => array( 'domain' => 'cataloguespdisfps93' ),
				'Thematiquefp93.type' => array( 'domain' => 'cataloguespdisfps93' ),
				'Thematiquefp93.name' => array( 'domain' => 'cataloguespdisfps93' ),
				'Categoriefp93.name' => array( 'domain' => 'cataloguespdisfps93' ),
				'Filierefp93.name' => array( 'domain' => 'cataloguespdisfps93' ),
				'Prestatairefp93.name' => array( 'domain' => 'cataloguespdisfps93' ),
				'Actionfp93.name' => array( 'domain' => 'cataloguespdisfps93' ),
				'Ficheprescription93.dd_action',
				'Ficheprescription93.df_action',
				'Ficheprescription93.date_signature',
				'Ficheprescription93.date_transmission',
				'Ficheprescription93.date_retour',
				'Ficheprescription93.personne_recue',
				'Ficheprescription93.motifnonreceptionfp93_id',
				'Ficheprescription93.personne_nonrecue_autre',
				'Ficheprescription93.personne_retenue',
				'Ficheprescription93.motifnonretenuefp93_id',
				'Ficheprescription93.personne_nonretenue_autre',
				'Ficheprescription93.personne_souhaite_integrer',
				'Ficheprescription93.motifnonsouhaitfp93_id',
				'Ficheprescription93.personne_nonsouhaite_autre',
				'Ficheprescription93.personne_a_integre',
				'Ficheprescription93.personne_date_integration',
				'Ficheprescription93.motifnonintegrationfp93_id',
				'Ficheprescription93.personne_nonintegre_autre',
				'Ficheprescription93.date_bilan_mi_parcours',
				'Ficheprescription93.date_bilan_final',
			)
		)
	);

	/**
	 * Liste des intitulés et des URL à faire apparaître dans le cadre
	 * "Prescripteur/Référent" de la fiche de prescription du CG 93.
	 *
	 * @var array
	 */
	Configure::write(
		'Cataloguepdifp93.urls',
		array(
			'Consultation du catalogue des actions (PDI)' => 'http://www.seine-saint-denis.fr/Catalogue-des-Actions-d-Insertion.html',
			'Consultation du site du CARIF' => 'http://www.carif-idf.org/',
			'Consultation INSER\'ECO93' => 'http://www.insereco93.com/',
		)
	);