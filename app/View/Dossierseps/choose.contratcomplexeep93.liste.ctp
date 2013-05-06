<?php
	$duree_engag = 'duree_engag_'.Configure::read( 'nom_form_ci_cg' );
	foreach( $dossiers[$theme] as &$dossierep ) {
		$dossierep['Contratinsertion']['duree_engag'] = Set::enum( $dossierep['Contratinsertion']['duree_engag'], $$duree_engag );
	}

	echo $this->Default2->index(
		$dossiers[$theme],
		array(
			'Dossier.numdemrsa',
			'Adresse.locaadr',
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Contratinsertion.num_contrat',
			'Contratinsertion.dd_ci',
			'Contratinsertion.duree_engag',
			'Contratinsertion.df_ci',
			'Structurereferente.lib_struc',
			'Contratinsertion.nature_projet',
			'Contratinsertion.type_demande',
			'Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' ),
			'Passagecommissionep.chosen' => array( 'input' => 'checkbox' ),
		),
		array(
			'cohorte' => true,
			'options' => $options,
			'hidden' => array( 'Dossierep.id', 'Passagecommissionep.id' ),
			'paginate' => Inflector::classify( $theme ),
			'id' => $theme,
			'labelcohorte' => array(
				'Enregistrer',
				'Annuler' => array( 'name' => 'Cancel' ),
			),
			'cohortehidden' => array( 'Choose.theme' => array( 'value' => $theme ) ),
			'trClass' => $trClass,
		)
	);
?>