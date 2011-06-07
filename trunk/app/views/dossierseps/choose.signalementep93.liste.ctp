<?php
	$duree_engag = 'duree_engag_'.Configure::read( 'nom_form_ci_cg' );
	foreach( $dossiers[$theme] as &$dossierep ) {
		$dossierep['Contratinsertion']['duree_engag'] = Set::enum( $dossierep['Contratinsertion']['duree_engag'], $$duree_engag );
	}

	echo $default2->index(
		$dossiers[$theme],
		array(
			'Dossier.numdemrsa',
			'Adresse.locaadr',
			'Contratinsertion.num_contrat',
			'Contratinsertion.dd_ci',
			'Contratinsertion.duree_engag',
			'Contratinsertion.df_ci',
			'Structurereferente.lib_struc',
			'Contratinsertion.nature_projet',
			'Contratinsertion.type_demande',
			'Passagecommissionep.chosen' => array( 'input' => 'checkbox' ),
		),
		array(
			'cohorte' => true,
			'options' => $options,
			'hidden' => array( 'Dossierep.id', 'Passagecommissionep.id' ),
			'paginate' => Inflector::classify( $theme ),
			'id' => $theme,
			'labelcohorte' => 'Enregistrer',
			'cohortehidden' => array( 'Choose.theme' => array( 'value' => $theme ) )
		)
	);
?>