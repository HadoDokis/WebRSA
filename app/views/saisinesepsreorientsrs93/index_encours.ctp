<h1> <?php echo $this->pageTitle = 'Liste des saisines de demande de réorientation des structures référentes'; ?> </h1>
<?php
	require_once( 'index.ctp' );

	$myServiceinstructeur_id = $session->read( 'Auth.User.serviceinstructeur_id' );
	$myGroup = $session->read( 'Auth.Group.name' );
	$disabled = "(
		'#Dossierep.etapedossierep#' != 'cree'
		|| (
			'#Saisineepreorientsr93.structurereferente_id#' != '{$myServiceinstructeur_id}'
			&& '{$myGroup}' != 'Administrateurs'
		)
	)";

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
			'Saisineepreorientsr93.accordaccueil' => array( 'type' => 'boolean' ),
			'Saisineepreorientsr93.accordallocataire' => array( 'type' => 'boolean' ),
			'Dossierep.etapedossierep',
		),
		array(
			'actions' => array(
				'Saisinesepsreorientsrs93::edit' => array(
					'disabled' => $disabled,
				),
				'Saisinesepsreorientsrs93::delete' => array(
					'disabled' => $disabled,
				)
			),
			'groupColumns' => array(
				'Dossier' => array( 1, 2 ),
				'Service référent demandeur' => array( 3, 4 ),
				'Service référent d\'accueil' => array( 5, 6, 7 ),
			),
			'paginate' => 'Saisineepreorientsr93',
			'options' => $options
		)
	);

// 	debug( $saisinesepsreorientsrs93 );
// 	debug( $session->read( 'Auth.User.serviceinstructeur_id' ) );
?>