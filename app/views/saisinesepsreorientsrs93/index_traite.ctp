<h1> <?php echo $this->pageTitle = 'Écran de synthèse des demandes de réorientation étudiées en EP'; ?> </h1>
<?php
	require_once( 'index.ctp' );

	echo $default2->index(
		$saisinesepsreorientsrs93,
		array(
			'Saisineepreorientsr93.created' => array( 'type' => 'date' ),
			// Allocataire
			'Dossierep.Personne.nom',
			'Dossierep.Personne.prenom',
			// Orientation de départ
			'Orientstruct.Typeorient.lib_type_orient',
			'Orientstruct.Structurereferente.lib_struc',
			// Orientation d'accueil
			'Typeorient.lib_type_orient',
			'Structurereferente.lib_struc',
			'Dossierep.etapedossierep',
			'Nvsrepreorientsr93.0.decision',
			'Nvsrepreorientsr93.0.Typeorient.lib_type_orient' => array( 'type' => 'text' ),
			'Nvsrepreorientsr93.0.Structurereferente.lib_struc' => array( 'type' => 'text' ),
			'Dossierep.Seanceep.dateseance' => array( 'type' => 'date' ),
		),
		array(
			'groupColumns' => array(
				'Dossier' => array( 1, 2 ),
				'Service référent demandeur' => array( 3, 4 ),
				'Service référent d\'accueil' => array( 5, 6, 7 ),
				'Réorientation finale' => array( 9, 10 ),
			),
			'paginate' => 'Saisineepreorientsr93',
			'options' => $options
		)
	);
// debug( $options );
// 	debug( $saisinesepsreorientsrs93 );
?>