<?php
	/**
	 * Filtres par défaut de la recherche par fiche de prescription.
	 */
	Configure::write(
		'Filtresdefaut.Fichesprescriptions93_search',
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