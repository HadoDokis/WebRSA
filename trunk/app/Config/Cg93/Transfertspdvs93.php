<?php
	/**
	 * Filtres par défaut de la recherche par allocataire sortant intra-département.
	 */
	Configure::write(
		'Filtresdefaut.Transfertspdvs93_search',
		array(
			/*'Dossier' => array(
				'dernier' => '1',
			),*/
		)
	);

	Configure::write(
		'ConfigurableQueryTransfertspdvs93',
		array(
			'search' => array(
				'fields' => array (
					'Dossier.numdemrsa' => array( 'sort' => false ),
					'Dossier.matricule' => array( 'sort' => false ),
					'Adresse.localite' => array( 'sort' => false ),
					'Personne.nom_complet' => array( 'sort' => false ),
					'Prestation.rolepers' => array( 'sort' => false ),
					'Transfertpdv93.created' => array(
						'sort' => false,
						'format' => '%d/%m/%Y'
					),
					'VxStructurereferente.lib_struc' => array( 'sort' => false ),
					'NvStructurereferente.lib_struc' => array( 'sort' => false ),
					'/Dossiers/view/#Dossier.id#' => array(
						'class' => 'external'
					)
				),
				'innerTable' => array(
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array()
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Dossier.matricule',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Prestation.rolepers',
				'Transfertpdv93.created' => array( 'type' => 'date' ),
				'VxStructurereferente.lib_struc',
				'NvStructurereferente.lib_struc',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet'
			)
		)
	);