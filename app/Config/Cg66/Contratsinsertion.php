<?php
	Configure::write(
		'Filtresdefaut.Contratsinsertion_search',
		array(
			'Pagination' => array(
				'nombre_total' => 0
			),
			'Dossier' => array(
				'dernier' => true
			)
		)
	);

	Configure::write(
		'Contratsinsertion',
		array(
			'search' => array(
				'fields' => array (
					'Personne.nom_complet',
					'Adresse.nomcom',
					'Referent.nom_complet',
					'Dossier.matricule',
					'Contratinsertion.created',
					'Contratinsertion.rg_ci',
					'Contratinsertion.decision_ci',
					'Contratinsertion.datevalidation_ci',
					'Contratinsertion.forme_ci',
					'Contratinsertion.positioncer',
					'Contratinsertion.df_ci',
					'/Contratsinsertion/index/#Contratinsertion.personne_id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Personne.dtnai',
					'Adresse.numcom',
					'Personne.nir',
					'Prestation.rolepers',
					'Situationdossierrsa.etatdosrsa',
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array( 'Contratinsertion.df_ci' )
			),
			'exportcsv' => array(
				'Dossier.numdemrsa',
				'Dossier.matricule',
				'Situationdossierrsa.etatdosrsa',
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Dossier.matricule',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.codepos',
				'Adresse.nomcom',
				'Typeorient.lib_type_orient',
				'Referent.nom_complet',
				'Structurereferente.lib_struc',
				'Contratinsertion.num_contrat',
				'Contratinsertion.dd_ci' => array( 'type' => 'date' ),
				'Contratinsertion.duree_engag',
				'Contratinsertion.df_ci' => array( 'type' => 'date' ),
				'Contratinsertion.decision_ci',
				'Contratinsertion.datevalidation_ci' => array( 'type' => 'date' ),
				'Structurereferenteparcours.lib_struc',
				'Referentparcours.nom_complet',
				'Canton.canton',
			)
		)
	);
?>