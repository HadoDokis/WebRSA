<?php echo $this->element( 'dossier_menu', array( 'id' => $dossierId) ); ?>

<div class="with_treemenu">
<h1>Voir les demandes de réorientation</h1>

	<?php
		echo $html->tag( 'h2', '1. Service référent initial' );
		echo $default->view(
			Set::merge( $orientstruct, $referentOrigine ),
			array(
				'Structurereferente.lib_struc',
				'Typeorient.lib_type_orient',
				'Referent.nom_complet',
				'Referent.fonction',
				'Referent.email' => array( 'type' => 'email' ),
				'Referent.numero_poste' => array( 'type' => 'phone' ),
			),
			array(
				'widget' => 'table',
				'class' => 'view wide'
			)
		);

		echo $html->tag( 'h2', '2. Le bénéficiaire' );
		echo $default->view(
			$personne,
			array(
				'Personne.nom_complet',
				'Personne.dtnai',
				'Adresse.localite',
				'Dossier.matricule',
				'Personne.idassedic',
			),
			array(
				'widget' => 'table',
				'class' => 'view wide'
			)
		);

        $demandereorient['Precoreorientreferent']['structurereferente_id'] = $demandereorient['Precoreorientreferent']['typeorient_id'].'_'.preg_replace( '/^.*_([0-9]+)$/', '\1', $demandereorient['Precoreorientreferent']['structurereferente_id'] );

		echo $html->tag( 'h2', '3. Conclusion du 1er entretien' );
		$step = 'referent';
		echo $default->view(
			$demandereorient,
			array(
				'Demandereorient.dtprementretien',
				'Motifdemreorient.name',
				'Demandereorient.urgent',
				"Precoreorient{$step}.typeorient_id",
				"Precoreorient{$step}.structurereferente_id",
			),
			array(
				'widget' => 'table',
				'options' => $options,
				'class' => 'view wide'
			)
		);
	?>

    <!--<?php
        echo $default->view(
            $demandereorient,
            array(
                'Reforigine.nom_complet',
    // 			'Demandereorient.reforigine_id',
    // 			'Demandereorient.motifdemreorient_id',
                'Motifdemreorient.name',
                'Demandereorient.commentaire',
                'Demandereorient.urgent',
    // 			'Demandereorient.ep_id',
                'Ep.name',
                'Demandereorient.created',
                /*'Reforigine.nom',
                'Motifdemreorient.name',
                'Demandereorient.commentairereforigine',
                'Refaccueil.nom',
    // 			'Demandereorient.dtdemrefaccueil',
                'Demandereorient.accordrefaccueil' => array( 'type' => 'boolean' ),
                'Demandereorient.commentairerefaccueil',
                'Demandereorient.accordbenef' => array( 'type' => 'boolean' ),
                'Demandereorient.urgent' => array( 'type' => 'boolean' ),
                'Demandereorient.created',
                'Ep.name',
                'Demandereorient.decisionep',
                'Demandereorient.motifdecisionep',
                'Demandereorient.refaccueilep_id',
                'Demandereorient.decisioncg',
                'Demandereorient.motifdecisioncg',
                'Demandereorient.refaccueilcg_id',
                'Demandereorient.dateimpression',*/
            ),
            array(
                'widget' => 'table'
            )
        );

        echo $default->button(
            'back',
            array(
                'controller' => 'demandesreorient',
                'action'     => 'index',
                Set::classicExtract( $demandereorient, 'Demandereorient.personne_id' )
            ),
            array(
                'id' => 'Back'
            )
        );
    // 	debug( $demandereorient );
    ?>-->
</div>
<div class="clearer"><hr /> </div>