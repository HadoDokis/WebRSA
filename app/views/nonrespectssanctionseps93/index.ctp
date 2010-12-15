<h1><?php echo $this->pageTitle = 'FIXME';?></h1>
<?php
	echo $default2->index(
		$nonrespectssanctionseps93,
		array(
			'Dossierep.created' => array( 'type' => 'date' ),
			// Allocataire
			'Dossierep.Personne.nom',
			'Dossierep.Personne.prenom',
			// Orientation de départ
			'Orientstruct.date_valid',
			// Dossier EP
			'Nonrespectsanctionep93.origine',
			'Dossierep.etapedossierep',
			'Nonrespectsanctionep93.decision',
			'Nonrespectsanctionep93.montantreduction',
			'Nonrespectsanctionep93.dureesursis',
			'Dossierep.Seanceep.dateseance' => array( 'type' => 'date' ),
		),
		array(
			/*'groupColumns' => array(
				'Dossier' => array( 1, 2 ),
				'Service référent demandeur' => array( 3, 4 ),
				'Service référent d\'accueil' => array( 5, 6, 7 ),
				'Réorientation finale' => array( 9, 10 ),
			),*/
			'paginate' => 'Nonrespectsanctionep93',
// 			'options' => $options
		)
	);

// 	debug( $nonrespectssanctionseps93 );
?>