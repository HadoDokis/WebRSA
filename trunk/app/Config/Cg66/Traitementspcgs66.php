<?php
	Configure::write(
		'Filtresdefaut.Traitementspcgs66_search',
		array(
			'Pagination' => array(
				'nombre_total' => 0
			),
			'Dossier' => array(
				'dernier' => '1'
			)
		)
	);

	Configure::write(
		'ConfigurableQueryTraitementspcgs66',
		array(
			'search' => array(
				'fields' => array (
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Dossierpcg66.datereceptionpdo',
					'Dossierpcg66.user_id',
					'Traitementpcg66.typetraitement',
					'Traitementpcg66.created',
					'Situationpdo.libelle',
					'Traitementpcg66.descriptionpdo_id',
					'Traitementpcg66.datereception',
					'Traitementpcg66.dateecheance',
					'Traitementpcg66.clos',
					'Traitementpcg66.annule',
					'Fichiermodule.nb_fichiers_lies',
					'Dossier.locked',
					'/Traitementspcgs66/index/#Personnepcg66.personne_id#/#Dossierpcg66.id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Situationdossierrsa.etatdosrsa',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Dossier.matricule',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array()
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Personne.nom_complet',
				'User.nom_complet',
				'Traitementpcg66.typetraitement',
				'Traitementpcg66.created',
				'Situationpdo.libelle',
				'Traitementpcg66.descriptionpdo_id',
				'Dossierpcg66.datereceptionpdo',
				'Traitementpcg66.datereception',
				'Traitementpcg66.dateecheance',
				'Traitementpcg66.clos',
				'Traitementpcg66.annule',
				'Fichiermodule.nb_fichiers_lies',
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
			)
		)
	);
?>
