<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $passage['Dossierep']['personne_id'] ) );?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle = 'Visualisation d\'un passage en commission d\'EP';?></h1>
	<?php
		$detailsDossier = array(
			'Commissionep.Ep.identifiant',
			'Commissionep.identifiant',
			'Commissionep.dateseance',
			'Passagecommissionep.etatdossierep',
			'Dossierep.themeep',
			'Dossierep.created',
		);

		switch( $passage['Dossierep']['themeep'] ) {
			case 'contratscomplexeseps93';
				break;
			case 'defautsinsertionseps66';
				$detailsDossier[] = "{$modeleTheme}.origine";
				$detailsDossier[] = "{$modeleTheme}.type";
				break;
			case 'nonorientationsproseps58';
				break;
			case 'nonorientationsproseps93';
				break;
			case 'nonrespectssanctionseps93':
				$detailsDossier[] = "{$modeleTheme}.origine";
				$detailsDossier[] = "{$modeleTheme}.rgpassage";
				break;
			case 'regressionsorientationseps58';
				$detailsDossier[] = "{$modeleTheme}.datedemande";
				$detailsDossier[] = "{$modeleTheme}.commentaire";
				break;
			case 'reorientationseps93';
				$detailsDossier[] = "{$modeleTheme}.datedemande";
				$detailsDossier[] = "Motifreorientep93.name";
				$detailsDossier[] = "{$modeleTheme}.commentaire";
				$detailsDossier[] = "{$modeleTheme}.accordaccueil";
				$detailsDossier[] = "{$modeleTheme}.desaccordaccueil";
				$detailsDossier[] = "{$modeleTheme}.accordallocataire";
				$detailsDossier[] = "{$modeleTheme}.urgent";
				break;
			case 'saisinesbilansparcourseps66';
				$detailsDossier[] = "{$modeleTheme}.choixparcours";
				$detailsDossier[] = "{$modeleTheme}.maintienorientparcours";
				$detailsDossier[] = "{$modeleTheme}.changementrefparcours";
				$detailsDossier[] = "{$modeleTheme}.reorientation";
				break;
			case 'saisinespdoseps66';
				break;
			case 'sanctionseps58';
				$detailsDossier[] = "{$modeleTheme}.origine";
				$detailsDossier[] = "{$modeleTheme}.commentaire";
				break;
			case 'sanctionsrendezvouseps58';
				$detailsDossier[] = "{$modeleTheme}.commentaire";
				break;
			case 'signalementseps93';
				$detailsDossier[] = "{$modeleTheme}.motif";
				$detailsDossier[] = "{$modeleTheme}.date";
				$detailsDossier[] = "{$modeleTheme}.rang";
				break;
		}

		echo $default2->view(
			$passage,
			$detailsDossier,
			array(
				'options' => $options,
				'class' => 'aere'
			)
		);

		// Décisions
		$detailsDecision = array( "{$modeleDecision}.decision" );

		switch( $passage['Dossierep']['themeep'] ) {
			case 'contratscomplexeseps93';
				$detailsDecision[] = "{$modeleDecision}.observ_ci";
				$detailsDecision[] = "{$modeleDecision}.datevalidation_ci";
				break;
			case 'defautsinsertionseps66';
				$detailsDecision[] = "{$modeleDecision}.decisionsup";
				$detailsDecision[] = "{$modeleDecision}.Typeorient.lib_type_orient";
				$detailsDecision[] = "{$modeleDecision}.Structurereferente.lib_struc";
				break;
			case 'nonorientationsproseps58';
				$detailsDecision[] = "{$modeleDecision}.Typeorient.lib_type_orient";
				$detailsDecision[] = "{$modeleDecision}.Structurereferente.lib_struc";
				break;
			case 'nonorientationsproseps93';
				$detailsDecision[] = "{$modeleDecision}.Typeorient.lib_type_orient";
				$detailsDecision[] = "{$modeleDecision}.Structurereferente.lib_struc";
				break;
			case 'nonrespectssanctionseps93':
				$detailsDecision[] = "{$modeleDecision}.montantreduction";
				$detailsDecision[] = "{$modeleDecision}.dureesursis";
				break;
			case 'regressionsorientationseps58';
				$detailsDecision[] = "{$modeleDecision}.Typeorient.lib_type_orient";
				$detailsDecision[] = "{$modeleDecision}.Structurereferente.lib_struc";
				$detailsDecision["{$modeleDecision}.Referent.nom_complet"] = array( 'type' => 'text' );
				break;
			case 'reorientationseps93';
				$detailsDecision[] = "{$modeleDecision}.Typeorient.lib_type_orient";
				$detailsDecision[] = "{$modeleDecision}.Structurereferente.lib_struc";
				break;
			case 'saisinesbilansparcourseps66';
				$detailsDecision[] = "{$modeleDecision}.Typeorient.lib_type_orient";
				$detailsDecision[] = "{$modeleDecision}.Structurereferente.lib_struc";
				$detailsDecision["{$modeleDecision}.Referent.nom_complet"] = array( 'type' => 'text' );
				$detailsDecision[] = "{$modeleDecision}.maintienorientparcours";
				$detailsDecision[] = "{$modeleDecision}.changementrefparcours";
				$detailsDecision[] = "{$modeleDecision}.reorientation";
				break;
			case 'saisinespdoseps66';
				$detailsDecision[] = "{$modeleDecision}.Decisionpdo.libelle";
				$detailsDecision[] = "{$modeleDecision}.nonadmis";
				$detailsDecision[] = "{$modeleDecision}.motifpdo";
				$detailsDecision[] = "{$modeleDecision}.datedecisionpdo";
				break;
			case 'sanctionseps58';
				$detailsDecision[] = "{$modeleDecision}.Listesanctionep58.sanction";
				break;
			case 'sanctionsrendezvouseps58';
				break;
			case 'signalementseps93';
				$detailsDecision[] = "{$modeleDecision}.montantreduction";
				$detailsDecision[] = "{$modeleDecision}.dureesursis";
				break;
		}

		$detailsDecision[] = "{$modeleDecision}.commentaire";
		$detailsDecision[] = "{$modeleDecision}.raisonnonpassage";

		for( $i = 0 ; $i <= 1 ; $i++ ) {
			if( !empty( $passage[$modeleDecision][0] ) ) {
				echo '<h2>'.( ( $i == 0 ) ? 'Avis EP' : 'Décision PCG' ).'</h2>';
				echo $default2->view(
					array( $modeleDecision => $passage[$modeleDecision][0] ),
					$detailsDecision,
					array(
						'options' => $options,
						'class' => 'aere'
					)
				);
			}
		}

		// TODO: bouton/lien retour
	?>
</div>
<div class="clearer"><hr /></div>