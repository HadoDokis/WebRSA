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
			'Typeorient.lib_type_orient',
			'Structurereferente.lib_struc',
			'Orientstruct.date_valid',
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