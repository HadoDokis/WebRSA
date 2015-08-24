<?php
	Configure::write(
		'Filtresdefaut.Defautsinsertionseps66_search_radies',
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
		'Filtresdefaut.Defautsinsertionseps66_search_noninscrits',
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
		'ConfigurableQueryDefautsinsertionseps66',
		array(
			'search_noninscrits' => array(
				'fields' => array (
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Orientstruct.date_valid',
					'Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' ),
					'Situationdossierrsa.etatdosrsa',
					'/Bilansparcours66/add/#Personne.id#/Bilanparcours66__examenauditionpe:noninscriptionpe' => array( 'class' => 'add external' ),
				),
				'innerTable' => array(
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array( 'Orientstruct.date_valid' )
			),
			'search_radies' => array(
				'fields' => array (
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Orientstruct.date_valid',
					'Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' ),
					'Situationdossierrsa.etatdosrsa',
					'/Bilansparcours66/add/#Personne.id#/Bilanparcours66__examenauditionpe:radiationpe' => array( 'class' => 'add external' ),
				),
				'innerTable' => array(
					'Structurereferenteparcours.lib_struc',
					'Referentparcours.nom_complet'
				),
				'order' => array( 'Historiqueetatpe.date', 'Historiqueetatpe.id' )
			),
		)
	);
?>