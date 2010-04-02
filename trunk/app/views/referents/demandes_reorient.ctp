<?php
	$statut = array( '1' => 'Enregistré', '2' => 'Décision EP', '3' => 'Décision CG' );
	$options = Set::insert( array(), 'Demandereorient.statut', $statut );

	echo $html->tag( 'h2', 'Écran de synthèse des demandes de réorientation en cours' );

	echo $default->index(
		$demandesOrigine,
		array(
			'Demandereorient.created' => array( 'type' => 'date' ),
			'Personne.nom_complet',
			'Reforigine.Typeorient.lib_type_orient',
			'Reforigine.Structurereferente.lib_struc',
// 			'Demandereorient.commentaire',
			'Demandereorient.accordbenef' => array( 'type' => 'boolean' ),
			'Demandereorient.statut',
			'Precoreorientreferent.Typeorient.lib_type_orient',
			'Precoreorientreferent.Structurereferente.lib_struc',
			'Precoreorientreferent.Referent.nom_complet',
			'Precoreorientreferent.accord' => array( 'type' => 'boolean' ),
		),
		array(
			'options' => $options,
            'class' => 'aere',
			'groupColumns' => array(
				'Référent de départ' => array( 2, 3 ),
				'Référent d\'accueil' => array( 7, 8, 9 )
			)
		)
	);

    echo $html->tag( 'br /');
// 	debug( $demandesOrigine );

	echo $html->tag( 'h2', 'Écran de synthèse des demandes de réorientation étudiées en EP' );

/// FIXME: step
	echo $default->index(
		$demandesDestination,
		array(
			'Demandereorient.created' => array( 'type' => 'date' ),
			'Personne.nom_complet',
			'Reforigine.Typeorient.lib_type_orient',
			'Reforigine.Structurereferente.lib_struc',
			'Reforigine.nom_complet',
// 			'Demandereorient.commentaire',
			'Demandereorient.accordbenef' => array( 'type' => 'boolean' ),
			'Demandereorient.statut',
			'Precoreorientconseil.Typeorient.lib_type_orient',
			'Precoreorientconseil.Structurereferente.lib_struc',
			'Precoreorientconseil.Referent.nom_complet',
			'Precoreorientconseil.accord' => array( 'type' => 'boolean' ),
		),
		array(
			'options' => $options,
			'groupColumns' => array(
				'Référent de départ' => array( 2, 3, 4 ),
				'Référent d\'accueil' => array( 8, 9, 10 )
			)
		)
	);

    echo $default->button(
        'back',
        array(
            'controller' => 'referents',
            'action'     => 'liste_demande_reorient'
        ),
        array(
            'id' => 'Back'
        )
    );
// 	debug( $demandesDestination );
?>