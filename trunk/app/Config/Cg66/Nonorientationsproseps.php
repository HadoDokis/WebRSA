<?php
	Configure::write(
		'Filtresdefaut.Nonorientationsproseps_search',
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
		'Nonorientationsproseps',
		array(
			'search' => array(
				'fields' => array (
					'Dossier.numdemrsa',
					'Personne.nom_complet',
					'Personne.dtnai',
					'Adresse.codepos',
					'Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' ),
					'Orientstruct.date_valid',
					'Contratinsertion.nbjours',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Referent.nom_complet',
					'/Rendezvous/index/#Personne.id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array( "(DATE_PART('day', NOW() - Contratinsertion.df_ci))" => 'DESC' )
			),
		)
	);
?>