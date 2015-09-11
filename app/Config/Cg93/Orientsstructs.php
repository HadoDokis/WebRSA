<?php
	/**
	 * Valeurs des filtres de recherche par défaut pour la "Recherche par
	 * orientation (nouveau)"
	 *
	 * @var array
	 */
	Configure::write(
		'Filtresdefaut.Orientsstructs_search',
		array(
			'Dossier' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'dernier' => '1'
			)
		)
	);

	/**
	 * Valeurs des filtres de recherche par défaut pour la cohorte d'orientation
	 * "Demandes non orientées (nouveau)"
	 *
	 * @todo oridemrsa
	 *
	 * @var array
	 */
	Configure::write(
		'Filtresdefaut.Orientsstructs_cohorte_nouvelles',
		array(
			'Dossier' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'dernier' => '1'
			),
			'Detailcalculdroitrsa' => array(
				'natpf_choice' => '1',
				'natpf' => array( 'RSD', 'RSI' )
			),
			'Detaildroitrsa' => array(
				'oridemrsa_choice' => '1',
				'oridemrsa' => array( 'DEM' )
			),
			'Situationdossierrsa' => array(
				'etatdosrsa_choice' => '1',
				'etatdosrsa' => array( 2, 3, 4 )
			)
		)
	);

	Configure::write(
		'ConfigurableQueryOrientsstructs',
		array(
			/**
			 * Les champs à faire apparaître dans les résultats de la "Recherche par
			 * orientation (nouveau)"
			 *	- lignes du tableau: ConfigurableQueryOrientsstructs.search.fields
			 *	- info-bulle du tableau: ConfigurableQueryOrientsstructs.search.innerTable
			 *	- export CSV: ConfigurableQueryOrientsstructs.exportcsv
			 *
			 * @var array
			 */
			'search' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Dossier.dtdemrsa',
					'Orientstruct.date_valid',
					'Orientstruct.propo_algo',
					'Orientstruct.origine',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Orientstruct.statut_orient',
					'Calculdroitrsa.toppersdrodevorsa' => array( 'type' => 'boolean' ),
					'/Orientsstructs/index/#Orientstruct.personne_id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Situationdossierrsa.etatdosrsa',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Historiqueetatpe.identifiantpe',
					'Modecontact.numtel',
					'Prestation.rolepers',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet',
				),
				'order' => array()
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Personne.nir',
				'Personne.dtnai',
				'Dossier.matricule',
				'Historiqueetatpe.identifiantpe',
				'Modecontact.numtel',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Dossier.dtdemrsa',
				'Situationdossierrsa.etatdosrsa',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
				'Orientstruct.origine',
				'Orientstruct.date_valid',
				'Structurereferente.lib_struc',
				'Orientstruct.statut_orient',
				'Calculdroitrsa.toppersdrodevorsa',
			),
			/**
			 *
			 */
			// http://localhost/webrsa/WebRSA-3.0.00/cg93/orientsstructs/cohorte_nouvelles
			'cohorte_nouvelles' => array(
				'fields' => array(
					'Adresse.nomcom' => array(
						'sort' => false
					),
					'Dossier.dtdemrsa' => array(
						'sort' => false
					),
					'Personne.has_dsp' => array(
						'sort' => false,
						'type' => 'boolean'
					),
					'Personne.nom_complet_court' => array(
						'sort' => false
					),
					'Suiviinstruction.typeserins' => array(
						'sort' => false
					),
					'Orientstruct.propo_algo' => array(
						'sort' => false
					),
					'Dossier.statut' => array(
						'sort' => false
					),
					'/Dossiers/view/#Dossier.id#' => array(
						'class' => 'external'
					)
				),
				'innerTable' => array(
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Personne.dtnai',
					'Dossier.matricule',
					'Personne.nir',
					'Adresse.codepos',
					'Situationdossierrsa.dtclorsa',
					'Situationdossierrsa.moticlorsa',
					'Prestation.rolepers',
					'Situationdossierrsa.etatdosrsa',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet',
				),
				//'order' => array()
			)
		)
	);
?>