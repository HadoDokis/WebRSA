<?php
	Configure::write(
		'Filtresdefaut.Demenagementshorsdpts_search',
		array(
			'Dossier' => array(
				'dernier' => '1',
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

	Configure::write(
		'ConfigurableQueryDemenagementshorsdpts',
		array(
			'search' => array(
				'fields' => array (
					'Dossier.matricule',
					'Personne.nom_complet',
					'Adressefoyer.dtemm',
					'Adresse.localite',
					'Adressefoyer2.dtemm' => array( 'type' => 'date' ),
					'Adresse2.localite',
					'Adressefoyer3.dtemm' => array( 'type' => 'date' ),
					'Adresse3.localite',
					'Dossier.locked' => array(
						'type' => 'boolean',
						'class' => 'dossier_locked'
					),
					'/Dossiers/view/#Dossier.id#' => array( 'class' => 'view' ),
				),
				'innerTable' => array(
				),
				'header' => array(
					array( ' ' => array( 'colspan' => 2 ) ),
					array( 'Adresse de rang 01' => array( 'colspan' => 2 ) ),
					array( 'Adresse de rang 02' => array( 'colspan' => 2 ) ),
					array( 'Adresse de rang 03' => array( 'colspan' => 2 ) ),
					array( ' ' => array() ),
					array( ' ' => array( 'class' => 'action noprint' ) ),
				),
				'order' => array()
			),
			'exportcsv' => array(
				'Dossier.matricule',
				'Personne.nom_complet',
				'Adressefoyer.dtemm',
				'Adresse.localite',
				'Adressefoyer2.dtemm' => array( 'type' => 'date' ),
				'Adresse2.localite',
				'Adressefoyer3.dtemm' => array( 'type' => 'date' ),
				'Adresse3.localite',
			)
		)
	);
?>