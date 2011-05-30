<?php
	echo $default2->index(
		$dossiers[$theme],
		array(
			'Dossier.numdemrsa',
			'Dossier.matricule',
			'Personne.qual',
			'Personne.nom',
			'Personne.prenom',
			'Personne.dtnai',
			'Structurereferente.lib_struc',
			'Motifreorientep93.name',
			'Reorientationep93.accordaccueil' => array( 'type' => 'boolean' ),
			'Reorientationep93.accordallocataire' => array( 'type' => 'boolean' ),
			'Reorientationep93.urgent' => array( 'type' => 'boolean' ),
			'Reorientationep93.datedemande',
			'Dossierep.chosen' => array( 'input' => 'checkbox' ),
		),
		array(
			'cohorte' => true,
			'options' => $options,
			'hidden' => array( 'Dossierep.id', 'Passagecommissionep.id' ),
			'paginate' => 'Dossierep',
			'id' => $theme,
			'labelcohorte' => 'Enregistrer'
		)
	);
?>