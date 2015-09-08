<?php
	Configure::write(
		'Filtresdefaut.Orientsstructs_search',
		array(
			'Dossier' => array(
				// Case à cocher "Uniquement la dernière demande RSA pour un même allocataire"
				'dernier' => '1',
				// Case à cocher "Filtrer par date de demande RSA"
				'dtdemrsa' => '0',
				// Du (inclus)
				'dtdemrsa_from' => date_sql_to_cakephp( date( 'Y-m-d', strtotime( '-1 week' ) ) ),
				// Au (inclus)
				'dtdemrsa_to' => date_sql_to_cakephp( date( 'Y-m-d', strtotime( 'now' ) ) ),
			)
		)
	);

	Configure::write(
		'ConfigurableQueryOrientsstructs',
		array(
			'search' => array(
				'fields' => array(
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Dossier.dtdemrsa',
					'Orientstruct.date_valid',
					'Orientstruct.typeorient_id',
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
					'Activite.act'
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
				'Orientstruct.date_valid',
				'Structurereferente.lib_struc',
				'Orientstruct.statut_orient',
				'Calculdroitrsa.toppersdrodevorsa',
				'Activite.act'
			)
		)
	);
?>